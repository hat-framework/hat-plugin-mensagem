<?php

class mensagemNotifier extends classes\Classes\Object{
    
    public function __construct() {
        $this->LoadModel('usuario/login', 'uobj');
        $this->tabela = $this->LoadModel('mensagem/mensagem', 'umss')->getTable();
        $this->LoadResource('html', 'html');
        $this->LoadResource('email', 'mail');
    }
    
    public function notifyAll(){
        $qtd    = 10;
        $where  = "visualizada='n' AND notified='n'";
        $campos = array("DISTINCT $this->tabela.`to`");
        if(false === $this->checkAll($campos, $where)){
            $this->checkGroups($campos, $where);
            $this->checkEach($where, $qtd);
        }
        return $this->umss->editar('n', array('notified' => 's'), 'notified');
    }
    
    private function checkAll($campos, $where){
        $dados  = $this->umss->selecionar($campos, "$where AND `to`='group_all'", 1);
        if(!empty($dados)){
            $this->Notify('');
            return true;
        }
        return false;
    }
    
    private function checkGroups($campos, &$where){
        $dados  = $this->umss->selecionar($campos, "$where AND `to` LIKE 'group_%'");
        $groups = (!empty($dados))?$this->notifyGroups($dados):array();
        if(!empty($groups)){
            $gr    = implode("','",$groups);
            $this->Notify("cod_perfil NOT IN('$gr')");
            $this->join('usuario/login', '`to`', 'cod_usuario');
            $where = "cod_perfil NOT IN('$gr') AND( $where )";
        }
    }
    
    private function checkEach($where, $qtd){
        $i      = 0;
        $campos = array("email");
        $dados  = $this->getNext($i, $campos, $where, $qtd);
        while(!empty($dados)){
            $to_notify = array();
            foreach($dados as $d){
                $to_notify[] = $d['email'];
            }
            $this->mailNotifyMessage($to_notify);
            $dados = $this->getNext($i, $campos, $where, $qtd);
        }
        return true;
    }
    
    private function getNext(&$i, $campos, $where, $qtd){
        $i++;
        $this->umss->join('usuario/login', '`to`', 'cod_usuario');
        return $this->umss->selecionar($campos, $where, $qtd, ($i-1)*$qtd);
    }
    
    private function notifyGroups($dados){
        $groups = array();
        foreach($dados as $d){
            $groups[] = substr($d['to'], 6, strlen($d['to'])-1);;
        }
        $gr = implode("','",$groups);
        $where = "cod_perfil IN('$gr')";
        $this->Notify($where);
        return $groups;
    }
    
    private function Notify($where){
        $i      = 1;
        $qtd    = 1;
        $campos = array("email");
        $dados  = $this->uobj->selecionar($campos, $where, $qtd, ($i-1)*$qtd);
        while (!empty($dados)){
            $to_notify = array();
            foreach($dados as $d){
                $to_notify[] = $d['email'];
            }
            $this->mailNotifyMessage($to_notify);
            $i++;
            $dados = $this->uobj->selecionar($campos, $where, $qtd, ($i-1)*$qtd);
        }
    }
    
    private function mailNotifyMessage($emails){
        $url         = $this->html->getLink("mensagem/mensagem/index");
        $assunto     = SITE_NOME . "- Nova Mensagem";
        $msg = "Você recebeu uma mensagem através do ". SITE_NOME. ". <br/> "
             . "Para visualizá-la <a href='$url'>clique aqui</a>";
        return $this->propagateMessage($this->mail, 'sendMail', $assunto, $msg, $emails);
    }
}