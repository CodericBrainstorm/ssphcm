<?php
require_once("planes.class.php");
class Primas extends Planes{
	protected $mto_prima;
	protected $mto_adicional;
	
	public function __construct(){
		$this->mto_prima=0.00;
		$this->mto_adicional=0.00;
	}
	
	public function primasPlan(){
		return "SELECT * FROM primas WHERE codpla=? AND (codnom=? OR codnom=0)";
	}
	
	public function validBenef(&$obJSON, $nexo, $edad, $qcantidad=0) {
		switch($nexo){
			case 5://Hijo(a)
				if($edad>18){
					$obJSON['mensaje']="Los hijos e hijas con edad mayor a 18 debera consignar documentos\nPresione Aceptar si autoriza la inscripcion";
					$obJSON['isvalid']=false;
					$obJSON['autorizar']=true;
				}
			break;
			default://Conyugue, padre, madre
				if($qcantidad>0){
					$obJSON['mensaje']="Ya existe otro familiar con dicho parentesco";
					$obJSON['isvalid']=false;
				}
			break;
		}		
	}
	public function mtoPrima($aprimas, $sexo, $nexo, $edad, $adicional, $sexofam=""){
		$primas_sexo=array();
		foreach($aprimas as $indice => $registro)
			if($registro['sexo']==$sexo || empty($registro['sexo']))
				array_push($primas_sexo, $registro);
		if(count($primas_sexo)>0){
			$primas_nexo=array();
			foreach($primas_sexo as $i => $registro)
				if($registro['cod_nexo']==$nexo || $registro['cod_nexo']==0)
					array_push($primas_nexo, $registro);
			if(count($primas_nexo)>0){
				foreach($primas_nexo as $i => $registro)
					if($registro['edad_max']>0){
						if($edad>$registro['edad_min'] && $edad<=$registro['edad_max']){
							$this->mto_prima=number_format($registro['mtoprima'],2,'.',',');
							break;
						}
					}else{
						$this->mto_prima=number_format($registro['mtoprima'],2,'.',',');
						break;
					}
			}
		}
		if(($sexo=="F" || ($sexo=="M" && $sexofam=="F")) && ($nexo==0 || $nexo==1) && $adicional=="S"){
			$this->mto_adicional=45.00;
		}
	}
}
?>