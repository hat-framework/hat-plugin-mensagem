'use strict';
message_userApp.controller('message_user_formCTRL',['$scope','$http','$rootScope',
    function($scope,$http,$rootScope) {
    $scope.cansend = true;
    $scope.sender  = [];
    $scope.user    = [];
    $scope.features= [];
    $scope.content = '';
    $scope.user_url= window.location.protocol+"//"+window.location.host+"/usuario/login/show";
    $scope.sendMessage = function(){
        $rootScope.$emit("message_user_send", {
            mensagem :$scope.content, 
            from     :$scope.sender.cod_usuario,
            fromname :$scope.sender.user_name,
            to       :$scope.user.cod_usuario,
            date     :$scope.getDate()
        });
        $scope.persistMessage();
    };
    $scope.getDate = function(){
        var currentdate = new Date(); 
        var datetime = currentdate.getFullYear()  + "-"
                + (currentdate.getMonth()+1<10?'0':'')   + currentdate.getMonth()+1 + "-" 
                + (currentdate.getDate()<10?'0':'')    + currentdate.getDate() + " " 
                + (currentdate.getHours()<10?'0':'')   + currentdate.getHours() + ":"  
                + (currentdate.getMinutes()<10?'0':'') + currentdate.getMinutes() + ":" 
                + (currentdate.getSeconds()<10?'0':'') + currentdate.getSeconds()
        return datetime;
    };
    
    $scope.persistMessage = function(msg){
        var url = window.location.protocol+"//"+window.location.host+"/index.php?ajax=true&url=mensagem/mensagem/formulario";
        var data = {
            from     :$scope.sender.cod_usuario, 
            to       :$scope.user.cod_usuario, 
            mensagem :$scope.content, 
        };
        $http.post(url, data).success(function(response) {
            message_json(response, 3600);
        });
        $scope.content = "";
    };
    
    $rootScope.$on('message_user_sender'   , function(ev, data){$scope.sender   = data;});
    $rootScope.$on('message_user_features' , function(ev, data){$scope.features = data;});
        
    $scope.redactorOptions = {
        buttons: ['bold','italic','deleted','|','outdent','indent', '|', 'image','video','link', '|', 'alignment', 'fontcolor', 'backcolor', 'formatting'], 
        imageUpload : getResourceUrl('upload')+'/src/lib/image.php',
        wym: true, lang: 'pt_br', focus: false, iframe: false, convertVideoLinks: true,
        autoresize: true, convertImageLinks: true, convertLinks: true, imageFloatMargin: '30px',
        mobile: true, observeLinks: true,
        pastePlainText: true, placeholder: true,  plugins: ['fullscreen:startFullscreen']
    };
}]);
