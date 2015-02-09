<?php 

class mensagemController extends classes\Controller\CController{
    
    public $model_name = 'mensagem/mensagem';
    public function __construct($vars) {
        $this->addToFreeCod(array(
            "data", "friendlist", "conversa", "getUserContactList", "searchUser", "moderador", "usuario"
        ));
        parent::__construct($vars);
    }    
    
    public function index($display = true, $link = "") {
        $perfil = usuario_loginModel::CodPerfil();
        if($perfil === "20"){Redirect(LINK ."/moderador");}
        
        if($this->LoadModel('usuario/login', 'uobj')->UserIsAdmin()){
            return $this->display(LINK."/index");
        }
        Redirect(LINK . "/usuario");
    }
    
    public function moderador(){
        $this->display(LINK."/moderador");
    }
    
    public function usuario(){
        $perfil = usuario_loginModel::CodPerfil();
        if($perfil === "20"){Redirect(LINK ."/moderador");}
        $this->display(LINK."/usuario");
    }
    
    private function typeIsUser(){
        $type = filter_input(INPUT_GET, 'type');
        return $type === 'user';
    }
    
    public function data(){
        $cod_user = usuario_loginModel::CodUsuario();
        if(!$this->typeIsUser()){
            $arr['friendlist']       = $this->model->getFriendList($cod_user);
            $arr['friendsPageCount'] = $this->model->getTotalPages($cod_user);
            $arr['groups']           = $this->model->getGroups($cod_user);
        }
        $arr['features']   = $this->model->getFeatures($cod_user);
        $arr['sender']     = $this->LoadModel('usuario/login', 'uobj')->getUserNick(array(), true);
        die(json_encode($arr, JSON_NUMERIC_CHECK));
    }

    public function getUserContactList(){
        $user = usuario_loginModel::CodUsuario();
        $page = isset($this->vars[0])?$this->vars[0]:'0';
        $arr  = $this->model->getFriendList($user, $page);
        die(json_encode($arr, JSON_NUMERIC_CHECK));
    }
    
    public function searchUser(){
        $user = usuario_loginModel::CodUsuario();
        $q = filter_input(INPUT_GET, 'q');
        $arr  = $this->model->findUsers($user, $q);
        die(json_encode($arr, JSON_NUMERIC_CHECK));
    }
    
    public function conversa(){
        $cod_usuario = (isset($this->vars[0]))?$this->vars[0]:"";
        if($cod_usuario === ""){die(json_encode(array()));}
        if($this->typeIsUser()){
            $page = (isset($this->vars[1]))?$this->vars[1]:"0";
            $arr  = $this->model->LoadUserTalk($cod_usuario, "", $page);
            $this->model->setRead("", $cod_usuario);
            //print_rd($arr);
            die(json_encode($arr));
        }
        
        $cod_friend = (isset($this->vars[1]))?$this->vars[1]:"";
        $page       = (isset($this->vars[2]))?$this->vars[2]:"0";
        if($cod_friend === ""){die(json_encode(array()));}
        $arr = $this->model->LoadUserTalk($cod_friend, "", $page);
        $this->model->setRead($cod_friend, $cod_usuario);
        die(json_encode($arr));
    }
    
    public function notify(){
        $this->LoadClassFromPlugin(LINK.'/mensagemNotifier', 'mnf')->notifyAll();
    }
}