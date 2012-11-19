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
				$objConnex= new Conexionbd();
				switch($_GET['evento']){
					
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