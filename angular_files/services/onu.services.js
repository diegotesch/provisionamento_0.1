angular
    .module('Provisionamento')
    .factory("onu", function($http){
        
        var _getMacList = function(){
            return $http.get('http://provisionamento.intercol.com.br/api/get.php/mac');
        };
        
        var _alocarEquipamentos = function(post){
            return $http({
               method: 'post',
               url: 'http://provisionamento.intercol.com.br/api/get.php/alocar',
               data: post,
               headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            });
        };
        
        var _defeitoInfo = function(post){
            return $http({
                method: 'post',
                url: 'http://provisionamento.intercol.com.br/api/get.php/defeito',
                data: post,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            });
        };
        
        var _findEquipamento = function(mac = null, name = null){
            return $http.get('http://provisionamento.intercol.com.br/api/get.php/find/'+mac+'/'+name);
        };
        
        return{
            getMacList: _getMacList,
            send: _alocarEquipamentos,
            defeito: _defeitoInfo,
            localizar: _findEquipamento
        };
    });