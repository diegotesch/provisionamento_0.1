<?php
session_start();

$send = filter_input(INPUT_GET, 'send', FILTER_VALIDATE_BOOLEAN);
require_once './_app/Config.inc.php';
require_once '_models/AdminOnu.class.php';

//var_dump($send, $_SESSION);
if($send){
    $cadLote = new AdminOnu();
    
    //var_dump($_SESSION);
    $cadLote->cadLote($_SESSION['lote'], $_SESSION['lote_tipo']);
    
    
    $cadLote->listOnuPorLote($cadLote->getResult());
    
    foreach($cadLote->getResult() as $onu){
        $gera = new AdminOnu();
        $gera->gerarArquivosDeConfiguracao($onu);
    }
    //$cadLote->listOnuPorLote($cadLote->getResult());
    //var_dump($cadLote);
    Redirecionar("index.php?msg=Lote Cadastrado com sucesso no sistema");
}

