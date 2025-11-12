<?php
session_start();
ob_end_clean();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

class PDF_WriteTag extends FPDF
{
	function Header()
	{    
		$this->Image('../../images/logo_nuevo.jpg',22,17,35);
		
		$this->SetY(20);
		$this->SetFont('Times','I',12);
		//$this->Cell(15,5,'');
		$this->Cell(0,5,'REPÚBLICA BOLIVARIANA DE VENEZUELA',0,0,'C'); 
		$this->Ln(6);
		//$this->Cell(15,5,'');
		$this->Cell(0,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); 
		$this->Ln(6);
		//$this->Cell(15,5,'');
		$this->Cell(0,5,'ESTADO GUÁRICO',0,0,'C'); 
		$this->Ln(6);
		//$this->Cell(0,5,'Ejercicio Fiscal '.$anno,0,0,'C'); $this->Ln(6);
		
		$this->SetFont('Times','BU',12);
		//$this->Cell(15,5,'');
		$this->Cell(0,5,'DESPACHO DEL ALCALDE',0,0,'C'); 
		$this->Ln(18);
	}	
	
	function Footer()
	{    
		//--------------
		$this->SetFont('Times','I',8);
		$this->SetY(-13);
		$this->SetTextColor(120);
		//--------------
		$usuario = persona($_SESSION['usuario']);
		//$jefe = persona(jefe('PRESUPUESTO'));
		//if ($usuario==$jefe)
		//	{	$iniciales = extraer_inciales($jefe);	}
		//	else
		//		{	$iniciales = extraer_inciales($jefe) . '/' . minuscula(extraer_inciales($usuario));	}
		//$this->Cell(80,0,extraer_inciales(persona(alcalde())) . '/' . $iniciales,0,0,'L');
		$this->Cell(0,0,'SIACEBG'.' '.$this->PageNo().' de paginas',0,0,'R');
		
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

$id = decriptar($_GET['id']);
$consultx = "SELECT credito_adicional.id, credito_adicional.tipo_orden, LOWER(credito_adicional.descripcion) as descripcion, credito_adicional.numero, credito_adicional.fecha, credito_adicional.total, credito_adicional.estatus, credito_adicional.anno, credito_adicional.usuario FROM credito_adicional WHERE credito_adicional.id = $id LIMIT 1;"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
//-------------
//$tipo_solicitud = $registro->tipo_solicitud;
$fecha = $registro->fecha;
$anno = $registro->anno;
$numero = $registro->numero;
$concepto = trim($registro->descripcion);
$_SESSION['total']= $registro->total;
$_SESSION['usuario']= $registro->usuario;
//--------------

$pdf=new PDF_WriteTag('P','mm','LETTER');
$pdf->AliasNbPages('paginas');
$pdf->SetMargins(20,15,20);
$pdf->SetFont('Times','',11);
$pdf->AddPage();
$linea = 7;

// Stylesheet
$pdf->SetStyle("strong","Times","B",12,"0,0,0");
$pdf->SetStyle("n","Times","",12,"0,0,0");

// Title
$pdf->SetFont('Times','B',13);
$txt="DECRETO Nº AMM- OPP- ".rellena_cero($numero,3)."/".$anno;
$pdf->SetTitle($txt);
$pdf->Cell(0,0,$txt);
$pdf->Ln(7);

//$alcalde = persona(alcalde());
// Text
$txt="<n><strong>$alcalde</strong>, Alcalde del Municipio Francisco de Miranda del Dirección de Administración y Presupuesto, en uso de las atribuciones legales que me confieren los  Artículos Nº.- 247, Numeral 4°, de la Ley Orgánica del Poder Público Municipal y en concordancia con el Articulo Nº.- 14, Literal A, de las Disposiciones Generales de la Ordenanza de Presupuesto de Ingresos y Gastos Públicos Municipal vigente para el ejercicio Fiscal ".$anno.". Se procede a dictar un crédito adicional por la cantidad de: <strong>".strtoupper(valorEnLetras($_SESSION['total']))." (Bs. ".formato_moneda($_SESSION['total']).")</strong>, ".ucwords(($concepto)).".</n>";
//$pdf->WriteTag(0,$linea,$txt,0,"J",0,0);
$pdf->Ln(7);
//$pdf->SetLineWidth(0.1);
//$pdf->SetFillColor(255,255,204);
//$pdf->SetDrawColor(102,0,102);

$pdf->SetFont('Times','B',12);
$txt="CONSIDERANDO";
$pdf->Cell(0,0,$txt,0,0,'C');
$pdf->Ln(7);

$pdf->SetFont('Times','',12);
$txt="Que el ciudadano Alcalde representa el Poder Ejecutivo Municipal, y tiene como funciones dirigir el gobierno local, representar al Municipio ante los organismos públicos, privados y ante las fuerzas vivas de la población.";
$pdf->MultiCell(0,$linea,$txt,0); 
$pdf->Ln(3);

$pdf->SetFont('Times','B',12);
$txt="CONSIDERANDO";
$pdf->Cell(0,0,$txt,0,0,'C');
$pdf->Ln(7);

$pdf->SetFont('Times','',12);
$txt="Que es competencia del Alcalde mantener los créditos Presupuestarios con disponibilidad a los efectos de cubrir los gastos ordinarios durante el ejercicio económico.";
$pdf->MultiCell(0,$linea,$txt,0); 
$pdf->Ln(7);

$pdf->SetFont('Times','B',12);
$txt="DECRETO";
$pdf->Cell(0,0,$txt,0,0,'C');
$pdf->Ln(7);

$txt="<n><strong>ARTÍCULO 1°.</strong> Se procede a dictar un Crédito Adicional por la cantidad de: <strong>".strtoupper(valorEnLetras($_SESSION['total']))." (Bs. ".formato_moneda($_SESSION['total']).")</strong>, ".ucwords(($concepto)).".</n>";
$pdf->WriteTag(0,$linea,$txt,0,"J",0,0);
$pdf->Ln(7);

if ($pdf->GetY()>234)	{$pdf->AddPage();}

$pdf->SetFont('Times','B',12);
$txt="Presupuesto de Ingresos:";//.$pdf->GetY()
$pdf->Cell(0,0,$txt,0,0,'L');
$pdf->Ln(4);
$pdf->SetFillColor(255);
$pdf->SetFont('Times','',12);
$txt="3.02.99.01.00  Otros Ingresos Extraordinarios ………………………………………………………";
$pdf->Cell(150,7,$txt,0,0,'L');
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,7,formato_moneda($_SESSION['total']),0,0,'R',1);
$pdf->Ln(7);

$pdf->SetFont('Times','',12);
$txt="      Los recursos señalados serán destinados a financiar las siguientes partidas en el Presupuesto de Gastos:";
$pdf->MultiCell(0,$linea,$txt,0); 
$pdf->Ln(7);

$pdf->SetFont('Times','BU',12);
$txt="PARTIDAS A INCREMENTAR:";
$pdf->Cell(0,0,$txt,0,0,'L');
$pdf->Ln(8);

$consultx = "SELECT a_categoria.codigo,	a_categoria.descripcion, credito_adicional_detalle.total FROM credito_adicional_detalle, a_categoria WHERE credito_adicional_detalle.categoria = a_categoria.codigo AND credito_adicional_detalle.id_credito = $id GROUP BY a_categoria.codigo;"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
{
	$pdf->SetFillColor(240);
	$categoria = trim($registro->codigo);
	$descripcion = trim($registro->descripcion);
	//------------
	$pdf->SetFont('Times','B',12);
	$txt=$descripcion.": ".formato_categoria($categoria);
	$pdf->Cell(0,6,$txt,1,0,'L',1);
	$pdf->Ln(6);
	$pdf->SetFont('Times','',12);
	//----------
	$consultx = "SELECT a_partidas.codigo, (a_partidas.descripcion) as descripcion, credito_adicional_detalle.total FROM credito_adicional_detalle,	a_partidas WHERE credito_adicional_detalle.categoria = '$categoria' AND credito_adicional_detalle.partida = a_partidas.codigo AND credito_adicional_detalle.id_credito = $id;"; //echo $consultx;
	$tablx1 = $_SESSION['conexionsql']->query($consultx);
	while ($registro1 = $tablx1->fetch_object())
	{
		if ($pdf->GetY()>=245) {$pdf->AddPage();}
		$pdf->SetFillColor(255);
		$codigo = trim($registro1->codigo);
		$descripcion = trim($registro1->descripcion);
		$total = formato_moneda($registro1->total);
		//------------
		$pdf->SetX(52);
		$y1=$pdf->GetY();
		$pdf->SetFont('Times','',11);
		$pdf->MultiCell(110,5.5,$descripcion,1,'L');
		$pdf->SetFont('Times','',12);
		$y2=$pdf->GetY();
		$pdf->SetY($y1);
		$pdf->Cell(32,$y2-$y1,formato_partida($codigo),1,0,'C');
		$pdf->Cell(110,$y2-$y1,'');
		$pdf->Cell(0,$y2-$y1,$total,1,0,'R',1);
		$pdf->Ln($y2-$y1);
	}
}
$pdf->SetFont('Times','B',12);
$pdf->Cell(142,7,'TOTAL A INCREMENTAR………………………………………………………',1,0,'L');
$pdf->Cell(0,7,formato_moneda($_SESSION['total']),1,0,'R',1);
$pdf->Ln(12);
//--------------

if ($pdf->GetY()>175)	{$pdf->AddPage(); $y=0;} else	{ $y=1;}

$txt="<n><strong>ARTÍCULO 3°.</strong> La Oficina de Planificación y Presupuesto cuidará de la Ejecución del presente Decreto.</n>".$pdf->GetY();
$pdf->WriteTag(0,$linea,$txt,0,"J",0,0);
$pdf->Ln(7);

$pdf->SetFont('Times','',12);
$txt="      Dada, firmada y sellada, en el Despacho del  Alcalde del Municipio Francisco de Miranda del Dirección de Administración y Presupuesto, en la Ciudad de Calabozo, a los ".dia($fecha)." días del Mes de ".$_SESSION['meses_anno'][abs(mes($fecha))]." del año ".$_SESSION['letras_anno'][(abs(anno($fecha))-2000)]." ($anno), Años 207º de la Independencia, 158 de la Federación y 19º de la Revolución Bolivariana.";
$pdf->MultiCell(0,$linea,$txt,0); 
//$pdf->Ln(10);

if ($y==1) {$pdf->SetY(-60);} else	{ $pdf->Ln(10); }

$pdf->SetFont('Times','B',12);
$txt="ATENTAMENTE;";
$pdf->Cell(0,0,$txt,0,0,'C');
$pdf->Ln(20);

$pdf->SetFont('Times','B',12);
$txt="$alcalde";
$pdf->Cell(0,0,$txt,0,1,'C');
$pdf->Ln(6);
$txt="ALCALDE DEL MUNICIPIO FRANCISCO DE MIRANDA";
$pdf->Cell(0,0,$txt,0,0,'C');
//$pdf->Ln(5);

$pdf->AddPage();

$pdf->SetFont('Times','B',12);
$txt="CALABOZO, ".dia($fecha)." DE ".strtoupper($_SESSION['meses_anno'][abs(mes($fecha))])." DEL $anno";
$pdf->Cell(0,0,$txt,0,1,'R');
$pdf->Ln(15);

$pdf->SetFont('Times','B',12);
$txt="CIUDADANO:";
$pdf->Cell(0,0,$txt,0,1,'L');
$pdf->Ln(6);

$pdf->SetFont('Times','B',12);
$txt="PRESIDENTE Y DEMÁS MIEMBROS DE LA ILUSTRE CÁMARA MUNICIPAL";
$pdf->Cell(0,0,$txt,0,1,'L');
$pdf->Ln(6);

$pdf->SetFont('Times','B',12);
$txt="SU DESPACHO ";
$pdf->Cell(0,0,$txt,0,1,'L');
$pdf->Ln(15);

$pdf->SetFont('Times','B',12);
$txt="DECRETO Nº AMM- OPP- ".rellena_cero($numero,3)."/".$anno;
$pdf->Cell(0,0,$txt,0,1,'R');
$pdf->Ln(18);

$txt="<n>Ante todo reciba un cordial saludo Revolucionario Y Socialista, la presente es para solicitar la aprobación y publicación de crédito adicional, por un monto de: <strong>".strtoupper(valorEnLetras($_SESSION['total']))." (Bs. ".formato_moneda($_SESSION['total']).")</strong>, ".(ucwords($concepto)).".</n>";
$pdf->WriteTag(0,$linea,$txt,0,"J",0,0);
$pdf->Ln(7);

$pdf->SetFont('Times','',12);
$txt="Sin otro particular a que hacer referencia, me suscribo de usted";
$pdf->Cell(0,0,$txt,0,1,'L');
//$pdf->Ln(8);

$pdf->SetY(-70);

$pdf->SetFont('Times','B',12);
$txt="ATENTAMENTE;";
$pdf->Cell(0,0,$txt,0,0,'C');
$pdf->Ln(20);

$pdf->SetFont('Times','B',12);
$txt="$alcalde";
$pdf->Cell(0,0,$txt,0,1,'C');
$pdf->Ln(6);
$txt="ALCALDE DEL MUNICIPIO FRANCISCO DE MIRANDA";
$pdf->Cell(0,0,$txt,0,0,'C');

$pdf->Output();
?>
