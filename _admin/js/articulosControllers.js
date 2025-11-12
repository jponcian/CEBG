var app = angular.module('proyectophp');
app.directive("fileInput", function($parse) {
    return {
        link: function($scope, element, attrs) {
            element.on("change", function(event) {
                var files = event.target.files;
                $parse(attrs.fileInput).assign($scope, element[0].files);
                $scope.$apply();
            });
        }
    }
});
app.controller("articulosController", ["$scope", "$http", function($scope, $http) {
    $scope.imagen = '';
    $scope.indice = '';
    $scope.articulo = {};
    $scope.articulos = [];
    //$scope.editdescripciondb = '<h1>El truco para que te crezca el pelo mas rápido</h1><br />¿Sabiais que la cebolla es uno de los ingredientes mágicos que hacen que te crezca el pelo más rápido? Mirad este truco para conseguir un pelo más largo con la ayuda de una <strong>cebolla</strong>.';
    $scope.editdescripciondb;
    $scope.edittitulo = '';
    $scope.editimagen = '';
    $scope.loading = false;
    $scope.usuario = localStorage.getItem('alc_usuario');
    $scope.mostrar = function() {
        $scope.articulo.descripcion = CKEDITOR.instances.txtdescripcion.getData();
        //$scope.articulo.descripcion = $scope.articulo.descripcion.replace(/&/g, "%26");
    };
    const $archivos = document.querySelector("#inputFileImagen");
    $scope.uploadFile = function() {
        $scope.loading = true;
        $('#agregararticulo').attr("disabled", true);
        $scope.articulo.descripcion = CKEDITOR.instances.txtdescripcion.getData();
        //$scope.articulo.descripcion = $scope.articulo.descripcion.replace(/&/g, "%26");
        var form_data = new FormData();
        angular.forEach($scope.files, function(file) {
            form_data.append('file', file);
        });
        $scope.articulo.usuario = $scope.usuario;
        form_data.append('titulo', $scope.articulo.titulo);
        form_data.append('descripcion', $scope.articulo.descripcion);
        form_data.append('usuario', $scope.usuario);
        //console.log(form_data);
        $http.post('scripts/articulos_agregar.php', form_data, {
            transformRequest: angular.identity,
            headers: {
                'Content-Type': undefined,
                'Process-Data': false
            }
        }).then(function success(response) {
            //console.log(response.data);
            $('#ModalAddArticulo').modal('hide');
            alertify.success(response.data.articulo.mensaje);
            $scope.listarArticulos();
            $scope.loading = false;
            $('#agregararticulo').attr("disabled", false);
        });
    };
    $scope.uploadFileEditar = function() {
        $scope.loading = true;
        $('#modificararticulo').attr("disabled", true);
        var form_data = new FormData();
        angular.forEach($scope.files, function(file) {
            form_data.append('file', file);
        });
        //console.log($scope.files);
        var id = $scope.articulos[$scope.indice].id;
        var imagen = $scope.editimagen;
        var titulo = $scope.edittitulo;
        var descripcion = CKEDITOR.instances.txtdescripcioneditar.getData();
        form_data.append('id', id);
        form_data.append('imagen', imagen);
        form_data.append('titulo', titulo);
        form_data.append('desc', descripcion);
        form_data.append('usuario', $scope.usuario);
        $http.post('scripts/articulos_editar.php', form_data, {
            transformRequest: angular.identity,
            headers: {
                'Content-Type': undefined,
                'Process-Data': false
            }
        }).then(function success(response) {
            $('#ModalEditArticulo').modal('hide');
            alertify.success(response.data.articulo.mensaje);
            $scope.listarArticulos();
            $scope.loading = false;
            $('#modificararticulo').attr("disabled", false);
        });
    };
    //Cargar el Indice
    $scope.cargarEditarArticulo = function(id) {
        $scope.loading = true;
        var index = $scope.articulos.indexOf($scope.articulos.find(x => x.id == id));
        $scope.indice = index;
        var id = $scope.articulos[index].id;
        $http.get('scripts/articulos_buscar.php?id=' + id, {}).then(function success(e) {
            $scope.editimagen = e.data[0].image;
            $scope.edittitulo = e.data[0].titulo;
            CKEDITOR.instances.txtdescripcioneditar.setData(e.data[0].descripcion);
            //$scope.listarSliders();
            $scope.loading = false;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    //listar Articulos
    $scope.listarArticulos = function() {
        $scope.loading = true;
        $http.get('scripts/articulos_listar.php', {}).then(function success(response) {
            $scope.articulos = response.data.resultado;
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarArticulos();
    //BORRAR SLIDER
    $scope.eliminarArticulo = function(id) {
        alertify.confirm("¿Estas seguro de elimnar el registro?", function(e) {
            if (e) {
                $scope.loading = true;
                //var id = $scope.articulos[index].id;
                //console.log('Id: ' + id);
                $http.get('scripts/articulos_eliminar.php?id=' + id, {}).then(function success(e) {
                    //console.log(e.data);
                    $scope.listarArticulos();
                    $scope.loading = false;
                }, function error(e) {
                    console.log("Se ha producido un error al recuperar la información");
                    $scope.loading = false;
                });
            }
        });
    };
    $scope.resetForm = function(form) {
        form.$setPristine();
        form.$setUntouched();
    };
    $scope.resetFormEditar = function(form) {
        form.$setPristine();
        form.$setUntouched();
        $scope.listarArticulos();
    };
}]);