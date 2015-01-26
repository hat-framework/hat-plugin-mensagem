'use strict';
message_userApp.controller('message_user_syncCTRL',['$http','$rootScope','getUrlVars',function($http,$rootScope, getUrlVars) {
    
    var init = function(){
        var add = getUrlVars()["_perfil"];
        if(typeof add !== 'undefined' && add !== ""){add = "&_perfil="+add;}
        else{add = "";}
        var url = window.location.protocol+"//"+window.location.host+"/index.php?ajax=true&url=mensagem/mensagem/data"+add+"&type=user";
        $http({method: 'GET', url: url}).success(function(data) {
            for(var i in data){
                var str = "message_user_"+i;
                $rootScope.$emit(str, data[i]);
                //console.log(str, data[i]);
            }
        });
    };
    init();
    
}]);
