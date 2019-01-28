<?php
session_start();
$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$id_lote = filter_input(INPUT_GET, 'lote_id', FILTER_VALIDATE_INT);
$control = filter_input(INPUT_GET, 'control', FILTER_VALIDATE_BOOLEAN);

//var_dump($post);


require('_app/Config.inc.php');
require_once '_models/AdminOnu.class.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Provissionamento de ONU</title>
        <link rel="stylesheet" type="text/css" href="css/etiquetas.css" media='print'/>
        <link rel="stylesheet" type="text/css" href="js/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="js/bootstrap/css/bootstrap-responsive.css" />

        <script src="js/jquery.js"></script>
        <script src="js/bootstrap/js/bootstrap.js"></script>
        <script src="js/jquery-validation-1.14.0/dist/jquery.validate.js"></script>
        <script src="js/maskedinput.js"></script>
        <script src="js/interface.js"></script>
        
        <style media="screen">
            #etiquetas{display: none;}
            
            /*BOTAO*/
            .botao{text-align: center; background-color: #2f9892; color: #FFF; border-radius: 3px; display: inline-block;padding-bottom: 5px;padding-top: 15px;padding-left: 10px; padding-right: 10px; cursor: pointer; transition: 0.5s linear;}
            .botao .icone img{width: 30px;height: 30px;position: relative;top: -5px;}
            .botao .texto{font-size: 1.5em; font-family: "Trebuchet MS"; text-align: center !important;}  
            .botao:hover{background-color: #e86936; text-decoration: none !important; color: #FFF !important;}
            /*FIM BOTAO*/
        </style>
        
        <style media="print">
            div#botoesPrint, .info{display: none !important;}
        </style>
    </head>
    <body>
       <div class='row-fluid' id='botoesPrint'>
            <div class='offset3 span6'>
                <a class="botao span4" onclick="window.print()">
                    <span class="texto">Etiquetas Gráficas</span>
                </a>
                
                <?php
                if($_SESSION['userlogin']['user_level'] != 1){
                    ?>
                <a class="botao span4" href='argox.php?<?= ($control != null ? 'lote_id='.$id_lote : http_build_query(array('onus' => $post['onu']))); ?>'>
                    <span class="texto">Imprimir Etiquetas</span>
                </a>
                <?php
                }
                ?>

                <a class="botao span4" href='index.php'>
                    <span class="texto">Pagina Principal</span>
                </a>
            </div>
        </div>
        
        <div id='etiquetas'> 
        <?php

if(isset($post) && isset($post['control']) && $post['control'] == 'true'){
    $controle = 0;
    foreach($post['onu'] as $onu){
        $controle++;
        $list = new AdminOnu();
        $list->listDadosOnu($onu);
        $dadosOnu = $list->getResult();
        $tipoLote = $list->getTipoLote($dadosOnu[0]['lote_id']);
        extract($dadosOnu[0]);
        
        
        ?>

        <div class='etq <?= ($controle%2 == 0 ? "dir" : "") ?>'>
            <div class='coluna1'>
                <h5>Atenção</h5>
                <p>Caso ocorram problemas de conexão,<br>
                    desligue e religue todos os equipamentos.<br>
                    <b>Nunca resete o equipamento.</b></p>
                <?= ($tipoLote != '1GEZ' ? "<p class='info'>Nome da Rede: <b>{$onu_ssid}</b> <br> Senha: <b>{$onu_wifi}</b></p>" : ''); ?>
            </div>
            <div class='coluna2'>
                <img src="images/logomarca.png" />
                <p class='comodato'>Equipamento em comodato<br>Venda Proibida</p>
            </div>
            
        </div>
<?php

    }
}else if(isset($id_lote) && isset($control) && $control == true){
    $listLote = new AdminOnu();
    $tipoLote = $listLote->getTipoLote($id_lote);
    $listLote->listOnuPorLote($id_lote);
    $controle = 0;
    foreach($listLote->getResult() as $onuL){
        $controle++;
        extract($onuL);
        ?>

        <div class='etq <?= ($controle%2 == 0 ? "dir" : "") ?>'>
            <div class='coluna1'>
                <h5>Atenção</h5>
                <p>Caso ocorram problemas de conexão,<br>
                    desligue e religue todos os equipamentos.<br>
                    <b>Nunca resete o equipamento.</b></p>
                <?= ($tipoLote != '1GE Z' ? "<p class='info'>Nome da Rede: <b>{$onu_ssid}</b> <br> Senha: <b>{$onu_wifi}</b></p>" : ''); ?>
            </div>
            <div class='coluna2'>
                <img src="images/logomarca.png" />
                <p class='comodato'>Equipamento em comodato<br>Venda Proibida</p>
            </div>
            
        </div>
<?php
    }
}else{
    Redirecionar("index.php?exe=lote/index&empty=true");
}
?>
    </div>
</body>
</html>
