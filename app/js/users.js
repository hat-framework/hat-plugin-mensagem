'use strict';
usuario_messageApp.controller('usuario_mensagem_usersCTRL',['$scope','$http','$rootScope', '$timeout','getUrlVars',
function($scope,$http,$rootScope, $timeout, getUrlVars) {
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

    $scope.currentGroup = function(group){
        var fakeuser = {cod_usuario: 'group_'+group.usuario_perfil_cod, user_name:group.usuario_perfil_nome};
        $rootScope.$emit("usuario_message_changeUser", fakeuser);
    };
    
    $scope.currentUser = function(user){
        if(typeof user.unread !== 'undefined'){delete user.unread;}
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
        $scope.setUsers();
        if($scope.friends.length < 10){$scope.stop = true;}
        if(typeof data[0] !== 'undefined'){
            $scope.currentUser(data[0]);
        }
    });
    
    $rootScope.$on('usuario_message_setGroups', function(ev, data){
        $scope.groups  = data;
        $scope.hideGroupForm = (data.length > 10)?false:true;
    });
    
    $rootScope.$on('usuario_message_setFeatures', function(ev, data){
        if(typeof data.MENSAGEM_FULL_CHAT === 'undefined' || data.MENSAGEM_FULL_CHAT === false){
            $scope.hideForm = true;
        }
    });
    $rootScope.$on('usuario_message_changeSender', function(ev, data){
        var cod_perfil = getUrlVars()["_perfil"];
        if(typeof cod_perfil === 'undefined' || cod_perfil === ""){cod_perfil = data.cod_perfil;}
        if(cod_perfil == '3' || cod_perfil == '2' || cod_perfil == '20'){
            $scope.hideall = false;
        }
        $rootScope.$emit("usuario_message_hiddenUsers", $scope.hideall);
    });
}]);