<?php

use classes\Classes\Actions;
class mensagemActions extends Actions{
        
    protected $permissions = array(
        'EnviarMensagens' => array(
            'nome'      => "mensagem_send",
            'label'     => "Enviar e receber mensagens",
            'descricao' => "Permite que o usuário envie e receba mensagens pelo sistema",
            'default'   => 's',
        )
    );
    
    protected $actions = array(
        
        'mensagem/index/index' => array(
            'label' => 'Todas as mensagens', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_AC', 'needcod' => false
        ),
        'mensagem/mensagem/index' => array(
            'label' => 'Todas as mensagens', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_AC', 'needcod' => false,
            'breadscrumb' => array('usuario/login/logado', 'mensagem/mensagem/index'),
        ),
        
        'mensagem/mensagem/formulario' => array(
            'label' => 'Enviar Mensagem', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_AC', 'needcod' => false,
            'breadscrumb' => array('usuario/login/logado', 'mensagem/mensagem/index', 'mensagem/mensagem/formulario' ),
        ),
        
        'mensagem/mensagem/data' => array(
            'label' => 'Recuperar dados', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_AC', 'needcod' => false,
        ),
        'mensagem/mensagem/conversa' => array(
            'label' => 'Recuperar conversa', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_AC', 'needcod' => false,
        ),
        'mensagem/mensagem/getUserContactList' => array(
            'label' => 'Lista de Contatos', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_AC', 'needcod' => false,
        ),
        'mensagem/mensagem/searchUser' => array(
            'label' => 'Buscar Usuário', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_AC', 'needcod' => false,
        ),
        'mensagem/mensagem/notify' => array(
            'label' => 'Notificar', 'publico' => 's', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_AC', 'needcod' => false,
        ),

    );
}