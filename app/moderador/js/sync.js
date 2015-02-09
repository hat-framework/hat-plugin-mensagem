'use strict';
usuario_messageApp.controller('usuario_mensagem_syncCTRL',['$api','$rootScope',function($api,$rootScope) {
    var init = function(){
        $api.execute('msg_data', function(data){
            $rootScope.$emit("usuario_message_changeSender"  , data.sender);
            $rootScope.$emit("usuario_message_setFriendTotal", data.friendsPageCount);
            $rootScope.$emit("usuario_message_setFriendList" , data.friendlist);
            $rootScope.$emit("usuario_message_setGroups"     , data.groups);
            $rootScope.$emit("usuario_message_setFeatures"   , data.features);
        });
    };
    init();
    
    $api.execute('msg_notifier', function(response){
        var labels = {a:'label-info',c:'label-success',i:'label-warning'};
        var totais = {};
        for(var i in response){
            for(var j in response[i]){
                if(response[i][j] < 0){continue;}
                if(typeof totais[j] === 'undefined'){totais[j] = 0;}
                totais[j]+=response[i][j];
                var label = (typeof labels[j] === 'undefined')?'label-success':labels[j];
                var temp = $('#a_'+i).children('.'+label);
                if(temp.length === 0){
                    var txt = $('#a_'+i).html() + " <span class='badge "+label+"'>"+response[i][j]+"</span>";
                    $('#a_'+i).html(txt);
                }else{temp.html(response[i][j]);}
            }
        }
        $('#a_messages > .badge').html('');
    }, 'messages');
}]);
