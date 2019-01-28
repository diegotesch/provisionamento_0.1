<?php

/**
 * <b>Bairro [ MODEL ]</b>
 * Responsavel por listar, cadastrar e editar os bairros
 * @copyright (c) 2016, Diego Tesch 
 */
class Bairro {
    private $id;
    private $dados;
    private $error;
    private $result;

    /*function __construct($bairro {
        $this->level = (int) $level;
    }*/
    
    public function getBairro($id_bairro = null) {
        $this->id = (!is_null($id_bairro) ? (int) $id_bairro : null);
        $read = new Read;
        $read->ExeRead('bairro', (!is_null($this->id) ? "WHERE bairro_id = :b_id" : null), (!is_null($this->id) ? "b_id={$this->id}" : null));
        if($read->getResult()){
            $this->result = $read->getResult();
        }else{
            $this->result = false;
            $this->error = array("Nenhum bairro encontrado!", MSG_INFO);
        }
    }

        
    function getError() {
        return $this->error;
    }

    function getResult() {
        return $this->result;
    }
    
    //PRIVATES

}
