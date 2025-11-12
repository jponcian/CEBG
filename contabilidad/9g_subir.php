<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-------------
$info = array();
$tipo = 'info';
//-------------	

$mensaje = ''; 

  If (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK)
  {
    // get details of the uploaded file
    $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
    $fileName = $_FILES['uploadedFile']['name'];
    $fileSize = $_FILES['uploadedFile']['size'];
    $fileType = $_FILES['uploadedFile']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // sanitize file-name
    //$newFileName = md5(time() . $fileName) . '.' . $fileExtension;

    // check if file has one of the following extensions
    $allowedfileExtensions = array('japo', 'xls');

    If (in_array($fileExtension, $allowedfileExtensions))
    {
      // directory in which the uploaded file will be moved
      $uploadFileDir = './cuentas/';
	
//--------
$consultai = "INSERT INTO estado_cuenta_excel(id_banco, usuario) VALUES ('".$_POST[txt_banco]."', '".$_SESSION[CEDULA_USUARIO]."')"; 
$tablai = $_SESSION['conexionsql']->query($consultai);
//--------
$consultax = "SELECT LAST_INSERT_ID() as id;";
$tablax = $_SESSION['conexionsql']->query($consultax);	
$registrox = $tablax->fetch_object();
$id = $registrox->id;

      $dest_path = $uploadFileDir . $id .'.' . $fileExtension;

      If(move_uploaded_file($fileTmpPath, $dest_path)) 
      {
        $mensaje ='Archivo Exitosamente cargado...';

	$i=0;
	$_SESSION['conexionsql']->query("SET NAMES 'utf8'");
	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	require_once '../lib/PHPExcel-1.8/Classes/PHPExcel.php';
	//-------------------------------------------------------------------
	$objReader = PHPExcel_IOFactory::createReader('Excel5');
	$objReader->setReadDataOnly(true);

	$objPHPExcel = $objReader->load("cuentas/".$id.".xls");
	$objWorksheet = $objPHPExcel->getActiveSheet();

	//echo '<table border="1">' . "\n";
	foreach ($objWorksheet->getRowIterator() as $row) {
	//echo '<tr>' . "\n";
	$j=0;
	$cellIterator = $row->getCellIterator();
	$cellIterator->setIterateOnlyExistingCells(false); // This loops all cells,
		
	foreach ($cellIterator as $cell) {
		if ($cell->getValue()=='ABONO')	{ $guarda = 'si'; }
		if ($guarda=='si' and $cell->getValue()<>'') 
			{
			if ($i>0)	
				{	
				$j++;
				$valor[$j] = $cell->getValue();
				//echo '<td>' . $cell->getValue() . '</td>' . "\n";
				}
			$i++;
			}
		}

	//echo '</tr>' . "\n";
		if ($j==4)	
			{
			if ($_POST[txt_banco]==4)
				{			$consultai = "INSERT INTO estado_cuenta(id_banco, id_carga, fecha, referencia, concepto, monto, usuario) VALUES ('".$_POST[txt_banco]."', '$id','".voltea_fecha($valor[1])."', '".$valor[2]."', '".$valor[3]."', '".$valor[4]."', '".$_SESSION[CEDULA_USUARIO]."')"; //echo $consultai;
				 }
			else
				{			$consultai = "INSERT INTO estado_cuenta(id_banco, id_carga, fecha, referencia, concepto, monto, usuario) VALUES ('".$_POST[txt_banco]."', '$id', '".voltea_fecha($valor[1])."', '".$valor[3]."', '".$valor[2]."', '".$valor[4]."', '".$_SESSION[CEDULA_USUARIO]."')"; //echo $consultai;
				}
				$tablai = $_SESSION['conexionsql']->query($consultai);
			}
	}
	//echo '</table>' . "\n";
		  
		  
	  }
      Else 
      {
        $mensaje = 'Error al mover el archivo temporal al Servidor.';
      }
    }
    Else
    {
      $mensaje = 'Error de Carga. Solo se permiten archivos de Excel: ' . implode(',', $allowedfileExtensions);
    }
  }
  Else
  {
    $mensaje = 'Error en la Carga. Por favor revise el siguiente error.<br>';
    $mensaje .= 'Error:' . $_FILES['uploadedFile']['error'];
  }

$consulta = "DELETE FROM estado_cuenta WHERE monto=0;";
$tabla = $_SESSION['conexionsql']->query($consulta);
$_SESSION['mostrar'] = 'si';
$_SESSION['message'] = $mensaje;
if ($_SERVER['HTTP_HOST']=='localhost')
	{	header("Location: http://localhost/alcaldia/principal.php?mnu=2;");	}
else	
	{	header("Location: http://alcaldia.alcaldiafranciscodemiranda.com.ve/principal.php?mnu=2;");	}

?>


