angular
    .module('Provisionamento')
    .controller('alocarEquipamento', function($scope, onu, $window){
        
        $scope.titulo = "Alocamento de Equipamentos";

        $scope.listTecnicos = [{nome: 'jovane'}, {nome: 'wendel'}];
        
        $scope.macsSel = [];
        
        onu.getMacList().success(function(data){
                $scope.listMac = data;
            }).error(function (data, status) {
                alert('Erro na consulta ' + status + '\n' + data);
            }).finally(function () {});
        
        $scope.isMacSelecionado = function(listMac){
            return listMac.some(function(mac){
                return !mac.selecionado;
            });
        };
        
        $scope.alocarEquipamentos = function(responsavel, equipamentos){
            $scope.post = [{responsavel},{equipamentos}];
            //console.log($scope.post);
            onu.send($scope.post).success(function(data){
                console.log(data);
                if(data == '1'){
                    $window.location.href = 'index.php?msgpostok="Equipamentos alocados com sucesso!"';
                }else{
                    alert('Erro ao alocar equipamentos, contate o administrador do sistema');
                }
            });
        };
        
        $scope.macsSelected = function(mac){
            /*angular.forEach($scope.listMac, function(value, key){
                if(value.selecionado){
                    $scope.macsSel.push(value);
                }
            });*/
            if(mac.selecionado){
                $scope.macsSel.push(mac);
            }else{
                $scope.macsSel.splice($scope.macsSel.indexOf(mac), 1);
            }
        };
        
});
