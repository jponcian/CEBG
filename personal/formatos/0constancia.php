<?php
session_start();
ob_end_clean();
session_start();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
setlocale(LC_TIME, 'sp_ES','sp', 'es');
//$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

if ($_GET['id']<>'0')
	{	$_SESSION['id_ct'] = decriptar($_GET['id']);	}
else
	{	$_SESSION['id_ct'] = $_POST['id'];	}

////////// DATOS
$consulta = "SELECT * FROM rac WHERE cedula = ".$_SESSION['id_ct']." LIMIT 1;"; 
$tabla = $_SESSION['conexionsql']->query($consulta);
$registro = $tabla->fetch_object();
if ($tabla->num_rows > 0) {
// --------------
$digito = $registro->digito;
$ci = $registro->ci;
$cedula = $registro->cedula;
$empleado = $registro->nombre." ".$registro->nombre2." ".$registro->apellido." ".$registro->apellido2;
$profesion = $registro->profesion;
$annos = annos(anno($registro->fecha_ingreso),mes($registro->fecha_ingreso),date('Y'),date('m'));
$antiguedad = intval($annos) + intval($registro->anos_servicio);
$cargo = $registro->cargo;
$ubicacion = $registro->ubicacion;
$profesion = $registro->profesion;
$fecha = ($registro->fecha_ingreso);
$sueldo = $registro->sueldo;
$categoria2 = $registro->categoria2;
$partida2 = $registro->categoria2;
if ($categoria <> '' or $partida2 <> '' and $registro->sueldo2>0)	
	{
	$sueldo2 = $registro->sueldo2;
	$sueldo = $sueldo2;//$sueldo + 
	}

$sus_lph = $registro->sus_lph;
$nomina = $registro->nomina;
$categoria = $registro->categoria;

$jefe_direccion = jefe_direccion(10);
$jefe = 10;
if ($jefe_direccion[0] == $cedula)
	{
	$jefe_direccion = jefe_direccion(1);
	$jefe = 1;
	}
// ----------
	
$txt="<n>Quien suscribe <strong>".$jefe_direccion[1]."</strong>, titular de la Cédula de Identidad N° <strong>V-".$jefe_direccion[0]."</strong>, en mi carácter de ".$jefe_direccion[2]." de la Contraloria del Estado Bolivariano de Guárico, según <strong>".$jefe_direccion[3]."</strong> de fecha <strong>".voltea_fecha($jefe_direccion[4])."</strong>, por medio de la presente hace constar que el Ciudadano: <strong>$empleado</strong>, Titular de la Cédula de Identidad: <strong>".formato_ci($ci)."</strong> trabaja en esta Institución desempeñándose en el cargo de: <strong>$cargo</strong> adscrito(a) a: <strong>$ubicacion</strong> desde el <strong>".voltea_fecha($fecha)."</strong>, hasta la presente fecha.</n>";
?>
<!DOCTYPE html>

<html >
<strong></strong>
<head >
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Contraloria del Estado Bolivariano de Guarico</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
	<link rel="stylesheet" href="../../lib/bootstrap/css/bootstrap.min.css">
	<link href="../../css/style.css" rel="stylesheet">
</head>

<body ng-controller="MainController" >

	</br>
	<div align="center">
	<table width="70%" border="0">
  <tbody>
    <tr>
      <td  width="10%" align="center" ><div align="center"><img src="../../images/logo_nuevo.jpg" width="180" alt=""/></div></td>
      <td width="80%" align="center"><h5>República Bolivariana de Venezuela</h5><h5>Contraloria del Estado Bolivariano de Guárico</h5><h5>Dirección de Talento Humano</h5></td>
      <td width="10%" align="center" ><div align="center"><img src="../funcionarios/<?php echo $cedula; ?>_0.jpg" width="150" alt=""/></div></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><br><strong><u><h3>CONSTANCIA</h3></u></strong><br></td>
    </tr>
    <tr>
      <td colspan="3" align="justify"><h5>Quien suscribe <strong><?php echo $cedula; ?></strong>, titular de la Cédula de Identidad N° <strong>V-<?php echo $jefe_direccion[0]; ?></strong>, en mi carácter de <?php echo $jefe_direccion[2]; ?> de la Contraloria del Estado Bolivariano de Guárico, según <strong><?php echo $jefe_direccion[3]; ?></strong> de fecha <strong><?php echo voltea_fecha($jefe_direccion[4]); ?></strong>, por medio de la presente hace constar que el Ciudadano: <strong><?php echo $empleado; ?></strong>, Titular de la Cédula de Identidad: <strong><?php echo formato_ci($ci); ?></strong> trabaja en esta Institución desempeñándose en el cargo de: <strong><?php echo $cargo; ?></strong> adscrito(a) a: <strong><?php echo $ubicacion; ?></strong> desde el <strong><?php echo voltea_fecha($fecha); ?></strong>, hasta la presente fecha.</h5></td>
    </tr>
    <tr>
      <td colspan="3" align="left"><br><h5>Constancia que se muestra como verificacion del funcionario, a los <?php echo (fecha_larga2(date('Y-m-d')))?></h5><br><br><br></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><img src="../../images/firma_rrhh.png" width="300" alt=""/></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><h5><?php include_once "../../funciones/firma_html.php";?></h5></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><br><br><br><h6>HACIA LA CONSOLIDACIÓN Y FORTALECIMIENTO DEL SISTEMA NACIONAL DE CONTROL FISCAL"<br>San Juan de los Morros, Calle Mariño, Edificio Don Vito Piso 1, 2 y 4 entre Av. Bolivar y Av. Monseñor Sendrea.<br>Telf: (0246) 432.14.33 email: controlguarico01@hotmail.com - web: www.cebg.com.ve<br>R.I.F. G-20001287-0</h6></td>
    </tr>
  </tbody>
</table>
	</div>
</body>
<br>
</html>
<script src="../../lib/jquery/jquery-3.4.1.min.js"></script>
<script src="../../lib/bootstrap/js/bootstrap.min.js"></script>
<script src="../../lib/angular/angular.min.js"></script>
<script src="../../lib/angular-sanitize/angular-sanitize.min.js"></script>
<script src="../../lib/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
<script src="../../lib/angular-route/angular-route.min.js"></script>
<?php
} else {
    echo 'NO EXISTE EL TRABAJADOR';
  }