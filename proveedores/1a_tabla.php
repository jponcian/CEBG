<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
	header("Location: ../validacion.php?opcion=val");
	exit();
}

$acceso = 48;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada datatabla" border="1" align="center" width="100%">
	<thead>
		<tr>
			<th bgcolor="#CCCCCC" align="center"><strong>#</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>RIF</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Nombre</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Representante</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Cel Contacto</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Email</strong></th>
			<th bgcolor="#CCCCCC" align="center"><strong>Opciones</strong></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 0;
		$consultx = "SELECT id, rif, nombre, representante, cel_contacto, email FROM contribuyente ORDER BY nombre";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		while ($registro = $tablx->fetch_object()) {
			$i++;
		?>
			<tr>
				<td align="center"><?php echo $i; ?></td>
				<td align="center"><?php echo htmlspecialchars($registro->rif); ?></td>
				<td><?php echo htmlspecialchars($registro->nombre); ?></td>
				<td><?php echo htmlspecialchars($registro->representante); ?></td>
				<td><?php echo htmlspecialchars($registro->cel_contacto); ?></td>
				<td><?php echo htmlspecialchars($registro->email); ?></td>
				<td align="center">
					<button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#modal_normal" onclick="agregar(<?php echo ($registro->id); ?>);" data-keyboard="false" title="Modificar"><i class="fas fa-edit"></i></button>
					<button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarProveedor(<?php echo ($registro->id); ?>);" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
				</td>
			</tr>
		<?php
		}
		?>
	</tbody>
</table>
<script language="JavaScript" src="funciones/datatable.js"></script>
<script>
	function eliminarProveedor(id) {
		Swal.fire({
			title: '¿Eliminar proveedor?',
			text: 'Esta acción no se puede deshacer',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Sí, eliminar',
			cancelButtonText: 'Cancelar'
		}).then(function(result) {
			if (result.isConfirmed) {
				$.ajax({
					type: 'POST',
					url: 'proveedores/1d_eliminar.php',
					dataType: 'json',
					data: {
						id: id
					},
					success: function(data) {
						if (data.tipo === 'info') {
							Swal.fire({
								toast: true,
								position: 'bottom-end',
								icon: 'success',
								title: data.msg,
								showConfirmButton: false,
								timer: 3000,
								timerProgressBar: true
							});
							buscar();
						} else {
							var icono = (data.tipo === 'error') ? 'error' : (data.tipo === 'alerta' ? 'warning' : 'info');
							Swal.fire({
								icon: icono,
								title: data.msg
							});
						}
					},
					error: function() {
						Swal.fire({
							icon: 'error',
							title: 'Error de red'
						});
					}
				});
			}
		});
	}
</script>