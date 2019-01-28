<?php

/**
 * <b>Classe Table</b>
 * Classe responsável por criar e manipular tabelas do banco de dados
 * [TABLE]
 * @copyright (c) 2016, Diego Tesch 
 */
class Table extends Conn{
    private $tabela;
    private $result;
    private $error;
    private $campos;
    private $acao;
    private $altcampo;

    /** @var PDOStatemet  */
    private $create;

    /** @var PDO */
    private $conn;
    
    /**
     * <b>ExeCreate:</b> 
    
     * @param String $nome_tabela = Informe o nome da tabela no banco!
     * @param array $campos = Informe um array com os campos a serem inseridos de forma completa
     * @example $cria->ExeCreate('tabela', ['ID INT(11) PRIMARY KEY AUTO_INCREMENT', ' NOME VARCHAR(50) NOT NULL', 'IDADE INT(2)']) 
     */
    public function ExeCreate($nome_tabela, array $campos) {
        $this->tabela = (string) $nome_tabela;
        $this->campos = $campos;
        
        $this->getSyntax();
        $this->Execute();
    }
    
    public function ExeAlterTable($nome_tabela, $acao, array $campos) {
        $this->tabela = (string) $nome_tabela;
        $acoes = array("add", "Add", "ADD", "change", "Change", "CHANGE", "drop", "Drop", "DROP");
        $this->acao = (in_array($acao, $acoes)? $acao : NULL);
        if($this->acao != NULL){
            $this->campos = $campos;
            $this->getAlterSyntax();
            $this->Execute();
        }else{
            $this->result = false;
            $this->error = ["Ação invalida para este método, escolha uma ação válida", CRM_INFO];
        }
    }
    
    function getResult() {
        return $this->result;
    }

    function getError() {
        return $this->error;
    }

        
    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    
    /**
     * Responsavel por obter a conexão com o Banco de dados do PDO e preparar a Query
     */
    private function Connect() {
        $this->conn = parent::getConn();
        $this->create = $this->conn->prepare($this->create);
    }
    
    
    private function getSyntax(){
        $fields = implode(', ', array_values($this->campos));
        $this->create = "CREATE TABLE {$this->tabela} ({$fields})";
    }
    
    private function getAlterSyntax() {
        $fields = implode(' ', array_values($this->campos));
        $this->create = "ALTER TABLE {$this->tabela} {$this->acao} {$fields}";
    }
    
    /**
     * Responsável por obter a conexão e a Sintax, então executa a Query
     */
    private function Execute() {
        $this->Connect();
        try{
            $this->create->execute($this->campos);
            $this->result = $this->conn->lastInsertId();            
        } catch (PDOException $e) {
            $this->result = null;
            CRMErro("Erro ao cadastrar: <b>{$e->getMessage()}</b>", $e->getCode());
        }
    }
    
}
