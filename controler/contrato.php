<?php
session_start();   
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
session_cache_limiter('private, must-revalidate');
require_once("../pdf/fpdf.php");
class Contrato extends FPDF{
	public function Header(){
		$this->SetFont('Arial','',9);
	    $this->Image('../img/gobernacion.jpg',10,5,47,20,'JPG');
		$this->Cell(0,4,'FUNDACION FONDO DE MUTUALIDAD',0,1,'C');
		$this->Cell(0,4,'PARA LA PROTECCION SOCIAL DE LOS',0,1,'C');
		$this->Cell(0,4,'TRABAJADORES Y TRABAJADORAS DE LA',0,1,'C');
		$this->Cell(0,4,'GOBERNACION DEL ESTADO FALCON',0,1,'C');
	    $this->Image('../img/fundamutual.jpg',160,5,43,24,'JPG');
		$this->Ln(3);
		$this->SetFont('Arial','B',10);
		$this->Cell(0,4,'CONTRATO DE AFILIACION PARA LA PRESTACION DE SERVICIOS DE SALUD',0,1,'C');
	}
	public function Footer(){
	    $this->SetY(-8);
	    $this->SetFont('Arial','I',7);
		$this->Cell(0,4,'SOFTHCM -FUNDAMUTUAL'.$this->PageNo().'/{nb}',0,1,'R');
		$this->Cell(0,4,'EL PRESENTE CONTRATO TIENE VIGENCIA DESDE EL 01-01-2013 HASTA EL 31-12-2013',0,0,'C');
	}
}
if(isset($_GET['codigo']) && !empty($_GET['codigo'])){
	try{
		require_once("../config.php");
		require_once("../class/lib_funciones.php");
		$objConnex= new Conexionbd();
		$objFamiliar= new Familiar();
		$sql=call_user_func(array($objFamiliar, "contrato"));
		$stmt=$objConnex->stmt_init();
		if($stmt->prepare($sql)){
			$stmt->bind_param('i', $_GET['codigo']);
			$stmt->execute();
			$result=$stmt->get_result();
			if($result->num_rows==1){
				$date= new DateTime();
				$rows=$result->fetch_object();
				$pdf = new Contrato("P","mm","Letter");
				$pdf->setAuthor('SSPHCM - Oficinas en Linea Falcon C.A.');
				$pdf->setTitle('CONTRATO DE POLIZA HCM - SSPHCM');
				$pdf->AliasNbPages();
				$pdf->AddPage();
				list($anio,$mes,$dia)=explode("-",$rows->fecren);
				$date->setDate((int) $anio,(int) $mes,(int) $dia);
				$fechanueva=$date->format('d/m/Y');
				$pdf->Cell(65,5,'Fecha de Renovacion:',0,0,'C');
				$pdf->Cell(32,5,$fechanueva,0,0,'C');
				$pdf->Cell(65,5,'Fecha de Inscripcion:',0,0,'C');
				list($anio,$mes,$dia)=explode("-",$rows->ingreso);
				$date->setDate((int) $anio,(int) $mes,(int) $dia);
				$fechanueva=$date->format('d/m/Y');
				$pdf->Cell(32,5,$fechanueva,0,1,'C');
				$pdf->Cell(35,5,'Cedula:','LT',0);
				$cedula=str_pad($rows->cedtit,10,"0", STR_PAD_LEFT);
				$pdf->Cell(35,5,$rows->abrev."   ".$cedula,'T',0);
				$pdf->Cell(35,5,'Codigo:','T',0);
				$pdf->Cell(35,5,$_GET['codigo'],'T',0);
				$pdf->Cell(54,5,'','TR',1,'C');
				//194
				$pdf->Cell(40,5,'Apellidos:','L',0);
				$pdf->Cell(57,5,$rows->apetit,0,0);
				$pdf->Cell(40,5,'Nombres:',0,0);
				$pdf->Cell(57,5,$rows->nomtit,'R',1);
				//194
				$pdf->Cell(40,5,'Lugar Nacimiento:','L',0);
				$pdf->Cell(70,5,$rows->lugnactit,0,0);
				$pdf->Cell(39,5,'Fecha Nacimiento:',0,0);
				list($anio,$mes,$dia)=explode("-",$rows->fecnactit);
				$date->setDate((int) $anio,(int) $mes,(int) $dia);
				$fechanueva=$date->format('d/m/Y');
				$pdf->Cell(25,5,$fechanueva,0,0);
				$pdf->Cell(14,5,'Sexo:',0,0);
				$pdf->Cell(6,5,$rows->sexo,'R',1);
				//194
				$pdf->Cell(40,5,'Direccion:','L',0);
				$pdf->Cell(154,5,$rows->dirtit,'R',1);
				//194
				$pdf->Cell(30,5,'Estado Civil:','L',0);
				$pdf->Cell(10,5,$rows->edocivil,0,0);
				$pdf->Cell(25,5,'Telefono:',0,0);
				$pdf->Cell(52,5,$rows->tlf,0,0);
				$pdf->Cell(25,5,'Celular:',0,0);
				$pdf->Cell(52,5,$rows->celular,'R',1);
	
				$pdf->Cell(40,5,'Email:','L',0);
				$pdf->Cell(154,5,$rows->email,'R',1);

				$pdf->Cell(40,5,'Dependencia:','L',0);
				$pdf->Cell(154,5,$rows->desnom,'R',1);
	
				$pdf->Cell(194,5,'PLAN DE COBERTURA',1,1,'C');

				$pdf->Cell(30,5,'Codigo','L',0,'C');
				$pdf->Cell(84,5,'Descripcion',0,0,'C');
				$pdf->Cell(45,5,'Cobertura',0,0,'C');
				$pdf->Cell(35,5,'Cuota','R',1,'C');
				$codpla=str_pad($rows->codpla, 2, "0", STR_PAD_LEFT);
				$pdf->Cell(30,5,$codpla,'LB',0,'C');
				$pdf->Cell(84,5,$rows->despla,'B',0,'C');
				$cobertura=number_format($rows->cobertura, 2, '.', ',');
				$pdf->Cell(45,5,$cobertura,'B',0,'C');
				$cuotatit=number_format($rows->cuota, 2, '.', ',');
				$pdf->Cell(35,5,$cuotatit,'RB',1,'C');

				$pdf->Ln(3);
				$pdf->Cell(63,5,'Apellidos y Nombres','TBL',0,'C');
				$pdf->Cell(25,5,'C.I.','TB',0,'C');
				$pdf->Cell(26,5,'Fec. Naci.','TB',0,'C');
				$pdf->Cell(15,5,'Parent.','TB',0,'C');
				$pdf->Cell(27,5,'Fec. Ingreso','TB',0,'C');
				$pdf->Cell(13,5,'O. C.','TB',0,'C');
				$pdf->Cell(25,5,'Prima','TBR',1,'R');
				$datosfam=array();
				$totpagar=(float) 0.00;
				$sql=call_user_func(array($objFamiliar, "beneficiarios"));
				if($stmt->prepare($sql)){
					$stmt->bind_param('i', $_GET['codigo']);
					$stmt->execute();
					$result=$stmt->get_result();
					if($result->num_rows>0){
						while($rowsf=$result->fetch_assoc())
							array_push($datosfam, $rowsf); 
					}
				}else
					throw new Exception("Ocurrio un problema con la siguiente consulta SQL: ".$sql);	
				$pdf->SetFont('Arial','',8);
				if(count($datosfam)==0)
					$pdf->Cell(194,5,'NO POSEE BENEFICIARIOS',0,1,'C');
				foreach($datosfam as $indice => $rows_fam){
					$pdf->Cell(63,5,$rows_fam['apefam'].",".$rows_fam['nomfam'],0,0);
					$cedfam=str_pad($rows_fam['cedfam'],10,"0",STR_PAD_LEFT);
					$pdf->Cell(25,5,$cedfam,0,0,'C');
					
					list($anio,$mes,$dia)=explode("-",$rows_fam['fecnacfam']);
					$date->setDate((int) $anio,(int) $mes,(int) $dia);
					$fecha=$date->format('d/m/Y');
					$pdf->Cell(26,5,$fecha,0,0,'C');

					$pdf->Cell(15,5,$rows_fam['nom_corto'],0,0,'C');
					list($anio,$mes,$dia)=explode("-",$rows_fam['fecingfam']);
					$date->setDate((int) $anio,(int) $mes,(int) $dia);
					$fecha=$date->format('d/m/Y');
					$pdf->Cell(27,5,$fecha,0,0,'C');
					$pdf->Cell(13,5,$rows_fam['otrocont'],0,0,'C');
					
					$cuotafam=number_format($rows_fam['cuota'], 2, '.', ',');
					$totpagar+=(float) $cuotafam;
					$pdf->Cell(25,5,$cuotafam,0,1,'R');
					
				}
				$pdf->SetFont('Arial','B',10);
				$pdf->Ln(1);
				$pdf->Cell(94,5,'',0,0,'C');
				$pdf->Cell(75,5,'Total a pagar por afiliados:','LTB',0,'R');
				$totpagar=number_format($totpagar, 2, '.', ',');
				$pdf->Cell(25,5,$totpagar,'TBR',1,'R');
				$pdf->Ln(1);
				$pdf->Cell(94,5,'',0,0,'C');
				$pdf->Cell(75,5,'Prima por Maternidad:','LTB',0,'R');
				$cuotaadi=($rows->adicional=="N")?0.00:$rows->cuotaadi;
				$cuotaadi=number_format($cuotaadi,2,'.',',');
				$pdf->Cell(25,5,$cuotaadi,'TBR',1,'R');
				$pdf->Ln(1);
				$pdf->Cell(94,5,'',0,0,'C');
				$pdf->Cell(75,5,'Total a Pagar:','LTB',0,'R');
				$totpagar+=((float) $cuotatit+(float) $cuotaadi);
				$totpagar=number_format($totpagar, 2, '.', ',');
				$pdf->Cell(25,5,$totpagar,'TBR',1,'R');
				$pdf->Ln(2);
				$pdf->SetFont('Arial','',8);
				$pdf->Cell(0,3,'SE APLICA DEDUCIBLE PARA TODOS LOS EVENTOS:',0,1);
				$pdf->Cell(0,3,'DEL (5)% CON UN MINIMO DE CIENTO CINCUENTA BOLIVARES CON CERO CENTIMOS ( 150.00 Bs.)',0,1);
				$pdf->Cell(0,3,'¿TIENE USTED CONTRATO CON OTRO SISTEMA DE SEGURO DE SALUD? ('.$rows->otrocont.')',0,1);
				$pdf->Cell(0,3,'¿DESEA USTED INCLUIR LA COBERTURA DE MATERNIDAD CON UN COSTO DE ('.$cuotaadi.') POR MES? ('.$rows->adicional.')',0,1);
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(0,5,'MUY IMPORTANTE',0,1);
				$pdf->SetFont('Arial','',9);
				$apenomt=strtoupper($rows->apetit).", ".strtoupper($rows->nomtit);
				$pdf->MultiCell(0,3,"Yo, (".$apenomt."), antes identificado, actuando en este acto, en mi condición de titular afiliado de los servicios de la Fundación Fondo de Mutualidad para la Protección Social de los Trabajadores y Trabajadoras de la Gobernación del estado Falcón (FUNDAMUTUAL), mediante la presente declaro:\n1.- Que tengo plena voluntad, libertad, discernimiento y capacidad jurídica, mental, física e intelectual para leer, entender y aceptar este contrato de afiliación y todo su contenido.\n2. Que tengo pleno conocimiento de la naturaleza jurídica de ésta Fundación, la cual fue creada bajo la figura de un régimen de prestaciones recíprocas, lo cual representa por excelencia un Fondo de inversión colectiva, en donde la Gobernación del estado Falcón y sus funcionarios, empleados y trabajadores, asumen de forma equitativa la responsabilidad directa e indirecta de garantizar su funcionamiento.\n3.- Que he leído de forma reiterada y exhaustiva cada una de las normas, procedimientos, condiciones y situaciones establecidas en el Reglamento para el Uso De Los Servicios De Atención Médica De La Fundación Fondo De Mutualidad Para La Protección Social De Los Trabajadores Y Trabajadoras De La Gobernación Del Estado Falcón, las cuales acepto y comprometo cumplir a cabalidad.\n4.- Que tengo conocimiento de la naturaleza jurídica del presente contrato, el cual de conformidad con el numeral 35 del artículo 3 del Reglamento antes mencionado, es un contrato de Adhesión, por lo que su clausulas no pueden ser relajadas y modificadas por parte de los afiliados y afiliadas y cuya  duración será la misma del año de cobertura, sin que durante ese lapso puedan retirarse de las obligaciones allí contenidas.\n5.- Que asumo con responsabilidad la difusión de la información aquí contenida, así como del Reglamento antes mencionado entre sus beneficiarios.\n6.- Que la Gobernación del estado Falcón ofrece un Plan de Cobertura Gratutita.\n7.- Que tiengo pleno conocimiento de las obligaciones aquí contraídas, las cuales declaro haber asumido de forma voluntaria.\n8.- Que la información que estoy aportando es verdadera, completa y exacta, por lo que no he omitido, ni disimulado ningún hecho o circunstancia que pueda inferir en la opinión de FUNDAMUTUAL sobre el riesgo a correr por el beneficio solicitado. Siendo nulo este contrato en caso de fraude o declaración falsa u omisiones.\n9.- Que el presente contrato y demás documentos anexos con la base para apreciar el riesgo y fijar la prima correspondiente de ser el caso.\n10.- Que autorizo a cualquier médico o centro de salud público o privado, que me hayan tratado o lo hicieren a futuro, proporcione a esta Fundación cualquier información que pueda requerir al respecto.",0);
				$h=(count($datosfam)<7)?3:12;
				$hCell=(count($datosfam)<4 || count($datosfam)>6)?15:35;
				$pdf->Ln($h);
				$pdf->SetFont('Arial','',10);
				$pdf->MultiCell(0,4,'Autorizo el descuento por nómina la cantidad única de ('.$totpagar.') mensual, correspondiente por el Plan de cobertura suscrito en el presente contrato',0);
				$pdf->SetFont('Arial','',10);
				$pdf->Cell(21,$hCell,'',0,0);
				$pdf->Cell(44,$hCell,'','B',0);
				$pdf->Cell(10,$hCell,'',0,0);
				$pdf->Cell(44,$hCell,'','B',0);
				$pdf->Cell(10,$hCell,'',0,0);
				$pdf->Cell(44,$hCell,'','B',0);
				$pdf->Cell(21,$hCell,'',0,1);
				$pdf->Cell(21,4,'',0,0);
				$pdf->Cell(44,4,'Firma y Huella del Trabajador',0,0,'C');
				$pdf->Cell(10,4,'',0,0);
				$pdf->Cell(44,4,'Revisado por',0,0,'C');
				$pdf->Cell(10,4,'',0,0);
				$pdf->Cell(44,4,'Aprobado por',0,1,'C');
				$name=$_GET['codigo'];
				$pdf->Output($name.".pdf", "D");			
			}else
				echo "NO hay registro";
		}else
			echo "Error: ".$sql;
	}catch(Exception $e){
		echo $e->getMessage();
	}
}
?>