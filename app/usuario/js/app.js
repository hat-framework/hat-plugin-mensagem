'use strict';
var message_userApp = angular.module('message_userApp', ['ngSanitize', 'angular-redactor'])
.filter('fromNow', function() {
    return function(date) {
      return moment(date).fromNow();
    };
});

message_userApp.factory('getUrlVars', [function() {
   return function() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
    };
 }]);