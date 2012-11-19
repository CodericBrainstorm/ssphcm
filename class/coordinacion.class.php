<?php
class Coordinacion{
	protected $codcor, $descor, $activo;
	
	public function __construct(){
		$this->codcor=0;
		$this->descor="";
		$this->activo="1";
	}
	
	public function listarCoordinacion(){
		return "SELECT * FROM coordinacion WHERE activo=?";
	}
}
?>