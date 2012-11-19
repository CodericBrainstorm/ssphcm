<?php
class Persona {
	protected $cedula;
	protected $apellido;
	protected $nombre;
	protected $fecha_nac;
	protected $sexo;
	protected $edo_civil;
	protected $direccion_habita;
	protected $telefono;
	protected $tlf_movil;
	
	public function __construct(){
		$this->cedula=NULL;
		$this->apellido=NULL;
		$this->nombre=NULL;
		$this->fecha_nac=NULL;
		$this->sexo=NULL;
		$this->edo_civil=NULL;
		$this->direccion_habita=NULL;
		$this->telefono=NULL;
		$this->tlf_movil=NULL;
	}
	
	public function setCedula($cedula){ $this->cedula=(int) $cedula;}
	public function getCedula(){ return $this->cedula;}
	public function setApellidos($apellido){ $this->apellido= trim(strtoupper($apellido));}
	public function getApellidos(){ return $this->apellido;}
	public function setNombres($nombre){ $this->nombre=trim(strtoupper($nombre));}
	public function getNombres(){ $this->nombre;}
	public function setFecNac($fecha_nac){ $this->fecha_nac=$fecha_nac;}
	public function getFecNac(){ $this->fecha_nac;}
	public function setSexo($sexo){ $this->sexo=$sexo;}
	public function getSexo(){ $this->sexo;}
	public function setEdoCivil($edo_civil){ $this->edo_civil=$edo_civil;}
	public function getEdoCivil(){ $this->edo_civil;}
	public function setDireccion($direccion_habita){ $this->direccion_habita=trim($direccion_habita);}
	public function getDireccion(){ $this->direccion_habita;}
	public function setTelefono($telefono){ $this->telefono=$telefono;}
	public function getTelefono(){ $this->telefono;}
	public function setTlfMovil($tlf_movil){ $this->tlf_movil=$tlf_movil;}
	public function getTlfMovil(){ $this->tlf_movil;}
	
	
}
?>