<?php
        
class mensagemConfigurations extends \classes\Classes\Options{
          
    protected $menu = array();
    
    protected $files   = array(
  
        'mensagem/mensagem' => array(
            'title'        => 'Opções de envio de mensagens',
            'descricao'    => 'Exibe as opções do plugin de mensagens',
            'visibilidade' => 'admin', //'usuario', 'admin', 'webmaster'
            'grupo'        => 'Plugin de Mensagens',
            'path'         => 'mensagem/mensagem',
            'updateplugins' => 'true',
            'configs'      => array(
                'MENSAGEM_ENABLED' => array(
                    'name'          => 'MENSAGEM_ENABLED',
                    'label'         => 'Habilitar envio de mensagens para os usuários',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'true',
                    'value'         => 'true',
                    'value_default' => 'true'
                ),
                'MENSAGEM_LIMIT_DATA' => array(
                    'name'          => 'MENSAGEM_LIMIT_DATA',
                    'label'         => 'Proibir que usuários vejam mensagens enviadas para grupos anteriores a data do cadastro?',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'true',
                    'value'         => 'true',
                    'value_default' => 'true'
                ),
                'MENSAGEM_EMAIL' => array(
                    'name'          => 'MENSAGEM_EMAIL',
                    'label'         => 'Habilitar notificação por email quando novas mensagens forem enviadas',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'true',
                    'value'         => 'true',
                    'value_default' => 'true'
                ),
                
                'MENSAGEM_EMAIL_BODY' => array(
                    'name'          => 'MENSAGEM_EMAIL_BODY',
                    'label'         => 'Permitir que a notificação por email contenha todo o corpo da mensagem? (do contrário haverá apenas um link para o sistema)',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                'MENSAGEM_ANY_USER' => array(
                    'name'          => 'MENSAGEM_ANY_USER',
                    'label'         => 'Permitir que qualquer usuário envie mensagens para os administradores',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                'MENSAGEM_FULL_CHAT' => array(
                    'name'          => 'MENSAGEM_FULL_CHAT',
                    'label'         => 'Permitir que qualquer usuário envie mensagens para qualquer outro usuário',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                'MENSAGEM_GROUP_CHAT' => array(
                    'name'          => 'MENSAGEM_GROUP_CHAT',
                    'label'         => 'Permitir que qualquer usuário envie mensagens para seu grupo de usuário',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                'MENSAGEM_ALL_CHAT' => array(
                    'name'          => 'MENSAGEM_ALL_CHAT',
                    'label'         => 'Permitir que qualquer usuário responda as mensagens enviadas para todos usuários (todos usuários visualizarão a resposta)',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'false',
                    'value'         => 'false',
                    'value_default' => 'false'
                ),
                'MENSAGEM_COPY' => array(
                    'name'          => 'MENSAGEM_COPY',
                    'label'         => 'Ao enviar uma mensagem em grupo, fazer uma cópia para cada usuário (ao invés de exibir as mensagens no grupo)',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       =>  "'true' => 'Sim', 'false' => 'Não'",
                    'default'       => 'true',
                    'value'         => 'true',
                    'value_default' => 'true'
                ),
            ),
        ),
    );
    
    public function getMenu(){
        if(false === getBoleanConstant("MENSAGEM_ENABLED")){
            unset($this->menu['0']);
        }
        return $this->menu;
    }
}