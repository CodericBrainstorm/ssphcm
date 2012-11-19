<?php
class Cargo{
	protected $codcar, $descar, $activo;
	
	public function __construct(){
		$this->codcar=0;
		$this->descar="";
		$this->activo="1";
	}
	
	public function listarCargo(){
		return "SELECT * FROM cargo WHERE activo=?";
	}
}
?>