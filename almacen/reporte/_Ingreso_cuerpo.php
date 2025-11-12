<?php
$pdf->AddPage();
		
$linea = 1; 
$alto = 4;
$i = 0;	

//////// ---- DETALLE
if ($estatus==0)
	{
	$consulta_x = "SELECT bn_materiales.*, bn_ingresos_detalle.cantidad, bn_categorias.codigo FROM bn_categorias, bn_materiales, bn_ingresos_detalle WHERE estatus=0 AND bn_ingresos_detalle.id_bien=bn_materiales.id_bien AND bn_materiales.id_categoria = bn_categorias.id_categoria ORDER BY bn_materiales.descripcion_bien;";
	}	
else
	{
	$consulta_x = "SELECT bn_materiales.*, bn_ingresos_detalle.cantidad, bn_categorias.codigo FROM bn_ingresos, bn_categorias, bn_materiales, bn_ingresos_detalle WHERE bn_ingresos.id = id_ingreso AND bn_ingresos.id=$id AND bn_ingresos_detalle.id_bien=bn_materiales.id_bien AND bn_materiales.id_categoria = bn_categorias.id_categoria ORDER BY bn_materiales.descripcion_bien;";
	}	
//echo $consulta_x;
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); 
//echo '<br> Cuerpo => '.$consulta_x;
$cantidad = 0;

while ($registro_x = $tabla_x->fetch_object())
	{	
	$i++;	
	//++++++++++++++++++++++++++
	if ($y1 > 170 or $y2 > 170) 
		{ 
		$pdf->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
		$pdf->Cell(20,$alto,$_SESSION['i'],1,0,'C');
		$pdf->Cell(25,$alto,'',1,0,'L');
		$pdf->Cell(25,$alto,'',1,0,'L');
		$pdf->Cell(130,$alto,'VAN',1,0,'C');
		$pdf->Cell(23,$alto,'SUBTOTAL',1,0,'C');	
		$pdf->Cell(0,$alto,formato_moneda($_SESSION['monto']),1,0,'R');	
		//----------------------------------
		$pdf->AddPage();  $y1=$pdf->GetY();	
		$pdf->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
		$pdf->Cell($a,4,$_SESSION['i'],1,0,'C');
		$pdf->Cell($b,4,'',1,0,'L');
		$pdf->Cell($c,4,'',1,0,'L');
		$pdf->Cell(strtoupper($d),4,'VIENEN',1,0,'C');
		$pdf->Cell($e,4,'',1,0,'L');	
		$pdf->Cell($f,4,formato_moneda($_SESSION['monto']),1,0,'R');	
		$pdf->Ln(4);
		}
	//-------------------
	$pdf->SetFont('Times','',9);

	//----- PARA ARRANCAR CON LA LINEA
	$y1=$pdf->GetY();
	$x=$pdf->GetX();
	$pdf->SetX($x+$a+$b+$c);
	//-----------------------------------------MULTICELL
	$pdf->MultiCell($d,$alto, ucfirst(strtolower($registro_x->descripcion_bien)),$linea,'J');
	$y2=$pdf->GetY();
	//- PARA PONER LAS COORDENADAS DESPUES DEL MULTICELL
	$pdf->SetY($y1);
	$pdf->SetX($x);
	$alto2 = $y2 - $y1;
	//---------------------------------------------------
	$pdf->Cell($a,($alto2),$registro_x->cantidad,$linea,0,'C');
	$pdf->Cell($b,($alto2), $registro_x->codigo,$linea,0,'C'); 
	$pdf->Cell($c,($alto2), '',$linea,0,'C'); 
	//$pdf->Cell($c,($alto2), $registro_x->numero_bien,$linea,0,'C'); 
	$pdf->SetX($x+$a+$b+$c+$d);
	$pdf->Cell($e,($alto2), $registro_x->conservacion,$linea,0,'C'); 
	$pdf->Cell($f,($alto2), formato_moneda($registro_x->valor),$linea,0,'R'); 
	//--------------------
	$_SESSION['monto'] = $_SESSION['monto']+($registro_x->valor);
	
	//---------------------
	$pdf->Ln($alto2);
	$_SESSION['i']++;
	$cantidad = $cantidad + $registro_x->cantidad;
	}

while ($pdf->GetY()<=170)
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

// TOTAL GENERAL
$pdf->SetY(-41.8);
$pdf->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$pdf->Cell(20,$alto,$cantidad,1,0,'C');
$pdf->Cell(25,$alto,'',1,0,'L');
$pdf->Cell(25,$alto,'',1,0,'L');
$pdf->Cell(130,$alto,'',1,0,'C');
$pdf->Cell(23,$alto,'TOTAL',1,0,'C');	
$pdf->Cell(0,$alto,formato_moneda($_SESSION['monto']),1,0,'R');	
//----------------------------------
?>