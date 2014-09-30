<?php 
class mensagem_mensagemData extends \classes\Model\DataModel{
    public $dados  = [
         'cod' => [
	    'name'     => 'Código',
	    'type'     => 'int',
	    'size'     => '20',
	    'pkey'    => true,
            'ai'      => true,
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
	    'private' => true
        ],
        'from' => [
	    'name'     => 'De',
	    'type'     => 'int',
	    'size'     => '11',
            'especial' => 'autentication',
            'autentication' => ['needlogin' => true],
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
	    'fkey' => [
	        'model' => 'usuario/login',
	        'cardinalidade' => '1n',
	        'keys' => ['cod_usuario', 'user_name'],
	    ],
        ],
        'to' => [
	    'name'     => 'Para',
	    'type'     => 'varchar',
	    'size'     => '20',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ],
        'data' => [
	    'name'      => 'Data',
	    'type'      => 'timestamp',
            'default'   => "CURRENT_TIMESTAMP",
            'especial'  => 'hide'
        ],
        'mensagem' => [
	    'name'     => 'Mensagem',
	    'type'     => 'text',
            'especial' => 'editor',
            'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ],
        
        'visualizada' => [
	    'name'     => 'Visualizada',
	    'type'     => 'enum',
            'default'  => 'n',
            'options'  => [
                's' => "Visualizada",
                'n' => "Não"
            ],
            'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ],
        'notified' => [
	    'name'     => 'Notificada',
	    'type'     => 'enum',
            'default'  => 'n',
            'options'  => [
                's' => "Notificada",
                'n' => "Não"
            ],
            'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ],
        'button'     => ['button' => 'Enviar Mensagem'],];
}