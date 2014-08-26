<?php 
class mensagem_mensagemData extends \classes\Model\DataModel{
    public $dados  = array(
         'cod' => array(
	    'name'     => 'Código',
	    'type'     => 'int',
	    'size'     => '20',
	    'pkey'    => true,
            'ai'      => true,
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
	    'private' => true
        ),
        'from' => array(
	    'name'     => 'De',
	    'type'     => 'int',
	    'size'     => '11',
            'especial' => 'autentication',
            'autentication' => array('needlogin' => true),
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
	    'fkey' => array(
	        'model' => 'usuario/login',
	        'cardinalidade' => '1n',
	        'keys' => array('cod_usuario', 'user_name'),
	    ),
        ),
        'to' => array(
	    'name'     => 'Para',
	    'type'     => 'varchar',
	    'size'     => '20',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        'data' => array(
	    'name'      => 'Data',
	    'type'      => 'timestamp',
            'default'   => "CURRENT_TIMESTAMP",
            'especial'  => 'hide'
        ),
        'mensagem' => array(
	    'name'     => 'Mensagem',
	    'type'     => 'text',
            'especial' => 'editor',
            'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        
        'visualizada' => array(
	    'name'     => 'Visualizada',
	    'type'     => 'enum',
            'default'  => 'n',
            'options'  => array(
                's' => "Visualizada",
                'n' => "Não"
            ),
            'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        'notified' => array(
	    'name'     => 'Notificada',
	    'type'     => 'enum',
            'default'  => 'n',
            'options'  => array(
                's' => "Notificada",
                'n' => "Não"
            ),
            'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        'button'     => array('button' => 'Enviar Mensagem'),);
}