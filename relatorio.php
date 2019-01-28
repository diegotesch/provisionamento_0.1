<?php
session_start();
include_once './_models/AdminOnu.class.php';
include_once './_app/Config.inc.php';

$id_lote = filter_input(INPUT_GET, 'lote_id', FILTER_VALIDATE_INT);

$rel = new AdminOnu();
$rel->listOnuPorLote($id_lote);
//var_dump($rel->getResult());
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
        
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="4">
                                Relatório de ONU's Lote Nº <?= $id_lote; ?>
                            </th>
                            <th>
                                Total: <?= count($rel->getResult()) ?>
                            </th>
                        </tr>
                        <tr>
                            <th>MAC</th>
                            <th>SSID</th>
                            <th>SENHA WIFI</th>
                            <th>PPPoE User</th>
                            <th>PPPoE Senha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($rel->getResult() as $onu){
                            extract($onu);
                            ?>
                        <tr>
                            <td><?= $onu_mac; ?></td>
                            <td><?= $onu_ssid; ?></td>
                            <td><?= $onu_wifi; ?></td>
                            <td><?= $onu_user_pppoe; ?></td>
                            <td><?= $onu_senha_pppoe; ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>             
                
            </div>
        </div>
    </body>
</html>

