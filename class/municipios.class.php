<?php
require_once("estados.class.php");
class Municipios extends Estados{
	protected $codmun, $desmun, $activomun;
	
	public function __construct(){
		$this->codmun=0;
		$this->desmun="";
		$this->activomun="1";
	}
	
	public function listarMunicipios(){
		return "SELECT * FROM municipios WHERE codest=? AND activo=?";
	}
}
##try{
##	$objPlan=new Plan();
##	$objPlan->__get("codigos");
##}catch(Exception $e){
##	echo $e->getMessage();
##}
?>