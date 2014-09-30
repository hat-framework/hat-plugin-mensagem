<?php 

class mensagemController extends classes\Controller\CController{
    
    public $model_name = 'mensagem/mensagem';
    public function __construct($vars) {
        $this->addToFreeCod(array("data", "friendlist", "conversa", "getUserContactList", "searchUser"));
        parent::__construct($vars);
    }    
    
    public function index($display = true, $link = "") {
        $this->display(LINK."/app");
    }
    
    public function data(){
        $arr['sender']     = $this->LoadModel('usuario/login', 'uobj')->getUserNick(array(), true);
        $arr['friendlist'] = $this->model->getFriendList(usuario_loginModel::CodUsuario());
        $arr['groups']     = $this->model->getGroups(usuario_loginModel::CodUsuario());
        $arr['features']   = $this->model->getFeatures(usuario_loginModel::CodUsuario());
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
        if(!isset($this->vars[0]) || !isset($this->vars[1])){die(json_encode([]));}
        $page = (isset($this->vars[2]))?$this->vars[2]:"0";
        $arr = $this->model->LoadUserTalk($this->vars[0], $this->vars[1], $page);
        $this->model->setRead($this->vars[1], $this->vars[0]);
        die(json_encode($arr));
    }
    
    public function notify(){
        $this->LoadClassFromPlugin(LINK.'/mensagemNotifier', 'mnf')->notifyAll();
    }
    
}