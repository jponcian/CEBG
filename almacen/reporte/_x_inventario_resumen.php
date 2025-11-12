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
$consulta_xxx = "SELECT SUM(bn_materiales.inventario) as cant, SUM(bn_materiales.valor) as total FROM bn_materiales WHERE 0=0 $filtro"; 
//echo $consulta_xxx;
$tabla_xxx = $_SESSION['conexionsql']->query($consulta_xxx);
while ($registro_xxx = $tabla_xxx->fetch_object())
	{
	//-----------
	$pdf->Cell($a,4,formato_moneda($registro_xxx->cant),1,0,'C');
	$pdf->Cell($b,4,'',1,0,'L');
	$pdf->Cell($c,4,'',1,0,'L');
	$pdf->Cell($d,4,'TOTAL EN ALMACEN',1,0,'L');
	$pdf->Cell($e,4,'',1,0,'L');	
	$pdf->Cell($f,4,formato_moneda($registro_xxx->total),1,0,'R');	
	$pdf->Ln(4);
	//----------------------
	$i++;
	}
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
$pdf->Cell($a,4,$_SESSION['i'],1,0,'C');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,'',1,0,'C');
$pdf->Cell($e,4,'TOTAL',1,0,'C');	
$pdf->Cell($f,4,formato_moneda($_SESSION['monto']),1,0,'R');	
$pdf->Ln(4);
//-------------------	
?>