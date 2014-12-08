<?php

class mensagemLoader extends classes\Classes\PluginLoader{

    public function setCommonVars(){
        if(false === strstr($_SERVER["SERVER_NAME"], 'dcoracoes')){return;}
        if(!in_array(usuario_loginModel::CodPerfil(), array(Visitante,'20', '15'))){
            die("Você não tem permissão para acessar esta página!");
        }
    }
    
    public function setAdminVars(){}
    
}