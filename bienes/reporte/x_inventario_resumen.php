<?php
$pdf->AddPage();
//-----------
$pdf->SetFont('Arial','B',8);
//----------- LINEA EN BLANCO
$pdf->Cell($a,4,'',1,0,'C');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,'',1,0,'C');
$pdf->Cell($e,4,'',1,0,'L');	
$pdf->Cell($f,4,'',1,0,'R');	
$pdf->Ln(4);
//----------------------
$pdf->Cell($a,4,'',1,0,'C');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,'RESUMEN DE BIENES NACIONALES',1,0,'C');
$pdf->Cell($e,4,'',1,0,'L');	
$pdf->Cell($f,4,'',1,0,'R');	
$pdf->Ln(4);
//----------------------
$pdf->Cell($a,4,'',1,0,'C');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,''.strtoupper(($_SESSION['DIVISION_L'])),1,0,'C');
$pdf->Cell($e,4,'',1,0,'L');	
$pdf->Cell($f,4,'',1,0,'R');	
$pdf->Ln(4);
//----------------------
$pdf->Cell($a,4,'',1,0,'C');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,'Fecha: '.date('d/m/Y'),1,0,'C');
$pdf->Cell($e,4,'',1,0,'L');	
$pdf->Cell($f,4,'',1,0,'R');	
$pdf->Ln(4);
//----------- LINEA EN BLANCO
$pdf->Cell($a,4,'',1,0,'C');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,'',1,0,'C');
$pdf->Cell($e,4,'',1,0,'L');	
$pdf->Cell($f,4,'',1,0,'R');	
$pdf->Ln(4);
//----------------------
$i=0;
//----------------------
	$pdf->Cell($a,4,$_SESSION['i'],1,0,'C');
	$pdf->Cell($b,4,'',1,0,'L');
	$pdf->Cell($b,4,'',1,0,'L');
	$pdf->Cell($b,4,'',1,0,'L');
	$pdf->Cell($b,4,'',1,0,'L');
	$pdf->Cell($c,4,'',1,0,'L');
	$pdf->Cell($d,4,$_SESSION['DIVISION_L'],1,0,'L');
	$pdf->Cell($e,4,'',1,0,'L');	
	$pdf->Cell($f,4,formato_moneda($_SESSION['monto']),1,0,'R');	
	$pdf->Ln(4);
	//----------------------
	$i++;
//----------------------
while ($i<=16)
	{
	//----------- LINEA EN BLANCO
	$pdf->Cell($a,4,'',1,0,'C');
	$pdf->Cell($b,4,'',1,0,'L');
	$pdf->Cell($b,4,'',1,0,'L');
	$pdf->Cell($b,4,'',1,0,'L');
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
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,'',1,0,'C');
$pdf->Cell($e,4,'TOTAL',1,0,'C');	
$pdf->Cell($f,4,formato_moneda($_SESSION['monto']),1,0,'R');	
$pdf->Ln(4);
//-------------------	

?>
<?php

$alto_cabecera = 4;
$a=35 ; 	
$b=100 ; 
$c=80 ;

if ($_SESSION['tipo']==21 or $_SESSION['tipo']==121)
	{
	$jefe = jefe_direccion($_SESSION['id_dependencia']);
	}
elseif ($_SESSION['tipo']==31 or $_SESSION['tipo']==131)
	{
	$jefe = jefe_direccion($_SESSION['id_dependencia']);
	}
else
	{
	$jefe = jefe_direccion($_SESSION['id_direccion']); 
	//echo $_SESSION['id_direccion'];
	}
//----------------------------
$pdf->SetY(-37.8+8);
$_SESSION['nombres']='no'; 
$pdf->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$pdf->Cell($a,$alto_cabecera+2,'V-'.formato_cedula($jefe[0]),1,0,'L');
$pdf->Cell($b,$alto_cabecera+2,$jefe[1],1,0,'L');
$pdf->Cell($c,$alto_cabecera+2,$jefe[2],1,0,'L');	
?>