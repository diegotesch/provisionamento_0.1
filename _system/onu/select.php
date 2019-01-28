<?php
require_once '_models/AdminOnu.class.php';
$postSel = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if(isset($postSel) && $postSel['onu_tipo'] != 'null'){
    $postSel = array_map('strtoupper', $postSel);
    $postSel = array_map('trim', $postSel);
    Redirecionar("?exe=onu/create&lote_tipo={$postSel['onu_tipo']}");
}else if($postSel['onu_tipo'] == 'null'){
    PROVErro("SELECIONE UM TIPO DE ONU VÁLIDO", MSG_INFO);
}
?>

<div class='offset4 span5' id="selOnuType">
    <h3 style="text-transform: uppercase;">Selecione o Tipo de Onu`s do Lote</h3>
    <form name="selOnuType" class="form-inline" method="post" action="" id='formSelOnuType'>
        <fieldset>
            <label>Tipo de Onu</label>
            <label>
                <select name="onu_tipo" style="text-transform: uppercase;">
                    <option value="null">SELECIONE O TIPO DE ONU</option>
                    <option value="v2801hw">v2801hw</option>
                    <option value="28hw">28hw</option>
                    <option value="28hwv2">28hw_v2</option>
                    <option value="tplink">Modem TP-LINK</option>
                    <option value="1gez">1GE (Z)</option>
                </select>
            </label>
        </fieldset>
        <fieldset>            
            <div class="botao" id="SelectOnuType">
                <span class="texto">Selecionar</span>
            </div>
        </fieldset>
    </form>
</div>
