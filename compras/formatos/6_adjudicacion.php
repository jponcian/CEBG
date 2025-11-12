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
		
		$this->SetFont('Times','',8);
		if ($this->PageNo()>1) {
		$this->SetY(12);
		$this->Cell(0,3,"Adjudicación de Fecha: 22/02/2021",0,1,'R');	}
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
//-------------	

// ENCABEZADO
$pdf=new PDF_WriteTag('P','mm','LETTER');
$pdf->AliasNbPages('nb');
$pdf->SetMargins(28,25,28);
$pdf->SetAutoPageBreak(1,30);
$pdf->SetTitle('Adjudicacion');
// Stylesheet
$pdf->SetStyle("strong","Times","B",10.5,"0,0,0");
$pdf->SetStyle("n","Times","",11.5,"0,0,0");

// ----------
$pdf->AddPage();

$consultx = "SELECT	presupuesto.*, contribuyente.nombre, contribuyente.rif FROM contribuyente, presupuesto WHERE $valor_consulta AND presupuesto.id_contribuyente = contribuyente.id LIMIT 1;";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
//-------------
$anno = $registro->anno;
$contribuyente = $registro->nombre;
$rif = $registro->rif;
$numero = rellena_cero($registro->numero,3);
$tipo = $registro->tipo_orden;
$fecha_presupuesto = voltea_fecha($registro->fecha_presupuesto);
$fecha_solicitud = voltea_fecha($registro->fecha_solicitud);
$fecha_recibido = voltea_fecha($registro->fecha_recibido);
$fecha_adjudicacion = voltea_fecha($registro->fecha_adjudicacion);
$memo = $registro->memo;
$punto_cuenta = $registro->punto_cuenta;
$concepto =  $registro->concepto;
$compra =  "CEBG-".$registro->tipo_orden.'-'.rellena_cero($registro->numero,3).'-'.$registro->anno;
//--------------
if ($registro->id_contribuyente2>0)
	{
	$contribuyente2 = contribuyente($registro->id_contribuyente2);
	$contribuyente3 = contribuyente($registro->id_contribuyente3);
	$empresas = $contribuyente .', '. $contribuyente2[1] .', ' . $contribuyente3[1];
	}
	
//--------------
$pdf->SetFillColor(240);
$pdf->Image('../../images/logo_nuevo.jpg',30,15,26);
$pdf->SetFont('Times','',11);
// ---------------------

$pdf->SetY(47);
$pdf->SetFont('Times','B',12);
$pdf->MultiCell(0,6,"ADJUDICACIÓN DEL PROCEDIMIENTO DE ".mayuscula(tipo_compra($tipo))." N° ".$compra." PARA LA CONTRATACIÓN DEL “".$concepto."”.",0); 		
$pdf->Ln(5);

$pdf->WriteTag(0,6,"<n> Quien suscribe, JULIO CÉSAR PÁEZ UZCATEGUI, venezolano, mayor de edad, titular de la C.I. Nº V-16.179.059, de este domicilio, en mi carácter de Contralor del Estado Bolivariano de  Guárico, según consta en Resolución Nº 01-00-000678 de fecha 05 de Diciembre de 2.018 (G.O.R.B.V. N° 41.541, de fecha 07-12-2018), con fundamento a lo establecido en el artículo 109 de la LEY DE CONTRATACIONES PÚBLICAS, publicada en Gaceta Oficial de la República Bolivariana de Venezuela Nº 6.154 Extraordinario del 19 de noviembre de 2014; y visto el Informe de Recomendación sobre el procedimiento de <strong>“".oraciones(tipo_compra($tipo))." Nº ".$compra."”</strong>, presentado por la Unidad Contratante Dirección de Administración y Presupuesto de este Órgano Contralor, con relación a la oferta válida presentada para la contratación del “".$concepto."”. considera lo siguiente: <strong>".oraciones(tipo_compra($tipo))." N° ".$compra."</strong>, se adelantó de conformidad con las instrucciones contenidas en el Punto de Cuenta Nº $punto_cuenta de fecha $fecha_presupuesto, aprobada en mi carácter de Contralor del Estado Bolivariano de Guárico, y de conformidad a las solicitudes de ofertas que realizara la Dirección de Administración y Presupuesto de la Contraloría del Estado Bolivariano de Guárico en su condición de Unidad Contratante, en fecha $fecha_solicitud a las empresas <strong>$empresas</strong>, y  de las cuales solo una (01) empresa presentó su oferta el día $fecha_recibido. En consecuencia, analizada la oferta presentada y tomando en consideración el Informe de Recomendación sobre el procedimiento de <strong>“".oraciones(tipo_compra($tipo))." Nº ".$compra."”</strong>, presentado por la Unidad Contratante Dirección de Administración y Presupuesto se tiene que: <strong>$contribuyente</strong> resultó favorecida por cumplir con las características exigida en la solicitud de oferta por el Órgano de Control Fiscal Externo, para la contratación del “".$concepto."”. En vista de lo antes expuesto se ordena la Adjudicación a la empresa <strong>$contribuyente</strong> para la contratación del “".$concepto."””.</n>",0); 	
$pdf->Ln(3);

$pdf->MultiCell(0,6,"En San Juan de los Morros a los ".fecha_larga2(voltea_fecha($fecha_adjudicacion)).".",0); 		
$pdf->Ln(3);

$pdf->SetFont('Times','B',11);
$pdf->SetY(-60);
$pdf->Cell(0,6,"Atentamente,",0,0,'C',0);
$pdf->Ln(20);
$pdf->Cell(0,5,"JULIO CÉSAR PÁEZ UZCATEGUI",0,0,'C',0);
$pdf->Ln(5);
$pdf->Cell(0,5,"CONTRALOR DEL ESTADO BOLIVARIANO DE GUARICO",0,0,'C',0);

$pdf->Output();
?>