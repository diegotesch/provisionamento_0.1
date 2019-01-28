angular
    .module('Provisionamento')
    .controller('rmaRemove', function($scope, onu, $window){
        
        $scope.informarDefeito = function(defeito, id){
            $scope.defeito = defeito;
            $scope.defeito.onu_id = id;
            //console.log($scope.defeito);
            onu.defeito($scope.defeito).success(function(data){
                console.log(data);
                if(data == '1'){
                    $window.location.href = 'index.php?msgpostok="Defeito Informado com sucesso"';
                }else{
                    alert('Erro ao informar defeito, contate o administrador do sistema');
                }
            });
            
        };
        
        $scope.voltar = function(id){
            if(id){
                $window.location.href = 'index.php?exe=lote/view&lote_id='+id;
            }
        }
});