<?php
$pdf->AddPage();
//-----------
$pdf->SetFont('Arial','B',8);
//----------- LINEA EN BLANCO
$pdf->Cell($a,4,'',1,0,'C');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,'',1,0,'C');
$pdf->Cell($e,4,'',1,0,'L');	
$pdf->Cell($f,4,'',1,0,'R');	
$pdf->Ln(4);
//----------------------
$pdf->Cell($a,4,'',1,0,'C');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,'RESUMEN DE MATERIALES',1,0,'C');
$pdf->Cell($e,4,'',1,0,'L');	
$pdf->Cell($f,4,'',1,0,'R');	
$pdf->Ln(4);
//----------------------
$pdf->Cell($a,4,'',1,0,'C');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,''.strtoupper(($_SESSION['DIVISION_L'])),1,0,'C');
$pdf->Cell($e,4,'',1,0,'L');	
$pdf->Cell($f,4,'',1,0,'R');	
$pdf->Ln(4);
//----------------------
$pdf->Cell($a,4,'',1,0,'C');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,'Fecha: '.date('d/m/Y'),1,0,'C');
$pdf->Cell($e,4,'',1,0,'L');	
$pdf->Cell($f,4,'',1,0,'R');	
$pdf->Ln(4);
//----------- LINEA EN BLANCO
$pdf->Cell($a,4,'',1,0,'C');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,'',1,0,'C');
$pdf->Cell($e,4,'',1,0,'L');	
$pdf->Cell($f,4,'',1,0,'R');	
$pdf->Ln(4);
//----------------------
$i=0;
//----------------------
//if ($_SESSION['estatus']==0)
//	{
//	$consulta_xxx = "SELECT SUM(bn_ingresos_detalle.cantidad) as cant, SUM(bn_materiales.valor) as total FROM bn_ingresos_detalle, bn_materiales WHERE estatus=0 AND bn_ingresos_detalle.id_bien=bn_materiales.id_bien"; //echo $consulta_xxx;
//	}	
//else
//	{
//	$consulta_xxx = "SELECT SUM(bn_ingresos_detalle.cantidad) as cant, SUM(bn_materiales.valor) as total FROM bn_ingresos_detalle, bn_materiales, bn_ingresos WHERE bn_ingresos.id = id_ingreso AND bn_ingresos.id=$id AND bn_ingresos_detalle.id_bien=bn_materiales.id_bien"; 
//	}	//echo $consulta_xxx;
//$tabla_xxx = $_SESSION['conexionsql']->query($consulta_xxx);
//while ($registro_xxx = $tabla_xxx->fetch_object())
//	{
	//-----------
	$pdf->Cell($a,4,$cantidad,1,0,'C');
	$pdf->Cell($b,4,'',1,0,'L');
	$pdf->Cell($c,4,'',1,0,'L');
	$pdf->Cell($d,4,$_SESSION['DIVISION_L'],1,0,'L');
	$pdf->Cell($e,4,'',1,0,'L');	
	$pdf->Cell($f,4,formato_moneda($_SESSION['monto']),1,0,'R');	
	$pdf->Ln(4);
	//----------------------
	$i++;
//	}
//----------------------
while ($i<=16)
	{
	//----------- LINEA EN BLANCO
	$pdf->Cell($a,4,'',1,0,'C');
	$pdf->Cell($b,4,'',1,0,'L');
	$pdf->Cell($c,4,'',1,0,'L');
	$pdf->Cell($d,4,'',1,0,'C');
	$pdf->Cell($e,4,'',1,0,'L');	
	$pdf->Cell($f,4,'',1,0,'R');	
	$pdf->Ln(4);
	//----------------------
	$i++;
	}

//----------------------
$pdf->Cell($a,4,$cantidad,1,0,'C');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,'',1,0,'C');
$pdf->Cell($e,4,'TOTAL',1,0,'C');	
$pdf->Cell($f,4,formato_moneda($_SESSION['monto']),1,0,'R');	
$pdf->Ln(4);
//-------------------	
?>