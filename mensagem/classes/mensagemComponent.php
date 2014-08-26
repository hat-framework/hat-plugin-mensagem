<?php
class mensagemComponent extends classes\Component\Component{
    public function formulario(){
        $this->gui = new \classes\Component\GUI();
        $class = 'span6';
        $this->gui->widgetOpen('', $class);
            $this->gui->title('Envie mensagem para todos os usuários do site através dessa plataforma. '
                    . 'Caso queira enviar para todos os perfis, selecione o perfil Administrador.');
            $this->form('mensagem/mensagem');
        $this->gui->widgetClose();
    }
}