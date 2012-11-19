<?php
class Nominas{
	protected $codnom, $desnom, $abrev, $con_benef, $activo;
	
	public function __construct(){
		$this->codnom=$this->con_benef=0;
		$this->desnom=$this->abrev="";
		$this->activopar="1";
	}
	
	public function aceptaFamiliares(){
		return "SELECT con_benef FROM tip_nomina WHERE activo=? AND codnom=?";
	}
	public function listarNominas(){
		return "SELECT * FROM tip_nomina WHERE activo=?";
	}
}
##try{
##	$objPlan=new Plan();
##	$objPlan->__get("codigos");
##}catch(Exception $e){
##	echo $e->getMessage();
##}
?>