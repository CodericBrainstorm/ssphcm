<?php
class Oficina{
	protected $codofi, $desofi, $activo;
	
	public function __construct(){
		$this->codofi=0;
		$this->desofi="";
		$this->activo="1";
	}
	
	public function listarOficina(){
		return "SELECT * FROM oficina WHERE activo=?";
	}
}
?>