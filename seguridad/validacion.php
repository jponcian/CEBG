<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
if ($_GET['opcion']=='val')
	{
	?>
	<script language="JavaScript">
	alert('Usuario no Validado!!!');
	</script>
	<?php
	}
if ($_GET['opcion']=='no')
	{
	?>
	<script language="JavaScript">
	alert('No posee acceso a esta opcion!!!');
	</script>
	<?php
	}
if ($_GET['opcion']=='mant')
	{
	?>
	<script language="JavaScript">
	alert('Opcion en mantenimiento!!!');
	</script>
	<?php
	}
	?>