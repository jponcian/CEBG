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
		$this->SetFont('Times','',7);
		$this->SetY(-20);
		$this->Cell(0,3,'“HACIA LA CONSOLIDACIÓN Y FORTALECIMIENTO DEL SISTEMA NACIONAL DE CONTROL FISCAL”',0,1,'C');
		$this->Cell(0,3,'San Juan de los Morros, Calle Mariño, Edificio Don Vito entre Av. Bolívar  y Av. Monseñor Sendrea.',0,1,'C');
		$this->Cell(0,3,'Telf:  (0246) 4312874 - Fax: (0246) 4314883 - email: contraloriaguarico02@gmail.com – web:  http://cebg.guarico.gob.ve',0,1,'C');
		
		if ($this->PageNo()>1) {
		$this->SetY(12);
		$this->Cell(0,3,"COTIZACIÓN ".$_SESSION['memo'],0,1,'R');	}
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

// ENCABEZADO
$id = decriptar($_GET['id']);
$aprobado = ($_GET['p']);
//-------------	
if ($aprobado==0)
	{$valor_consulta = "presupuesto.estatus=0 AND id_contribuyente = $id";}
else
	{$valor_consulta = "id_solicitud = $id";}

// ENCABEZADO
$pdf=new PDF_WriteTag('P','mm','LETTER');
$pdf->AliasNbPages('nb');
$pdf->SetMargins(26,25,26);
$pdf->SetAutoPageBreak(1,28);
$pdf->SetTitle('Solicitud de Oferta');
// Stylesheet
$pdf->SetStyle("strong","Times","B",11,"0,0,0");
$pdf->SetStyle("n","Times","",12,"0,0,0");


// ----------
$id = decriptar($_GET['id']);
$consultx = "SELECT	presupuesto.*, contribuyente.nombre, contribuyente.rif FROM contribuyente, presupuesto WHERE $valor_consulta AND presupuesto.id_contribuyente = contribuyente.id LIMIT 1;";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
//-------------
if ($registro->tipo_orden=='CD')
	{	$zz = 1 ;	}
	elseif ($registro->tipo_orden=='CP')
		{	$zz = 3 ;	}
		elseif ($registro->tipo_orden=='CC')
			{	$zz = 5 ;	}
$j = 1;

