<?php

$consultx = "SELECT * FROM a_direcciones WHERE id=0".$jefe.";";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro_x = $tablx->fetch_object();

//---------------------------------
$cedula = "C.I. N° V-" .$registro_x->cedula;
$cargo = $registro_x->cargo;
$providencia = $registro_x->providencia;
$fecha_prov = $registro_x->fecha_prov;
$gaceta = $registro_x->gaceta;
$fechgac = $registro_x->fecha_gaceta;
$empleado = empleado($registro_x->cedula);
//---------------------------------
$pdf->SetFont('Times','',9);
$pdf->Cell(0,5,'_________________________________',0,0,'C'); 
$pdf->Ln(8);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,mayuscula($empleado[1]),0,0,'C'); 
$pdf->Ln(5);
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,5,($cargo),0,0,'C'); 
$pdf->Ln(5);
$pdf->SetRightMargin(60);
$pdf->SetLeftMargin(60);
$pdf->SetFont('Times','',10.5);
$pdf->MultiCell(0,4,($providencia).' de fecha '.voltea_fecha($fecha_prov),0,'C'); 
$pdf->MultiCell(0,4,($gaceta).' de fecha '.voltea_fecha($fechgac),0,'C'); 
// FIN
$pdf->SetRightMargin(17);
$pdf->SetLeftMargin(17);
//----------------
?>