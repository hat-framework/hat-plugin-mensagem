'use strict';
message_userApp.controller('message_userCTRL',['$scope','$http','$rootScope','$sce',
function($scope,$http,$rootScope, $sce) {
    $scope.messages = [];
    $scope.sender   = [];
    $scope.page     = 0;
    $scope.busy     = false;
    $scope.stop     = false;
    $scope.url      = window.location.protocol+"//"+window.location.host+"/usuario/login/show";
    //$scope.reddit   = new Reddit();

    $rootScope.$on('message_user_send', function(ev, data){
        console.log(data);
        $scope.messages.unshift($scope.prepareMessageData(data));
    });
    
    $scope.prepareMessageData = function(data){
        data['cod_usuario'] = data['from'];
        data['from'] = (data['from'] == $scope.sender.cod_usuario)?$scope.sender.user_name:$scope.user.user_name;
        return data;
    };
    
    $rootScope.$on('message_user_sender', function(ev, data){
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
        if($scope.sender.length === 0){return;}
        if(true === $scope.stop){return;}
        if(true === $scope.busy){return;}
        
        $scope.busy = true;
        var link    = $scope.sender.cod_usuario+"/"+$scope.page++;
        var url     = window.location.protocol+"//"+window.location.host+"/index.php?ajax=true&url=mensagem/mensagem/conversa/"+link+"&type=user";
        $http({method: 'GET', url: url}).success(function(response) {
            $('#a_messages > .badge').html('');
            if(response.length === 0 || response.length < 10){$scope.stop = true;}
            for(var i in response){
                $scope.messages.push(response[i]);
            }
            $scope.busy = false;
        });
    };
    
}]);