while ($j<=$zz)
{
//-------------
if ($j==1)
	{	$consultx = "SELECT	presupuesto.*, presupuesto.solicitud as nsolicitud, contribuyente.nombre, contribuyente.rif FROM contribuyente, presupuesto WHERE $valor_consulta AND presupuesto.id_contribuyente = contribuyente.id LIMIT 1;";
	}
	elseif ($j==2)
		{	$consultx = "SELECT	presupuesto.*, presupuesto.solicitud2 as nsolicitud, contribuyente.nombre, contribuyente.rif FROM contribuyente, presupuesto WHERE $valor_consulta AND presupuesto.id_contribuyente2 = contribuyente.id LIMIT 1;";
		}
	elseif ($j==3)
		{	$consultx = "SELECT	presupuesto.*, presupuesto.solicitud3 as nsolicitud, contribuyente.nombre, contribuyente.rif FROM contribuyente, presupuesto WHERE $valor_consulta AND presupuesto.id_contribuyente3 = contribuyente.id LIMIT 1;";
		}
	elseif ($j==4)
		{	$consultx = "SELECT	presupuesto.*, presupuesto.solicitud4 as nsolicitud, contribuyente.nombre, contribuyente.rif FROM contribuyente, presupuesto WHERE $valor_consulta AND presupuesto.id_contribuyente4 = contribuyente.id LIMIT 1;";
		}
	elseif ($j==5)
		{	$consultx = "SELECT	presupuesto.*, presupuesto.solicitud5 as nsolicitud, contribuyente.nombre, contribuyente.rif FROM contribuyente, presupuesto WHERE $valor_consulta AND presupuesto.id_contribuyente5 = contribuyente.id LIMIT 1;";
		}
$j++;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
//-------------
$pdf->AddPage();
$anno = $registro->anno;
$contribuyente = $registro->nombre;
$rif = $registro->rif;
$numero = rellena_cero($registro->numero,3);
$tipo = $registro->tipo_orden;
$solicitud = rellena_cero($registro->nsolicitud,4);
$fecha_presupuesto = voltea_fecha($registro->fecha_presupuesto);
$fecha_solicitud = voltea_fecha($registro->fecha_solicitud);
$memo = $registro->memo;
$_SESSION['memo']=$solicitud;
$punto_cuenta = $registro->punto_cuenta;
$concepto =  $registro->concepto;
//--------------

$consultxx = "SELECT partida FROM presupuesto WHERE $valor_consulta AND left(partida,3)='403' AND left(partida,5)<>'40318' LIMIT 1;";
$tablxx = $_SESSION['conexionsql']->query($consultxx);
if ($tablxx->num_rows>0)
	{ $dias = 'cinco (05)'; } else { $dias = 'cuatro (04)'; }

$pdf->SetFillColor(240);
$pdf->Image('../../images/logo_nuevo.jpg',30,15,26);
$pdf->SetFont('Times','',11);
// ---------------------

$pdf->SetY(40);
$pdf->SetFont('Times','B',11);
$pdf->Cell(0,6,"San Juan de los Morros, $fecha_solicitud",0,1,'R'); 
$pdf->Cell(0,6,"Nº $solicitud",0,1,'L'); 
$pdf->Cell(0,6,"SEÑORES:",0,1,'L'); 
$pdf->Cell(0,5,$contribuyente,0,1,'L'); 
$pdf->Cell(0,6,formato_rif($rif),0,1,'L'); 
$pdf->Ln(3);

$pdf->SetFont('Times','',11);
$pdf->MultiCell(0,6,"	Tengo el agrado de dirigirme a usted, de conformidad con lo establecido en el artículo 97 de la Ley de Contrataciones Públicas, a los fines de solicitarle oferta para participar en el procedimiento administrativo de contratación pública celebrado por ".tipo_compra($tipo).", signada con el Nº CEBG-$tipo-$numero-$anno la cual tiene como objeto la contratación de “ $concepto ”.",0); 		
$pdf->Ln(3);

$pdf->MultiCell(0,6,"En este sentido, cumplo con informarles los Términos Generales de la Contratación:",0); 		
$pdf->Ln(3);

$pdf->WriteTag(0,6,"<n><strong>1. Documentación Legal Requerida:</strong> Comprobante de inscripción en el Registro Único de Contrataciones Públicas, declaración jurada de no estar incurso en ninguna causal de inhabilitación y declaración jurada de compromiso de responsabilidad social en caso que aplique.</n>",0); 	
$pdf->Ln(3);

$pdf->WriteTag(0,6,"<n><strong>2. Condiciones de Entrega:</strong> la oferta deberá presentarse en la sede de este Órgano Contralor, a nombre de la Contraloría del Estado Bolivariano de Guárico, con su Nº de R.I.F. G-20001287-0, Dirección calle Mariño entre Av. Bolívar y Sendrea Edificio Don Vito, San Juan de los Morros Estado Bolivariano de Guárico, dentro del horario laboral comprendido entre las 08:00 am. A 12:00 pm. Y de 01:00 pm a 04:00 pm, Telefax 0246-4314883, E-mail: Contraloriaguarico02@gmail.com.</n>",0); 		
$pdf->Ln(3);

$pdf->WriteTag(0,6,"<n><strong>3. Lapso y Validez de la Oferta:</strong> La oferta y los documentos necesarios para participar, deben ser enviados a la dirección antes mencionada en un lapso no mayor de $dias días hábiles después de recibida la presente invitación, de conformidad con lo establecido en el artículo 67 de la Ley de Contrataciones Públicas y debe tener una validez no menor a $dias días hábiles después de recibida por el Órgano Contratante.</n>",0); 		
$pdf->Ln(3);

$pdf->WriteTag(0,6,"<n><strong>4. Lapso y Lugar para Solicitar las Aclaratorias:</strong> Los participantes podrán solicitar por escrito cualquier aclaratoria acerca de las condiciones establecidas en la presente invitación, en un lapso no mayor de un (01) día hábil después de recibida la solicitud de la oferta, en la dirección antes mencionada.</n>",0); 	
$pdf->Ln(3);

$pdf->WriteTag(0,6,"<n><strong>5. Compromiso de Responsabilidad Social:</strong> Será requerido en todas las ofertas presentadas en las modalidades de selección de Contratistas previstas en la Ley de Contrataciones Públicas, así como; en los procedimientos excluidos de la aplicación de estas, cuyo monto total, incluido los tributos, superen las Cinco Mil Unidades para el Cálculo Aritmético del Umbral (5.000 UCAU).</n>",0); 		
$pdf->Ln(3);

$pdf->WriteTag(0,6,"<n><strong>6. Especificaciones Técnicas los suministros:</strong> A continuación, se mencionan los renglones a cotizar:</n>",0); 		
$pdf->Ln(5);

$pdf->SetFont('Times','B',10);
$pdf->Cell(8,8,'');
$pdf->Cell($a=20,8,'RENGLÓN',1,0,'C',1);
$pdf->Cell($b=108,8,'DESCRIPCIÓN',1,0,'C',1);
$pdf->Cell($c=20,8,'CANTIDAD',1,0,'C',1);
$pdf->Ln();
$i=0;

$pdf->SetFont('Times','',11);
//-------------
$consultx = "SELECT	presupuesto.*, contribuyente.nombre FROM contribuyente, presupuesto WHERE left(partida,7)<>'4031801' AND $valor_consulta AND presupuesto.id_contribuyente = contribuyente.id;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	$y=$pdf->GetY();
	$pdf->Cell(8,5,'');
	$pdf->Cell($a,5,'');
	$x=$pdf->GetX();
	$pdf->SetFont('Times','',9);
	$pdf->Multicell($b,5,$registro->descripcion,1,'J',0);
	$pdf->SetFont('Times','',10);
	$y2=$pdf->GetY();
	$pdf->SetY($y);
	$pdf->Cell(8,5,'');
	$pdf->Cell($a,$y2-$y,$i,1,0,'C',0);
	$pdf->SetX($x+$b);
	$pdf->Cell($c,$y2-$y,$registro->cantidad,1,0,'C',0);
	$pdf->Ln($y2-$y);
	$total += $registro->total;
	}

