<?php
class Division{
	protected $coddiv, $desdiv, $activo;
	
	public function __construct(){
		$this->coddiv=0;
		$this->desdiv="";
		$this->activo="1";
	}
	
	public function listarDivision(){
		return "SELECT * FROM division WHERE activo=?";
	}
}
?>