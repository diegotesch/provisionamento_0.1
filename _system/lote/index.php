<?php
require_once '_models/AdminOnu.class.php';
$list = new AdminOnu;
$list->listLote();
if($list->getResult()){
$lotes = $list->getResult();
?>
<div class="offset1 span10">
        <?php
        $cont = 0;
        foreach($lotes as $lote){
            $cont++;
            if($cont%6 == 1){
                echo "<div class='row-fluid loteLine'>";
            }
            extract($lote);
            ?>
        <a class="lote span2" href='?exe=lote/view&lote_id=<?= $lote_id ?>'>
            <figure>
                <img class='imgLote' src="images/icone_lotes.png" alt="Lista de ONU's lote Nº.: <?= $lote_id ?>" title="Lista de ONU's lote Nº.: <?= $lote_id ?>" />
            </figure>
            <p class='loteDesc'>Lote Nº.: <span class='loteNumber'><?= $lote_id ?></span><br>Modelo:<br> <?= $lote_tipo ?></p>
            <p class='loteData'><?= date('d/m/Y H:i:s', strtotime($lote_data)) ?></p>
        </a>
        <?php
            if($cont%6 == 0){
                echo "</div>";
            }
        }
        ?>
    </div>
</div>

<?php
}