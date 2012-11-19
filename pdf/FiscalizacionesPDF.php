<?php
session_start();   
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
//session_cache_limiter('private, must-revalidate');
//array_key_exists("consultar",$_POST)
$consultar=true;
$buscar=1;
if(isset($consultar)){
	//define('FPDF_FONTPATH','font/');
	require_once('fpdf.php');
	$obj_ficalizacion = new Fiscalizacion();
	if(array_key_exists("fec_ini",$_POST) && array_key_exists("fec_fin",$_POST)){
	//buscar por fecing y fecfin
	$inicio=trim($_POST["fec_ini"]);
	$fin=trim($_POST["fec_fin"]);
	$sql="SELECT * FROM Fiscalizacion WHERE fec_solicitud>='".$inicio."' AND fec_solicitud<='".$fin."'";
	$obj_ficalizacion->QueryFree($sql);
	if($obj_ficalizacion->result_Set){
		if($obj_ficalizacion->n_register_query>0){
		//|| D A T O S   D E   S O L I C I T U D        || D A T O S D E L A E M P R E S A ||
		//||Num. Solicitud|| Apellido y Nombres|| Fecha ||Nombre/Razon Social  || Nº Cuenta||
		}else{
		
		}
	}
	}elseif(isset($buscar)){
	//buscar por numero de solicitud
	$solicitud=trim($buscar);
	$obj_ficalizacion->Set_IdFisca($solicitud);
	$registro=$obj_ficalizacion->ExistFiscalizacion();
	}else{
	print("<script language='javascript'>");
	print("close();");
	print("</script>");
	}
}
//if(isset($_POST['buscar']) && !empty($_POST['buscar'])){
if($buscar){
if($registro){
$obj_usuario = new Usuario();
$obj_usuario->SetIdEmpleado($registro[11]);
$obj_usuario->ConsultarId();
$obj_subActividad=new SubActividad();
$obj_subActividad->SetIdActiv($registro[14]);
$obj_subActividad->SetIdSubActiv($registro[15]);
$obj_subActividad->ConsultSubActividad();
$obj_subActividad->ConsultActividad();

//$fecha = fecha_correcta($registro[1]);
class PDF extends FPDF{
	//Cabecera de página
	function Encabezado(){
    	//Logo
	    $this->Image('imaud.jpg',150,12,45,20,'JPG');
	    //Arial bold 12
    	$this->SetFont('Arial','B',12);
		//Salto de línea
		$this->Ln(17);
		//Movernos a la derecha
    	$this->Cell(30);
	    //Título 1
    	$this->Cell(0,0,'INSTITUTO MUNICIPAL',0,1,'L');
		//Salto de línea
		$this->Ln(0,5);
	    //Movernos a la derecha
    	$this->Cell(20);
	    //Título 2
		$this->Cell(0,10,'DE ASEO URBANO Y DOMICILIO',0,1,'L');
		//Salto de línea
		$this->Ln(0,5);
	    //Movernos a la derecha
    	$this->Cell(25);
		//Arial bold 10
    	$this->SetFont('Arial','BI',8);
	    //Título 3
	$this->Cell(0,0,'UNA ALTERNATIVA DE SANEAMIENTO !!',0,1,'L');
	//Salto de línea
	$this->Ln(5);
    //Movernos a la derecha
    $this->Cell(125);
    //Título 4
	$this->Cell(0,0,'Zona Industrial, Avenida 4 - Galpón IMAUD',0,1,'L');
	//Salto de línea
	$this->Ln(3);
	//Movernos a la derecha
    $this->Cell(100);
    //Título 5
	$this->Cell(0,0,'Coro - Estado Falcón',0,1,'C');
	//Salto de línea
    $this->Ln(20);
}

//Pie de página
function Footer(){
    //Posición: a 1,5 cm del final
    $this->SetY(-20);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Número de página
	$this->Cell(0,10,'Sistema IMAUD '.$this->PageNo().'/{nb}',0,0,'R');
	$this->Ln(3);
    $this->Cell(0,10,'Coop. Programación Web S.R.L.',0,0,'R');
}
}

//Creación del objeto de la clase heredada

$pdf=new PDF('Portrait','mm','Letter');
$pdf->setAuthor('Sistema Imaud - Modulo de Fiscalizacion');
$pdf->setDisplayMode('real','continuous');
$pdf->setTitle('Fiscalizacion'.$registro[0]);
$pdf->SetMargins(3,3,3);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',11.5);
$pdf->Cell(0,0,'F I S C A L I Z A C I O N ',0,0,'C');
$pdf->Ln(5);
$pdf->Cell(150);
$pdf->Cell(0,0,'N°  '.$registro[0],0,1,'L');
$pdf->Ln(10);
/////////////////////////////////////////////////
$pdf->Cell(30);
$pdf->Cell(0,0,'Fecha de Solicitud:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Cell(85);
$pdf->Cell(0,0,$fecha,0,1,'L');
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->Cell(30);
$pdf->SetFont('Arial','B','');
$pdf->Cell(0,0,'Nombre ó Razón Social:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Cell(85);
$pdf->Cell(0,0,$registro[2],0,1,'L');
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Nombre del Solicitante:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Cell(85);
$pdf->Cell(0,0,$registro[3],0,1,'L');
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Numero de Cuenta:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Cell(85);
$pdf->Cell(0,0,$registro[4],0,1,'L');
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Teléfono de Contacto:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Cell(85);
$pdf->Cell(0,0,$registro[5],0,1,'L');
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Motivo:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Ln(5);
$pdf->Cell(50);
$pdf->MultiCell(140,0,$registro[6]);
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Dirección:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Ln(5);
$pdf->Cell(50);
$pdf->MultiCell(140,0,$registro[7]);
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Manzana:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Cell(85);
$pdf->Cell(0,0,$registro[8],0,1,'L');
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Lado:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Cell(85);
$pdf->Cell(0,0,$registro[9],0,1,'L');
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Portal:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Cell(85);
$pdf->Cell(0,0,$registro[10],0,1,'L');
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Supervisor:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Cell(85);
$pdf->Cell(0,0,"N/A",0,1,'L');
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Área Total(m2):  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Cell(85);
$pdf->Cell(0,0,$registro[12],0,1,'L');
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Área Util(m2):  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Cell(85);
$pdf->Cell(0,0,$registro[13],0,1,'L');
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Actividad Desarrollada:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Ln(5);
$pdf->Cell(50);
$pdf->MultiCell(140,0,"Ninguna");
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Sub Actividad:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Cell(85);
$pdf->Cell(0,0,"Ninguna",0,1,'L');
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Residencia:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Cell(85);
$pdf->Cell(0,0,"N/A",0,1,'L');
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Tipo de Residencia:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Cell(85);
$pdf->Cell(0,0,"N/A",0,1,'L');
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Observación:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Ln(5);
$pdf->Cell(50);
$pdf->MultiCell(140,0,$registro[18]);
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->SetFont('Arial','B','');
$pdf->Cell(30);
$pdf->Cell(0,0,'Resolución:  ',0,1,'L');
$pdf->SetFont('Arial','','');
$pdf->Ln(5);
$pdf->Cell(50);
$pdf->MultiCell(140,0,$registro[19]);
$pdf->Ln(5);
/////////////////////////////////////////////////
$pdf->Output();
}else
echo "No existe Fiscalizacion";
}else
echo "No Seteado!!!";
?>