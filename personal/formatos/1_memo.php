<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

if ($_SESSION['VERIFICADO'] != "SI") { 
    header ("Location: ../index.php?errorusuario=val"); 
    exit(); 
	}
	
class PDF_WriteTag extends FPDF
{	
	function Footer()
	{    
//		$this->SetTextColor(50);
//		$this->SetFont('Times','I',11);
//		$this->SetY(-12);
//		$this->Cell(0,0,'SIACEBG',0,0,'L');
	}
//----------------- PARA NEGRITAS EN TEXTO

	protected $wLine; // Maximum width of the line
	protected $hLine; // Height of the line
	protected $Text; // Text to display
	protected $border;
	protected $align; // Justification of the text
	protected $fill;
	protected $Padding;
	protected $lPadding;
	protected $tPadding;
	protected $bPadding;
	protected $rPadding;
	protected $TagStyle; // Style for each tag
	protected $Indent;
	protected $Space; // Minimum space between words
	protected $PileStyle; 
	protected $Line2Print; // Line to display
	protected $NextLineBegin; // Buffer between lines 
	protected $TagName;
	protected $Delta; // Maximum width minus width
	protected $StringLength; 
	protected $LineLength;
	protected $wTextLine; // Width minus paddings
	protected $nbSpace; // Number of spaces in the line
	protected $Xini; // Initial position
	protected $href; // Current URL
	protected $TagHref; // URL for a cell

	// Public Functions	
	
	function WriteTag($w, $h, $txt, $border=0, $align="J", $fill=false, $padding=0)
	{
		$this->wLine=$w;
		$this->hLine=$h;
		$this->Text=trim($txt);
		$this->Text=preg_replace("/\n|\r|\t/","",$this->Text);
		$this->border=$border;
		$this->align=$align;
		$this->fill=$fill;
		$this->Padding=$padding;

		$this->Xini=$this->GetX();
		$this->href="";
		$this->PileStyle=array();		
		$this->TagHref=array();
		$this->LastLine=false;
		$this->NextLineBegin=array();

		$this->SetSpace();
		$this->Padding();
		$this->LineLength();
		$this->BorderTop();

		while($this->Text!="")
		{
			$this->MakeLine();
			$this->PrintLine();
		}

		$this->BorderBottom();
	}


	function SetStyle($tag, $family, $style, $size, $color, $indent=-1)
	{
		 $tag=trim($tag);
		 $this->TagStyle[$tag]['family']=trim($family);
		 $this->TagStyle[$tag]['style']=trim($style);
		 $this->TagStyle[$tag]['size']=trim($size);
		 $this->TagStyle[$tag]['color']=trim($color);
		 $this->TagStyle[$tag]['indent']=$indent;
	}


	// Private Functions

	function SetSpace() // Minimal space between words
	{
		$tag=$this->Parser($this->Text);
		$this->FindStyle($tag[2],0);
		$this->DoStyle(0);
		$this->Space=$this->GetStringWidth(" ");
	}


	function Padding()
	{
		if(preg_match("/^.+,/",$this->Padding)) {
			$tab=explode(",",$this->Padding);
			$this->lPadding=$tab[0];
			$this->tPadding=$tab[1];
			if(isset($tab[2]))
				$this->bPadding=$tab[2];
			else
				$this->bPadding=$this->tPadding;
			if(isset($tab[3]))
				$this->rPadding=$tab[3];
			else
				$this->rPadding=$this->lPadding;
		}
		else
		{
			$this->lPadding=$this->Padding;
			$this->tPadding=$this->Padding;
			$this->bPadding=$this->Padding;
			$this->rPadding=$this->Padding;
		}
		if($this->tPadding<$this->LineWidth)
			$this->tPadding=$this->LineWidth;
	}


	function LineLength()
	{
		if($this->wLine==0)
			$this->wLine=$this->w - $this->Xini - $this->rMargin;

		$this->wTextLine = $this->wLine - $this->lPadding - $this->rPadding;
	}


	function BorderTop()
	{
		$border=0;
		if($this->border==1)
			$border="TLR";
		$this->Cell($this->wLine,$this->tPadding,"",$border,0,'C',$this->fill);
		$y=$this->GetY()+$this->tPadding;
		$this->SetXY($this->Xini,$y);
	}


	function BorderBottom()
	{
		$border=0;
		if($this->border==1)
			$border="BLR";
		$this->Cell($this->wLine,$this->bPadding,"",$border,0,'C',$this->fill);
	}


