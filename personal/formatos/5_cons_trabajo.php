<?php
session_start();
ob_end_clean();
session_start();
include_once "../../conexion.php";
include_once "../../funciones/auxiliar_php.php";
require_once ('../../lib/fpdf/fpdf.php');
setlocale(LC_TIME, 'sp_ES','sp', 'es');
$_SESSION['conexionsql']->query("SET NAMES 'latin1'");

//if ($_SESSION['VERIFICADO'] != "SI") { 
//    header ("Location: ../index.php?errorusuario=val"); 
//    exit(); 
//	}

if ($_GET['id']<>'0' and $_GET['id']<>'')
	{	$_SESSION['id_ct'] = decriptar($_GET['id']);	}
else
	{	$_SESSION['id_ct'] = $_SESSION['CEDULA_USUARIO'];	}

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

// ENCABEZADO
$pdf=new PDF_WriteTag('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->SetMargins(20,20,20);
$pdf->SetAutoPageBreak(1,10);
$pdf->SetTitle('Constancia de Trabajo');
$linea = 7;

// Stylesheet
$pdf->SetStyle("strong","Times","B",12,"0,0,0");
$pdf->SetStyle("n","Times","",12,"0,0,0");

////////// DATOS
$consulta = "SELECT * FROM rac WHERE cedula = ".$_SESSION['id_ct']." LIMIT 1;"; 
$tabla = $_SESSION['conexionsql']->query($consulta);
$registro = $tabla->fetch_object();

// --------------
$digito = $registro->digito;
$ci = $registro->ci;
$cedula = $registro->cedula;
$empleado = $registro->nombre." ".$registro->nombre2." ".$registro->apellido." ".$registro->apellido2;
$profesion = $registro->profesion;
$annos = annos(anno($registro->fecha_ingreso),mes($registro->fecha_ingreso),date('Y'),date('m'));
$antiguedad = intval($annos) + intval($registro->anos_servicio);
$cargo = $registro->cargo;
$ubicacion = $registro->ubicacion;
$profesion = $registro->profesion;
$fecha = ($registro->fecha_ingreso);
$sueldo = $registro->sueldo;
$categoria2 = $registro->categoria2;
$partida2 = $registro->categoria2;
if ($categoria <> '' or $partida2 <> '' and $registro->sueldo2>0)	
	{
	$sueldo2 = $registro->sueldo2;
	$sueldo = $sueldo2;//$sueldo + 
	}

$sus_lph = $registro->sus_lph;
$nomina = $registro->nomina;
$categoria = $registro->categoria;

$code = generarRuta('01','cebg', $_SESSION['id_ct']);

$jefe_direccion = jefe_direccion(10);
$jefe = 10;
if ($jefe_direccion[0] == $cedula)
	{
	$jefe_direccion = jefe_direccion(1);
	$jefe = 1;
	}
	
// ----------
$pdf->AddPage();
$pdf->SetFillColor(190);
//$pdf->Image('../../images/personal.png',145,19,50);
$pdf->Image('../../images/logo_nuevo.jpg',27,14,30);
//$pdf->Image('../../images/logo_claro.png',53,60,110);
//$pdf->Image('../../images/todos.jpg',20,255,12);
$pdf->Image('../../images/bandera_linea.png',0,0,216,1);
$pdf->Image('../../images/bandera_linea.png',0,278,216,1);
if ($_SERVER['HTTP_HOST']=='localhost')
	{$pdf->Image("http://localhost/samatfram/scripts/qr_generador.php?code=".$code,180,245,25,25,"png");}
else	{$pdf->Image("http://app.cebg.com.ve/scripts/qr_generador.php?code=".$code,180,245,25,25,"png");}

//$instituto = instituto();
$pdf->SetFont('Times','I',11);
$pdf->SetX(51);
$pdf->Cell(98,5,'República Bolivariana de Venezuela',0,0,'C'); 
$pdf->Ln(5);
$pdf->SetX(51);
$pdf->Cell(98,5,'Contraloria del Estado Bolivariano de Guárico',0,0,'C'); 
$pdf->Ln(5);
$pdf->SetX(51);
$pdf->Cell(98,5,'Dirección de Talento Humano',0,0,'C'); 
$pdf->Ln(20);

//$pdf->Ln(20);

$pdf->SetFont('Times','BIU',14);
$pdf->Cell(0,5,'CONSTANCIA',0,0,'C'); //DE TRABAJO
$pdf->Ln(20);

//--------
//if ($profesion>1)
//	{	$profesion1=$sueldo*$_SESSION['prima_prof'][intval($profesion)]/100;
//	 	$profesion=', mas Bs. '.formato_moneda($sueldo*$_SESSION['prima_prof'][intval($profesion)]/100).' de Prima por Profesionalización';
//		
//		} else	{ $profesion=''; $profesion1=0; }
////--------
//if ($antiguedad>0)
//	{	$antiguedad1=(($_SESSION['prima_anno'][intval($antiguedad)]*$sueldo)/100);	
//	 	$antiguedad=', mas Bs. '.formato_moneda((($_SESSION['prima_anno'][intval($antiguedad)]*$sueldo)/100)).' de Prima por Antiguedad';	
//		} else	{	$antiguedad='';	$antiguedad1=0;	}
//--------
//if ($categoria == '0201000053' or $categoria == '0201000054')	
//	{ 	$bono = ", adicionalmente un Bono Especial de Estabilizacion Economica de ".formato_moneda(($sueldo+valortickets()+$profesion1+$antiguedad1)*80/100);
//		$bono1 = ($sueldo+valortickets()+$profesion1+$antiguedad1)*80/100;	}
//	elseif ($nomina=='005 JUBILADOS' or $nomina=='006 PENSIONADO') 
//		{ 	$bono = '';	$bono1 = 0;	}
//			else
//				{ 	$bono = ", adicionalmente un Bono Especial de Estabilizacion Economica de Bs. ".formato_moneda(($sueldo+valortickets()+$profesion1+$antiguedad1)*80/100)." ";	
//				 	$bono1 = ($sueldo+valortickets()+$profesion1+$antiguedad1)*80/100;	}
//--------
//if ($nomina<>'005 JUBILADOS' and $nomina<>'006 PENSIONADO' and $nomina<>'001 ELECCION POPULAR')
//	{	$cestaticket=', mas Bs. '.formato_moneda(valortickets()).' de beneficio de la Ley de Alimentación';
//		$cestaticket1=valortickets();	}
//		else	{	$cestaticket='';	$cestaticket1=0;	}
////--------
//if ($sus_lph>0 and $sus_lph<>'' and $sus_lph<>'0')
//	{	$lph=' y es Cotizante del 1% de la Ley de Politica Habitacional';	}
////--------
//if ($cestaticket<>'' or $bono<>'' or $profesion<>'' or $antiguedad<>'')
//	{ $total=', promediando un Sueldo Integral de ** <strong>Bs. '.formato_moneda($sueldo+$bono1+$cestaticket1+$profesion1+$antiguedad1).'</strong>**';	}
//		else	{	$total='';	}		
	
$pdf->SetFont('Times','B',12);
$txt="<n>Quien suscribe <strong>".$jefe_direccion[1]."</strong>, titular de la Cédula de Identidad N° <strong>V-".$jefe_direccion[0]."</strong>, en mi carácter de ".$jefe_direccion[2]." de la Contraloria del Estado Bolivariano de Guárico, según <strong>".$jefe_direccion[3]."</strong> de fecha <strong>".voltea_fecha($jefe_direccion[4])."</strong>, por medio de la presente hace constar que el Ciudadano(a): <strong>$empleado</strong>, Titular de la Cédula de Identidad: <strong>".formato_ci($ci)."</strong>, presta sus servicios en este Órgano de Control Fiscal desde el ".voltea_fecha($fecha).", ejerciendo el cargo de: <strong>$cargo</strong>, perteneciente a la nómina de <strong>$nomina</strong>, devengando un sueldo mensual de *** <strong>Bs. ".formato_moneda($sueldo)."</strong> ***.</n>";//$bono$profesion$antiguedad$cestaticket$lph
$pdf->WriteTag(0,8,$txt,0,"J",0,0);
$pdf->Ln(4); 
//publicada en Gaceta Oficial Extraordinaria del Estado Bolivariano de Guárico <strong>N° 107</strong> de fecha <strong>03/08/2020</strong>, 
$pdf->MultiCell(0,8,"      Constancia que se expide a petición de la parte interesada, en San Juan de los Morros, a los ".(fecha_larga2(date('Y-m-d'))).".");
//$pdf->Ln(7); 

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

if ($jefe<>1)
	{	$pdf->Image('../../images/firma_rrhh.png',43,165,80);	}
// FIN
	
$pdf->Output();
?>