<?php
session_start();
require('_app/Config.inc.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>LOGIN - PROVISIONAMENTO</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="js/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="js/bootstrap/css/bootstrap-responsive.css" />

        <script src="js/jquery.js"></script>
        <script src="js/bootstrap/js/bootstrap.js"></script>
        <script src="js/jquery-validation-1.14.0/dist/jquery.validate.js"></script>
        <script src="js/maskedinput.js"></script>
        <script src="js/interface.js"></script>
    </head>
    <body class="login">

        <div id="login" style="margin-top: 150px;">
            <div class="boxin span5 offset5">
                <h1 style="text-align: center;">Provisionamento<br>VIAON</h1>

                <?php
                $login = new Login(1);
           
                if($login->CheckLogin()){
                    header('Location: index.php');
                }
                
                $dadosLogin = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if(!empty($dadosLogin['AdminLogin'])){
                    unset($dadosLogin['AdminLogin']);
                    
                    //$dadosLogin['user_senha'] = sha1(md5(base64_encode($dadosLogin['user_senha'])));
                    
                    //var_dump($dadosLogin);
                    //SE O FORMULARIO FOR ENVIADO
                    $login->ExeLogin($dadosLogin);
                    if(!$login->getResult()){
                        //SE O LOGIN FALHAR (RETORNAR ERRO) EXIBE MENSAGEM NA TELA
                        $erro = $login->getError();
                        PROVErro($erro[0], $erro[1]);
                    }else{
                        //SE O LOGIN ESTIVER OK, REDIRECIONA O USUARIO PARA O PAINEL ADMINISTRATIVO
                        header('Location: index.php');
                    }
                }
            
                $get = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
                if(!empty($get)){
                    if($get == 'restrito'){
                        PROVErro("<b>Acesso negado.</b><br> Favor efetuar login para acessar o painel!", MSG_ALERTA);
                    }elseif($get == 'logoff'){
                        PROVErro("<b>Você saiu: </b><br>Sua sessão foi encerrada com sucesso, volte sempre!", MSG_OK);
                    }
                }
                ?>

                <form name="FormLogin" id="FormLogin" action="" method="post">
                    <label class="row-fluid">
                        <span class="span4">Usuário:</span>
                        <input class="span8" type="text" name="user_nome" />
                    </label>

                    <label class="row-fluid">
                        <span class="span4">Senha:</span>
                        <input class="span8" type="password" name="user_senha" />
                    </label>  

                    <input type="submit" value="Logar" class="btn blue" name="AdminLogin" />

                </form>
            </div>
        </div>

    </body>
</html>
