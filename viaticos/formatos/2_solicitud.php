<?php
session_start();
//ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class PDF extends FPDF
{
	function Header()
	{    
		//$this->SetY(10);
		//--------------
		$this->SetY(17);
		$this->SetFillColor(240);
		$this->Image('../../images/logo_nuevo.jpg',28,12,25);
		//$this->Image('../../images/bandera_linea.png',17,41,182,1);
		// ---------------------
		//$this->SetY(12);
		$this->SetFont('Times','I',12);
		$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); $this->Ln(5);
		$this->Cell(0,5,'Dirección de Administración y Presupuesto',0,0,'C'); $this->Ln(5);		
		$this->Cell(0,5,'Rif G-20001287-0',0,0,'C'); $this->Ln(10);
	}	
	
	function Footer()
	{}	
}

$id = decriptar($_GET['id']);
//-------------	

// ENCABEZADO
$pdf=new PDF('P','mm','LETTER');
$pdf->AliasNbPages('paginas');
$pdf->SetMargins(21,80,21);
$pdf->SetAutoPageBreak(1,10);
$pdf->SetTitle('Solicitud de Viaticos');
$pdf->SetFillColor(240);
// ----------
$pdf->AddPage();
$pdf->SetFont('Times','',9);
$a=75;
$b=15;
$id = decriptar($_GET['id']);
$consulta = "SELECT 	a_direcciones.cedula as jefe, a_direcciones.cargo, a_direcciones.providencia, 	a_direcciones.fecha_prov, a_direcciones.gaceta, a_direcciones.fecha_gaceta, viaticos_solicitudes.fecha,	viaticos_solicitudes.numero, viaticos_solicitudes.concepto,	viaticos_solicitudes.desde,	viaticos_solicitudes.hora1,	viaticos_solicitudes.hasta,	viaticos_solicitudes.hora2,	viaticos_solicitudes.ciudad, a_zonas_viaticos.zona,	rac.cedula,	CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre,	rac.cargo,	a_direcciones.direccion,	a_areas.area FROM	rac	INNER JOIN viaticos_solicitudes ON rac.cedula = viaticos_solicitudes.cedula	INNER JOIN a_zonas_viaticos ON a_zonas_viaticos.id = viaticos_solicitudes.zona	INNER JOIN a_direcciones ON viaticos_solicitudes.direccion = a_direcciones.id	INNER JOIN a_areas ON viaticos_solicitudes.area = a_areas.id where viaticos_solicitudes.id=$id";
//echo $consulta;
$tabla = $_SESSION['conexionsql']->query($consulta);
$registro = $tabla->fetch_object();
//-----------------
$fecha = voltea_fecha($registro->fecha);
$numero = rellena_cero($registro->numero,5);
$cedula = formato_cedula($registro->cedula);
$nombre = ($registro->nombre);
$cargo = ($registro->cargo);
$direccion = ($registro->direccion);
$area = ($registro->area);
$zona = ($registro->zona);
$ciudad = ($registro->ciudad);
$concepto = ($registro->concepto);
$salida = voltea_fecha($registro->desde).' '.($registro->hora1);
$llegada = voltea_fecha($registro->hasta).' '.($registro->hora2);
$jefe = ($registro->jefe);
$cargo = ($registro->cargo);
$providencia = ($registro->providencia);
$fecha_prov = voltea_fecha($registro->fecha_prov);
$empleado = empleado($jefe);
//-----------------

//-------------
$pdf->SetFont('Times','B',15);
$pdf->Cell(0,5,'SOLICITUD DE VIATICOS',0,0,'C'); 		
$pdf->Ln();

