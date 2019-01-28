<?php

/**
 * <b>Login [ MODEL ]</b>
 * Responsavel por autenticar, validar e checar usuarios do sistema de login
 * @copyright (c) 2016, Diego Tesch 
 */
class Login {

    private $level;
    private $user;
    private $senha;
    private $error;
    private $result;

    function __construct($level) {
        $this->level = (int) $level;
    }

    public function ExeLogin(array $UserData) {
        $this->user = (string) strip_tags(trim($UserData['user_nome']));
        $this->senha = (string) strip_tags(trim($UserData['user_senha']));
        $this->setLogin();
    }
    
    function getError() {
        return $this->error;
    }

    function getResult() {
        return $this->result;
    }
    
    public function CheckLogin() {
        if(empty($_SESSION['userlogin']) || $_SESSION['userlogin']['user_level'] < $this->level){
            unset($_SESSION['userlogin']);
            return false;
        }else{
            return true;
        }
    }

    
    //PRIVATES
    private function setLogin() {
        //var_dump($this);
        if (!$this->user || !$this->senha) {
            $this->error = array('Informe usuário e senha para efetuar o login!', MSG_INFO);
            $this->result = false;
        } elseif (!$this->getUser()) {
            $this->error = array('Os dados informados não são compatíveis', MSG_ALERTA);
            $this->result = false;
        } elseif ($this->result['user_level'] < $this->level) {
            $this->error = array("Desculpe {$this->result['user_nome']}, você não tem permissão para acessar esta área", MSG_ERRO);
            $this->result = false;
        } else {
            $this->Execute();
        }
    }

    private function getUser() {
        $this->senha = sha1(md5(base64_encode($this->senha)));
        //var_dump($this);
        $read = new Read;
        $read->ExeRead('user', "WHERE user_nome = :e AND user_senha = :p", "e={$this->user}&p={$this->senha}");
        if ($read->getResult()) {
            $res = $read->getResult();
            $this->result = $res[0];
            return true;
        } else {
            return false;
        }
    }

    private function Execute() {
        if (!session_id()) {
            session_start();
        }

        $_SESSION['userlogin'] = $this->result;
        $this->error = array("Olá {$this->result['user_nome']}, seja bem vindo(a). Aguarde redirecionamento!", CRM_OK);
        $this->result = true;
    }

}
