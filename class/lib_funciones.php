<?php
spl_autoload_extensions(".class.php, .php, .inc");
spl_autoload_register(function($namefile){
	$appendfile="../class/".strtolower($namefile).".class.php";
	if(file_exists($appendfile))
		require_once($appendfile);
	else
		throw new Exception("No se pudo cargar el archivo ".$appendfile);
});

function Ghidden($name_element, $value=''){
	echo '<input type="hidden" name="'.$name_element.'" id="'.$name_element.'" value="'.$value.'" />';
}
function GText($name_element, $value=''){
	echo '<input type="text" name="'.$name_element.'" id="'.$name_element.'" value="'.$value.'" />';
}
function idleLogin(&$accesslast, $timeidle){
	if((time()-$accesslast)>$timeidle)
		$accesslast=NULL;
	else
		$accesslast=time();
}
function timeInactividad(&$ultimoacceso){
	$ahora=date("Y-n-j H:i:s");
	$tiempo_inactivo=(strtotime($ahora)-strtotime($ultimoacceso));
	$ultimoacceso=$ahora;
	if($tiempo_inactivo>=SEGUNDOS_INACTIVOS){
		$_SESSION['autorizado']=$_SESSION['id_usuario']=$ultimoacceso=NULL;
		session_destroy();
	}
}
function Edad($fecnac, $anio_curso="", $mes_curso="", $dia_curso=""){ 
	list($dia,$mes,$anio)=explode("/",$fecnac);
	$anio_actual=(!empty($anio_curso))?$anio_curso:date("Y");
	$mes_actual=(!empty($mes_curso))?$mes_curso:date("m");
	$dia_actual=(!empty($dia_curso))?$dia_curso:date("d");
	$anioDif=$anio_actual-$anio;//2013-1983:30
	$diaDif=$dia_actual-$dia;//1-24:-23
	$mesDif=$mes_actual-$mes;//6-10:4
	if($diaDif<0 && $mesDif<0)//
		$anioDif--;
	return $anioDif;
}
?>