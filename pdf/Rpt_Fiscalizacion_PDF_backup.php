<?php
session_start();
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
//session_cache_limiter('private, must-revalidate');
if(array_key_exists("consultar",$_REQUEST)){
	if(!array_key_exists("status_all",$_REQUEST)){
	$add_filter='';
	    foreach($_POST as $valor){
			if(is_array($valor)){
		   	$field_name="estatus";
		   		foreach($valor as $valores){
			   		switch(count($valor)){
					case count($valor)>1:
					$add_filter.=(empty($add_filter))?" AND ( ":" OR ";
					$add_filter.=$field_name."='".strtoupper($valores)."'";
						if($valores==$valor[count($valor)-1])
						$add_filter.=")";
					break;
			   		case 1:
					$add_filter=" AND ".$field_name."='".strtoupper($valores)."'";
					break;
					default:
					$add_filter='';
					}
		   		}
			}
	  	}
	}else
	$add_filter='';

	echo $add_filter;
	die();
	include_once("../clases/Solicitudes.class.php");
	include_once("../clases/Write_PDF.php");
	$conver_date=new Converter_Fecha();
	$obj_solicitud = new Solicitud();
	if(array_key_exists("fec_ini",$_POST) && array_key_exists("fec_fin",$_POST)){
		$inicio=$conver_date->ConverFecha($_POST["fec_ini"],"AAAA-MM-DD");
		$fin=$conver_date->ConverFecha($_POST["fec_fin"],"AAAA-MM-DD");
		$existe_query=$obj_solicitud->Solicitud4fecha($inicio,$fin,$add_filter);
		$orientacion="L";
		$eje_x=200;
		$eje_y=1;
		$Title_Principal="Solicitudes Desde ".$_POST["fec_ini"]." Hasta ".$_POST["fec_fin"];
	}elseif(array_key_exists("buscar",$_REQUEST)){
		$solicitud=deleted_char(trim($_REQUEST["buscar"]),'0');
		$obj_solicitud->SetIdSolicit($solicitud);
		$existe_query=$obj_solicitud->Exists_Solicitud();
		$orientacion="P";
		$eje_x=139;
		$eje_y=1;
		$Title_Principal="Solicitud de ";
	}

if($existe_query){
	if(is_array($obj_solicitud->datosofarray)){
	
	}else{
	    if($obj_solicitud->tipfiscalObject->View_Registro())
			$des_solicitud=$obj_solicitud->tipfiscalObject->GetDescTipo();
			$Title_Principal.=$des_solicitud;
			$estado_solicitud=$obj_solicitud->GetStatusSolicit();
			switch($des_solicitud){
			case 'Fiscalizacion':
			$page='1';
			include_once'../clases/Fiscalizacion.class.php';
			$datosfiscalizados=new Fiscalizaciones();
			$datosfiscalizados->SolicitudObject->SetIdSolicit($obj_solicitud->GetIdSolicit());
			$datosfiscalizados->fExistsFiscalizacion();
			break;	
			case 'Levantamiento Catastral':
			include_once'../clases/levcatastral.class.php';
			$page='2';
			$datoslevcatastral=new LevantamientoCatastral();
			$datoslevcatastral->SolicitudObject->SetIdSolicit($obj_solicitud->GetIdSolicit());
			$datoslevcatastral->ExistsLevantamientoCatastral();
			break;
			}
		}
	}

$obje_usuario=new Usuario();
$pdf=new PDF($orientacion,'mm','Letter');
$pdf->SetPathLogo("../view/images/logo.jpg");
$pdf->Setancho_imagen(40);
$pdf->Setalto_imagen(40);
$pdf->Setimagen_position_x($eje_x);
$pdf->Setimagen_position_y($eje_y);
$pdf->setAuthor('SISTEMA IMAUD');
$pdf->setDisplayMode('real');
$pdf->setTitle('Reporte de Fiscalizacion');
$pdf->SetMargins(3,2,3);
$pdf->AliasNbPages('Total_page');
$pdf->AddPage();
$pdf->SetFont('Arial','B',11.5);
$pdf->Cell(0,0,$Title_Principal,0,0,'C');
$pdf->Ln(3);

if($orientacion=="P"){
$new_line=6;
$pdf->Cell(150);
$pdf->Cell(0,0,'Solicitud N: '.str_pad($obj_solicitud->GetIdSolicit(),10,"0",STR_PAD_LEFT),0,1,'L');
$pdf->Ln(10);
$pdf->Cell(10);
$pdf->Cell(0,0,'Estatus: '.$obj_solicitud->GetStatusSolicit(),0,0,'L');
if(trim($obj_solicitud->GetStatusSolicit())!="EN PROCESO"){
$numerolcorf=($des_solicitud=='Fiscalizacion')?$datosfiscalizados->GetNFiscalizacion():$datoslevcatastral->GetLevCatast();
$pdf->Cell(-10,0,'N de '.$des_solicitud.": ".str_pad($numerolcorf,10,"0",STR_PAD_LEFT),0,0,'R');
}
$pdf->Ln($new_line);
$margen_izqu=10;
$pdf->SetFont('','',12);
$pdf->Cell($margen_izqu);
$pdf->SetFont('','B');
$pdf->Cell(0,0,'Fecha de Solicitud:',0,1,'L');
$pdf->Cell(50);
$pdf->SetFont('','');
$pdf->Cell(0,0,$obj_solicitud->GetFecSolicit(),0,1,'L');
if(trim($obj_solicitud->GetStatusSolicit())!="EN PROCESO"){
$pdf->Cell(110);
$pdf->SetFont('','');
$pdf->Cell(0,0,"Fecha de ".$des_solicitud.':',0,1,'L');
$pdf->Cell(176);
$pdf->SetFont('','');
$datelcorf=($des_solicitud=='Fiscalizacion')?$datosfiscalizados->GetFecFiscalizacion():$datoslevcatastral->GetFecLevCatast();
$pdf->Cell(0,0,$conver_date->ConverFecha($datelcorf,'DD/MM/AAAA'),0,1,'L');
}
$pdf->Ln($new_line);
	if($page=='2'){
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(50,6,'Parroquia','LTRB',0,'C');
	$pdf->Cell(40,6,'Urbanizaci�n','LTRB',0,'C');
	$pdf->Cell(50,6,'Sector','LTRB',0,'C');
	$pdf->Cell(10,6,'N','LTRB',0,'C');
	$pdf->Cell(10,6,'M','LTRB',0,'C');
	$pdf->Cell(10,6,'L',1,0,'C');
	$pdf->Cell(10,6,'P',1,0,'C');
	$pdf->Cell(10,6,'U',1,0,'C');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','');
	$datoslevcatastral->SolicitudObject->parroqObject->DatosOf();
	$pdf->Cell(50,6,$datoslevcatastral->SolicitudObject->parroqObject->GetNombreP(),1,0,'C');
	$pdf->Cell(40,6,$datoslevcatastral->SolicitudObject->GetCodUrbanizacion(),1,0,'C');
	$pdf->Cell(50,6,'',1,0,'C');
	$pdf->Cell(10,6,'',1,0,'C');
	$pdf->Cell(10,6,$datoslevcatastral->SolicitudObject->GetNumManzana(),1,0,'C');
	$pdf->Cell(10,6,$datoslevcatastral->SolicitudObject->GetNumLado(),1,0,'C');
	$pdf->Cell(10,6,$datoslevcatastral->SolicitudObject->GetNumPortal(),1,0,'C');
	$pdf->Cell(10,6,$datoslevcatastral->SolicitudObject->GetNumUsuario(),1,0,'C');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(50,6,"Nombre o Raz�n Social:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(140,6,$datoslevcatastral->SolicitudObject->GetRazonSocial(),'TRB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(46,6,"Representante Legal:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(144,6,$datoslevcatastral->SolicitudObject->replegal->GetApellido(),'TRB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(35,6,"Direcci�n Fiscal:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(155,6,$datoslevcatastral->SolicitudObject->GetDirFiscal(),'TRB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$dir_inmueble=($datoslevcatastral->SolicitudObject->GetDirInmueble()=='')? $obj_solicitud->solicitanteObject->GetDireccion():$datoslevcatastral->SolicitudObject->GetDirInmueble();
	$pdf->Cell(50,6,"Direcci�n del Inmueble:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(140,6,$dir_inmueble,'TRB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(42,6,"Direcci�n de Cobro:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(148,6,$datoslevcatastral->SolicitudObject->GetDirCobro(),'TRB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(50,6,'Tel�fono',1,0,'C');
	$pdf->Cell(50,6,'R.I.F.',1,0,'C');
	$pdf->Cell(50,6,'N.I.T.',1,0,'C');
	$pdf->Cell(40,6,'Patente',1,0,'C');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','');
	$pdf->Cell(50,6,$datoslevcatastral->SolicitudObject->GetTlfInmueble(),1,0,'C');
	$pdf->Cell(50,6,$datoslevcatastral->SolicitudObject->GetRIF(),1,0,'C');
	$pdf->Cell(50,6,'',1,0,'C');
	$pdf->Cell(40,6,$datoslevcatastral->SolicitudObject->GetPatente(),1,0,'C');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$array_label=$datoslevcatastral->SolicitudObject->Espec_Tip_Dsctos();
	$pdf->Cell(25,6,$array_label["vivi_acti"],'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(92,6,CODE_HTML_CHARACTER($array_label["result_vivi_acti"],2),'TRB',0,'L');
	$pdf->SetFont('','B');
	$pdf->Cell(33,6,'Fecha de Inicio','LTRB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(40,6,$conver_date->ConverFecha($datoslevcatastral->SolicitudObject->GetFecEjerEco(),'DD/MM/AAAA'),1,0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(25,6,'�rea Total:','LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(25,6,$datoslevcatastral->SolicitudObject->GetAreaTotal(),'TRB',0,'L');
	$pdf->SetFont('','B');
	$pdf->Cell(23,6,'�rea �til:','LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(27,6,$datoslevcatastral->SolicitudObject->GetAreaUtil(),'TRB',0,'L');
	$pdf->SetFont('','B');
	$pdf->Cell(50,6,'N�mero de Empleados:','LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(40,6,$datoslevcatastral->SolicitudObject->GetNumEmpleados(),'TRB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(50,6,'Facturaci�n del servicio:','LTB',0,'L');
	$pdf->SetFont('','');
	$datoslevcatastral->SolicitudObject->tipfactuObject->VerRegistro();
	$pdf->Cell(50,6,$datoslevcatastral->SolicitudObject->tipfactuObject->GetDescripcionTF(),'TRB',0,'L');
	$pdf->SetFont('','B');
	$pdf->Cell(40,6,'Municipio:','LTB',0,'L');
	$pdf->SetFont('','');
	$datoslevcatastral->SolicitudObject->parroqObject->viewMunicipio();
	$pdf->Cell(50,6,$datoslevcatastral->SolicitudObject->parroqObject->GetNombre(),'TRB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(20,6,'Medidor',1,0,'C');
	$pdf->Cell(62,6,'N�mero de Cuenta El�ctrica',1,0,'C');
	$pdf->Cell(58,6,'Uso del Inmueble',1,0,'C');
	$pdf->Cell(50,6,'Tipo de recolecci�n',1,0,'C');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','');
	$exist=($datoslevcatastral->SolicitudObject->GetExistMedidor()=='1')?'Si':'No';
	$pdf->Cell(20,6,$exist,1,0,'C');
	$pdf->Cell(62,6,$datoslevcatastral->SolicitudObject->GetNumCtaElect(),1,0,'C');
	$pdf->Cell(58,6,$array_label["result_clasi_subact"],1,0,'C');
	$datoslevcatastral->SolicitudObject->tiprecolObject->Consultar();
	$pdf->Cell(50,6,$datoslevcatastral->SolicitudObject->tiprecolObject->GetDesc_Recoleccion(),1,0,'C');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(26,6,'Supervisor:','LTB',0,'L');
	$pdf->SetFont('','');
	$datoslevcatastral->SolicitudObject->supervisorObject->uDatosUsuario();
	$pdf->Cell(164,6,$datoslevcatastral->SolicitudObject->supervisorObject->GetApellido().', '.$datoslevcatastral->SolicitudObject->supervisorObject->GetNombre(),'TRB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B',13);
	$pdf->Cell(190,6,'Datos de la Empresa Suministrados por',1,0,'C');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B',12);
	$pdf->Cell(40,6,"Nombre y Apellido:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(150,6,$datoslevcatastral->empleadoObject->GetApellido(),'TRB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(14,6,"Cargo:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(176,6,$datoslevcatastral->empleadoObject->GetCargo(),'TRB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(190,6,"Observaciones:",'LRT',1,'L');
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','');
	$pdf->Cell(190,6,$datoslevcatastral->SolicitudObject->GetObservacion(),'LRB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(190,6,"Resolucion:",'LRT',1,'L');
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','');
	$pdf->Cell(190,6,$datoslevcatastral->SolicitudObject->GetResolucion(),'LRB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B',13);
	$pdf->Cell(190,6,'Informaci�n del Solicitante',1,0,'C');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B',12);
	$pdf->Cell(41,6,"Nombre y Apellido:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(149,6,$datoslevcatastral->SolicitudObject->solicitanteObject->GetApellido(),'RTB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(20,6,"Tel�fono:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(170,6,$datoslevcatastral->SolicitudObject->solicitanteObject->GetTelefono(),'RTB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(15,6,"Motivo:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(175,6,$datoslevcatastral->SolicitudObject->GetMotivo(),'RTB',0,'L');
	$pdf->Ln($new_line);
	}elseif($page=='1'){
	/*
	RPT DE FISCALIZACION
	*/
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B',12);
	$pdf->Cell(20,6,"Usuario:",'LTB',0,'L');
	$pdf->SetFont('','');
	$objCliente=new Cliente();
	$objCliente->SetCodClient($datosfiscalizados->SolicitudObject->GetCodClient());
	$objCliente->SetIdCta($datosfiscalizados->SolicitudObject->GetIdCta());
	$objCliente->InfoClienteofCta();
	$pdf->Cell(170,6,$objCliente->GetRazonSocial(),'TRB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B',12);
	$pdf->Cell(28,6,"No. Cuenta:",'1',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(162,6,$objCliente->GetNumCtaElect(),'1',0,'L');	
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B',12);
	$pdf->Cell(42,6,"Persona Solicitante:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(148,6,$datosfiscalizados->SolicitudObject->solicitanteObject->GetApellido(),'TBR',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B',12);
	$pdf->Cell(35,6,"Direcci�n:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(155,6,$datosfiscalizados->SolicitudObject->GetDirFiscal(),'TBR',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B',12);
	$pdf->Cell(28,6,"Tel�fono:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(162,6,$datosfiscalizados->SolicitudObject->solicitanteObject->GetTelefono(),'TBR',0,'L');
	$pdf->Ln($new_line+$new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B',12);
	$pdf->Cell(20.5,6,"Manzana:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(27,6,$datosfiscalizados->SolicitudObject->GetNumManzana(),'TBR',0,'L');
	$pdf->SetFont('','B',12);
	$pdf->Cell(12.5,6,"Lado:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(35,6,$datosfiscalizados->SolicitudObject->GetNumLado(),'TBR',0,'L');
	$pdf->SetFont('','B',12);
	$pdf->Cell(14.5,6,"Portal:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(33,6,$datosfiscalizados->SolicitudObject->GetNumPortal(),'TBR',0,'L');
	$pdf->SetFont('','B',12);
	$pdf->Cell(18.5,6,"Usuario:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(29,6,$datosfiscalizados->SolicitudObject->GetNumUsuario(),'TBR',0,'L');
	$pdf->Ln($new_line+$new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B',12);
	$pdf->Cell(28,6,"Atendido por:",'LTB',0,'L');
	$pdf->SetFont('','');
	$datosfiscalizados->SolicitudObject->usuarioObject->uDatosUsuario();
	$pdf->Cell(162,6,$datosfiscalizados->SolicitudObject->usuarioObject->GetApellido().', '.$datosfiscalizados->SolicitudObject->usuarioObject->GetNombre(),'TBR',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B',12);
	$pdf->Cell(37,6,"Tipo de Solicitud:",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(153,6,$des_solicitud,'TBR',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(190,6,"Motivo:",'LRT',1,'L');
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','');
	$pdf->MultiCell(190,6,$datosfiscalizados->SolicitudObject->GetMotivo(),'LRB');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(190,6,"Comercial",0,0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(24,6,"Area Total",'LTB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(30,6,$datosfiscalizados->SolicitudObject->GetAreaTotal(),'RTB',0,'L');
	$pdf->SetFont('','B');
	$pdf->Cell(24,6,"Area Util",'TB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(30,6,$datosfiscalizados->SolicitudObject->GetAreaUtil(),'TBR',0,'L');
	$pdf->SetFont('','B');
	$pdf->Cell(35,6,"No. Empleado",'TB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(10,6,$datosfiscalizados->SolicitudObject->GetNumEmpleados(),'TBR',0,'L');
	$pdf->SetFont('','B');
	$pdf->Cell(20,6,'A�os','TB',0,'L');
	$pdf->SetFont('','');
	$pdf->Cell(17,6,'','TBR',1,'L');
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$array_label=$datosfiscalizados->SolicitudObject->Espec_Tip_Dsctos();
	$actividad= (in_array('Actividad :',$array_label))?$array_label["result_vivi_acti"]:'';
	$pdf->Cell(95,6,"Actividad :",'LT',0,'L');
	$pdf->Cell(95,6,"Descripci�n :",'RT',1,'L');
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','');
	$pdf->Cell(95,5,CODE_HTML_CHARACTER($actividad,2),'BL',0,'L');
	$descripcion=(in_array('Actividad :',$array_label))?$array_label["result_clasi_subact"]:'';
	$pdf->Cell(95,5,CODE_HTML_CHARACTER($descripcion,2),'BR',0,'L');
	$pdf->Ln($new_line);
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(190,6,"Residencial",0,0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(25,6,"Tipo Casa:",'LTB',0,'L');
	$pdf->SetFont('','');
	$tipocasa= (in_array('Vivienda',$array_label))?$array_label["vivi_acti"]:'';
	$pdf->Cell(70,6,$tipocasa,'RTB',0,'L');
	$pdf->SetFont('','B');
	$pdf->Cell(35,6,"Apartamento:",'TB',0,'L');
	$pdf->SetFont('','');
	$clasificacion= (in_array('Vivienda',$array_label))?$array_label["result_clasi_subact"]:'';
	$pdf->Cell(60,6,$clasificacion,'TBR',1,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(190,6,"Observacion:",'LTR',1,'L');
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','');
	$pdf->Cell(190,6,$datosfiscalizados->SolicitudObject->GetObservacion(),'RLB',0,'L');
	$pdf->Ln($new_line);
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','B');
	$pdf->Cell(190,6,"Resolucion:",'LTR',1,'L');
	$pdf->Cell($margen_izqu);
	$pdf->SetFont('','');
	$pdf->Cell(190,6,$datosfiscalizados->SolicitudObject->GetResolucion(),'RLB',0,'L');
	$pdf->Ln($new_line*5);
	$pdf->Cell($margen_izqu);
	$pdf->Cell(95,6,"Firma y Sello del usuario",0,0,'C');
	$pdf->Cell(95,6,"Firma del Supervisor",0,0,'C');
	}
}else{
$tHeader=array("N Solicitud"=>25,"Apellido y Nombre"=>75,"Fecha"=>25,"Nombre/Razon Social"=>110,"N Cuenta"=>30);
$rDatos=array();
$aDatos=array();
foreach($obj_solicitud->datosofarray as $aKey => $avalor){//Registros
	if(is_array($avalor)){//Datos Fila
	$objCliente=new Cliente();
		   if((!empty($avalor['COD_CLIENT']) && !empty($avalor['ID_CTAS'])) && ($avalor['COD_CLIENT']!=0 && $avalor['ID_CTAS']!=0)){
			$objCliente->SetCodClient($avalor['COD_CLIENT']);
			$objCliente->SetIdCta($avalor['COD_CLIENT']);
			$objCliente->InfoClienteofCta();
		   }
	array_push($aDatos,$avalor[0],$objCliente->GetRazonSocial(),$avalor['9'],$objCliente->GetRazonSocial(),$objCliente->GetNumCtaElect());
echo $avalor[0]." , ".$objCliente->GetRazonSocial()." , ".$avalor['9'].", ".$objCliente->GetRazonSocial().",".$objCliente->GetNumCtaElect()."<br>";
	}
array_push($rDatos,$aDatos);
}
$pdf->header=$tHeader;
$pdf->ResulSetQuery=$rDatos;
$pdf->Create_Table();
}
$pdf->Output();
}

?>