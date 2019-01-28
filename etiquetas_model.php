<?php
/* session_start();
  $msg = filter_input(INPUT_GET, 'msg', FILTER_DEFAULT); */
$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
require('_app/Config.inc.php');

if(isset($post) && isset($post['control']) && $post['control'] == 'true'){
    var_dump($post);
}
//$getExe = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
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
        <?php
        for ($i = 0; $i < 7; $i++) {
            ?>
            <div class='etq'>
                <img src="images/logomarca.png" />
                <h5>Atenção</h5>
                <p>Caso ocorram problemas de conexão,<br>
                    desligue e religue todos os equipamentos.<br>
                    <b>Nunca resete o equipamento.</b></p>
                <p class='info'>Nome da Rede: <b>REDEWIFI</b> | Senha: <b>SENHAWIFI</b></p>
            </div>
            <div class="gap"></div>
            <div class='etq dir'>
                <img src="images/logomarca.png" />
                <h5>Atenção</h5>
                <p>Caso ocorram problemas de conexão,<br>
                    desligue e religue todos os equipamentos.<br>
                    <b>Nunca resete o equipamento.</b></p>
                <p class='info'>Nome da Rede: <b>REDEWIFI</b> | Senha: <b>SENHAWIFI</b></p>
            </div>
            <?php
        }
        ?>
    </body>
</html>
