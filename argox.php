<?php
session_start();
include_once './_app/Config.inc.php';
include_once './_models/AdminOnu.class.php';

$lote_id = filter_input(INPUT_GET, 'lote_id', FILTER_VALIDATE_INT);
$onus = filter_input_array(INPUT_GET, FILTER_DEFAULT);
$etq = array();

if($lote_id == null){
    $print = new AdminOnu();
    $print->geraEtiquetaPrn($onus['onus']);
    /*foreach ($onus['onus'] as $c => $v){
        

        array_push($etq, $print->getResult()[0]);
    }*/
    
    //var_dump($print);
}else if($lote_id != null){
    $print = new AdminOnu();
    $print->listOnuPorLote($lote_id);
    //var_dump($print);
    $onus = array();
    foreach($print->getResult() as $onu){
        array_push($onus, $onu['onu_id']);
    }
    $print->geraEtiquetaPrn($onus);
}

