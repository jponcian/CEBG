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
$pdf->Cell($d,4,'RESUMEN DE BIENES NACIONALES',1,0,'C');
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
//----------------------
$pdf->Cell($a,4,'',1,0,'C');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,'',1,0,'C');
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
$consulta_xxx = "SELECT COUNT(bn_bienes.id_bien) as cant, division, SUM(bn_bienes.valor) as total FROM bn_bienes, a_areas, a_direcciones WHERE bn_bienes.id_area = a_areas.id AND a_areas.id_direccion = a_direcciones.id GROUP BY a_direcciones.id"; //echo $consulta_xxx;
$tabla_xxx = $_SESSION['conexionsql']->query($consulta_xxx);
while ($registro_xxx = $tabla_xxx->fetch_object())
	{
	//-----------
	$pdf->Cell($a,4,$registro_xxx->cant,1,0,'C');
	$pdf->Cell($b,4,'',1,0,'L');
	$pdf->Cell($c,4,'',1,0,'L');
	$pdf->Cell($d,4,$registro_xxx->division,1,0,'L');
	$pdf->Cell($e,4,'',1,0,'L');	
	$pdf->Cell($f,4,formato_moneda($registro_xxx->total),1,0,'R');	
	$pdf->Ln(4);
	//----------------------
	$i++;
	$_SESSION['i'] += $registro_xxx->cant;
	$_SESSION['monto'] += $registro_xxx->total;
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
//---------------
?>