	function DoStyle($tag) // Applies a style
	{
		$tag=trim($tag);
		$this->SetFont($this->TagStyle[$tag]['family'],
			$this->TagStyle[$tag]['style'],
			$this->TagStyle[$tag]['size']);

		$tab=explode(",",$this->TagStyle[$tag]['color']);
		if(count($tab)==1)
			$this->SetTextColor($tab[0]);
		else
			$this->SetTextColor($tab[0],$tab[1],$tab[2]);
	}


	function FindStyle($tag, $ind) // Inheritance from parent elements
	{
		$tag=trim($tag);

		// Family
		if($this->TagStyle[$tag]['family']!="")
			$family=$this->TagStyle[$tag]['family'];
		else
		{
			foreach($this->PileStyle as $val)
			{
				$val=trim($val);
				if($this->TagStyle[$val]['family']!="") {
					$family=$this->TagStyle[$val]['family'];
					break;
				}
			}
		}

		// Style
		$style="";
		$style1=strtoupper($this->TagStyle[$tag]['style']);
		if($style1!="N")
		{
			$bold=false;
			$italic=false;
			$underline=false;
			foreach($this->PileStyle as $val)
			{
				$val=trim($val);
				$style1=strtoupper($this->TagStyle[$val]['style']);
				if($style1=="N")
					break;
				else
				{
					if(strpos($style1,"B")!==false)
						$bold=true;
					if(strpos($style1,"I")!==false)
						$italic=true;
					if(strpos($style1,"U")!==false)
						$underline=true;
				} 
			}
			if($bold)
				$style.="B";
			if($italic)
				$style.="I";
			if($underline)
				$style.="U";
		}

		// Size
		if($this->TagStyle[$tag]['size']!=0)
			$size=$this->TagStyle[$tag]['size'];
		else
		{
			foreach($this->PileStyle as $val)
			{
				$val=trim($val);
				if($this->TagStyle[$val]['size']!=0) {
					$size=$this->TagStyle[$val]['size'];
					break;
				}
			}
		}

		// Color
		if($this->TagStyle[$tag]['color']!="")
			$color=$this->TagStyle[$tag]['color'];
		else
		{
			foreach($this->PileStyle as $val)
			{
				$val=trim($val);
				if($this->TagStyle[$val]['color']!="") {
					$color=$this->TagStyle[$val]['color'];
					break;
				}
			}
		}
		 
		// Result
		$this->TagStyle[$ind]['family']=$family;
		$this->TagStyle[$ind]['style']=$style;
		$this->TagStyle[$ind]['size']=$size;
		$this->TagStyle[$ind]['color']=$color;
		$this->TagStyle[$ind]['indent']=$this->TagStyle[$tag]['indent'];
	}


	function Parser($text)
	{
		$tab=array();
		// Closing tag
		if(preg_match("|^(</([^>]+)>)|",$text,$regs)) {
			$tab[1]="c";
			$tab[2]=trim($regs[2]);
		}
		// Opening tag
		else if(preg_match("|^(<([^>]+)>)|",$text,$regs)) {
			$regs[2]=preg_replace("/^a/","a ",$regs[2]);
			$tab[1]="o";
			$tab[2]=trim($regs[2]);

			// Presence of attributes
			if(preg_match("/(.+) (.+)='(.+)'/",$regs[2])) {
				$tab1=preg_split("/ +/",$regs[2]);
				$tab[2]=trim($tab1[0]);
				foreach($tab1 as $i=>$couple)
				{
					if($i>0) {
						$tab2=explode("=",$couple);
						$tab2[0]=trim($tab2[0]);
						$tab2[1]=trim($tab2[1]);
						$end=strlen($tab2[1])-2;
						$tab[$tab2[0]]=substr($tab2[1],1,$end);
					}
				}
			}
		}
	 	// Space
	 	else if(preg_match("/^( )/",$text,$regs)) {
			$tab[1]="s";
			$tab[2]=' ';
		}
		// Text
		else if(preg_match("/^([^< ]+)/",$text,$regs)) {
			$tab[1]="t";
			$tab[2]=trim($regs[1]);
		}

		$begin=strlen($regs[1]);
 		$end=strlen($text);
 		$text=substr($text, $begin, $end);
		$tab[0]=$text;

		return $tab;
	}


