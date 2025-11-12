<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
if ($_GET['opcion']=='val')
	{
	?>
	<script language="JavaScript">
	alertify.alert('Usuario no Validado!!!');
	</script>
	<?php
	}
if ($_GET['opcion']=='no')
	{
	?>
	<script language="JavaScript">
	alertify.alert('No posee acceso a esta opcion!!!');
	</script>
	<?php
	}
if ($_GET['opcion']=='mant')
	{
	?>
	<script language="JavaScript">
	alertify.alert('Opcion en mantenimiento!!!');
	</script>
	<?php
	}
	?>