$pdf->Ln(5);
$pdf->MultiCell(0,6,"6.1 El proveedor debe desglosar el precio unitario, precio total, base imponible e IVA.
6.2 Las características de los renglones solicitados debe señalarse de forma clara.
6.3 Debe señalar en la oferta el lapso de entrega.",0); 		
$pdf->Ln(3);


$pdf->WriteTag(0,6,"<n><strong>7. Criterios de Evaluación, su Ponderación y la Forma en que se Cuantificaran el Precio y los Demás Factores Definidos como Criterios.</strong></n>",0); 		
$pdf->Ln(5);

$pdf->SetFont('Times','B',11);
$pdf->Cell(0,6,"MATRIZ DE PONDERACIÓN",0,0,'C',0);
$pdf->Ln(8);

$pdf->Cell(70,7,"PARÁMETROS",1,0,'C',1);
$pdf->Cell(60,7,"CRITERIOS",1,0,'C',1);
$pdf->Cell(0,7,"PONDERACIÓN",1,0,'C',1);
$pdf->Ln();

$pdf->SetFont('Times','',10);
$pdf->Cell(70,18,"Precio",1,0,'C',0);
$pdf->Cell(60,6,"Menor Precio",1,0,'C',0);
$pdf->Cell(0,6,"70",1,1,'C',0);

$pdf->Cell(70,6,"",0,0,'C',0);
$pdf->Cell(60,6,"Precio Intermedio",1,0,'C',0);
$pdf->Cell(0,6,"60",1,1,'C',0);

$pdf->Cell(70,6,"",0,0,'C',0);
$pdf->Cell(60,6,"Precio Mayor",1,0,'C',0);
$pdf->Cell(0,6,"50",1,1,'C',0);

$y=$pdf->GetY();
$pdf->SetFont('Times','',9);
$pdf->Multicell(70,9,"Tiempo de entrega de los suministros
de la Contratación.",1,'C',0);
$pdf->SetFont('Times','',10);
$x=$pdf->GetX();
$y2=$pdf->GetY();
$pdf->SetY($y);

$pdf->SetFont('Times','',11);
$pdf->Cell(70,18,"",1,0,'C',0);
$pdf->Cell(60,6,"Menor a 5 días hábiles",1,0,'C',0);
$pdf->Cell(0,6,"15",1,1,'C',0);

$pdf->Cell(70,6,"",0,0,'C',0);
$pdf->Cell(60,6,"5 días hábiles",1,0,'C',0);
$pdf->Cell(0,6,"5",1,1,'C',0);

$pdf->Cell(70,6,"",0,0,'C',0);
$pdf->Cell(60,6,"Mayor a 5 días hábiles",1,0,'C',0);
$pdf->Cell(0,6,"0",1,1,'C',0);

$y=$pdf->GetY();
$pdf->SetFont('Times','',11);
$pdf->Multicell(70,9,"Experiencia en el Área objeto
de la contratación.",1,'C',0);
$pdf->SetFont('Times','',11);
$x=$pdf->GetX();
$y2=$pdf->GetY();
$pdf->SetY($y);

$pdf->SetFont('Times','',11);
$pdf->Cell(70,18,"",1,0,'C',0);
$pdf->Cell(60,6,"Menor a 3 años",1,0,'C',0);
$pdf->Cell(0,6,"0",1,1,'C',0);

$pdf->Cell(70,6,"",0,0,'C',0);
$pdf->Cell(60,6,"3 años",1,0,'C',0);
$pdf->Cell(0,6,"5",1,1,'C',0);

$pdf->Cell(70,6,"",0,0,'C',0);
$pdf->Cell(60,6,"Mayor a 3 años",1,0,'C',0);
$pdf->Cell(0,6,"10",1,1,'C',0);

$y=$pdf->GetY();
$pdf->SetFont('Times','',11);
$pdf->Multicell(70,6,"Ubicación en localidad donde se van a suministrar los bienes",1,'C',0);
$pdf->SetFont('Times','',10);
$x=$pdf->GetX();
$y2=$pdf->GetY();
$pdf->SetY($y);

$pdf->SetFont('Times','',11);
$pdf->Cell(70,12,"",1,0,'C',0);
$pdf->Cell(60,6,"Igual localidad",1,0,'C',0);
$pdf->Cell(0,6,"5",1,1,'C',0);

$pdf->Cell(70,6,"",0,0,'C',0);
$pdf->Cell(60,6,"Zona adyacente",1,0,'C',0);
$pdf->Cell(0,6,"0",1,1,'C',0);

$pdf->SetFont('Times','B',11);
$pdf->Cell(130,6,"TOTAL",1,0,'C',0);
$pdf->Cell(0,6,"100",1,1,'C',0);

$pdf->Ln(5);

$pdf->SetFont('Times','',11);
$pdf->MultiCell(0,6,"Los criterios cuantificados para la toma de decisiones, se basaran en lo indicado en la matriz de ponderación:",0); 		
$pdf->Ln(3);

$pdf->MultiCell(0,6,"La Contraloría del Estado Bolivariano de Guárico se reserva el derecho a declarar desierto el procedimiento de contratación pública, diferir, modificar, extender el lapso para la presentación de los recaudos, rechazar las ofertas que no se ajusten a los requisitos, características y condiciones exigidas, otorgar la adjudicación de forma parcial o total, modificar las cantidades requeridas, no aceptar ofertas de proveedores que en concursos anteriores a estos, hayan incumplido con el contrato para el cual la Institución les haya otorgado la adjudicación, sin que por ello la Contraloría del Estado Bolivariano de Guárico esté obligada a responder en lo mercantil, civil o penalmente ante las empresas participantes o ante terceros.",0); 		
$pdf->Ln(3);
$pdf->Cell(0,6,"Agradeciendo su colaboración y en espera de su pronta respuesta.",0,0,'L',0);

$firma = firma(12);
	
$pdf->SetFont('Times','B',11);
$pdf->SetY(-60);
$pdf->Cell(0,6,"Atentamente,",0,0,'C',0);
$pdf->Ln(20);
$pdf->Cell(0,5,mayuscula($firma[1]),0,0,'C',0);
$pdf->Ln(5);
$pdf->Cell(0,5,mayuscula($firma[2]),0,0,'C',0);
}

$pdf->Output();
?>