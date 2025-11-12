<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link rel="stylesheet" href="">
</head>

<body>
    <?php include_once "ayuda_login.php"; ?>
    <div class="login-container">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="card login-card" tabindex="0">
                <div class="card-body" tabindex="-1">
                    <div class="text-center mb-4">
                        <img src="images/logo.png" alt="Logo" style="width: 100px; margin-bottom: 1rem;">
                        <h4 class="text-primary font-weight-bold">Contraloría del Estado Bolivariano de Guárico</h4>
                        <p class="text-muted">Por favor inicie sesión para continuar</p>
                    </div>
                    <form id="formLogin" name="formLogin" method="post" autocomplete="off">
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user fa-lg"></i></span>
                                </div>
                                <input type="text" class="form-control" id="loginUser" name="loginUser" ng-model="usuario.userid" placeholder="Cédula de Usuario" required onclick="this.select()">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key fa-lg"></i></span>
                                </div>
                                <input type="password" class="form-control" id="loginPassword" name="loginPassword" ng-model="usuario.passw" placeholder="Contraseña" required onclick="this.select()">
                            </div>
                        </div>
                        <div class="alert alert-danger" role="alert" ng-show="logueado.mostrar == 0">{{logueado.mensajelogin}}</div>
                        <div class="form-group mt-4">
                            <button type="button" class="btn btn-primary btn-block" ng-click="loginUsuario()">Ingresar</button>
                        </div>
                        <div class="text-center">
                            <a href="#" data-toggle="modal" data-target="#ModalRecuperar">¿Olvidó su Usuario o Clave?</a>
                        </div>
                        <div class="text-center mt-2">
                            <a href="#" data-toggle="modal" data-target="#modal_ayuda_login"><b>Ayuda</b></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Recuperar Contraseña -->
    <div ng-controller="registroController" class="modal fade" id="ModalRecuperar" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Recuperar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form name="formrecuperar" id="formrecuperar" autocomplete="off">
                        <div class="form-group">
                            <label for="recuperarrif">Ingrese su número de cédula:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                </div>
                                <input ng-model="recuperarrif" name="recuperarrif" type="text" class="form-control" placeholder="Cédula" required>
                            </div>
                            <small class="text-danger" ng-show="regrif.length > 9 && idcliente === 0">{{mensaje}}</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" ng-click="recuperarUsuario()">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<script>
    function foco1() {
        setTimeout(function() {
            document.formLogin.loginUser.focus();
        }, 200)
    }

    function foco2(e) {
        key = e.keyCode ? e.keyCode : e.which;
        if (key == 13) {
            document.formLogin.loginPassword.focus();
        }
    }

    // Al presionar Enter en la contraseña, ejecutar el login
    function enterLogin(e) {
        var key = e.keyCode ? e.keyCode : e.which;
        if (key === 13) {
            // Limitar la búsqueda del botón al modal de login para evitar el de recuperación
            var btn = document.querySelector('#myModal #btnLogin');
            if (btn && typeof btn.click === 'function') {
                btn.click();
            }
        }
    }

    // Al presionar Enter en recuperación, ejecutar aceptar
    function enterRecuperar(e) {
        var key = e.keyCode ? e.keyCode : e.which;
        if (key === 13) {
            var btn = document.querySelector('#ModalRecuperar #btnRecuperarUsuario');
            if (btn && typeof btn.click === 'function') {
                btn.click();
            }
        }
    }
</script>
<script>
    $(function() {
        $('#myModal').modal('show');
    });
</script>