<?php

class mensagemLoader extends classes\Classes\PluginLoader{

    public function setCommonVars(){
        if(false === strstr($_SERVER["SERVER_NAME"], 'dcoracoes')){return;}
        $cod_perfil = usuario_loginModel::CodPerfil();
        if(!in_array($cod_perfil, array('3','2',Visitante,'4','20', '15'))){
            throw new classes\Exceptions\AcessDeniedException(
                     "O seu perfil de usuário ($cod_perfil) não está credenciado a acessar esta página. "
                    ."Podem acessar a página apenas <br>: Administradores, Visitantes, Moderadores de Mensagem, Assinantes Temporários e Revendedoras"
            );
        }
    }
    
    public function setAdminVars(){}
    
}