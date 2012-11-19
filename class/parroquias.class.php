<?php
require_once("municipios.class.php");
class Parroquias extends Municipios{
	protected $codpar, $despar, $activopar;
	
	public function __construct(){
		$this->codpar=0;
		$this->despar="";
		$this->activopar="1";
	}
	
	public function listarParroquias(){
		return "SELECT * FROM parroquias WHERE codmun=? AND activo=?";
	}
}
##try{
##	$objPlan=new Plan();
##	$objPlan->__get("codigos");
##}catch(Exception $e){
##	echo $e->getMessage();
##}
?>