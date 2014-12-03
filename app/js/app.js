'use strict';
var usuario_messageApp = angular.module('usuario_messageApp', ['ngSanitize', 'angular-redactor'])
.filter('fromNow', function() {
    return function(date) {
      return moment(date).fromNow();
    };
});
usuario_messageApp.factory('getUrlVars', [function() {
   return function() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
    };
 }]);
 
 
usuario_messageApp.controller('usuario_mensagem_globalCTRL',['$rootScope','$scope',function($rootScope, $scope) {
    $scope.hideall = true;
    
    $rootScope.$on('usuario_message_hiddenUsers', function(ev, data){
        $scope.hideall = !data;
    });
}]);
