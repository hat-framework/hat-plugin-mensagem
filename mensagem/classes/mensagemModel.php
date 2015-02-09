<?php 
class mensagem_mensagemModel extends \classes\Model\Model{
    public $tabela      = "mensagem_mensagem";
    public $pkey        = 'cod';
    protected $feature  = "MENSAGEM_ENABLED";
    private $lastWhere  = "";
    private $limit      = 10;
    
    public function __construct() {
        $this->LoadModel('usuario/login', 'uobj');
        parent::__construct();
    }
    
    public function getGroups($cod_usuario){
        $this->LoadModel('usuario/perfil', 'perf');
        $perfil = $this->uobj->getCodPerfil($cod_usuario);
        $where  = "";
        if(!in_array($perfil, array(Webmaster, Admin))){
            //if(false === getBoleanConstant('MENSAGEM_ANY_USER')){return array();}
            $where  = "usuario_perfil_cod='$perfil'";
        }
        if($perfil == '20'){
            $wh     = "usuario_perfil_cod NOT IN('".Webmaster."','".Admin."')";
            $where  = ($where === "")?$wh:"$where OR ($wh)";
        }
        
        $all = array('usuario_perfil_cod' => 'todos', 'usuario_perfil_nome' => 'Todos Usuários');
        $out = $this->perf->ignorePath()->selecionar(array('usuario_perfil_cod', 'usuario_perfil_nome'), $where);
        if($perfil != '20'){array_unshift($out, $all);}
        return $out;
    }
    
    public function getTotalPages($cod_usuario){
        if($this->lastWhere === ""){
            $this->getFriendList($cod_usuario);
            if($this->lastWhere === ""){return 1;}
        }
        $total = $this->uobj->getCount($this->lastWhere);
        if($this->limit < 1){$this->limit = 1;}
        $response = ceil($total/$this->limit);
        if($response < 0){$response = 1;}
        return $response;
    }
    
    public function getFriendList($cod_usuario, $page = 0){
        $where  = "";
        $perfil = $this->uobj->getCodPerfil($cod_usuario);
        if(!in_array($perfil, array(Webmaster, Admin))){
            $where  = "cod_perfil IN('".Webmaster."','".Admin."')";
            if($perfil == '20'){
                $where  = "cod_perfil NOT IN('".Webmaster."','".Admin."')";
            }
        }
        return $this->getLastInteractions($cod_usuario, $page, $where);
    }
    
    public function findUsers($cod_usuario, $q){
        $where = "user_name LIKE '$q%'";
        $perfil = $this->LoadModel('usuario/login', 'uobj')->getCodPerfil($cod_usuario);
        if($perfil == '20'){
            return $this->getLastInteractions($cod_usuario, 0, "$where AND cod_perfil NOT IN('".Webmaster."','".Admin."')");
        }
        return $this->getLastInteractions($cod_usuario, 0, $where);
    }
    
    private function getLastInteractions($cod_usuario, $page = 0, $where = ""){
        $w               = "cod_usuario !='$cod_usuario' ";     
        $wh              = ($where === "")?"$w":"$w AND ($where)";
        $limit           = 10;
        $offset          = ($page * $limit);
        $this->lastWhere = $wh;
        $this->join('mensagem/mensagem', 'cod_usuario', '`to`', 'LEFT', 'usuario/login');
        return $this->uobj->selecionar(array(
            'DISTINCT cod_usuario', 'cod_perfil','user_name'
        ), "(`from`='$cod_usuario' AND `to`!='$cod_usuario') OR $wh", $limit, $offset, "$this->tabela.data DESC");
    }
    
    public function LoadUserTalk($from, $to = "", $page = 0){
        $limit  = 10;
        $offset = $limit * $page;
        $from   = $this->antinjection($from);
        $to     = $this->antinjection($to);
        $where  = "(`from`='$from' OR `to`='$from')";     
        
        $this->db->Join($this->tabela, 'usuario as u1',array('`from`'), array('cod_usuario'), "LEFT");
        $this->db->Join($this->tabela, 'usuario as u2', array('`to`'), array('cod_usuario'), "LEFT");
        $var    = $this->selecionar(
                array('mensagem', '`from`', '`to`', 'data', 'visualizada', 'u1.user_name as fromname', 'u2.user_name as toname'), 
                $where, $limit, $offset, "data DESC"
        );
        return $var;
    }
    
    public function setRead($from, $to = ""){
        $post  = array('visualizada' => 's');
        $where = ($to !== "")?"`from`='$from' AND `to`='$to'":"`from`='$from'";
        if(!$this->db->Update($this->tabela,$post, $where)){
            $this->setErrorMessage($this->db->getErrorMessage());
            return false;
        }
        
        $this->LoadModel('notificacao/notifycount', 'nnc', false);
        if(!isset($this->nnc) || $this->nnc === null){return true;}
        
        if($to !== ''){
            $this->nnc->dropNotify($to, "{$this->notifyName}");
            $this->nnc->dropNotify($to, "{$this->notifyName}_{$from}");
        }      
        return true;
    }
    
    public function getFeatures($cod_usuario){
        $consts = returnConstants('MENSAGEM');
        $perfil = $this->uobj->getCodPerfil($cod_usuario);
        if(in_array($perfil, array(Webmaster, Admin, '20'))){
            foreach($consts as $name => $val){
                $consts[$name] = true;
            }
        }
        return $consts;
    }
    
    private $notifyName = 'messages';
    public function inserir($dados) {
        $bool = true;
        if(!parent::inserir($dados)){return false;}
        if(isset($dados['to'])){
            if(false === strstr($dados['to'], 'group_')){
                $this->notificar($dados['to']);
            }
            else {$bool = $this->doCopy($dados); }
        }else{$this->notifyStaff($dados['from']);}
        return $bool;
    }
    
    private function doCopy($dados){
        $grupo = substr($dados['to'], '6', strlen($dados['to'])-1);
        $i = 1; $bool = true;
        while ($all = $this->getNext("cod_perfil IN('$grupo')", $i)){
            if(empty($all)){break;}
            foreach($all as $cod_usuario){
                $dados['to'] = $cod_usuario;
                $bool = $bool && parent::inserir($dados);
                $this->notificar($dados['to']);
            }
        }
        return $bool;
    }
    
    private function notifyStaff($from){
        $perfis = $this->LoadModel('plugins/acesso', 'perm')->getPerfisOfPermission('mensagem_manage');
        $out = $this->LoadModel('usuario/login', 'uobj')->getUsuariosPorPerfil($perfis, array('cod_usuario'));
        foreach($out as $o){
            $this->notificar($o['cod_usuario'], $from);
        }
    }
    
    private function notificar($to, $from = ''){
        if($to === ""){return;}
        $this->LoadModel('notificacao/notifycount', 'nnc', false);
        if(!isset($this->nnc) || $this->nnc === null){return true;}
        $name = ($from === "")?$this->notifyName:"{$this->notifyName}_{$from}";
        $this->nnc->addNotify($to, $name);
    }
    
    private $qtd = 5;
    private function getNext($where, &$i){
        if($i < 1){$i = 1;}
        $campos = array("cod_usuario");
        $dados  = $this->uobj->selecionar($campos, $where, $this->qtd, ($i-1)*  $this->qtd);
        if(empty($dados)) {return array();}
        foreach($dados as $d){
            $to_notify[] = $d['cod_usuario'];
        }
        $i++;
        return $to_notify;
    }
    
}