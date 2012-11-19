<?php
class Profesion{
	protected $codigo, $descripcion, $activo;
	
	public function __construct(){
		$this->codigo=0;
		$this->descripcion="";
		$this->activo="0";
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
	
	public function agregarPlan(){
		return "INSERT INTO planes (despla, cobertura, mto_maternidad, mto_amb, mto_prenat, mto_medic, mto_lentes, mto_odonto, indem_muer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
	}
	
	public function modificarPlan(){
		return "UPDATE planes SET despla=?, cobertura=?, mto_maternidad=?, mto_amb=?, mto_prenat=?, mto_medic=?, mto_lentes=?, mto_odonto=?, indem_muer=?, activo=? WHERE codpla=?";
	}
	
	public function eliminarPlan(){
		return "UPDATE planes SET activo=? WHERE codpla=?";
	}
	
	public function consultarCodigo(){
		return "SELECT * FROM planes WHERE codpla=?";
	}
	public function consultarDescripcion(){
		return "SELECT * FROM planes WHERE despla=?";
	}
	
	public function existeDescripcion(){
		return "SELECT codpla FROM planes WHERE despla=? AND codpla!=?";
	}
	
	public function listarProfesion(){
		return "SELECT * FROM profesion WHERE activo=?";
	}
}
##try{
##	$objPlan=new Plan();
##	$objPlan->__get("codigos");
##}catch(Exception $e){
##	echo $e->getMessage();
##}
?>