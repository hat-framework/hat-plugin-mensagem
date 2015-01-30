<?php 
class mensagem_mensagemModel extends \classes\Model\Model{
    public $tabela      = "mensagem_mensagem";
    public $pkey        = 'cod';
    protected $feature  = "MENSAGEM_ENABLED";
    
    public function __construct() {
        $this->LoadModel('usuario/login', 'uobj');
        parent::__construct();
    }
    
    public function getGroups($cod_usuario){
        $this->LoadModel('usuario/perfil', 'perf');
        $perfil = usuario_loginModel::CodPerfil();
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
    
    public function getFriendList($cod_usuario, $page = 0){
        $perfil = $this->uobj->getCodPerfil($cod_usuario);
        if(in_array($perfil, array(Webmaster, Admin))){
            return $this->getList($cod_usuario, $page);
        }
        if($perfil == '20'){
            return $this->getList($cod_usuario, $page, "cod_perfil NOT IN('".Webmaster."','".Admin."')");
        }
        
        $arr = $this->getLastInteractions($cod_usuario, $page);
        if(!empty($arr)){
            $where = "cod_usuario IN('".implode("','",$arr)."') OR cod_perfil IN('".Webmaster."','".Admin."')";
            return $this->getList($cod_usuario, $page, $where);
        }
        return $this->getList($cod_usuario, $page, "cod_perfil IN('".Webmaster."','".Admin."')");
    }
    
    public function findUsers($cod_usuario, $q){
        
        $where = "user_name LIKE '$q%'";
        $perfil = $this->LoadModel('usuario/login', 'uobj')->getCodPerfil($cod_usuario);
        if($perfil == '20'){
            return $this->getList($cod_usuario, 0, "$where AND cod_perfil NOT IN('".Webmaster."','".Admin."')");
        }
        
        return $this->getList($cod_usuario, 0, $where);
    }
    
    private function getList($cod_usuario, $page, $where = ""){
        $limit   = 10;
        $offsset = $limit * $page;
        $wh      = "cod_usuario != '$cod_usuario'";
        $where   = ($where === "")?$wh:"$wh AND ($where)";
        $arr     = $this->getLastInteractions($cod_usuario, $page);
        $list    = $this->uobj->selecionar(array('cod_usuario', 'user_name', 'cod_perfil'), $where, $limit, $offsset);
        $out     = array();
        $findqtd = array();
        foreach($arr as $cod){
            foreach($list as $cod_list => $user){
                if($cod !== $user['cod_usuario']){continue;}
                $findqtd[] = $user['cod_usuario'];
                $out[$user['cod_usuario']] = $user;
                unset($list[$cod_list]);
                break;
            }
        }
        
        $this->getUnreadList($out,$cod_usuario,$findqtd);
        if(empty($list)){return $out;}
        return array_merge($out, $list);
    }
    
    private function getUnreadList(&$out,$cod_usuario,$findqtd){
        $find = implode("','",$findqtd);
        $w    = "`from`IN('$find') AND `to`='$cod_usuario' AND visualizada='n' GROUP BY `to`";
        $res  = $this->selecionar(array("COUNT(*) as total", "`from`"), $w);
        foreach($res as $r){
            $out[$r['from']]['unread'] = $r['total'];
        }
        $out = array_values($out);
    }
    
    private function getLastInteractions($cod_usuario, $page = 0){
        $users = array();
        $where = "`from`='$cod_usuario' OR `to`='$cod_usuario'";
        $this->prepareUserList('to'  , $where, $users, $page);
        $this->prepareUserList('from', $where, $users, $page);
        return $users;
    }
    
    private function prepareUserList($col, $where, &$users, $page){
        $limit  = 10;
        $offset = ($page * $limit);
        $results = $this->selecionar(array("DISTINCT `$col` as cod_usuario"), $where, $limit, $offset, "data DESC");
        if(empty($results)){return array();}
        foreach($results as $res){
            $users[$res['cod_usuario']] = $res['cod_usuario'];
        }
    }
    
    public function LoadUserTalk($from, $to = "", $page = 0){
        $limit  = 10;
        $offset = $limit * $page;
        $from   = $this->antinjection($from);
        $to     = $this->antinjection($to);
        $where  = "(`from`='$from' OR `to`='$from')";
        
        /*if($to !== ""){
            $type   = substr($to, 0, 5);
            $where  = "(`from`='$from' AND `to`='$to') OR (`from`='$to' AND `to`='$from')";
            if(in_array($type, array('todos', 'group'))){
                $data = "";
                //limita a visualização dos grupos apenas para a data após o ingresso do usuário no sistema
                if(true === getBoleanConstant("MENSAGEM__LIMIT_DATA")){
                    $user   = $this->uobj->getItem($from, "", false, array('user_criadoem'));
                    $data   = ($user['user_criadoem'] === "")?"":" AND data >= '{$user['user_criadoem']}'";
                }
                $where  = "(`to`='$to') $data";
            }
        }*/
        
        
        $this->db->Join($this->tabela, 'usuario as u1',array('`from`'), array('cod_usuario'), "LEFT");
        $this->db->Join($this->tabela, 'usuario as u2', array('`to`'), array('cod_usuario'), "LEFT");
        $var    = $this->selecionar(
                array('mensagem', '`from`', '`to`', 'data', 'visualizada', 'u1.user_name as fromname', 'u2.user_name as toname'), 
                $where, $limit, $offset, "data DESC"
        );
        return $var;
    }
    
    public function setRead($from, $to){
        $post  = array('visualizada' => 's');
        $where = "`from`='$from' AND `to`='$to'";
        if(!$this->db->Update($this->tabela,$post, $where)){
            $this->setErrorMessage($this->db->getErrorMessage());
            return false;
        }
        
        $this->LoadModel('notificacao/notifycount', 'nnc', false);
        if(!isset($this->nnc) || $this->nnc === null){return true;}
        $this->nnc->addNotify($to, $this->notifyName, 0);
        
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
        if(false !== strstr($dados['to'], 'group_')){
            $bool = $this->doCopy($dados);
        }
        else {$this->notificar($dados['to']);}
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
    
    private function notificar($to){
        $this->LoadModel('notificacao/notifycount', 'nnc', false);
        if(!isset($this->nnc) || $this->nnc === null){return true;}
        $not = $this->nnc->getNotify($to, $this->notifyName);
        $count = ($not === "")?1:(int)$not  + 1;
        $this->nnc->addNotify($to, $this->notifyName, $count);
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