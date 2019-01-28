<div class='row-fluid' ng-controller='buscarEquipamento'>
    
    <div class="offset2 span8">
        <md-content layout="row" class="noOverflow">
            <div flex-xs flex-gt-xs="100" layout="column">
                <md-card md-theme="" md-theme-watch>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline titulo">{{ titulo }}</span>
                        </md-card-title-text>
                    </md-card-title>
                    
                    <md-card-content>
                        
                        <form name="formFind" >
                            <md-input-container class="md-block">
                                <label>Mac do equipamento:</label>
                                <input placeholder="24a43cdbe681" name="mac" type="text" ng-model='encontrar.mac' class='paliativoInput' md-autofocus />
                            </md-input-container>
                            <md-input-container class="md-block">
                                <label>Alocada para:</label>
                                <input placeholder="Fulano de Tal" name="nome" type="text" ng-model='encontrar.nome' class='paliativoInput' />
                            </md-input-container>
                            <md-card-actions>
                                <md-button ng-disabled='checkParams(encontrar.mac, encontrar.nome)' class="md-warn md-raised" ng-click="locateEquipamento(encontrar)">Buscar</md-button>
                            </md-card-actions>
                        </form>
                        
                    </md-card-content>
                    
                    <md-card-content>
                        
                        <table class='table table-hover table-bordered listOnuLote' ng-if='showTable'>
                            <thead>
                                <tr>
                                    <th>MAC</th>
                                    <th>Alocado Para</th>
                                    <th>Status Onu</th>
                                    <th colspan='3'>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                            <form name='etiquetas_onu' method='post' action='' id='formEtiquetasOnu'>
                                <input type='hidden' name='control' value="true" />
                                
                                <tr ng-if='teste.colspan'>
                                    <td colspan="6">{{ teste.msg }}</td>
                                </tr>
                                
                                <tr ng-if='!teste.colspan' ng-repeat="eq in teste">
                                    <td>{{ eq.onu_mac }}</td>
                                    <td>{{ eq.onu_cliente }}</td>
                                    <td>{{ eq.onu_status2 }}</td>
                                    <td><a href='{{ eq.onu_file_conf }}' target="_blank" title='Download Configuração ONU {{ eq.onu_mac }}'><i class='icon-download-alt'></i></a></td>
                                    <td style="display: <?= ($_SESSION['userlogin']['user_level'] > 1 ? 'block' : 'none' ) ?>">
                                        <a ng-if="eq.onu_status == '3'" title="Equipamento com defeito, opção indisponível" href='javascript:void(0);' alt='' ><i class="icon-warning-sign"></i></a>
                                        <a ng-if="eq.onu_status != '3'" title="" href='?exe=lote/rma&lote_id={{ eq.lote_id }}&id_onu={{ eq.onu_id }}'><i class="icon-warning-sign"></i></a>
                                    </td>
                                    <td><a href='javascript:void(0);' ng-click="showDados(eq)" title='Mostrar Dados'><i class='icon-eye-open'></i></a></td>
                                </tr>
                            </form>
                            </tbody>
                        </table>
                        
                    </md-card-content>

                </md-card>
            </div>
        </md-content>
        
    </div>
</div>

