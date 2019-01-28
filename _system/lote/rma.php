<?php
require_once '_models/AdminOnu.class.php';
$id_onu = filter_input(INPUT_GET, 'id_onu', FILTER_VALIDATE_INT);
$id_lote = filter_input(INPUT_GET, 'lote_id', FILTER_VALIDATE_INT);
$dadosOnu = new AdminOnu;
$dadosOnu->listDadosOnu($id_onu);
$dados = $dadosOnu->getResult();
$dados = $dados[0];
//var_dump($dados);
//echo $id_onu;
?>
<div class="offset3 span6" ng-controller="rmaRemove">
    
    <md-content layout="row">
        <div flex-xs flex-gt-xs="100" layout="column">
            <md-card md-theme="" md-theme-watch>
                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline titulo">Defeito ou RMA</span>
                    </md-card-title-text>
                </md-card-title>
                
                <md-card-content>
                    <span class="md-headline">Equipamento (MAC): <?= $dados['onu_mac'] ?></span>
                    <h5>Informe os dados</h5>

                    <md-input-container class="md-block">
                        <label>Motivo</label>
                        <input class="paliativoInput" type="text" ng-model="defeito.titulo">
                    </md-input-container>

                    <md-input-container class="md-block">
                        <label>Descricao</label>
                        <textarea md-no-resize ng-model="defeito.descricao" md-maxlength="200" ></textarea>
                    </md-input-container>

                </md-card-content>


                <md-card-actions layout="row" layout-align="end center">
                    <md-button ng-click="voltar(<?= $id_lote ?>)">Cancelar</md-button>
                    <md-button ng-click="informarDefeito(defeito, <?= $dados['onu_id'] ?>)">Salvar</md-button>
                </md-card-actions>
            </md-card>
        </div>
    </md-content>
</div>