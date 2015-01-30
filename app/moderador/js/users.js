'use strict';
usuario_messageApp.controller('usuario_mensagem_usersCTRL',['$scope','$http','$location','$rootScope', '$timeout','getUrlVars',
function($scope,$http,$location,$rootScope, $timeout, getUrlVars) {
    if(typeof $scope.hideall !== 'undefined'){return;}
    $scope.friends       = [];
    $scope.hideForm      = true;
    $scope.hideall       = true;
    $scope.groups        = [];
    $scope.hideGroupForm = true;
    $scope.busy          = false;
    $scope.stop          = false;
    $scope.hideMore      = false;
    $scope.busySearch    = false;
    $scope.page          = 1;
    $scope.search        = '';
    $scope.user_url      = window.location.protocol+"//"+window.location.host+"/usuario/login/show";
    $scope.activeid      = "";
    $scope.currentGroup = function(group){
        if(typeof group.usuario_perfil_cod === 'undefined'){console.log(group);return;}
        var fakeuser = {cod_usuario: 'group_'+group.usuario_perfil_cod, user_name:group.usuario_perfil_nome};
        $location.path('group/'+group.usuario_perfil_cod);
        $scope.activeid = group.usuario_perfil_cod;
        $('title').html("Grupo "+ group.usuario_perfil_nome);
        $rootScope.$emit("usuario_message_changeUser", fakeuser);
    };
    
    $scope.currentUser = function(user){
        if(typeof user.unread !== 'undefined'){delete user.unread;}
        $location.path('user/'+user.cod_usuario);
        $scope.activeid = user.cod_usuario;
        $('title').html("Conversa com "+ user.user_name);
        $rootScope.$emit("usuario_message_changeUser", user);
    };
    
    $scope.hideButton = function(){
        return $scope.busy||$scope.stop||$scope.hideMore;
    };
    
    $scope.loadMore = function(){
        if($scope.friends.length === 0){return;}
        if(true === $scope.stop){return;}
        if(true === $scope.busy){return;}
        
        $scope.busy = true;
        var url = window.location.protocol+"//"+window.location.host+"/mensagem/mensagem/getUserContactList/"+$scope.page++;
        $http({method: 'GET', url: url}).success(function(response) {
            if(response.length === 0 || response.length < 10){$scope.stop = true;}
            for(var i in response){
                $scope.friends.push(response[i]);
            }
            $scope.busy = false;
        });
    };
    
    $scope.setGroups = function(){
        $scope.active   = $scope.groups;
        $scope.last     = 'groups';
        $scope.hideMore = true;
    };
    
    $scope.setUsers = function(){
        $scope.active   = $scope.friends;
        $scope.last     = 'users';
        $scope.hideMore = false;
    };
    
    $scope.current = function(active){
        if($scope.last === 'users'){
            $scope.currentUser(active);
        }else{
            $scope.currentGroup(active);
        }
    };
    
    $scope.getId = function(active){
        return ($scope.last === 'users')?active.cod_usuario:active.usuario_perfil_cod;
    };
    
    $scope.getName = function(active){
        return ($scope.last === 'users')?active.user_name:active.usuario_perfil_nome;
    };
    
    $scope.$watch('search', function(newValue, oldValue) {
        if($scope.search.length === 0){
            $scope.setUsers();
            return;
        }
        
        if(true === $scope.busySearch){
            return;
        }
        
        $scope.busySearch = true;
        $timeout(function(){   //Set timeout
            var url = window.location.protocol+"//"+window.location.host+"/index.php?ajax=true&url=mensagem/mensagem/searchUser&q="+$scope.search;
            $http({method: 'GET', url: url}).success(function(response) {
                $scope.active = response;
                $scope.busySearch = false;
            });
        },300);
    }, true);
    
    $rootScope.$on('usuario_message_setFriendList', function(ev, data){
        $scope.friends  = data;
        $scope.hideForm = (data.length >= 10)?false:true;
        if($scope.friends.length < 10){$scope.stop = true;}       
        $scope.restore(); 
    });
    
    $rootScope.$on('usuario_message_setGroups', function(ev, data){
        $scope.groups  = data;
        $scope.hideGroupForm = (data.length > 10)?false:true;
        $scope.restore();
    });
    
    $rootScope.$on('usuario_message_setFeatures', function(ev, data){
        if(typeof data.MENSAGEM_FULL_CHAT === 'undefined' || data.MENSAGEM_FULL_CHAT === false){
            $scope.hideForm = true;
        }
    });
    
    $scope.isActive = function(group){
        if($scope.activeid != $scope.getId(group)){return false;}
        var type = $scope.getType();
        if(type[0] === 'group'){
            return(typeof group.usuario_perfil_cod !== 'undefined')?true:false;
        }
        return(typeof group.cod_usuario !== 'undefined')?true:false;
    };
    
    $scope.$watch(function() {
        return $location.path();
     }, function(val){
        $scope.restore();
     });
     
     $scope.getType = function(){
        var v = $location.path().split('/');
        if(v.length < 2){return {};}
        var out = [];
        out.push(v[1]);
        out.push(v[2]);
        return out;
    };
    
    $scope.restore = function(){
        if(typeof $scope.friends[0] === 'undefined'){return;}
        if(typeof $scope.groups[0] === 'undefined'){return;}
        var data = $scope.getType();
        var key = 'usuario_perfil_cod';
        if(typeof data[0] === 'undefined'){
            data[0]       = 'user';
            $scope.active = $scope.friends;
        }
        
        if(data[0] === 'user'){
            key = 'cod_usuario';
            $scope.setUsers();
        }
        else{$scope.setGroups();}
        for(var i in $scope.active){
            if(data[1] != $scope.active[i][key]){continue;}
            (data[0] === 'group')?$scope.currentGroup($scope.active[i]):$scope.currentUser($scope.active[i]);
            $scope.hideall = false;
            return;
        }
        $scope.currentUser($scope.friends[0]);
        $scope.hideall = false;
    };
     
}]);