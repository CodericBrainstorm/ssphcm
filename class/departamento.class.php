<?php
class Departamento{
	protected $cod_dpto, $nom_dpto, $activo;
	
	public function __construct(){
		$this->cod_dpto=0;
		$this->nom_dpto="";
		$this->activo="1";
	}
	
	public function listarDepartamento(){
		return "SELECT * FROM departamentos WHERE activo=?";
	}
}
?>