<?php 
session_start();
session_name("ssphcm");
require_once("../config.php");
require_once("../class/lib_funciones.php");
idleLogin($_SESSION['timestamp'], IDLE_TIMES);
$response=array('error'=>false, 'success'=>false, 'mensaje'=>'', 'data_object'=>array(),'autorize'=>false);
if(!isset($_SESSION['timestamp'], $_SESSION['autorizado'], $_SESSION['id_usuario'])){
	try{
		if(isset($_POST['login'], $_POST['clave']) && !empty($_POST['login']) && !empty($_POST['clave'])){
			$objConex= new Conexionbd();
			$sql="SELECT cod_user, password FROM usuario WHERE login=?";
			if($stmt=$objConex->prepare($sql)){
				$stmt->bind_param('s',$_POST['login']);
				$stmt->execute();
				$result=$stmt->get_result();
				$response['success']=true;
				$response['mensaje']="Opps, nombre de usuario no existe";
				if($result->num_rows==1){
					$rows=$result->fetch_object();
					if($rows->password!=md5(sha1(trim($_POST['clave']))))
						$response['mensaje']="Opps, la clave que indico es incorrecta";
					else{
						$response['mensaje']="";
						$response['autorize']=$_SESSION['autorizado']=true;
						$_SESSION['id_usuario']=$rows->cod_user;
						$_SESSION['timestamp']=time();
					}
				}
				$stmt->close();
			}
		}
	}catch(Exception $e){
		echo $e->getMessage();
	}
}
echo json_encode($response);
?>