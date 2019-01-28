<div class="span12 dash">
    <div class="row-fluid">
        <?php
        
        //var_dump($_SESSION);
        if($_SESSION['userlogin']['user_level'] == 3){
            ?>
        <div class="span3 linkDash">
            <a href="?exe=onu/select">
                <img style="height: 250px; width: 300px;" src="images/img_lote.png" />
                <p>Cadastrar Lote de ONU's</p>
            </a>
        </div>
        <?php
        }
        ?>
        
        <!--<div class="offset<?=($_SESSION['userlogin']['user_level'] == 3 ? '1' : ($_SESSION['userlogin']['user_level'] == 1 ? '5' : '3')); ?> span3 linkDash">
            <a href="?exe=lote/index">
                <img src="images/img_relatorio.jpg" />
                <p>Verificar Lotes Cadastrados</p>
            </a>
        </div>-->
        
        <div class="<?= ($_SESSION['userlogin']['user_level'] == 1 ? 'offset3 ' : ($_SESSION['userlogin']['user_level'] == 2 ? 'offset2 ' : ''))?>span3 linkDash">
            <a href="?exe=lote/index">
                <img style="height: 250px; width: 300px;" src="images/img_relatorio.jpg" />
                <p>Verificar Lotes Cadastrados</p>
            </a>
        </div>
        
        <?php
        if($_SESSION['userlogin']['user_level'] > 1){
        ?>
        <div class="span3 linkDash">
            <a href="?exe=onu/alocar">
                <img style="height: 250px; width: 300px;" src="images/img_alocar.jpg" />
                <p>Alocar Equipamentos</p>
            </a>
        </div>
        <?php
        }
        ?>
        
        <div class="span3 linkDash">
            <a href="?exe=onu/buscar">
                <img style="height: 250px; width: 300px;" src="images/img_busca.jpg" />
                <p>Localizar Equipamentos</p>
            </a>
        </div>
    </div>
</div>
