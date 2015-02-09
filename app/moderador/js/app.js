'use strict';
var usuario_messageApp = angular.module('usuario_messageApp', ['globalApp', 'ngSanitize', 'angular-redactor']);
usuario_messageApp.filter('fromNow', function() {return function(date) {return moment(date).fromNow();};});

usuario_messageApp.config(['$apiProvider', function ($apiProvider) {
    $apiProvider.concatInUrl("&ajax=1");
    $apiProvider.cacheList(true);
    $apiProvider.registerServices({
        'msg_data'          :{type:'list' , 'urltype':'common', 'url':'mensagem/mensagem/data'},
        'msg_conversa'      :{type:'list' , 'urltype':'common', 'url':'mensagem/mensagem/conversa'},
        'msg_formulario'    :{type:'save' , 'urltype':'common', 'url':'mensagem/mensagem/formulario'},
        'msg_contact_list'  :{type:'list' , 'urltype':'common', 'url':'mensagem/mensagem/getUserContactList'},
        'msg_search_user'   :{type:'list' , 'urltype':'common', 'url':'mensagem/mensagem/searchUser'},
        'msg_notifier'      :{type:'get'  , 'urltype':'common', 'url':'notificacao/notifycount/load'}
    });
}]);

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
