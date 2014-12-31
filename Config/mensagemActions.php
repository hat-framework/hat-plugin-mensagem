<?php

use classes\Classes\Actions;
class mensagemActions extends Actions{
        
    protected $permissions = array(
        'EnviarMensagens' => array(
            'nome'      => "mensagem_send",
            'label'     => "Enviar e receber mensagens",
            'descricao' => "Permite que o usuário envie e receba mensagens pelo sistema",
            'default'   => 's',
        ),
        
        'ModerarMensagems' => array(
            'nome'      => "mensagem_manage",
            'label'     => "Moderar Mensagens",
            'descricao' => "Permite que o modere as mensagens enviadas pelos usuários do sistema",
            'default'   => 'n',
        )
    );
    
    protected $actions = array(
        
        'mensagem/index/index' => array(
            'label' => 'Todas as mensagens', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'mensagem_send', 'needcod' => false
        ),
        'mensagem/mensagem/index' => array(
            'label' => 'Todas as mensagens', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'mensagem_send', 'needcod' => false,
            'breadscrumb' => array('usuario/login/logado', 'mensagem/mensagem/index'),
        ),
        
        'mensagem/mensagem/formulario' => array(
            'label' => 'Enviar Mensagem', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'mensagem_send', 'needcod' => false,
            'breadscrumb' => array('usuario/login/logado', 'mensagem/mensagem/index', 'mensagem/mensagem/formulario' ),
        ),
        
        'mensagem/mensagem/data' => array(
            'label' => 'Recuperar dados', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'mensagem_send', 'needcod' => false,
        ),
        'mensagem/mensagem/conversa' => array(
            'label' => 'Recuperar conversa', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'mensagem_send', 'needcod' => false,
        ),
        'mensagem/mensagem/getUserContactList' => array(
            'label' => 'Lista de Contatos', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'mensagem_manage', 'needcod' => false,
        ),
        'mensagem/mensagem/searchUser' => array(
            'label' => 'Buscar Usuário', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'mensagem_manage', 'needcod' => false,
        ),
        'mensagem/mensagem/notify' => array(
            'label' => 'Notificar', 'publico' => 's', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'mensagem_send', 'needcod' => false,
        ),

    );
    
    protected $perfis = array(
        'Moderador' => array(
            'cod'         => '20',
            'nome'        => 'Moderador de Mensagens',
            'default'     => '0',
            'tipo'        => 'sistema',
            'descricao'   => 'Perfil destinado aos Moderadores do sistema de mensagens. Eles terão acesso a todas as mensagens enviadas pelos usuários, e
                              terão a opção de respondê-los. Assim como recebem notificações de novas mensagens ao acessar o sistema',
            'permissions' => array('mensagem_manage' => 's')
        ),
    );
}