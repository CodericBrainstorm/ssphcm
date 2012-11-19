<?php
class Direccionsup{
	protected $coddir, $desdir, $activo;
	
	public function __construct(){
		$this->coddir=0;
		$this->desdir="";
		$this->activo="1";
	}
	
	public function listarDireccionsup(){
		return "SELECT * FROM direccion_superior WHERE activo=?";
	}
}
##try{
##	$objPlan=new Plan();
##	$objPlan->__get("codigos");
##}catch(Exception $e){
##	echo $e->getMessage();
##}
?>