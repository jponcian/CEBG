<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
//setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION[ 'conexionsql' ]->query( "SET NAMES 'utf8'" );

if ( $_SESSION[ 'VERIFICADO' ] != "SI" ) {
  header( "Location: ../index.php?errorusuario=val" );
  exit();
}

$i = 0;
$nomina = '';
$ubicacion = '';
//-----------------
$tabla = $_SESSION[ 'conexionsql' ]->query( $_SESSION[ 'consulta' ] );
//-----------------
$i = 0;
$monto = 0;
?>
<table border="1">
  <?php
  while ( $registro = $tabla->fetch_object() ) {
    ?>
  <tr>
    <td><?php echo $registro->ubicacion; ?></td>
    <td><?php echo $registro->cargo; ?></td>
    <td><?php echo $registro->tipo_cargo; ?></td>
    <td><?php echo $registro->nombre." ".$registro->nombre2." ".$registro->apellido." ".$registro->apellido2; ?></td>
    <td><?php echo $registro->cedula; ?></td>
    <td><?php echo voltea_fecha($registro->fecha_ingreso); ?></td>
    <td><?php echo $registro->tipo; ?></td>
    <td><?php echo $registro->grados; ?></td>
    <td><?php echo $registro->paso; ?></td>
    <?php
    //	if ($nomina<>$registro->nomina)
    //		{	
    //		$pdf->Cell(0,5.5,'				'.$registro->nomina,1,1,'L',1);	
    //		$nomina = $registro->nomina ;
    //		}
    	if ($ubicacion<>$registro->ubicacion)
    		{	
    		 ?><tr><td colspan="9"></td></tr><?php
    		$ubicacion = $registro->ubicacion ;
    		}
    //----------
    //	$pdf->Cell($aa,5.5,$i+1,1,0,'C',1);
    //	$pdf->Cell($a,5.5,$registro->cedula,1,0,'C',1);
    //	$pdf->Cell($b,5.5,$registro->nombre." ".$registro->nombre2." ".$registro->apellido." ".$registro->apellido2,1,0,'L',1);
    //	$pdf->Cell($c,5.5,$registro->cargo,1,0,'L',1);
    //	$pdf->Cell($d,5.5,voltea_fecha($registro->fecha_ingreso),1,0,'C',1);
    //		if ($_SESSION['filtro']==3)
    //			{$pdf->Cell($d1,5.5,voltea_fecha($registro->fecha_egreso),1,0,'C',1);}
    //		else	
    //			{$pdf->Cell($d1,5.5,$_SESSION['profesion'][$registro->profesion],1,0,'L',1);}
    //	$pdf->Cell($d2,5.5,$registro->hijos,1,0,'C',1);
    //	$pdf->Cell($d,5.5,$registro->suspendido,1,0,'C',1);
    //	$pdf->Cell($e,5.5,$registro->telefono,1,0,'L',1);
    //-----------
    //-----------
    ?>
  </tr>
  <?php
  $monto = $monto + $registro->sueldo;
  $i++;
  }

  ?>
</table>
