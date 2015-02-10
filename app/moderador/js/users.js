'use strict';
usuario_messageApp.controller('usuario_mensagem_usersCTRL',[
    '$scope','$location','$rootScope', '$timeout','$api',
function($scope,$location,$rootScope, $timeout, $api) {
    if(typeof $scope.busy !== 'undefined'){return;}
    $scope.busy       = false;
    $scope.useractive = false;
    $scope.hideForm   = false;
    $scope.page       = 1;
    $scope.search     = '';
    $scope.last       = 0;
    
    var getType = function(param){
        var v = $location.path().split('/');
        if(v.length < 2){
            return (typeof param !== 'undefined')?"":['',''];
        }
        var out = [];
        out.push(v[1]);
        out.push(v[2]);
        return (typeof param !== 'undefined' && typeof out[param] !== 'undefined')?out[param]:out;
    };
    
    var changePath = function(type,coduser){
        $location.path(type+'/'+coduser);
    };
        
    $scope.setCurrent = function(group){
        try{
            var type = getType(0);
            var cod  = $scope.getId(group);
            $('#a_messages_'+cod+ ' > .badge').html('');
            changePath(type, cod);
        }catch(e){console.log(e);}
    };
        
    $scope.getId = function(group){
        try{
            var type = getType(0);
            return (type === 'user')?group.cod_usuario:group.usuario_perfil_cod;
        }catch(e){console.log(e); return '';}
    };
        
    $scope.getData = function(type, active){
        if(type === 'id'){
            return (getType(0) === 'user')?active.cod_usuario:active.usuario_perfil_cod;
        }
        return (getType(0) === 'user')?active.user_name:active.usuario_perfil_nome;
    };
    
    $scope.setGroups = function(group){
        $scope.active     = $scope.groups;
        $scope.useractive = false;
        if(typeof group === 'undefined'){
            group = (typeof $scope.lastGroup !== 'undefined')?$scope.lastGroup:$scope.groups[0];
            if(typeof group === 'undefined'){return;}
        }
        $scope.lastGroup = group;
        var fakeuser    = {cod_usuario: 'group_'+group.usuario_perfil_cod, user_name:group.usuario_perfil_nome};
        $('title').html("Grupo "+ group.usuario_perfil_nome);
        $rootScope.$emit("usuario_message_changeUser", fakeuser);
        changePath('group',group.usuario_perfil_cod);
    };
    
    $scope.setUsers = function(user){
        $scope.active     = $scope.friends;
        $scope.useractive = true;
        if(typeof user === 'undefined'){
            user = ($scope.lastUser !== 'undefined')?$scope.lastUser:$scope.friends[0];
            if(typeof user === 'undefined'){return;}
        }
        $scope.lastUser = user;
        $('title').html("Conversa com "+ user.user_name);
        $rootScope.$emit("usuario_message_changeUser", user);
        changePath('user',user.cod_usuario);
    };
    
    $scope.restore = function(){
        var type = getType(0);
        if(type === ""){return;}
        if(type === 'group'){return $scope.setGroups($scope.groups[0]);}
        $scope.setUsers($scope.friends[0]);
    };
    
    $scope.isActive = function(group){
        try{
            var type = getType();
            var cod  = (type[0] === 'group')? group.usuario_perfil_cod:group.cod_usuario;
            return(type[1] == cod)?true:false; 
        }catch(e){return false;}
    };
    
    $scope.loadPage = function(page){
        if($scope.friends.length === 0){return;}
        if(true === $scope.busy){return;}
        $scope.busy = true;
        
        if(typeof page === 'undefined'){page = 1;}
        if(page < 1){page = 1;}
        if(page > $scope.last && $scope.last > 0){page = $scope.last;}
        $scope.page = page;
        page--;
        $api.execute('msg_contact_list', function(response){
            $scope.busy    = false;
            if(typeof response === 'undefined' || response.length === 0){
                $scope.page --;
                $scope.last = $scope.page; 
                return;
            }
            if(response.length < 10){$scope.last = $scope.page;}
            
            $scope.friends = [];
            for(var i in response){
                $scope.friends.push(response[i]);
            }
            $scope.active = $scope.friends;
            $scope.setUsers($scope.active[0]);
            try{
                verifyNotifications(function(response){
                    console.log(response);
                });
            }catch(e){console.log('verifyNotifications não instanciado!', e);}
            
        }, ''+page);
    };
    
    
    /*************************
           Watchers
     *************************/    
    $scope.$watch('search', function() {
        if(true === $scope.busySearch){return;}
        $scope.busySearch = true;
        
        if($scope.search.length === 0){
            $scope.busySearch = false;
            $scope.setUsers();
            return;
        }

        $api.execute('msg_search_user', function(response){
            $scope.busySearch = false;
            if($scope.search.length === 0){
                return $scope.setUsers();
            }
            $scope.active     = response;
        }, "&q="+$scope.search);
    }, true);
    
    $scope.$watch(function() {
        return $location.path();
    },function(val){
        try{
            var data   = getType();
            var user   = '';
            var key    = (data[0] === 'user')?'cod_usuario':'usuario_perfil_cod';
            var list   = (data[0] === 'user')?$scope.friends:$scope.groups;
            
            //se tem algum search
            if($scope.search.length > 0){list = $scope.active;}
            if(data[1] === ""){return;}
            for(var i in list){
                if(list[i][key] != data[1]){continue;}
                user = list[i];
                break;
            }
            if(user === ''){return;}
            
            if(data[0] === 'group'){return $scope.setGroups(user);}
            $scope.setUsers(user);
            if($scope.search.length > 0){$scope.active = list;}
        }catch(e){}
    });
    /*************************
           Inicialização
     *************************/    
    $rootScope.$on('usuario_message_setFriendList', function(ev, data){
        $scope.friends  = data;
        if(typeof $scope.friends[0] !== 'undefined'){
            changePath('user', $scope.friends[0].cod_usuario);
        }
        $scope.restore(); 
    });
    
    $rootScope.$on('usuario_message_setGroups', function(ev, data){
        $scope.groups        = data;
        $scope.hideGroupForm = (data.length > 10)?false:true;
        $scope.restore();
    });
    
    $rootScope.$on('usuario_message_setFriendTotal', function(ev, data){
        $scope.last = data;
    });
    
    $rootScope.$on('usuario_message_setFeatures', function(ev, data){
        if(typeof data.MENSAGEM_FULL_CHAT === 'undefined' || data.MENSAGEM_FULL_CHAT === false){
            $scope.hideForm = true;
        }
    });
     
}]);