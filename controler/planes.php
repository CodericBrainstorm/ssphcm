<?php
session_start();
session_name("ssphcm");
//error_reporting(E_ALL); 
//ini_set('display_errors',1);
$_SESSION['autorizado']=$_SESSION['id_usuario']=$_SESSION['timestamp']=time();
if(isset($_SESSION['autorizado'], $_SESSION['id_usuario'])){
	try{
		require_once("../config.php");
		require_once("../class/lib_funciones.php");
		idleLogin($_SESSION['timestamp'], IDLE_TIMES);
		if(isset($_SESSION['timestamp'])){
			$response_json=array('redirect'=>'false','error'=>'false','success'=>'false', 'mensaje'=>'','obj_form'=>array(), 'url_redirect'=>'false', 'json_response'=>'');
			$metodos=array("datagrid");
			if(isset($_GET['evento']) && in_array($_GET['evento'], $metodos)){
				$objConnex= new Conexionbd();
				$objPlan= new Planes();
				switch($_GET['evento']){
					case "datagrid":
						$sql=call_user_func(array($objPlan, "listarPlanes"));
						$stmt=$objConnex->stmt_init();
						if($stmt->prepare($sql)){
							$stmt->execute();
							$result=$stmt->get_result();
							$datagridjson=array("total"=>0,"page"=>0,"records"=>0,"rows"=>array());
							$datagridjson['records']=$result->num_rows;
							$datagridjson['page']=(int) $_POST['page'];
							if($datagridjson['records']>0){
								$datagridjson['total']=ceil($datagridjson['records']/$_POST['rows']);
								while($rows=$result->fetch_assoc()){
									$ceill=array('id'=>$rows['codpla'],'cell'=>array_values($rows));
									array_push($datagridjson['rows'], $ceill);
								}
							}
							$response_json['json_response']=$datagridjson;
						}
						echo json_encode($datagridjson);
					break;
				}
			}
		}else
			header("Location: ./login2.html");
	}catch(Exception $e){
		echo $e->getMessage();
	}
}else
	header("Location: ./login.html");
?>