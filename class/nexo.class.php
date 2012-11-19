<?php
class Nexo{
	protected $cod_nexo, $nom_corto, $nom_largo;
	
	public function __construct(){
		$this->cod_nexo=0;
		$this->nom_corto=$this->nom_largo="";
	}

	public function __get($atributo){
		if(isset($this->$atributo))
			return $this->$atributo;
		else
			throw new Exception('Propiedad desconocida '.$atributo);
	}
	public function __set($atributo, $valor){
		if(isset($this->$atributo))
			$this->$atributo=$valor;
		else
			throw new Exception('Propiedad desconocida '.$atributo);
	}
	
	public function listarNexo(){
		return "SELECT * FROM nexo";
	}
}
##try{
##	$objPlan=new Plan();
##	$objPlan->__get("codigos");
##}catch(Exception $e){
##	echo $e->getMessage();
##}
?>