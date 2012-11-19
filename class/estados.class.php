<?php
class Estados{
	protected $codigo, $descripcion, $activo;
	
	public function __construct(){
		$this->codigo=0;
		$this->descripcion="";
		$this->activo="1";
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
	
	public function listarEstados(){
		return "SELECT * FROM estados WHERE activo=?";
	}
}
##try{
##	$objPlan=new Plan();
##	$objPlan->__get("codigos");
##}catch(Exception $e){
##	echo $e->getMessage();
##}
?>