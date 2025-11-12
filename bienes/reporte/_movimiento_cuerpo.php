<?php
$pdf->AddPage();
		
$linea = 1; 
$alto = 4;
$i = 0;	

if ($_SESSION['tipo']==21 or $_SESSION['tipo']==121) {	$comprobante = '21';	$concepto = 'Recepción'; 	}
if ($_SESSION['tipo']==31 or $_SESSION['tipo']==131) {	$comprobante = '31';	$concepto = 'Entrega';		}

//////// ---- DETALLE
if ($_SESSION['tipo']==21 or $_SESSION['tipo']==31 or $_SESSION['tipo']==121 or $_SESSION['tipo']==131)
	{
	//----------
	if ($_SESSION['estatus']==0)
		{
		$consulta_x = "SELECT bn_bienes.*, bn_categorias.codigo FROM bn_categorias, bn_reasignaciones_detalle, bn_bienes WHERE bn_categorias.id_categoria=bn_bienes.id_categoria AND bn_bienes.id_bien = bn_reasignaciones_detalle.id_bien 	AND bn_reasignaciones_detalle.id_origen = ".$_SESSION['origen']." AND bn_reasignaciones_detalle.id_destino = ".$_SESSION['destino']." AND bn_reasignaciones_detalle.estatus = 0;";
		}
	else
		{
		$consulta_x = "SELECT bn_bienes.*, bn_categorias.codigo FROM bn_reasignaciones, bn_categorias, bn_reasignaciones_detalle, bn_bienes WHERE bn_reasignaciones_detalle.id_reasignacion=bn_reasignaciones.id AND bn_categorias.id_categoria=bn_bienes.id_categoria AND bn_bienes.id_bien = bn_reasignaciones_detalle.id_bien AND bn_reasignaciones.id = ".$_SESSION['id'].";";	
		}
	}
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); 
//echo '<br> Cuerpo => '.$consulta_x;

while ($registro_x = $tabla_x->fetch_object())
	{	
	$i++;	
	//++++++++++++++++++++++++++
	if ($y1 > 165 or $y2 > 165) 
		{ 
		$pdf->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
		$pdf->Cell($a,4,$_SESSION['i'],1,0,'C');
		$pdf->Cell($b,4,'',1,0,'L');
		$pdf->Cell($b,4,'',1,0,'L');
		$pdf->Cell($b,4,'',1,0,'L');
		$pdf->Cell($b,4,'',1,0,'L');
		$pdf->Cell($c,4,'',1,0,'L');
		$pdf->Cell(strtoupper($d),4,'VAN',1,0,'C');
		$pdf->Cell($f,4,'',1,0,'L');	
		$pdf->Cell($e,4,'SUBTOTAL',1,0,'R');	
		$pdf->Cell(0,$alto,formato_moneda($_SESSION['monto']),1,0,'R');	
		//----------------------------------
		$pdf->AddPage();  $y1=$pdf->GetY();	
		$pdf->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
		$pdf->Cell($a,4,$_SESSION['i'],1,0,'C');
		$pdf->Cell($b,4,'',1,0,'L');
		$pdf->Cell($b,4,'',1,0,'L');
		$pdf->Cell($b,4,'',1,0,'L');
		$pdf->Cell($b,4,'',1,0,'L');
		$pdf->Cell($c,4,'',1,0,'L');
		$pdf->Cell(strtoupper($d),4,'VIENEN',1,0,'C');
		$pdf->Cell($f,4,'',1,0,'L');	
		$pdf->Cell($e,4,'',1,0,'R');	
		$pdf->Cell($h,4,formato_moneda($_SESSION['monto']),1,0,'R');	
		$pdf->Ln(4);
		}
	//-------------------
	$pdf->SetFont('Times','',9);

	//----- PARA ARRANCAR CON LA LINEA
	$y1=$pdf->GetY();
	$x=$pdf->GetX();
	$pdf->SetX($x+$a+$b+$b+$b+$b+$c);
	//-----------------------------------------MULTICELL
	$pdf->MultiCell($d,$alto, ucfirst(strtolower($registro_x->descripcion_bien)),$linea,'J');
	$y2=$pdf->GetY();
	//- PARA PONER LAS COORDENADAS DESPUES DEL MULTICELL
	$pdf->SetY($y1);
	$pdf->SetX($x);
	$alto2 = $y2 - $y1;
	//---------------------------------------------------
	$pdf->Cell($a,($alto2),'01',$linea,0,'C');
	$pdf->Cell($b,($alto2), $registro_x->grupo,$linea,0,'C'); 
	$pdf->Cell($b,($alto2), $registro_x->subgrupo,$linea,0,'C'); 
	$pdf->Cell($b,($alto2), $registro_x->seccion,$linea,0,'C'); 
	$pdf->Cell($b,($alto2), $registro_x->subseccion,$linea,0,'C'); 
	$pdf->Cell($c,($alto2), $registro_x->numero_bien,$linea,0,'C'); 
	$pdf->SetX($x+$a+$b+$b+$b+$b+$c+$d);
	$pdf->Cell($f,($alto2), $concepto,$linea,0,'C'); 
	$pdf->Cell($e,($alto2), voltea_fecha($registro_x->fecha_adquisicion),$linea,0,'C'); 
	$pdf->Cell($h,($alto2), formato_moneda($registro_x->valor),$linea,0,'R'); 
	//--------------------
	$_SESSION['monto'] = $_SESSION['monto']+($registro_x->valor);
	
	//---------------------
	$pdf->Ln($alto2);
	$_SESSION['i']++;
	}

while ($pdf->GetY()<=170)
	{
	//----------- LINEA EN BLANCO
	$pdf->Cell($a,4,'',1,0,'C');
	$pdf->Cell($b,4,'',1,0,'L');
	$pdf->Cell($b,4,'',1,0,'L');
	$pdf->Cell($b,4,'',1,0,'L');
	$pdf->Cell($b,4,'',1,0,'L');
	$pdf->Cell($c,4,'',1,0,'L');
	$pdf->Cell($d,4,'',1,0,'C');
	$pdf->Cell($f,4,'',1,0,'R');	
	$pdf->Cell($e,4,'',1,0,'L');	
	$pdf->Cell($h,4,'',1,0,'R');	
	$pdf->Ln(4);
	//----------------------
	$i++;
	}

// TOTAL GENERAL
$pdf->SetY(-41.8);
$pdf->SetFont('Arial','B',$_SESSION['fuente_cabecera']);
$pdf->Cell($a,$alto,$_SESSION['i'],1,0,'C');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($b,4,'',1,0,'L');
$pdf->Cell($c,4,'',1,0,'L');
$pdf->Cell($d,4,'',1,0,'C');
$pdf->Cell($f,4,'',1,0,'L');	
$pdf->Cell($e,$alto,'TOTAL',1,0,'C');	
$pdf->Cell(0,$alto,formato_moneda($_SESSION['monto']),1,0,'R');	
//----------------------------------
?>