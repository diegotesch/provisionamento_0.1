<?php

/**
 * <b>Classe Read</b>
 * Classe responsável por leituras no banco de dados
 * [SELECT]
 * @copyright (c) 2016, Diego Tesch 
 */
class Read extends Conn {

    private $select;
    private $places;
    private $result;

    /** @var PDOStatemet  */
    private $read;

    /** @var PDO */
    private $conn;

    /**
     * <b>ExeRead:</b> 
     * Executa a leitura de uma tabela no banco de dados utilizando prepared statements.
     * Basta Informar o nome da tabela, os termos para a consulta (não obrigatorio) e os indices seguido por seus respectivos valores
     * 
     * @param String $tabela = Informe o nome da tabela no banco!
     * @param String $termos = Informe os termos da consulta (Ex: WHERE campo = valor AND campo2 = valor2)
     * @param String $ParseString = Informe a associacao chave valor em formato string para a leitura da tabela (Ex: campo=valor&campo2=valor2)
     */
    public function ExeRead($tabela, $termos = null, $ParseString = null, $alias = "*") {
        if(!empty($ParseString)){
            parse_str($ParseString, $this->places);
        }
        
        $this->select = "SELECT {$alias} FROM {$tabela} {$termos}";
        $this->Execute();
    }
    
    
    /**
     * <b>Obter Resultado</b>
     * retorna o resultado da consulta em formato de array
     * @return array $variavel = resultado OR False;
     */
    public function getResult() {
        return $this->result;
    }
    
    /**
     * <b>Obtem o numero de resultados obtidos</b>
     * retorna o numero de resultados obtidos pela consulta
     * @return int $resultado
     */
    public function getRowCount(){
        return $this->read->rowCount();
    }
    
    /**
     * <b>FullRead</b>
     * Utilizada para fazer a leitura da tabela, assim como ExeRead, porém utilizando a query em seu formato completo
     * @example $var->FullRead("SELECT * FROM tabela WHERE campo = :campo AND campo2 = :campo2", "campo=valor&campo2=valor2");
     * @param String $query
     * @param String $ParseString
     */
    public function FullRead($query, $ParseString = null) {
        $this->select = (string) $query;
        if(!empty($ParseString)){
            parse_str($ParseString, $this->places);
        }
        $this->Execute();
    }
    
    /**
     * <b>setPlaces</b>
     * Utilizada para modificar os termos da consulta
     * @param String $ParseString
     * @example  $var->setPlaces("campo=valor&campo2=valor2&campo3=valor3");
     */
    public function setPlaces($ParseString) {
        parse_str($ParseString, $this->places);
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
        $this->read = $this->conn->prepare($this->select);
        $this->read->setFetchMode(PDO::FETCH_ASSOC);
    }
       
    
    
    /**
     * Responsavel por criar a sintaxe da Query para Prepared Statement
     */
    private function getSyntax() {
        if($this->places){
            foreach($this->places as $vinculo => $valor){
                if($vinculo == 'limit' || $vinculo == 'offset'){
                    $valor = (int) $valor;
                }
                
                $this->read->bindValue(":{$vinculo}", $valor, (is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR));
            }
        }
    }
    
    /**
     * Responsável por obter a conexão e a Sintax, então executa a Query
     */
    private function Execute() {
        $this->Connect();
        try{
            $this->getSyntax();
            $this->read->execute();
            
            $this->result = $this->read->fetchAll();
        } catch (PDOException $e) {
            $this->result = null;
            PROVErro("Erro ao selecionar: <b>{$e->getMessage()}</b>. Na Linha #<b>{$e->getLine()}</b>", $e->getCode());
        }
    }
    
}
