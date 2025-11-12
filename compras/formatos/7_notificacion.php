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
//-------------	

// ENCABEZADO
$pdf=new PDF_WriteTag('P','mm','LETTER');
$pdf->AliasNbPages('nb');
$pdf->SetMargins(26,25,26);
$pdf->SetAutoPageBreak(1,10);
$pdf->SetTitle('Notificacion');
// Stylesheet
$pdf->SetStyle("strong","Times","B",10,"0,0,0");
$pdf->SetStyle("n","Times","",11,"0,0,0");

// ----------
$pdf->AddPage();

$id = decriptar($_GET['id']);
$aprobado = ($_GET['p']);
//-------------	
if ($aprobado==0)
	{$valor_consulta = "presupuesto.estatus=0 AND id_contribuyente = $id";}
else
	{$valor_consulta = "id_solicitud = $id";}
//-------------	

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
$fecha_notificacion = voltea_fecha($registro->fecha_notificacion);
$fecha_recibido = voltea_fecha($registro->fecha_recibido);
$fecha_ofertas = voltea_fecha($registro->fecha_ofertas);
$memo = $registro->memo;
$punto_cuenta = $registro->punto_cuenta;
$concepto =  $registro->concepto;
$solicitud = rellena_cero($registro->solicitud,4);
$compra =  "CEBG-".$registro->tipo_orden.'-'.$numero.'-'.$registro->anno;
//--------------

$pdf->SetFillColor(240);
$pdf->Image('../../images/logo_nuevo.jpg',30,15,26);
$pdf->SetFont('Times','',11);
// ---------------------

$pdf->SetY(44);
$pdf->SetFont('Times','B',11);
$pdf->Cell(0,6,"SEÑORES:",0,1,'L'); 
$pdf->Cell(0,5,$contribuyente,0,1,'L'); 
$pdf->Cell(50,6,formato_rif($rif),0,0,'L'); 
$pdf->Cell(0,6,"Fecha, $fecha_notificacion",0,1,'R'); 
$pdf->Ln(3);

$pdf->WriteTag(0,6,"<n>Me dirijo a ustedes, en atención a lo establecido en el artículo 126 del Reglamento de la Ley de Contrataciones Públicas, publicada en la Gaceta Oficial de la República Bolivariana de Venezuela Nº 6.154 Extraordinario del 19 de noviembre de 2014; y una vez evaluada  la oferta válida presentada en su oportunidad para la(el) ".oraciones(tipo_compra($tipo))." <strong>N° $compra</strong>, concerniente a la contratación del “".$concepto."”,  a la cual fuera invitado a través del oficio Nº $solicitud de fecha $fecha_solicitud, enviado por la Dirección de Administración de este Órgano de Control, recibido por esa empresa el día $fecha_recibido y en fecha $fecha_ofertas se recibió su oferta en dicha Unidad Contratante; en consecuencia se le notifica que la misma resultó favorecida, como la única opción para la contratación del “".$concepto."”, todo ello tomando en consideración los criterios de evaluación establecidos en el oficio de solicitud de oferta. En virtud de lo antes dicho, se adjudica para la contratación antes mencionada a la empresa <strong>$contribuyente</strong> A los fines legales consiguientes, se le estima firmar el duplicado de la presente notificación, en el recuadro ubicado en la parte final de la misma, indicando nombre completo, cédula de identidad, fecha hora y lugar donde se encuentra al momento de suscribirla como constancia de su recepción.</n>",0); 		
$pdf->Ln(5);

$pdf->SetFont('Times','B',11);
//$pdf->SetY(-60);
$pdf->Cell(0,6,"Atentamente,",0,0,'C',0);
$pdf->Ln(20);
$pdf->Cell(0,5,"JULIO CÉSAR PÁEZ UZCATEGUI",0,0,'C',0);
$pdf->Ln(5);
$pdf->Cell(0,5,"CONTRALOR DEL ESTADO BOLIVARIANO DE GUARICO",0,0,'C',0);

$pdf->Ln(10);
$pdf->Cell(0,5,"Nombre y Apellido: ________________________________",0,0,'R',0);
$pdf->Ln(5);
$pdf->Cell(0,5,"C.I. ________________________________",0,0,'R',0);
$pdf->Ln(5);
$pdf->Cell(0,5,"Lugar: ________________________________",0,0,'R',0);
$pdf->Ln(5);
$pdf->Cell(0,5,"Hora: ________________________________",0,0,'R',0);
$pdf->Ln(5);
$pdf->Cell(0,5,"Fecha: ________________________________ ",0,0,'R',0);
$pdf->Ln(5);
$pdf->Cell(0,5,"Firma: ________________________________",0,0,'R',0);

$pdf->Output();
?>