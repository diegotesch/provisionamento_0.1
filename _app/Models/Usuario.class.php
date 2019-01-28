<?php

/**
 * <b>Usuário [ MODEL ]</b>
 * Responsavel por listar, cadastrar e editar os bairros
 * @copyright (c) 2016, Diego Tesch 
 */
class Usuario {
    private $id;
    private $dados;
    private $error;
    private $result;

    const Tbl = 'usuario';
    
    function __construct(array $dados) {
        $this->dados = $dados;
    }
    
    public function CadUsuario() {
        $this->CheckUser();
        if(!$this->result){
            $this->Create();
            if(!$this->result){
                $this->error = array("<b>Erro:</b>Falha ao cadastrar usuário<br>");
                $this->result = false;
            }   
        }
    }
    
            
    function getError() {
        return $this->error;
    }

    function getResult() {
        return $this->result;
    }
    
    //PRIVATES   
    private function Create() {
        $create = new Create();
        $create->ExeCreate(self::Tbl, $this->dados);
        if ($create->getResult()) {
            $this->result = $create->getResult();
            $this->error = array("<b>Sucesso: </b><br>Usuário cadastrado com sucesso", MSG_OK);
        }else{
            $this->result = false;
            $this->error = array("<b>Erro: </b><br>Falha ao concluir cadastro", MSG_OK);
        }
    }
    
    private function CheckUser() {
        $read = new Read();
        $read->ExeRead(self::Tbl, "WHERE usuario_email = :usermail", "usermail={$this->dados['usuario_email']}");
        if($read->getResult()){
            $this->result = false;
            $this->error = array("Existe outro usuario com este endereço de e-mail!", MSG_ERRO);
        }else{
            $this->result = true;
        }
    }
}
