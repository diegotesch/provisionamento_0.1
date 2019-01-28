<?php
$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($post) AND $post != null) {
    require('_app/Config.inc.php');
    require_once '_models/AdminOnu.class.php';
    var_dump($post);
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Provissionamento de ONU</title>
            <link rel="stylesheet" type="text/css" href="css/style.css" media='print'/>
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
                    <div class="offset4 span4" id="alocaTecnico">
                        <form name="alocaTecnico" method="post" action='' id="formAlocaTecnico">
                            <h3>Alocar Onu's: (x, x, x)</h3>
                            <fieldset class="row-fluid">
                                <label class='offset1 span3'>Técnico</label>   
                                <label class='span3'><input type="text" name="onu_cliente" /></label>
                            </fieldset>
                            <fieldset class="row-fluid botoes">
                                <input type="submit" class='offset2 span5 botao' value="Alocar Onu" />
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>


        </body>
    </html>
    <?php
} else {
    Redirecionar("index.php?exe=lote/index&empty=true");
}

