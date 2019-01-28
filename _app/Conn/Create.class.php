<?php

/**
 * <b>Classe Create</b>
 * Classe responsável por cadastros no banco de dados
 * [INSERT]
 * @copyright (c) 2016, Diego Tesch 
 */
class Create extends Conn {
private $tabela;
    private $dados;
    private $result;
    private $campos;

    /** @var PDOStatemet  */
    private $create;

    /** @var PDO */
    private $conn;

    /**
     * <b>ExeCreate:</b> 
     * Executa um cadastro simplificado no banco de dados utilizando prepared statements.
     * Basta Informar o nome da tabela e um array atribuitivo com nome da coluna e valor!
     * 
     * @param String $tabela = Informe o nome da tabela no banco!
     * @param array $dados = Informe um array atribuitivo (Nome da Coluna => Valor)
     */
    public function ExeCreate($tabela, array $dados) {
        $this->tabela = (string) $tabela;
        $this->dados = $dados;
        
        $this->getSyntax();
        $this->Execute();
    }

    /**
     * <b>Obter Resultado</b>
     * retorna o ultimo ID inserido na tabela ou false caso nenhum registro seja inserido!.
     * @return int $variavel = lastInsertId OR False;
     */
    public function getResult() {
        return $this->result;
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
    
    /**
     * Responsavel por criar a sintaxe da Query para Prepared Statement
     */
    private function getSyntax() {
        $fields = implode(', ', array_keys($this->dados));
        $places = ':' . implode(', :', array_keys($this->dados));
        $this->create = "INSERT INTO {$this->tabela} ({$fields}) VALUES ({$places})";
    }
    
    /**
     * Responsável por obter a conexão e a Sintax, então executa a Query
     */
    private function Execute() {
        $this->Connect();
        try{
            $this->create->execute($this->dados);
            $this->result = $this->conn->lastInsertId();            
        } catch (PDOException $e) {
            $this->result = null;
            PROVErro("Erro ao cadastrar: <b>{$e->getMessage()}</b>", $e->getCode());
        }
    }
    
}
