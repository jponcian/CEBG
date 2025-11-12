<?php

include_once "conexion.php";
if ($_SESSION['ADMINISTRADOR'] != 1) {
	if ($acceso == 999) {
		//header ("Location: validacion.php?opcion=mant"); 
		exit();
	} else {
		//----------- PARA VALIDAR SI TIENE ACCESO A LA OPCION
		$consulta_x = "SELECT usuarios_accesos.acceso, ip FROM usuarios_accesos, usuarios WHERE usuarios_accesos.usuario = usuarios.usuario AND usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND usuarios_accesos.acceso IN ($acceso)";
		$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
		if ($tabla_x->num_rows > 0)
		//-------------
		{
			$registro = $tabla_x->fetch_object();
			if ($registro->ip <> $_SESSION['ip']) {
?>
				<script language="JavaScript">
					Swal.fire({
						//					  title: '',
						icon: 'error',
						title: 'Debe Cerrar la Sesion del Sistema y Volver a Iniciar!',
						timer: 3000,
						timerProgressBar: true,
						showDenyButton: false,
						showCancelButton: false
					})
				</script>
			<?php
				exit();
			}
		} else {
			//header ("Location: ../principal.php?opcion=no"); 
			?>
			<script language="JavaScript">
				Swal.fire({
					//					  title: '',
					icon: 'error',
					title: 'No Posee Acceso a esta Opción!',
					timer: 2000,
					timerProgressBar: true,
					showDenyButton: false,
					showCancelButton: false
				})
			</script>
<?php
			exit();
		}
	}
}
?>