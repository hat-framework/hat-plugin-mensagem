'use strict';
usuario_messageApp.controller('usuario_mensagem_talkWithCTRL',['$scope','$http','$rootScope',function($scope,$http,$rootScope) {
    $scope.user     = [];
    $scope.user_url = window.location.protocol+"//"+window.location.host+"/usuario/login/show";
    $rootScope.$on('usuario_message_changeUser', function(ev, data){
        $scope.user = data;
    });
    
}]);