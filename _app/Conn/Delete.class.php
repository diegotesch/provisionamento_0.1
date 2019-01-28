<?php

/**
 * <b>Classe Delete</b>
 * Classe responsável por deletear dados no banco de dados
 * [DELETE]
 * @copyright (c) 2016, Diego Tesch 
 */
class Delete extends Conn {

    private $tabela;
    private $termos;
    private $places;
    private $result;

    /** @var PDOStatemet  */
    private $delete;

    /** @var PDO */
    private $conn;

    /**
     * <b>ExeDelete:</b> 
     * Executa a atualização de dados no banco de dados utilizando prepared statements.
     * É obrigatorio informar o nome da tabela, um array atribuitivo contendo nome do campo => valor do campo, termos da consulta, e o parse de chave=valor a ser alterado
     * 
     * @param String $tabela = Informe o nome da tabela no banco!
     * @param Array $dados = Array atribuitivo (Nome da Coluna => Valor)
     * @param String $termos = Informe os termos da consulta (Ex: WHERE campo = :campo AND campo2 = :campo2)
     * @param String $ParseString = Informe a associacao chave valor em formato string para a leitura da tabela (Ex: campo=valor&campo2=valor2)
     */
    public function ExeDelete($tabela, $termos, $ParseString) {
        $this->tabela = (string) $tabela;
        $this->termos = (string) $termos;
        
        parse_str($ParseString, $this->places);
        $this->getSyntax();
        $this->Execute();
    }
    
    /**
     * <b>getResult</b>
     * retorna true caso a atualização tenha sido bem sucedida ou false, caso algum erro tenha ocorrido
     * @return Boolean
     */
    public function getResult() {
        return $this->result;
    }
    
    /**
     * <b>getRowCount()</b>
     * Retorna o numero de linhas atualizadas pela consulta
     * @return int $row
     */
    public function getRowCount(){
        return $this->delete->rowCount();
    }
    
    /**
     * <b>setPlaces</b>
     * Utilizada para modificar os termos da consulta
     * @param String $ParseString
     * @example  $var->setPlaces("campo=valor&campo2=valor2&campo3=valor3");
     */
    public function setPlaces($ParseString) {
        parse_str($ParseString, $this->places);
        $this->getSyntax();
        $this->Execute();
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
        $this->delete = $this->conn->prepare($this->delete);
    }
    
    /**
     * Responsavel por criar a sintaxe da Query para Prepared Statement
     */
    private function getSyntax() {
        $this->delete = "DELETE FROM {$this->tabela} {$this->termos}";
    }
    
    /**
     * Responsável por obter a conexão e a Sintax, então executa a Query
     */
    private function Execute() {
        $this->Connect();
        try{
            $this->delete->execute($this->places);
            $this->result = true;
        } catch (PDOException $e) {
            $this->result = null;
            CRMErro("Erro ao deletar: <b>{$e->getMessage()}</b>", $e->getCode());
        }
    }
    
}
