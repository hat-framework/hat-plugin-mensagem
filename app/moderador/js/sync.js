'use strict';
usuario_messageApp.controller('usuario_mensagem_syncCTRL',['$http','$rootScope','getUrlVars',function($http,$rootScope, getUrlVars) {
    var init = function(){
        var add = getUrlVars()["_perfil"];
        if(typeof add !== 'undefined' && add !== ""){add = "&_perfil="+add;}
        else{add = "";}
        var url = window.location.protocol+"//"+window.location.host+"/index.php?ajax=true&url=mensagem/mensagem/data"+add;
        $http({method: 'GET', url: url}).success(function(data) {
            $rootScope.$emit("usuario_message_changeSender" , data.sender);
            $rootScope.$emit("usuario_message_setFriendList", data.friendlist);
            $rootScope.$emit("usuario_message_setGroups"    , data.groups);
            $rootScope.$emit("usuario_message_setFeatures"  , data.features);
        });
    };
    init();
    
}]);
