<?php
class Secretaria{
	protected $codsec, $dessec, $activo;
	
	public function __construct(){
		$this->codsec=0;
		$this->dessec="";
		$this->activo="1";
	}
	
	public function listarSecretaria(){
		return "SELECT * FROM secretaria WHERE activo=?";
	}
}
##try{
##	$objPlan=new Plan();
##	$objPlan->__get("codigos");
##}catch(Exception $e){
##	echo $e->getMessage();
##}
?>