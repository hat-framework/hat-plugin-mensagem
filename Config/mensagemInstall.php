<?php

class mensagemInstall extends classes\Classes\InstallPlugin{
    
    protected $dados = array(
        'pluglabel'  => 'Mensagens',
        'isdefault'  => 'n',
        'system'     => 'n',
        'description'=> ' 
            O plugin de mensagens, permite interagir melhor com os usuários do site. Embora seja inspirado na usabilidade do chat do facebook,
            tal plugin deve ser utilizado apenas como forma de enviar emails para os usuários. Ele não é um plugin de chat.
         '
    );
    
    public function install(){
        return true;
    }
    
    public function unstall(){
        return true;
    }
}