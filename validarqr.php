<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Validando QR</title>
	<link rel="stylesheet" href="">
</head>

<body>
	<?php
	require_once __DIR__ . '/scripts/funciones.php';
	if ($_GET['qrv'] == 1) {
		$reporte = 1;
		$clave = $_GET['ci'];
	} else {
		$leervariable = decrypt($_GET['qrv']);
		$clave = (int)substr($leervariable, 6, 12);
		$reporte = substr($leervariable, 0, 2);
		//echo $clave.'<br>';
		//echo $reporte.'<br>';
	}
	if ($reporte == 1) {
		$ruta = "personal/formatos/0constancia.php?id=" . encrypt($clave);
	}

	?>
	<form name='envia' action="<?php echo $ruta; ?>" target="_blank" method="post" accept-charset="utf-8">
		<input type="hidden" name="id" id="id" value="<?php echo $clave; ?>">
	</form>
	<script language="JavaScript">
		document.envia.submit();
		window.close();
	</script>
</body>

</html>