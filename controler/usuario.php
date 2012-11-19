<?php
session_start();
session_name("ssphcm");
//error_reporting(E_ALL); ini_set('display_errors',1);
$_SESSION['autorizado']=$_SESSION['id_usuario']=$_SESSION['timestamp']=time();
if(isset($_SESSION['autorizado'], $_SESSION['id_usuario'])){
	try{
		require_once("../config.php");
		require_once("../class/lib_funciones.php");
		idleLogin($_SESSION['timestamp'], IDLE_TIMES);
		if(isset($_SESSION['timestamp'])){			
			$response_json=array('redirect'=>'false','error'=>'false','success'=>false, 'mensaje'=>'','obj_form'=>false, 'url_redirect'=>'false', 'json_response'=>'');
			$metodos=array("consultar");
			if(isset($_GET['evento']) && in_array($_GET['evento'], $metodos)){
				$objUsuario= new Usuario();
				$objConex= new Conexionbd();
				$stmt=$objConex->stmt_init();
				$active_commit=FALSE;
				$objConex->autocommit($active_commit);
				switch($_GET['evento']){
					case 'consultar':
						if(isset($_POST['cod_user'], $_POST['cedusu']) && !empty($_POST['cedusu'])){
							$sql=call_user_func(array($objUsuario,"consultarCedula"));
							if($stmt->prepare($sql)){
								$stmt->bind_param('s', $_POST['cedusu']);
								$stmt->execute();
								$result=$stmt->get_result();
								if($result->num_rows==0){
								
								}elseif($result->num_rows==1){
								
								}else{
								
								}
							}
						}
					break;
				}
				
			}
			$objConex->autocommit($active_commit);
			echo json_encode($response_json);			
			/*switch($_GET['evento']){
				case 'guardar':
					if(isset($_POST['form']['id_usuario'], $_POST['form']['id_empleado'], $_POST['form']['id_perfil'], $_POST['form']['login'], $_POST['form']['clave'], $_POST['form']['rep_clave']) && $_POST['form']['evento'][0]=="Guardar" && !empty($_POST['form']['id_empleado']) && !empty($_POST['form']['id_perfil']) && !empty($_POST['form']['login']) && !empty($_POST['form']['clave']) && $_POST['form']['clave']==$_POST['form']['rep_clave']){
						$sql=call_user_func(array($objUsuario,"existsUsuarioEmpleado"));
						$clave=md5(sha1($_POST['form']['clave']));
						$activo=(isset($_POST['form']['activo']))?'1':'0';
						$id_usuario=$_POST['form']['id_usuario'];
						if($stmt->prepare($sql)){
							$stmt->bind_param('ii', $_POST['form']['id_empleado'], $_POST['form']['id_usuario']);
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json['success']=true;
							$sql=call_user_func(array($objUsuario,($result->num_rows==1)?"actualizarusuario":"agregusuario"));
							$stmt->reset();
							if($stmt->prepare($sql)){
								$stmt->bind_param('iisssi', $_POST['form']['id_empleado'], $_POST['form']['id_perfil'], $_POST['form']['login'], $clave, $activo, $id_usuario);
								$stmt->execute();
								if($stmt->affected_rows>0){
									if($stmt->insert_id==0){
										$response_json['mensaje']='Se actualizaron los datos del usuario satisfactoriamente';	
										$sql=call_user_func(array($objUsuario,"eliminarPermisos"));
										$stmt->reset();
										if($stmt->prepare($sql)){
											$stmt->bind_param($id_usuario);
											$stmt->execute();
										}
									}else{
										$id_usuario=$stmt->insert_id;
										$response_json['mensaje']='El usuario fue creado satisfactoriamente';	
									}
								}else
									$response_json['mensaje']='No se realizo cambio en los datos del usuario';
								$paramswin=array('id_ventana'=>'','open'=>'', 'view'=>'', 'add'=>'', 'update'=>'', 'delete'=>'');
								$objPerfiles= new Perfil();
								$sql=call_user_func(array($objPerfiles,"verPerfil"));
								$stmt->reset();
								if($stmt->prepare($sql)){
									$stmt->bind_param('i', $_POST['form']['id_perfil']);
									$stmt->execute();
									$result=$stmt->get_result();
									if($result->num_rows==1){
										$rows=$result->fetch_object();
										if($rows->activo==0){
											$response_json['mensaje']="No se pudo registrar el usuario ya que el perfil se encuentra inactivo";
											$error=1;
										}elseif($rows->is_personalizado!=1){
											$sql=call_user_func(array($objPerfiles,"permisosPerfil"));
											$stmt->reset();
											if($stmt->prepare($sql)){
												$stmt->bind_param('i', $_POST['form']['id_perfil']);
												$stmt->execute();
												$result=$stmt->get_result();
												$datos_permisos=array();
												while($rows=$result->fetch_object()){
													$paramswin['id_ventana']=$rows->id_ventana;
													$paramswin['open']=$rows->event_open;
													$paramswin['view']=($rows->event_view);
													$paramswin['add']=($rows->event_add);
													$paramswin['update']=($rows->event_update);
													$paramswin['delete']=($rows->event_delete);
													array_push($datos_permisos, $rows);
												}
											}else
												throw new Exception(date('d/m/Y G:i:s T')." Error#:".$stmt->errno." [ ".$stmt->error." ]");
										}else{//Es personalizado
											$datos_permisos=array();
											foreach($_POST['datagrid'] as $indice){
												$paramswin['id_ventana']=$indice['id_ventana'];
												$paramswin['open']=($indice['open']=='Yes')?'1':'0';
												$paramswin['view']=$indice['view']=='Yes'?'1':'0';
												$paramswin['add']=$indice['add']=='Yes'?'1':'0';
												$paramswin['update']=$indice['update']=='Yes'?'1':'0';
												$paramswin['delete']=$indice['delete']=='Yes'?'1':'0';
												array_push($datos_permisos,$paramswin);
											}
										}
										if(count($datos_permisos)>0 && !isset($error)){ 
											$sql=call_user_func(array($objUsuario,"addPermisos"));
											$stmt->reset();
											if($stmt->prepare($sql)){
												foreach($datos_permisos as $permisos){
													$stmt->bind_param('iiiiiii', $id_usuario, $permisos[0], $permisos[1], $permisos[2], $permisos[3], $permisos[4], $permisos[5]);
													$stmt->execute();
													if($stmt->affected_rows==0){
														$error=1;
														break;
													}
												}
											}
										}
										if(isset($error)){
											$objConex->rollback();
											$response_json['mensaje']='Vaya!, ocurrio un problema al guardar los datos del empleado como usuario';
											$response_json['error']=true;
										}else{
											$objConex->commit();
											$response_json['mensaje'].='<br/>Los permisos para el usuario fueron asignados satisfactoriamente';
										}
										$objConex->autocommit(TRUE);
									}else
										$response_json['mensaje']="Vaya!, ocurrio un problema no se encontro el perfil que selecciono";
								}else
									throw new Exception(date('d/m/Y G:i:s T')." Error#:".$stmt->errno." [ ".$stmt->error." ]");
							}else
								throw new Exception(date('d/m/Y G:i:s T')." Error#:".$stmt->errno." [ ".$stmt->error." ]");
						}else
							throw new Exception(date('d/m/Y G:i:s T')." Error#:".$stmt->errno." [ ".$stmt->error." ]");
					}
				break;
				case 'listado':
					$response_json=array('records'=>0, 'page'=>0, 'total'=>0, 'rows'=>array());
					if($_GET['codigo']=='grid' && isset($_POST['datagrid'])){
						$sql=call_user_func(array($objUsuario,"usuariosEmpleados"));
						if($stmt->prepare($sql)){
							$stmt->execute();
							$result=$stmt->get_result();
							$response_json['records']=$result->num_rows;//Total de Registros
							if($result->num_rows>0){
								$response_json['total']=ceil($response_json['records']/$_POST['rows']);//Total de Paginas
								$response_json['page']=$_POST['page'];//Total de Paginas
								//$tmp_result=$result->mysqli_store_result();
								//$init=(int) ($add_datos['page']-1)*$_POST['rows'];//Mover puntero a la posicion n de las filas
								//$final=(int) $add_datos['page']*$_POST['rows'];
								//$result->data_seek();
								//$registro=$result->fetch_rows();
								while($rows=$result->fetch_assoc()){
									$ceil=array('id'=>$rows['id_usuario'],'cell'=>array_values($rows));
									array_push($response_json['rows'],$ceil);
								}
							}
							$stmt->close();
						}else
							throw new Exception(date('d/m/Y G:i:s T')." Error#:".$stmt->errno." [ ".$stmt->error." ]");
					}					
				break;
				case 'existe':
					if(isset($_POST['id_usuario'], $_POST['login']) && $_GET['codigo']=="login"){
						$sql=call_user_func(array($objUsuario, 'loginDisponible'));
						if($stmt->prepare($sql)){
							$stmt->bind_param('si', $_POST['login'], $_POST['id_usuario']);
							$stmt->execute();
							$result=$stmt->get_result();
							echo ($result->num_rows>0)?'false':'true';
							$stmt->close();
							die();
						}else
							throw new Exception(date('d/m/Y G:i:s T')." Error#:".$stmt->errno." [ ".$stmt->error." ]");
					}
				break;
			}*/
		}
	}catch(Exception $e){
			$objConex->rollback();
		echo json_encode($response_json);
	}
}else{
	//redireccionar al login
	$response=array("error"=>null ,"url"=>'', "access_autorizado"=>false, "mensaje"=>false);
	echo json_encode($response_json);
}
?>