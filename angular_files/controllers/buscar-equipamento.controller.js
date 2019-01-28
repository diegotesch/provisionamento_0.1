angular
    .module('Provisionamento')
    .controller('buscarEquipamento', function($scope, onu, $window, $mdDialog){
        
        $scope.titulo = "Localizar Equipamentos";
        $scope.acesso = true;
        $scope.teste = [];
        var alert;
        $scope.showDados = showDados;

        $scope.locateEquipamento = function(encontrar){
            if(!encontrar.mac || encontrar.mac === ''){
                encontrar.mac = null;
            }
            
            if(!encontrar.nome || encontrar.nome === ''){
                encontrar.nome = null;
            }
            onu.localizar(encontrar.mac, encontrar.nome).success(function(data){
                $scope.teste = data;
                
                angular.forEach($scope.teste, function(value, key){
                    //console.log(value + " = "+key+ "<br>");
                    
                   if(value.onu_status == '1'){
                        $scope.teste[key].onu_status2 = 'Equipamento em estoque';
                    }else if(value.onu_status == '0'){
                        $scope.teste[key].onu_status2 = 'Posse Técnica';
                    }else if(value.onu_status == '3'){
                        $scope.teste[key].onu_status2 = "DEFEITO / RMA";
                    }else{
                        $scope.teste[key].onu_status2 = "Alocada";
                    }

                });
            }).error(function(data, status){
                alert('Erro na consulta ' + status + '\n' + data);
            }).finally(function () {
                if($scope.teste.length <= 0){
                    $scope.teste.colspan = true;
                    $scope.teste.msg = "A busca não retornou nenhum resultado!";
                }
                $scope.showTable = true;
            });
        };
        
        $scope.checkParams = function(par1 = null, par2 = null){
            if((par1 || par2) && (par1 !== '' || par2 !== '')){
                //console.log(par1, par2);
                return false;
            }else{
                //console.log("else: "+par1, par2);
                return true;
            }
        };
        
        function showDados(dados){
            alert = $mdDialog.alert({
               template: '<md-card md-theme="" md-theme-watch>'+
                            '<md-card-title>'+
                                '<md-card-title-text>'+
                                    '<b>DADOS DO EQUIPAMENTO<b>'+
                                '</md-card-title-text>'+
                            '</md-card-title>'+
                            '<md-card-content>'+
                                '<p><b>MAC: </b>'+dados.onu_mac+'</p>'+
                                '<p><b>SSID: </b>'+dados.onu_ssid+'</p>'+
                                '<p><b>SENHA WI-FI: </b>'+dados.onu_wifi+'</p>'+
                                '<p><b>USER PPPoE: </b>'+dados.onu_user_pppoe+'</p>'+
                                '<p><b>SENHA PPPoE: </b>'+dados.onu_senha_pppoe+'</p>'+
                            '</md-card-content>'+
                            '<md-card-actions>'+
                                '<md-button class="md-warn md-raised" ng-click="closeModal()">Fechar</md-button>'+
                            '</md-card-actions>'+
                        '</md-card>',
                controller: modalController
            });
            
            function modalController($scope, $mdDialog){
                $scope.closeModal = function(){
                    $mdDialog.hide();
                };
            }
            
            $mdDialog.show(alert)
                    .finally(function(){
                        alert = undefined;
                    });
        };
});
