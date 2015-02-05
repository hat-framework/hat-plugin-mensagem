'use strict';
usuario_messageApp.controller('usuario_mensagemCTRL',['$scope','$api','$rootScope',
function($scope,$api,$rootScope) {
    $scope.messages = [];
    $scope.sender   = [];
    $scope.user     = [];
    $scope.page     = 0;
    $scope.busy     = false;
    $scope.stop     = false;
    $scope.url      = window.location.protocol+"//"+window.location.host+"/usuario/login/show";
    //$scope.reddit   = new Reddit();

    $rootScope.$on('usuario_message_send', function(ev, data){
        $scope.messages.unshift(data);
    });
    
    $rootScope.$on('usuario_message_changeUser', function(ev, data){
        $scope.user = data;
        $scope.reset();
        $scope.getMessages();
    });
    
    $rootScope.$on('usuario_message_changeSender', function(ev, data){
        $scope.sender   = data;
        $scope.reset();
        $scope.getMessages();
    });
    
    $scope.reset = function(){
        $scope.page     = 0;
        $scope.stop     = false;
        $scope.messages = [];
    };
    
    $scope.hideButton = function(){
        return $scope.busy||$scope.stop;
    };
    
    $scope.getMessages = function(){
        if($scope.user.length === 0 || $scope.sender.length === 0){return;}
        if(true === $scope.stop){return;}
        if(true === $scope.busy){return;}
        $scope.busy = true;
        var link    = $scope.sender.cod_usuario+"/"+$scope.user.cod_usuario+"/"+$scope.page++;
        $api.execute('msg_conversa', function(response){
            if(response.length === 0 || response.length < 10){$scope.stop = true;}
            for(var i in response){
                $scope.messages.push(response[i]);
            }
            $scope.busy = false;
        }, link);
    };
    
}]);