	function MakeLine()
	{
		$this->Text.=" ";
		$this->LineLength=array();
		$this->TagHref=array();
		$Length=0;
		$this->nbSpace=0;

		$i=$this->BeginLine();
		$this->TagName=array();

		if($i==0) {
			$Length=$this->StringLength[0];
			$this->TagName[0]=1;
			$this->TagHref[0]=$this->href;
		}

		while($Length<$this->wTextLine)
		{
			$tab=$this->Parser($this->Text);
			$this->Text=$tab[0];
			if($this->Text=="") {
				$this->LastLine=true;
				break;
			}

			if($tab[1]=="o") {
				array_unshift($this->PileStyle,$tab[2]);
				$this->FindStyle($this->PileStyle[0],$i+1);

				$this->DoStyle($i+1);
				$this->TagName[$i+1]=1;
				if($this->TagStyle[$tab[2]]['indent']!=-1) {
					$Length+=$this->TagStyle[$tab[2]]['indent'];
					$this->Indent=$this->TagStyle[$tab[2]]['indent'];
				}
				if($tab[2]=="a")
					$this->href=$tab['href'];
			}

			if($tab[1]=="c") {
				array_shift($this->PileStyle);
				if(isset($this->PileStyle[0]))
				{
					$this->FindStyle($this->PileStyle[0],$i+1);
					$this->DoStyle($i+1);
				}
				$this->TagName[$i+1]=1;
				if($this->TagStyle[$tab[2]]['indent']!=-1) {
					$this->LastLine=true;
					$this->Text=trim($this->Text);
					break;
				}
				if($tab[2]=="a")
					$this->href="";
			}

			if($tab[1]=="s") {
				$i++;
				$Length+=$this->Space;
				$this->Line2Print[$i]="";
				if($this->href!="")
					$this->TagHref[$i]=$this->href;
			}

			if($tab[1]=="t") {
				$i++;
				$this->StringLength[$i]=$this->GetStringWidth($tab[2]);
				$Length+=$this->StringLength[$i];
				$this->LineLength[$i]=$Length;
				$this->Line2Print[$i]=$tab[2];
				if($this->href!="")
					$this->TagHref[$i]=$this->href;
			 }

		}

		trim($this->Text);
		if($Length>$this->wTextLine || $this->LastLine==true)
			$this->EndLine();
	}


	function BeginLine()
	{
		$this->Line2Print=array();
		$this->StringLength=array();

		if(isset($this->PileStyle[0]))
		{
			$this->FindStyle($this->PileStyle[0],0);
			$this->DoStyle(0);
		}

		if(count($this->NextLineBegin)>0) {
			$this->Line2Print[0]=$this->NextLineBegin['text'];
			$this->StringLength[0]=$this->NextLineBegin['length'];
			$this->NextLineBegin=array();
			$i=0;
		}
		else {
			preg_match("/^(( *(<([^>]+)>)* *)*)(.*)/",$this->Text,$regs);
			$regs[1]=str_replace(" ", "", $regs[1]);
			$this->Text=$regs[1].$regs[5];
			$i=-1;
		}

		return $i;
	}


	function EndLine()
	{
		if(end($this->Line2Print)!="" && $this->LastLine==false) {
			$this->NextLineBegin['text']=array_pop($this->Line2Print);
			$this->NextLineBegin['length']=end($this->StringLength);
			array_pop($this->LineLength);
		}

		while(end($this->Line2Print)==="")
			array_pop($this->Line2Print);

		$this->Delta=$this->wTextLine-end($this->LineLength);

		$this->nbSpace=0;
		for($i=0; $i<count($this->Line2Print); $i++) {
			if($this->Line2Print[$i]=="")
				$this->nbSpace++;
		}
	}


	function PrintLine()
	{
		$border=0;
		if($this->border==1)
			$border="LR";
		$this->Cell($this->wLine,$this->hLine,"",$border,0,'C',$this->fill);
		$y=$this->GetY();
		$this->SetXY($this->Xini+$this->lPadding,$y);

		if($this->Indent!=-1) {
			if($this->Indent!=0)
				$this->Cell($this->Indent,$this->hLine);
			$this->Indent=-1;
		}

		$space=$this->LineAlign();
		$this->DoStyle(0);
		for($i=0; $i<count($this->Line2Print); $i++)
		{
			if(isset($this->TagName[$i]))
				$this->DoStyle($i);
			if(isset($this->TagHref[$i]))
				$href=$this->TagHref[$i];
			else
				$href='';
			if($this->Line2Print[$i]=="")
				$this->Cell($space,$this->hLine,"         ",0,0,'C',false,$href);
			else
				$this->Cell($this->StringLength[$i],$this->hLine,$this->Line2Print[$i],0,0,'C',false,$href);
		}

		$this->LineBreak();
		if($this->LastLine && $this->Text!="")
			$this->EndParagraph();
		$this->LastLine=false;
	}


