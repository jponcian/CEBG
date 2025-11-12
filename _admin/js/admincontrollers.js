var app = angular.module('proyectophp', ['ngRoute']);
app.factory("serviceLogin", function() {
    return {
        usuario: []
    }
});
app.controller('adminController', ['$scope', '$http', function($scope, $http, serviceLogin) {
    //console.log('test');
    $scope.slide_images = [];
    $scope.articulos = [];
    $scope.errors = [];
    $scope.CurrentDate = new Date();
    $scope.usuarios = [];
    $scope.usuario = {};
    $scope.logueado = {};
    $scope.usuario = localStorage.getItem('alc_usuario');
}]);