//-------------
$pdf->SetFont('Arial','B',14);
$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,6,'N° '.$numero,0,0,'R',0);
$pdf->SetTextColor(0);
$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6,'Fecha: '.$fecha,0,0,'R',0);
$pdf->Ln(10);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,'DATOS DEL SOLICITANTE:',1,0,'L',1);
$pdf->Ln();
$y = $pdf->GetY();
$pdf->Cell(0,6,'',1,0,'L',0);
$pdf->Ln();
$pdf->SetY($y);
$pdf->SetFont('Arial','',10);
$pdf->Cell(30,6.5,'CEDULA:',0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,$cedula,0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$y = $pdf->GetY();
$pdf->Cell(0,6.5,'',1,0,'L',0);
$pdf->Ln();
$pdf->SetY($y);
$pdf->Cell(30,6.5,'FUNCIONARIO:',0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6.5,$nombre,0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$y = $pdf->GetY();
$pdf->Cell(0,6.5,'',1,0,'L',0);
$pdf->Ln();
$pdf->SetY($y);
$pdf->Cell(30,6.5,'CARGO:',0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6.5,$cargo,0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$y = $pdf->GetY();
$pdf->Cell(0,6.5,'',1,0,'L',0);
$pdf->Ln();
$pdf->SetY($y);
$pdf->Cell(30,6.5,'DEPENDENCIA:',0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6.5,$direccion,0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Ln();

$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,'DATOS DE LA COMISIÓN:',1,0,'L',1);
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Multicell(0,6.5,'MOTIVO: '.$concepto,1,'J',0);
$y = $pdf->GetY();
$pdf->Cell(40,6.5,'',1,0,'L',0);
$pdf->Cell(0,6.5,'',1,0,'L',0);
$pdf->Ln();
$pdf->SetY($y);
$pdf->Cell(15,6.5,'ZONA:',0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,6.5,$zona,0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,6.5,'CIUDAD:',0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6.5,$ciudad,0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$y = $pdf->GetY();
$pdf->Cell(0,6.5,'',1,0,'L',0);
$pdf->Ln();
$pdf->SetY($y);
$pdf->Cell(48,6.5,'FECHA Y HORA DE SALIDA:',0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(37,6.5,$salida,0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(52,6.5,'FECHA Y HORA DE LLEGADA:',0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6.5,$llegada,0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Ln();

$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,'CÁLCULO DEL VIATICO:',1,0,'L',1);
$pdf->SetFont('Arial','',10);
$pdf->Ln();

$y = $pdf->GetY();
$pdf->Cell($a=182/4,26,'',1,0,'L',0);
$pdf->Cell($a,26,'',1,0,'L',0);
$pdf->Cell($a,26,'',1,0,'L',0);
$pdf->Cell(0,26,'',1,0,'L',0);
$pdf->Ln();
$pdf->SetY($y);

$y = $pdf->GetY();
$x = $pdf->GetX();
$pdf->Multicell($a,5,'Dias (Desayuno, Almuerzo y Cena) Bs.',0,'C',0);
$pdf->SetXY($x+$a,$y);
$pdf->Multicell($a,10,'Dias (Transporte) Bs.',0,'C',0);
$pdf->SetXY($x+$a*2,$y);
$pdf->Multicell($a,10,'Dias (Alojamiento) Bs.',0,'C',0);
$pdf->SetXY($x+$a*3,$y);
$pdf->Multicell(0,5,'Km (Asignación por Kilómetro) Bs.',0,'C',0);
//$pdf->Ln(1);

$pdf->SetFont('Arial','B',11.5);
$monto = 0;

$consultaX = "SELECT (sum(viaticos_solicitudes_detalle.total)+0) as total FROM 	viaticos_solicitudes_detalle, a_item_viaticos WHERE a_item_viaticos.id=viaticos_solicitudes_detalle.id_tipo and	viaticos_solicitudes_detalle.id_solicitud = $id and a_item_viaticos.grupo=1 GROUP BY a_item_viaticos.grupo";
//echo $consulta;
$tablaX = $_SESSION['conexionsql']->query($consultaX);

if ($tablaX->num_rows>0)	{
	$registroX = $tablaX->fetch_object(); $monto += $registroX->total;
	$pdf->Cell($a,16,formato_moneda($registroX->total),0,0,'C',0);
	}
else
	{	$pdf->Cell($a,16,formato_moneda(0),0,0,'C',0);	}

$consultaX = "SELECT (sum(viaticos_solicitudes_detalle.total)+0) as total FROM 	viaticos_solicitudes_detalle, a_item_viaticos WHERE a_item_viaticos.id=viaticos_solicitudes_detalle.id_tipo and	viaticos_solicitudes_detalle.id_solicitud = $id and a_item_viaticos.grupo=2 GROUP BY a_item_viaticos.grupo";
//echo $consulta;
$tablaX = $_SESSION['conexionsql']->query($consultaX);

if ($tablaX->num_rows>0)	{
	$registroX = $tablaX->fetch_object(); $monto += $registroX->total;
	$pdf->Cell($a,16,formato_moneda($registroX->total),0,0,'C',0);
	}
else
	{	$pdf->Cell($a,16,formato_moneda(0),0,0,'C',0);	}

$consultaX = "SELECT (sum(viaticos_solicitudes_detalle.total)+0) as total FROM 	viaticos_solicitudes_detalle, a_item_viaticos WHERE a_item_viaticos.id=viaticos_solicitudes_detalle.id_tipo and	viaticos_solicitudes_detalle.id_solicitud = $id and a_item_viaticos.grupo=4 GROUP BY a_item_viaticos.grupo";
//echo $consulta;
$tablaX = $_SESSION['conexionsql']->query($consultaX);

if ($tablaX->num_rows>0)	{
	$registroX = $tablaX->fetch_object(); $monto += $registroX->total;
	$pdf->Cell($a,16,formato_moneda($registroX->total),0,0,'C',0);
	}
else
	{	$pdf->Cell($a,16,formato_moneda(0),0,0,'C',0);	}

$consultaX = "SELECT (sum(viaticos_solicitudes_detalle.total)+0) as total FROM 	viaticos_solicitudes_detalle, a_item_viaticos WHERE a_item_viaticos.id=viaticos_solicitudes_detalle.id_tipo and	viaticos_solicitudes_detalle.id_solicitud = $id and a_item_viaticos.grupo=3 GROUP BY a_item_viaticos.grupo";
//echo $consulta;
$tablaX = $_SESSION['conexionsql']->query($consultaX);

if ($tablaX->num_rows>0)	{
	$registroX = $tablaX->fetch_object(); $monto += $registroX->total;
	$pdf->Cell($a,16,formato_moneda($registroX->total),0,0,'C',0);
	}
else
	{	$pdf->Cell($a,16,formato_moneda(0),0,0,'C',0);	}

$pdf->Ln();
$pdf->MultiCell(0,5.5,'TOTAL EN BOLIVARES : *** '.strtoupper(valorEnLetras($monto)).' ***',1,'J',1);
$pdf->Ln(1);

$pdf->SetFont('Arial','B',11);

$pdf->Cell(182/4,8,'SOLICITANTE',1,0,'C',1);
$pdf->Cell(0,8,'APROBACION',1,0,'C',1);
$pdf->Ln();

$pdf->SetFont('Arial','',9);

$y = $pdf->GetY();
$y2 = $pdf->GetY();
$pdf->Cell($a=182/4,26,'',1,0,'L',0);
$pdf->Cell($a,26,'',1,0,'L',0);
$pdf->Cell($a,26,'',1,0,'L',0);
$pdf->Cell(0,26,'',1,0,'L',0);
$pdf->Ln();
$pdf->SetY($y+16);

$y = $pdf->GetY();
$x = $pdf->GetX();
$pdf->SetXY($x,$y-8);
$pdf->Multicell($a,4,'Firma

'.$nombre,0,'C',0);
$pdf->SetXY($x+$a,$y);
$pdf->Multicell($a,4,'Firma',0,'C',0);
$pdf->SetXY($x+$a*2,$y-12);
$pdf->Multicell($a,4,$empleado[1].'
'.$providencia.' de fecha '.$fecha_prov,0,'C',0);
$pdf->SetXY($x+$a*3,$y-4);
$pdf->SetFont('Arial','',11);
$pdf->Multicell(0,4,$fecha,0,'C',0);
$pdf->SetY($y2+27);

$pdf->SetFont('Arial','B',11);

$pdf->Cell(0,8,'SOLO PARA USO DE ADMINISTRACION Y PRESUPUESTO',1,0,'C',1);
$pdf->Ln();

$y = $pdf->GetY();
$pdf->Cell($a=182/4,26,'',1,0,'L',0);
$pdf->Cell($a,26,'',1,0,'L',0);
$pdf->Cell($a,26,'',1,0,'L',0);
$pdf->Cell(0,26,'',1,0,'L',0);
$pdf->Ln();

$pdf->SetFont('Arial','',8.5);

$pdf->SetY($y+13);
$y = $pdf->GetY();
$x = $pdf->GetX();
$pdf->SetXY($x,$y+4);
$pdf->Multicell($a,4,'SOLICITADO POR DIRECTOR ADMON Y PRESUPUESTO',0,'C',0);
$pdf->SetXY($x+$a,$y+4);
$pdf->Multicell($a,4,'REVISADO POR DIRECTOR ADMON Y PRESUPUESTO',0,'C',0);
$pdf->SetXY($x+$a*2,$y);
$pdf->Multicell($a,4,'AUTORIZADO POR CONTRALOR (A) DEL ESTADO BOLIVARIANO GUARICO',0,'C',0);
$pdf->SetXY($x+$a*3,$y);
$pdf->Multicell(0,4,'REVISADO POR CONTRALOR (A) DEL ESTADO BOLIVARIANO',0,'C',0);



$pdf->Output();
?>