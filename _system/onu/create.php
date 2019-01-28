<?php
require_once '_models/AdminOnu.class.php';
$postOnu = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$cadLote = filter_input(INPUT_GET, 'cadLote', FILTER_VALIDATE_BOOLEAN);
$loteTipo = filter_input(INPUT_GET, 'lote_tipo', FILTER_DEFAULT);

if (isset($loteTipo)) {
    $_SESSION['lote_tipo'] = $loteTipo;
} else if (isset($_SESSION['lote_tipo'])) {
    if(isset($_SESSION['lote'])){
        extract($_SESSION['lote'][count($_SESSION['lote']) - 1]);
        PROVErro("ONU MAC: {$onu_mac}, foi adicionada ao lote!", MSG_INFO);
    }
} else {
    PROVErro("SELECIONE UM TIPO DE LOTE VÁLIDO!", MSG_ERRO);
    Redirecionar("index.php", 3000);
}


if (isset($postOnu) && isset($postOnu['onu_mac'])) {
    $add = new AdminOnu();
    $add->addOnuToLote($postOnu, $_SESSION['lote_tipo']);
}

if ($cadLote) {
    if ($_SESSION['lote']) {
        /*echo '<pre>';
        var_dump($_SESSION);
        echo '</pre>';*/
        ?>
        <div class='contorno'>        
        </div>
        <div class="offset3 span6" id="modalConfirm">
            <form name="CadConfirm" method="post" id="CadLoteOnu">
                <h3>Confirmar Cadastro de Lote</h3>
                <fieldset class="row-fluid">
                    <label class='cadInfo'>As seguintes ONU's serão cadastradas:</label>
                    <div class='onuList span9'>
                        <?php
                        $i = 0;
                        foreach ($_SESSION['lote'] as $lote) {
                            extract($lote);
                            echo "<span class='onu'>{$onu_mac}</span>";
                            if($i == 3){
                                echo '<br>';
                                $i = 0;
                            }else{
                                echo ' - ';
                                $i++;
                            }
                        }
                        ?>
                    </div>
                </fieldset>
                <fieldset class="row-fluid botoes">
                    <div class='offset4 span5'>
                        <a class="botao" href='gerador.php?send=true'>
                            <span class="texto">Sim</span>
                        </a>
                        <a class="botao" href='?exe=onu/create'>
                            <span class="texto">Não</span>
                        </a>
                    </div>
                </fieldset>
            </form>
        </div>
        <?php
    } else {
        PROVErro("Nenhuma ONU inclusa no lote! Insira ao menos uma e tente novamente!", MSG_INFO);
        Redirecionar("?exe=onu/create", 4000);
    }
}

$onu = new AdminOnu();
?>
<h3 style="text-align: center; margin-bottom: 30px;">CADASTRO DE LOTE DE ONU's Nº.: <?= $onu->checkNumLote(); ?></h3>

<?php
if (isset($_SESSION['lote'])) {
    ?>
    <div class='onuList span4'>
        <b>ONU's no Lote</b>
        <?php
        foreach ($_SESSION['lote'] as $lote) {
            extract($lote);
            echo "<p class='onu'>{$onu_mac}</p>";
        }
        ?>
    </div>
<?php } ?>

<div class='<?= (!isset($_SESSION['lote']) ? "offset4" : "") ?> span5' id="cadLoteOnu">

    <form name="cadLoteOnu" class="form-inline" method="post" action="" id='formCadLoteOnu'>
        <fieldset>
            <label>MAC do Equipamento</label>
            <label>
                <input type="text" name="onu_mac" id="mac" />
            </label>
        </fieldset>
        <fieldset>            
            <a href="?exe=onu/create&cadLote=true" class="botao" id="">
                <span class="texto">Cadastrar Lote</span>
            </a>
        </fieldset>
    </form>


</div>
