<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$modulo = decriptar($_GET['modulo']);
$cedula = decriptar($_GET['cedula']);
?>

<div class="table-responsive-sm">
	<table class="table table-striped table-hover table-sm">
<?php
$consultx = "SELECT * FROM accesos_individual WHERE modulo='$modulo' ORDER BY modulo, menu, descripcion"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_array())
	{
		$consultx1 = "SELECT * FROM usuarios_accesos WHERE acceso='".$registro_x['id']."' and usuario='$cedula' LIMIT 1"; 
		$tablx1 = $_SESSION['conexionsql']->query($consultx1);
		if ($tablx1->num_rows>0)
			{	$valor1 = 'checked';	$valor2 = 'no';	}
		else
			{	$valor1 = '';	$valor2 = 'si';	}
		?>
	<tr>
		<td style="vertical-align: middle"><strong><?php echo $registro_x['menu']; ?></strong></td>
		<td style="vertical-align: middle"><strong><?php echo $registro_x['descripcion']; ?></strong></td>
		<td style="vertical-align: middle" align="right">

	<input onClick="asignar('<?php echo $registro_x['id']; ?>', '<?php echo $cedula; ?>', '<?php echo $valor2; ?>');" id="txt_exento<?php echo $registro_x['id']; ?>" name="txt_exento<?php echo $registro_x['id']; ?>" type="checkbox" class="switch_new" value="1" <?php echo $valor1; ?> />
	<label for="txt_exento<?php echo $registro_x['id']; ?>" class="lbl_switch"></label>		
			
			
			
<!--
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
			  <label onClick="asignar('<?php //echo $registro_x['id']; ?>', '<?php //echo $cedula; ?>', 'si');" class="btn btn-primary <?php //echo $valor1; ?>">
				<input type="radio" name="options" autocomplete="off" checked> <i class="fas fa-lock-open"></i>
			  </label>
			  <label onClick="asignar('<?php //echo $registro_x['id']; ?>', '<?php //echo $cedula; ?>', 'no');" class="btn btn-primary <?php //echo $valor2; ?>">
				<input type="radio" name="options" autocomplete="off"> <i class="fa-solid fa-lock"></i>
			  </label>
			</div>
-->
		</td>
    </tr>
<?php	
	}
?>
	</table>
</div>