<?php
class Direccion{
	protected $coddirec, $desdirec, $activo;
	
	public function __construct(){
		$this->coddirec=0;
		$this->desdirec="";
		$this->activo="1";
	}
	
	public function listarDireccion(){
		return "SELECT * FROM direccion WHERE activo=?";
	}
}
?>