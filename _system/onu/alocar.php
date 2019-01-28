<div class='row-fluid' ng-controller='alocarEquipamento'>
    
    <div class="offset2 span8">
        <md-content layout="row" class="noOverflow">
            <div flex-xs flex-gt-xs="100" layout="column">
                <md-card md-theme="" md-theme-watch>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline titulo">Alocar Equipamentos</span>
                            <md-input-container class="md-block" flex-gt-sm>
                                <label>Selecione um técnico:</label>
                                <md-select ng-model="tecnico">
                                    <md-option ng-repeat="tecnicos in listTecnicos" value="{{tecnicos.nome}}">
                                        {{tecnicos.nome | uppercase}}
                                    </md-option>
                                </md-select>
                            </md-input-container>
                            
                            <div class='boxList' ng-if='tecnico'>
                                <span class="md-headline">Selecione equipamentos por MAC</span>
                                <md-input-container class="md-block">
                                    <label>Filtrar:</label>
                                    <input placeholder="24a43cdbe681" type="text" ng-model='findEquipamento' class='paliativoInput' />
                                </md-input-container>
                                
                                <md-input-container class="md-block" flex-gt-sm >
                                    <label>Lista de equipamentos:</label>
                                    <md-list-item ng-repeat="mac in listMac | filter:findEquipamento">
                                        <p ng-class="{negrito: mac.selecionado}"> {{ mac.onu_mac }} </p>
                                        <md-checkbox class="md-primary" ng-model="mac.selecionado" aria-label='{{ mac.onu_mac }}' ng-change="macsSelected(mac)" ></md-checkbox>
                                    </md-list-item>
                                </md-input-container>
                            </div>
                        </md-card-title-text>
                        
                        <md-card-title-media>
                            
                            <div class="md-media-lg card-media">
                                <div class='mcsSel' ng-if="macsSel !== ''" >
                                    <!--{{ macsSel }}-->
                                    <md-chips ng-repeat="sel in macsSel">
                                        <md-chip ng-model='sel'>
                                            {{ sel.onu_mac }} 
                                        </md-chip>
                                    </md-chips>
                                </div>
                            </div>
                        </md-card-title-media>
                        
                    </md-card-title>
                    
                    <md-card-actions layout="row" layout-align="end center">
                        <md-button class='md-raised' ng-click="alocarEquipamentos(tecnico, macsSel)">Alocar</md-button>
                    </md-card-actions>
                    
                </md-card>
                
            </div>
        </md-content>
        
    </div>
</div>