	function LineAlign()
	{
		$space=$this->Space;
		if($this->align=="J") {
			if($this->nbSpace!=0)
				$space=$this->Space + ($this->Delta/$this->nbSpace);
			if($this->LastLine)
				$space=$this->Space;
		}

		if($this->align=="R")
			$this->Cell($this->Delta,$this->hLine);

		if($this->align=="C")
			$this->Cell($this->Delta/2,$this->hLine);

		return $space;
	}


	function LineBreak()
	{
		$x=$this->Xini;
		$y=$this->GetY()+$this->hLine;
		$this->SetXY($x,$y);
	}


	function EndParagraph()
	{
		$border=0;
		if($this->border==1)
			$border="LR";
		$this->Cell($this->wLine,$this->hLine/2,"",$border,0,'C',$this->fill);
		$x=$this->Xini;
		$y=$this->GetY()+$this->hLine/2;
		$this->SetXY($x,$y);
	}

}

//-------------	

// ENCABEZADO
$pdf=new PDF_WriteTag('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(30,30,30);
$pdf->SetAutoPageBreak(1,10);
$pdf->SetTitle('Solicitud de Pago de Nomina');

// Stylesheet
$pdf->SetStyle("strong","Times","B",12,"0,0,0");
$pdf->SetStyle("n","ARIAL","",12,"0,0,0");

// ----------
$pdf->AddPage();
$pdf->SetFont('Times','',9);

$id = decriptar($_GET['id']);
$consultx = "SELECT * FROM nomina_solicitudes WHERE id = $id LIMIT 1;"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
//-------------
$descripcion = $registro->descripcion;
$tipo_pago = $registro->tipo_pago;
$fecha_sol = $registro->fecha_sol;
$numero = $registro->numero;
$num_sol_pago = $registro->num_sol_pago;
$nomina = $registro->nomina;
$anno = $registro->anno;
$fecha = $registro->fecha;
$desde = $registro->desde;
$hasta = $registro->hasta;
$asignaciones = $registro->asignaciones;
$descuentos = $registro->descuentos;
$total = $registro->total;
//--------------
$jefe_direccion = jefe_direccion(10);
$jefe = 10;
if ($jefe_direccion[0] == $cedula)
	{
	$jefe_direccion = jefe_direccion(1);
	$jefe = 1;
	}
	$consultx = "SELECT * FROM a_direcciones WHERE id=0".$jefe.";";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$registro_x = $tablx->fetch_object();
	//---------------------------------
	$cargo = $registro_x->cargo;
//--------------
$consulta = "SELECT (cedula) FROM nomina WHERE id_solicitud = $id GROUP BY cedula;";
$tabla = $_SESSION['conexionsql']->query($consulta);
$trabajadores = $tabla->num_rows;
//-----------------
$adicional = "";
//-----------------
if ($tipo_pago=='001')
	{
	$quincena = 'PRIMERA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
	if ((dia($desde)=='1' or dia($desde)=='01') and dia($hasta)=='15')
		{	$quincena = 'PRIMERA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);	}
	if (dia($desde)=='16' and intval(dia($hasta))>=28)
		{	$quincena = 'SEGUNDA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);	}
    $adicional = "con aportes parafiscales";
	}
if ($tipo_pago=='001' and mes($desde)<>mes($hasta))
	{
	$quincena = $descripcion . ' DEL ' . anno($desde);
	}
if ($tipo_pago=='002')
	{
	$quincena = 'CESTATICKETS DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
	}
if ($tipo_pago=='003')
	{
	$quincena = 'BONO VACACIONAL DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
	}
if ($tipo_pago=='004')
	{
	$quincena = 'DIFERENCIA PRIMERA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
	if ((dia($desde)=='1' or dia($desde)=='01') and dia($hasta)=='15')
		{	$quincena = 'DIFERENCIA PRIMERA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);	}
	if (dia($desde)=='16' and intval(dia($hasta))>=28)
		{	$quincena = 'DIFERENCIA SEGUNDA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);	}
	}
if ($tipo_pago=='005')
	{
		$quincena = 'DIFERENCIA BONO VACACIONAL DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
	}
if ($tipo_pago=='006')
	{
		$quincena = 'BONO ESCOLAR BOMBEROS MES DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
	}
if ($tipo_pago=='007')
	{
		$quincena = "$descripcion DESDE EL ".voltea_fecha($desde)." AL ".voltea_fecha($hasta)."";
	}
if ($tipo_pago=='008')
	{
		$quincena = "$descripcion de fecha ".voltea_fecha($desde)."";
	}
if ($tipo_pago=='009' or $tipo_pago=='010' or $tipo_pago=='011' or $tipo_pago=='012' or $tipo_pago=='013')
	{
		$quincena = "$descripcion";
	}
//Desde: ".voltea_fecha($desde)." Hasta: ".voltea_fecha($hasta)."
$pdf->SetFillColor(255);
$pdf->Image('../../images/logo_nuevo.jpg',27,7,30);
//$pdf->Image('../../images/personal.png',145,14,45);

$pdf->SetY(38);
$pdf->SetFont('Arial','B',13.5);
//$pdf->SetTextColor(0,0,255);
$pdf->Cell(0,5,'Nro: 06-'.rellena_cero($num_sol_pago,5).'-'.anno($fecha_sol),0,0,'R'); 
$pdf->Ln(7);

//$pdf->SetFont('Arial','B',13.5);
$pdf->Cell(0,5,'MEMORANDO',0,0,'C'); 
$pdf->Ln(10);

$pdf->SetFont('Arial','B',11.5);
$pdf->Cell(24,5,'PARA:',0,0,'L');
$pdf->Cell(0,5,'FRANKLIN DE JESUS PALACIOS COLMENAREZ',0,0,'L');
$pdf->Ln(6);
$pdf->Cell(24,5,'',0,0,'L');
$pdf->Cell(0,5,'DIRECTOR DE ADMINISTRACION Y PRESUPUESTO.',0,0,'L');
$pdf->Ln(6);
$pdf->Cell(24,5,'DE:',0,0,'L');
//$pdf->Cell(0,5,'YECENIA EVELYN MORALES DE RATTIA',0,0,'L');
//$pdf->Ln(6);
//$pdf->Cell(24,5,'',0,0,'L');
$pdf->Cell(0,5,mayuscula($cargo),0,0,'L');
$pdf->Ln(6);
$pdf->Cell(24,5,'FECHA:',0,0,'L');
$pdf->Cell(0,5,voltea_fecha($fecha_sol),0,0,'L');
$pdf->Ln(6);
$pdf->Cell(24,5,'ASUNTO:',0,0,'L');
$pdf->Cell(0,5,'EN EL TEXTO',0,0,'L');
$pdf->Ln(15);

$y = $pdf->GetY();
$txt="<n>.     Tengo a bien dirigirme a usted, no sin antes brindarle un cordial saludo institucional, extensivo a su equipo de labores, en la oportunidad de remitirle (vía Spark), nómina $adicional y formato TXT correspondiente a la nómina: <strong>$nomina</strong> de <strong>$quincena</strong>, conformada por <strong>$trabajadores Trabajadores</strong> generando un Monto Total de Bs: ".formato_moneda($asignaciones)." menos las Retenciones de Ley por Bs: ".formato_moneda($descuentos)." quedando un Monto Neto a pagar de <strong>Bs: ".formato_moneda($total)."</strong>, todo ello para su verificación y posterior registro del fichero en la Plataforma Patria.</n>";
$pdf->WriteTag(0,6,$txt,0,"J",0,0);
$pdf->Ln(6); 
$y2 = $pdf->GetY();

$pdf->SetY($y);
$pdf->Cell(5,6,' ',0,0,'L',1);
$pdf->SetY($y2);

$pdf->MultiCell(0,6,"      Requerimiento que se hace, para su debido conocimiento y demás fines consiguientes.",0,'J');// 

$pdf->SetY(-75);
include_once "../../funciones/firma.php";

$pdf->Ln(15);

$pdf->SetFont('Times','',8.5);
$pdf->Cell(0,5,'"HACIA LA CONSOLIDACIÓN Y FORTALECIMIENTO DEL SISTEMA NACIONAL DE CONTROL FISCAL"',0,0,'C');
$pdf->Ln(3);
$pdf->Cell(0,5,'San Juan de los Morros, Calle Mariño, Edificio Don Vito Piso 1, 2 y 4 entre Av. Bolivar y Av. Monseñor Sendrea.',0,0,'C');
$pdf->Ln(3);
$pdf->Cell(0,5,'Telf: (0246) 432.14.33 email: controlguarico01@hotmail.com - web: www.cebg.com.ve',0,0,'C');
$pdf->Ln(3);
$pdf->Cell(0,5,'R.I.F. G-20001287-0',0,0,'C');

$pdf->Image('../../images/firma_rrhh.png',43,165,80);

$pdf->Output();
?>