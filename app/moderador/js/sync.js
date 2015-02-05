'use strict';
usuario_messageApp.controller('usuario_mensagem_syncCTRL',['$api','$rootScope',function($api,$rootScope) {
    var init = function(){
        $api.execute('msg_data', function(data){
            $rootScope.$emit("usuario_message_changeSender"  , data.sender);
            $rootScope.$emit("usuario_message_setFrinedTotal", data.friendsPageCount);
            $rootScope.$emit("usuario_message_setFriendList" , data.friendlist);
            $rootScope.$emit("usuario_message_setGroups"     , data.groups);
            $rootScope.$emit("usuario_message_setFeatures"   , data.features);
            
        });
    };
    init();
}]);
