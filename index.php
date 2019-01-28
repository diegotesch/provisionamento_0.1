<?php
session_start();
$msg = filter_input(INPUT_GET, 'msg', FILTER_DEFAULT);
$msg2 = filter_input(INPUT_GET, 'msgpostok', FILTER_DEFAULT);

$empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
$logoff = filter_input(INPUT_GET, 'logoff', FILTER_VALIDATE_BOOLEAN);
require('_app/Config.inc.php');
$getExe = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);

$login = new Login(1);

if(!$login->CheckLogin()){
    //CASO TENHA SIDO ACESSADO SEM EFETUAR LOGIN, RETORNA PARA A PAGINA DE LOGIN E EXIBE MENSAGEM DE ERRO
    unset($_SESSION['userlogin']);
    header('Location: login.php?exe=restrito');
}else{
    //CASO LOGIN ESTEJA CORRETO, ADICIONA A SESSAO A VARIAVEL
    $userlogin = $_SESSION['userlogin'];
}

if($logoff){
    unset($_SESSION['userlogin']);
    Redirecionar('login.php');
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Provissionamento ViaON</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.css">
        <link rel="stylesheet" type="text/css" href="js/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="js/bootstrap/css/bootstrap-responsive.css" />
        

        <script src="js/jquery.js"></script>
        <script src="js/bootstrap/js/bootstrap.js"></script>
        <script src="js/jquery-validation-1.14.0/dist/jquery.validate.js"></script>
        <script src="js/maskedinput.js"></script>
        <script src="js/interface.js"></script>
        
        <!-- Angular Material requires Angular.js Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-aria.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-messages.min.js"></script>

        <!-- Angular Material Library -->
        <script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.js"></script>
                
        <script src='angular_files/app.module.js'></script>
        <script src='angular_files/controllers/alocar-equipamento.controller.js'></script>
        <script src='angular_files/controllers/rma-remove.controller.js'></script>
        <script src='angular_files/controllers/buscar-equipamento.controller.js'></script>
        <script src='angular_files/services/onu.services.js'></script>
    </head>
    <body ng-app="Provisionamento">
        <div class="container-fluid">
            <header class="row-fluid header">
                <a href='index.php'>
                    <figure class="span2">
                        <img src="images/logomarca.png" alt="logomarca viaon" title="logomarca ViaOn" />
                    </figure>
                </a>
                <div class="offset2 span6">
                    <h1>Provissionamento de Equipamentos</h1>
                </div>
                <div class="span2">
                    <a href="index.php?logoff=true">Sair</a>
                </div>
            </header>
            <hr class="linha">
            <?php
            $cancel = filter_input(INPUT_GET, 'cancel', FILTER_VALIDATE_BOOLEAN);
            if($cancel){
                unset($_SESSION['lote']);
                unset($_SESSION['lote_tipo']);
                unset($cancel);
            }
            ?>
            <a href="index.php?cancel=true" class="botao" id="btCancelar">
                <span class="texto">X</span>
            </a>

            <div class="row-fluid conteudo">
                <?php
                if(isset($msg)){
                    PROVErro($msg, MSG_OK);
                }else if(isset ($empty)){
                    PROVErro("Você tentou acessar uma página sem permissão!", MSG_ERRO);
                }else if(isset($msg2)){
                    PROVErro($msg2, MSG_OK);
                }
                //QUERY STRING
                if (!empty($getExe)) {
                    $includepatch = __DIR__ . '/_system/' . strip_tags(trim($getExe) . '.php');
                } else {
                    $includepatch = __DIR__ . '/_system/dash.php';
                }

                if (file_exists($includepatch)) {
                    require_once($includepatch);
                } else {
                    echo "<div class=\"content notfound\">";
                    PROVErro("<b>Erro ao incluir tela:</b><br> Erro ao incluir o controller /{$getExe}.php!", MSG_ERRO);
                    echo "</div>";
                }
                ?>
            </div>

            <hr class="linha">
            <footer class="row-fluid footer">
                <p>Desenvolvido por <span id="me">&COPY;Diego Tesch</span></p>
            </footer>
        </div>
    </body>
</html>
