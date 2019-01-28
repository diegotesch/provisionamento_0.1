<?php
session_start();
include_once './_app/Config.inc.php';
include_once './_models/AdminOnu.class.php';


/*$lote = new AdminOnu;
$lote->listOnuPorLote(1);


foreach($lote->getResult() as $onu){
    $lote->RegerarConf($onu);
    //var_dump($onu);
}*/

//var_dump($lote);

$lote = new AdminOnu;
$lote->listOnuPorLote(9);

//var_dump($lote);

foreach($lote->getResult() as $onu){
    $o = new AdminOnu();
    $o->ajustarConf($onu);
}


