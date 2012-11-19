<?php
session_start();
session_name("ssphcm");
//error_reporting(E_ALL); 
//ini_set('display_errors',1);
if(isset($_SESSION['timestamp'], $_SESSION['autorizado'], $_SESSION['id_usuario'])){
	try{
		require_once("../config.php");
		require_once("../class/lib_funciones.php");
		idleLogin($_SESSION['timestamp'], IDLE_TIMES);
		if(isset($_SESSION['timestamp'])){
			$response_json=array('redirect'=>'false','error'=>'false','success'=>false, 'mensaje'=>'','obj_form'=>false, 'url_redirect'=>'false', 'json_response'=>'');
			$metodos=array("datagrid","funcion","profesion","planes","datosplan","estados","listmunicipios","listparroquias","nominas","direccionsuperior","secretaria","coordinacion","division","direccion","oficina","departamento","cargo","puesto","datosnomina", "primatitular", "primafamiliar","nexo","validar","calcularprimas", "buscar", "consultar","guardar", "validarcedula");
			if(isset($_GET['evento']) && in_array($_GET['evento'], $metodos)){
				if ($_GET['evento']!="funcion")
					$objConnex= new Conexionbd();
				switch($_GET['evento']){
					case "validarcedula":
						$validaton_json=array('isvalid'=>true,'msj'=>"");
						if(isset($_POST['codtit'], $_POST['codpla']) && !empty($_POST['cedfam']) && !empty($_POST['codpla'])){
							$stmt=$objConnex->stmt_init();
							$cedula=($_GET['codigo']=="titular")?$_POST['cedtit']:$_POST['cedfam'];
							$sql="SELECT codigo, codpla FROM socio WHERE cedtit=? AND codigo!=?";
							if($stmt->prepare($sql)){
								$stmt->bind_param('si', $cedula, $_POST['codtit']);
								$stmt->execute();
								$result=$stmt->get_result();
								if($result->num_rows==1){
									$plantit=$result->fetch_object();
									if($_GET['codigo']=="titular"){
										$validaton_json['isvalid']=false;
										$validaton_json['msj']="La cedula que indico pertenece al titular con el código ".$plantit->codigo;
									}else
										if($plantit->codpla==1 AND $_POST['codpla']==1){
											$validaton_json['isvalid']=false;
											$validaton_json['msj']="La cedula que indico es Titular y posee Plan Básico,\nNo puede gozar de doble cobertura.";
										}
								}
							}
							if($validaton_json['isvalid']){
								$sql="SELECT s.codigo, s.codpla, f.codigo_fam FROM familiar AS f LEFT OUTER JOIN socio AS s ON s.codigo=f.codigo WHERE f.cedfam=? AND f.codigo!=?";
								if($stmt->prepare($sql)){
									$stmt->bind_param('si', $cedula, $_POST['codtit']);
									$stmt->execute();
									$result=$stmt->get_result();
									if($result->num_rows>0){
										$validaton_json['isvalid']=1;
										while($rows=$result->fetch_object()){
											if($rows->codpla==1 AND $_POST['codpla']==1){
												$validaton_json['msj']="La cedula que indico es Beneficiario del Titular de codigo ".$rows->codigo."\nBeneficiario no puede gozar de doble cobertura.";
												break;
											}
										}
									}
								}
							}
						}
						echo json_encode($validaton_json);
					break;
					case "guardar":
						if(isset($_POST["codtit"], $_POST["cedtit"], $_POST["apetit"], $_POST["nomtit"], $_POST["lugnactit"], $_POST["tlf"], $_POST["celular"], $_POST["email"], $_POST["fecnactit"], $_POST["edocivil"], $_POST["sexo"], $_POST["fecinglab"], $_POST["codprof"], $_POST["dirtit"], $_POST["adicional"], $_POST["codnom"], $_POST["codpla"], $_POST["cobertura"], $_POST["cuota"], $_POST["otroconttit"], $_POST["desconttit"], $_POST["codest"], $_POST["codmun"], $_POST["codpar"], $_POST["coddir"], $_POST["codsec"], $_POST["codcor"], $_POST["coddiv"], $_POST["coddirec"], $_POST["codofi"], $_POST["cod_dpto"], $_POST["codcar"], $_POST["codpues"]) && !empty($_POST['cedtit']) && !empty($_POST['apetit']) && !empty($_POST['nomtit']) && !empty($_POST['fecnactit']) && !empty($_POST['edocivil']) && !empty($_POST['sexo']) && !empty($_POST['adicional']) && !empty($_POST['codpla']) && is_numeric($_POST['cobertura']) && is_numeric($_POST['cuota'])){
							$objFamiliar= new Familiar();
							$stmt=$objConnex->stmt_init();
							if(is_numeric($_POST['codtit'])){
								$sql=call_user_func(array($objFamiliar, "existeCodigo"));
								if($stmt->prepare($sql)){
									$stmt->bind_param('i', $_POST['codtit']);
									$stmt->execute();
									$result=$stmt->get_result();
									if($result->num_rows==1){
										$sql=call_user_func(array($objFamiliar, "modificarSocio"));
									}elseif($result->num_rows==0){
										$response_json['error']=true;
										$response_json['mensaje']="Opss!, no se puede modificar el registro, codigo de titular no se encuentra";
										$sql=NULL;
									}
								}
							}else{
								$sql=call_user_func(array($objFamiliar, "nuevoSocio"));
								$_POST['codtit']=0;
							}
							if(isset($sql)){
								$ingreso=date("Y-m-d");
								$next_anio=date("Y");
								$edad=Edad($_POST['fecnactit'], ++$next_anio, 6, 1);
								$date=new DateTime();
								list($dia,$mes,$anio)=explode("/",$_POST['fecnactit']);
								$date->setDate((int) $anio,(int) $mes,(int) $dia);
								$_POST['fecnactit']=$date->format('Y-m-d');
								if(!empty($_POST['fecinglab'])){
									list($dia,$mes,$anio)=explode("/",$_POST['fecinglab']);
									if(!checkdate((int) $mes,(int) $dia,(int) $anio))
										$_POST['fecinglab']="";
									else{
										$date->setDate((int) $anio,(int) $mes,(int) $dia);
										$_POST['fecinglab']=$date->format('Y-m-d');
									}
								}
								$cod_nexo=0;
								$cuota=$cuotaadi=0.00;
								$codpla=(int) $_POST['codpla'];
								$codnom=(int) $_POST['codnom'];
								$adicional=($codpla==1 || $_POST['sexo']=="M")?"N":$_POST['adicional'];
								/*MONTO PARA LAS PRIMAS SEGUN EL PLAN SELECCIONADO*/
								$objPrima= new Primas();
								$sqlprimas=call_user_func(array($objPrima, "primasPlan"));
								$stmtprimas=$objConnex->stmt_init();
								if($stmtprimas->prepare($sqlprimas)){
									$stmtprimas->bind_param('ii', $codpla, $codnom);
									$stmtprimas->execute();
									$result=$stmtprimas->get_result();
									if($result->num_rows>0){
										$aprimasnomina=array();
										while($rows=$result->fetch_assoc())
											if($rows['codnom']==$codnom || $rows['codnom']==0)
												array_push($aprimasnomina, $rows);
										$objPrima->mtoPrima($aprimasnomina, $_POST['sexo'], $cod_nexo, $edad, $adicional);
										$cuota=number_format($objPrima->__get("mto_prima"),2,'.',',');
										$cuotaadi=number_format($objPrima->__get("mto_adicional"),2,'.',',');
									}
								}
								if(count($aprimasnomina)>0){
									$objConnex->autocommit(FALSE);
									if($stmt->prepare($sql)){
										$stmt->bind_param('sssssssisssidsdsssiiiiiiiiiiiiiissii', $_POST['cedtit'], $_POST['apetit'], $_POST['nomtit'], $_POST['lugnactit'], $_POST['tlf'], $_POST['celular'], $_POST['fecnactit'], $edad, $_POST['edocivil'], $_POST['sexo'], $_POST['dirtit'], $codpla, $cuota, $_POST['adicional'], $cuotaadi, $ingreso, $_POST['otroconttit'], $_POST['desconttit'], $_POST['codest'], $_POST['codmun'], $_POST['codpar'], $_POST['codnom'], $_POST['coddir'], $_POST['codsec'], $_POST['codcor'], $_POST['coddiv'], $_POST['coddirec'], $_POST['codofi'], $_POST['cod_dpto'], $_POST['codcar'], $_POST['codpues'], $_POST['codprof'], $_POST['fecinglab'], $_POST['email'], $_SESSION['id_usuario'], $_POST['codtit']);
										$stmt->execute();
										$response_json['success']=true;
										if($stmt->affected_rows>=0){//
											$_POST['codtit']=($stmt->insert_id!=0)?$stmt->insert_id:$_POST['codtit'];
											$acodigosfam=array();
											$sql=call_user_func(array($objFamiliar, "familiaresTit"));
											if($stmt->prepare($sql)){
												$stmt->bind_param('i', $_POST['codtit']);
												$stmt->execute();
												$result=$stmt->get_result();
												if($result->num_rows>0)
													while($rows=$result->fetch_object())
														array_push($acodigosfam, $rows->codigo_fam);
											}
										}else
											throw new Exception("Ocurrio un problema al registrar el titular");	
										$codigo_fam=$regdelete=0;
										$codigosfamiliares=array();
										$_POST['fam']=(!isset($_POST['fam']))?array():$_POST['fam'];
										if(count($_POST['fam'])>0){
											$_POST['adicional']=($_POST['codpla']==1)?"N":$_POST['adicional'];
											foreach($_POST['fam'] as $indice => $rowfam){
												$sql=NULL;
												if(substr($rowfam['codigo_fam'],0,3)=="TMP"){//Nuevo afiliado
													$sql=call_user_func(array($objFamiliar, "agregarfam"));
													$codigo_fam=0;//Autogenerar
												}else{
													if(in_array($rowfam['codigo_fam'], $acodigosfam)){
														$sql=call_user_func(array($objFamiliar, "modificarfam"));
														$codigo_fam=$rowfam['codigo_fam'];
														array_push($codigosfamiliares, $codigo_fam);
													}
												}
												if(isset($sql)){
													$edad=Edad($rowfam['fecnacfam'], $next_anio, 6, 1);
													list($dia,$mes,$anio)=explode("/",$rowfam['fecnacfam']);
													$date->setDate((int) $anio,(int) $mes,(int) $dia);
													$rowfam['fecnacfam']=$date->format('Y-m-d');
													$cod_nexo=(int) $rowfam['cod_nexo'];
													$objPrima->mtoPrima($aprimasnomina, $_POST['sexo'], $cod_nexo, $edad, $_POST['adicional'], $rowfam['sexofam']);
													$cuota=number_format($objPrima->__get("mto_prima"),2,'.',',');
													$cuotaadi=number_format($objPrima->__get("mto_adicional"),2,'.',',');
													if($stmt->prepare($sql)){
														$stmt->bind_param('isssssiisssddsi', $_POST['codtit'], $rowfam['cedfam'], $rowfam['apefam'], $rowfam['nomfam'], $rowfam['sexofam'], $rowfam['fecnacfam'], $edad, $cod_nexo, $rowfam['lugnacfam'], $rowfam['otrocontfam'], $rowfam['descontfam'], $cuota, $cuotaadi, $ingreso, $codigo_fam);
														$stmt->execute();
														if($stmt->affected_rows==-1)
															throw new Exception("Ocurrio un problema con el procedimiento: ".$sql);	
													}else{
														$objConnex->rollback();
														throw new Exception("Ocurrio un problema con el procedimiento: ".$sql);	
													}
												}
											}
											if(isset($acodigosfam) && is_array($acodigosfam)){
												$sql=call_user_func(array($objFamiliar, "deleteFam"));
												if($stmt->prepare($sql)){
													foreach($acodigosfam as $id_fam){
														if(!in_array($id_fam, $codigosfamiliares)){
															$stmt->bind_param('si', $ingreso, $id_fam);
															$stmt->execute();
															if($stmt->affected_rows>0)
																$regdelete++;
														}
													}
												}
											}
										}else{
											if(isset($acodigosfam) && is_array($acodigosfam)){
												$sql=call_user_func(array($objFamiliar, "deleteFam"));
												if($stmt->prepare($sql)){
													foreach($acodigosfam as $codigo_fam){
														$stmt->bind_param('si', $ingreso, $codigo_fam);
														$stmt->execute();
														if($stmt->affected_rows>0)
															$regdelete++;
													}
												}else{
													$objConnex->rollback();
													throw new Exception("Ocurrio un problema con el procedimiento: deleteFam");	
												}
											}
										}
									}else{
										throw new Exception("Ocurrio un problema con el procedimiento: ".$sql);	
										$objConnex->rollback();
									}
								}else
									throw new Exception("No existen primas para el plan de la poliza que selecciono");	
							}
						}
						$objConnex->commit();
						$response_json['url_redirect']="./contrato/consultar/".$_POST['codtit'];
						echo json_encode($response_json);
					break;
					case "consultar":
						$datosjson=array("cedtit"=>"","apetit"=>"", "nomtit"=>"", "lugnactit"=>"", "tlf"=>"", "celular"=>"", "email"=>"", "fecnactit"=>"", "edocivil"=>"", "sexo"=>"", "fecinglab"=>"", "codprof"=>"", "dirtit"=>"", "adicional"=>"", "codnom"=>"", "codpla"=>"", "cobertura"=>0.00, "cuota"=>0.00, "otroconttit"=>"N", "desconttit"=>"", "codest"=>"", "codmun"=>"", "codpar"=>"", "coddir"=>"", "codsec"=>"", "codcor"=>"", "coddiv"=>"", "coddirec"=>"", "codofi"=>"", "cod_dpto"=>"", "codcar"=>"", "codpues"=>"", "activo"=>"");
						if(isset($_POST['codtit']) && is_numeric($_POST['codtit'])){
							$objSocio= new Socio();
							$sql=call_user_func(array($objSocio, "consultarCodigo"));
							$stmt=$objConnex->stmt_init();
							if($stmt->prepare($sql)){
								$stmt->bind_param('i', $_POST['codtit']);
								$stmt->execute();
								$result=$stmt->get_result();
								$response_json['success']=true;
								if($result->num_rows==1){
									$rows=$result->fetch_assoc();
									$datosjson=array_combine(array_keys($datosjson), array_values($rows));
									$date = new DateTime($datosjson['fecnactit']);
									$datosjson['fecnactit']=$date->format('d/m/Y');
									list($anio,$mes,$dia)=explode("-",$datosjson['fecinglab']);
									if(!checkdate($mes, $dia, $anio))
										$datosjson['fecinglab']="";
									else{
										$date->setDate((int) $anio,(int) $mes,(int) $dia);				
										$datosjson['fecinglab']=$date->format('d/m/Y');
									}
									$datosjson["otroconttit"]=$rows['otrocont'];
									$datosjson["desconttit"]=$rows['descont'];
								}else
									array_push($datosjson, array("codtit"=>""));
							}
						}
						echo json_encode($datosjson);
					break;
					case "buscar":
						$response_json=array("success"=>false, "activo"=>"", "codigo"=>false);
						if(isset($_POST['cedula']) && !empty($_POST['cedula'])){
							$objSocio= new Socio();
							$sql=call_user_func(array($objSocio, "buscarCedula"));
							$stmt=$objConnex->stmt_init();
							if($stmt->prepare($sql)){
								$stmt->bind_param('s', $_POST['cedula']);
								$stmt->execute();
								$result=$stmt->get_result();
								$response_json['success']=true;
								if($result->num_rows==1){
									$rows=$result->fetch_object();
									$response_json['activo']=$rows->activo;
									$response_json['codigo']=$rows->codigo;
								}
							}
						}
						echo json_encode($response_json);
					break;
					case "validar":
						$objPrima= new Primas();
						$response_json=array("isvalid"=>true, "mensaje"=>"", "autorizar"=>false);
						if(isset($_GET['codigo'], $_POST['codtit'], $_POST['sexo'], $_POST['codigo_fam'], $_POST['cod_nexo'], $_POST['fecnacfam'], $_POST['sexofam']) && !empty($_POST['fecnacfam']) && !empty($_POST['sexofam']) && !empty($_POST['cod_nexo']) && !empty($_POST['sexo']) && $_GET['codigo']=="beneficiario"){
							$sql="SELECT COUNT(codigo_fam) AS cantidad, cod_nexo FROM familiar WHERE codigo=? AND codigo_fam!=? GROUP BY cod_nexo";
							$next_anio=date("Y");
							$edad=Edad($_POST['fecnacfam'], ++$next_anio, 6, 1);
							$stmt=$objConnex->stmt_init();
							if($stmt->prepare($sql)){
								$stmt->bind_param('ii', $_POST['codigo'], $_POST['codigo_fam']);
								$stmt->execute();
								$result=$stmt->get_result();
								if($result->num_rows>0){
									while($rows=$result->fetch_assoc())
										if($rows['cod_nexo']==$_POST['cod_nexo']){
											$objPrima->validBenef($response_json, $_POST['cod_nexo'], $edad, $rows['cantidad']);
											break;
										}
								}else
									$objPrima->validBenef($response_json, $_POST['cod_nexo'], $edad);
							}
						}elseif(isset($_GET['codigo'], $_POST['cod_nexo'], $_POST['fecnacfam'], $_POST['datosfam']) && $_GET['codigo']=="familiares" && !empty($_POST['cod_nexo']) && !empty($_POST['fecnacfam']) && is_array($_POST['datosfam'])){
							$cntfamiliar=array_count_values($_POST['datosfam']);
							$next_anio=date("Y");
							$edad=Edad($_POST['fecnacfam'], ++$next_anio, 6, 1);
							foreach($cntfamiliar as $id_nexo => $cnt_nexo){
								if($id_nexo==$_POST['cod_nexo'])
									$objPrima->validBenef($response_json, $_POST['cod_nexo'], $edad, $cnt_nexo);
							}
						}
						echo json_encode($response_json);
					break;
					case "calcularprimas":
						$response_json=array("success"=>false, "cuota"=>false, "familiares"=>array());
						if(isset($_POST['sexo'], $_POST['fecnactit'], $_POST['adicional'], $_POST['codnom'], $_POST['codpla']) && !empty($_POST['codpla']) && !empty($_POST['codnom']) && !empty($_POST['adicional']) && !empty($_POST['fecnactit']) && !empty($_POST['sexo'])){
							$cod_nexo=0;
							$next_anio=date("Y");
							$codpla=(int) $_POST['codpla'];
							$codnom=(int) $_POST['codnom'];
							$edad=Edad($_POST['fecnactit'], ++$next_anio, 6, 1);
							$objPrima= new Primas();
							$sql=call_user_func(array($objPrima, "primasPlan"));
							$stmt=$objConnex->stmt_init();
							if($stmt->prepare($sql)){
								$stmt->bind_param('ii', $codpla, $codnom);
								$stmt->execute();
								$result=$stmt->get_result();
								if($result->num_rows>0){
									$response_json['success']=true;
									$aprimasnomina=$aprimas=array();
									while($rows=$result->fetch_assoc())
										if($rows['codnom']==$codnom || $rows['codnom']==0)
											array_push($aprimasnomina, $rows);
									$adicional=($_POST['codpla']==1 || $_POST['sexo']=="M")?"N":$_POST['adicional'];
									$objPrima->mtoPrima($aprimasnomina, $_POST['sexo'], $cod_nexo, $edad, $_POST['adicional']);
									$response_json['cuota']=number_format($objPrima->__get("mto_prima"),2,'.',',');
								}
							}
							if(isset($_POST['familiares']) && count($_POST['familiares'])>0){
								foreach($_POST['familiares'] as $indice => $registros){
									$cod_nexo=(int) $registros['cod_nexo'];
									$edad=Edad($registros['fecnacfam'], ++$next_anio, 6, 1);
									$adicional=($codpla==1)?"N":$_POST['adicional'];
									$objPrima->mtoPrima($aprimasnomina, $_POST['sexo'], $cod_nexo, $edad, $adicional, $registros['sexofam']);
									array_push($response_json['familiares'],array("codigo_fam"=>$registros['codigo_fam'], "cuota_fam"=>number_format($objPrima->__get("mto_prima"),2,'.',',')));
								}
							}
						}
						echo json_encode($response_json);
					break;
					case "primafamiliar":
						$response_json=array("success"=>false, "cuota_fam"=>false);
						if(isset($_POST['fecnacfam'], $_POST['sexofam'], $_POST['cod_nexo'], $_POST['adicional'], $_POST['sexo'], $_POST['codpla'], $_POST['codnom'] ) && (!empty($_POST['fecnacfam']) && !empty($_POST['sexofam']) && !empty($_POST['cod_nexo']) && !empty($_POST['adicional']) && !empty($_POST['sexo']) && !empty($_POST['codpla']) && !empty($_POST['codnom'])))
						{
							$cod_nexo=(int) $_POST['cod_nexo'];
							$codpla=(int) $_POST['codpla'];
							$codnom=(int) $_POST['codnom'];
							$objPrima= new Primas();
							$sql=call_user_func(array($objPrima, "primasPlan"));
							$stmt=$objConnex->stmt_init();
							if($stmt->prepare($sql)){
								$stmt->bind_param('ii', $codpla, $codnom);
								$stmt->execute();
								$result=$stmt->get_result();
								if($result->num_rows>0){
									$response_json['success']=true;
									$next_anio=date("Y");
									$edad=Edad($_POST['fecnacfam'], ++$next_anio, 6, 1);
									$aprimasnomina=$aprimas=array();
									while($rows=$result->fetch_assoc())
										if($rows['codnom']==$codnom || $rows['codnom']==0)
											array_push($aprimasnomina, $rows);
									$adicional=($_POST['codpla']==1)?"N":$_POST['adicional'];
									$objPrima->mtoPrima($aprimasnomina, $_POST['sexo'], $cod_nexo, $edad, $adicional, $_POST['sexofam']);
									$response_json['cuota_fam']=number_format($objPrima->__get("mto_prima"),2,'.',',');
								}
							}
							echo json_encode($response_json);
						}
					break;
					case "primatitular":
						$response_json=array("success"=>false, "cuota"=>false);
						if(isset($_POST['adicional'], $_POST['codnom'], $_POST['codpla'], $_POST['fecnactit'], $_POST['sexo']) && (!empty($_POST['adicional']) && !empty($_POST['codnom']) && !empty($_POST['codpla']) && !empty($_POST['fecnactit']) && !empty($_POST['sexo'])))
						{
							$cod_nexo=0;
							$codpla=(int) $_POST['codpla'];
							$codnom=(int) $_POST['codnom'];
							$objPrima= new Primas();
							$sql=call_user_func(array($objPrima, "primasPlan"));
							$stmt=$objConnex->stmt_init();
							if($stmt->prepare($sql)){
								$stmt->bind_param('ii', $codpla, $codnom);
								$stmt->execute();
								$result=$stmt->get_result();
								if($result->num_rows>0){
									$response_json['success']=true;
									$next_anio=date("Y");
									$edad=Edad($_POST['fecnactit'], ++$next_anio, 6, 1);
									$aprimasnomina=$aprimas=array();
									while($rows=$result->fetch_assoc())
										if($rows['codnom']==$codnom || $rows['codnom']==0)
											array_push($aprimasnomina, $rows);
									$adicional=($_POST['codpla']==1 || $_POST['sexo']=="M")?"N":$_POST['adicional'];
									$objPrima->mtoPrima($aprimasnomina, $_POST['sexo'], $cod_nexo, $edad, $adicional);
									$response_json['cuota']=number_format($objPrima->__get("mto_prima"),2,'.',',');
								}
							}
						}
						echo json_encode($response_json);
					break;
					case "datosnomina":
						$response_json=array("success"=>false, "tabs_disabled"=>array());
						if(isset($_POST['codnom']) && !empty($_POST['codnom'])){
							$activo="1";
							$objNominas= new Nominas();
							$sql=call_user_func(array($objNominas, "aceptaFamiliares"));
							$stmt=$objConnex->stmt_init();
							if($stmt->prepare($sql)){
								$stmt->bind_param("ss", $activo, $_POST['codnom']);
								$stmt->execute();
								$result=$stmt->get_result();
								$response_json["success"]=true;
								if($result->num_rows==1){
									$rows=$result->fetch_object();
									if($rows->con_benef==0)
										array_push($response_json['tabs_disabled'],2);
								}
							}
						}
						echo json_encode($response_json);
					break;
					case "nexo":
						$objNexo= new Nexo();
						$sql=call_user_func(array($objNexo, "listarNexo"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows>0){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('value'=>$rows->cod_nexo, 'text'=>$rows->nom_largo));
							}
						}
						echo json_encode($response_json);
					break;
					case "puesto":
						$activo="1";
						$objPuesto= new Puesto();
						$sql=call_user_func(array($objPuesto, "listarPuesto"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->bind_param("s", $activo);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows>0){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('value'=>$rows->codpues, 'text'=>$rows->despues));
							}
						}
						echo json_encode($response_json);
					break;
					case "cargo":
						$activo="1";
						$objCargo= new Cargo();
						$sql=call_user_func(array($objCargo, "listarCargo"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->bind_param("s", $activo);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows>0){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('value'=>$rows->codcar, 'text'=>$rows->descar));
							}
						}
						echo json_encode($response_json);
					break;
					case "departamento":
						$activo="1";
						$objDepartamento= new Departamento();
						$sql=call_user_func(array($objDepartamento, "listarDepartamento"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->bind_param("s", $activo);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows>0){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('value'=>$rows->cod_dpto, 'text'=>$rows->nom_dpto));
							}
						}
						echo json_encode($response_json);
					break;
					case "oficina":
						$activo="1";
						$objOficina= new Oficina();
						$sql=call_user_func(array($objOficina, "listarOficina"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->bind_param("s", $activo);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows>0){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('value'=>$rows->codofi, 'text'=>$rows->desofi));
							}
						}
						echo json_encode($response_json);
					break;
					case "direccion":
						$activo="1";
						$objDireccion= new Direccion();
						$sql=call_user_func(array($objDireccion, "listarDireccion"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->bind_param("s", $activo);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows>0){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('value'=>$rows->coddirec, 'text'=>$rows->desdirec));
							}
						}
						echo json_encode($response_json);
					break;
					case "division":
						$activo="1";
						$objDivision= new Division();
						$sql=call_user_func(array($objDivision, "listarDivision"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->bind_param("s", $activo);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows>0){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('value'=>$rows->coddiv, 'text'=>$rows->desdiv));
							}
						}
						echo json_encode($response_json);
					break;
					case "coordinacion":
						$activo="1";
						$objCoordinacion= new Coordinacion();
						$sql=call_user_func(array($objCoordinacion, "listarCoordinacion"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->bind_param("s", $activo);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows>0){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('value'=>$rows->codcor, 'text'=>$rows->descor));
							}
						}
						echo json_encode($response_json);
					break;
					case "secretaria":
						$activo="1";
						$objSecre= new Secretaria();
						$sql=call_user_func(array($objSecre, "listarSecretaria"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->bind_param("s", $activo);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows>0){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('value'=>$rows->codsec, 'text'=>$rows->dessec));
							}
						}
						echo json_encode($response_json);
					break;
					case "direccionsuperior":
						$activo="1";
						$objDirsup= new Direccionsup();
						$sql=call_user_func(array($objDirsup, "listarDireccionsup"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->bind_param("s", $activo);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows>0){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('value'=>$rows->coddir, 'text'=>$rows->desdir));
							}
						}
						echo json_encode($response_json);
					break;
					case "nominas":
						$activo="1";
						$objNominas= new Nominas();
						$sql=call_user_func(array($objNominas, "listarNominas"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->bind_param("s", $activo);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows>0){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('value'=>$rows->codnom, 'text'=>$rows->desnom));
							}
						}
						echo json_encode($response_json);
					break;
					case "listparroquias":
						if(isset($_POST['codmun'], $_POST['codest']) && !empty($_POST['codmun']) && !empty($_POST['codest'])){
							$activo="1";
							$objParroquia= new Parroquias();
							$sql=call_user_func(array($objParroquia, "listarParroquias"));
							$stmt=$objConnex->stmt_init();
							if($stmt->prepare($sql)){
								$stmt->bind_param("is", $_POST['codmun'], $activo);
								$stmt->execute();
								$result=$stmt->get_result();
								$response_json["success"]=true;
								if($result->num_rows>0){
									$response_json['obj_form']=array();
									while($rows=$result->fetch_object())
										array_push($response_json['obj_form'], array('value'=>$rows->codpar, 'text'=>$rows->denpar));
								}
							}				
						}
						echo json_encode($response_json);
					break;
					case "listmunicipios":
						if(isset($_POST['codest']) && !empty($_POST['codest'])){
							$activo="1";
							$objMunicipio= new Municipios();
							$sql=call_user_func(array($objMunicipio, "listarMunicipios"));
							$stmt=$objConnex->stmt_init();
							if($stmt->prepare($sql)){
								$stmt->bind_param("ss", $_POST['codest'], $activo);
								$stmt->execute();
								$result=$stmt->get_result();
								$response_json["success"]=true;
								if($result->num_rows>0){
									$response_json['obj_form']=array();
									while($rows=$result->fetch_object())
										array_push($response_json['obj_form'], array('value'=>$rows->codmun, 'text'=>$rows->denmun));
								}
							}				
						}
						echo json_encode($response_json);
					break;
					case "estados":
						$activo="1";
						$objEstado= new Estados();
						$sql=call_user_func(array($objEstado, "listarEstados"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->bind_param("s", $activo);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows>0){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('value'=>$rows->codest, 'text'=>$rows->desest));
							}
						}
						echo json_encode($response_json);
					break;
					case "datosplan":
						$objPlan= new Planes();
						$activo="1";
						$sql="SELECT cobertura FROM planes WHERE codpla=? AND activo=?";
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->bind_param("ss", $_POST['codpla'], $activo);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows==1){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('cobertura'=>$rows->cobertura));
							}
						}
						echo json_encode($response_json);						
					break;
					case "planes":
						$activo="1";
						$objPlan= new Planes();
						$sql=call_user_func(array($objPlan, "listarPlanes"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->bind_param("s", $activo);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows>0){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('value'=>$rows->codpla, 'text'=>$rows->despla));
							}
						}
						echo json_encode($response_json);
					break;
					case "profesion":
						$activo="1";
						$objProfesion= new Profesion();
						$sql=call_user_func(array($objProfesion, "listarProfesion"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->bind_param("s", $activo);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json["success"]=true;
							if($result->num_rows>0){
								$response_json['obj_form']=array();
								while($rows=$result->fetch_object())
									array_push($response_json['obj_form'], array('value'=>$rows->codprof, 'text'=>$rows->desc_prof));
							}
						}
						echo json_encode($response_json);
					break;
					case "funcion": 
						$response_json=array("success"=>false, "edad"=>-1);
						if( (isset($_POST['fecnactit']) || isset($_POST['fecnacfam'])) && (!empty($_POST['fecnactit']) || !empty($_POST['fecnacfam']))){
							$fecha=(isset($_POST['fecnactit']))?$_POST['fecnactit']:$_POST['fecnacfam'];
							list($dia, $mes, $anio)=explode("/",$fecha);
							if(checkdate(settype($mes,"integer"),settype($dia,"integer"),settype($anio,"integer"))){
								$next_anio=date("Y");
								$response_json['edad']=Edad($fecha, ++$next_anio, 6, 1);
								$response_json['success']=true;
							}
						}
						echo json_encode($response_json);
					break;
					case "datagrid":
						$datagridjson=array("total"=>0,"page"=>0,"records"=>0,"rows"=>array());
						if(isset($_POST['codtit']) && is_numeric($_POST['codtit'])){
							$objFamiliar= new Familiar();
							$sql=call_user_func(array($objFamiliar, "listarfamiliares"));
							$stmt=$objConnex->stmt_init();
							if($stmt->prepare($sql)){
								$stmt->bind_param('i', $_POST['codtit']);
								$stmt->execute();
								$result=$stmt->get_result();
								$datagridjson['records']=$result->num_rows;
								$datagridjson['page']=(int) $_POST['page'];
								if($datagridjson['records']>0){
									$datagridjson['total']=ceil($datagridjson['records']/$_POST['rows']);
									while($rows=$result->fetch_assoc()){
										$date = new DateTime($rows['fecnacfam']);
										$rows['fecnacfam']=$date->format('d/m/Y');
										$ceill=array('id'=>$rows['codigo_fam'],'cell'=>array_values($rows));
										array_push($datagridjson['rows'], $ceill);
									}
								}
							}
						}
						echo json_encode($datagridjson);
					break;
				}
			}
		}else
			header("Location: ./login2.html");
	}catch(Exception $e){
		$response_json['success']=false;
		echo "Error en linea: ".$e->getLine();
		echo "Detalle: ".$e->getMessage();
	}
}else
	header("Location: ./login.html");
?>