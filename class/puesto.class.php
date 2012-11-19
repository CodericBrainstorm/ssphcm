<?php
class Puesto{
	protected $codpues, $despues, $activo;
	
	public function __construct(){
		$this->codpues=0;
		$this->despues="";
		$this->activo="1";
	}
	
	public function listarPuesto(){
		return "SELECT * FROM puesto WHERE activo=?";
	}
}
?>