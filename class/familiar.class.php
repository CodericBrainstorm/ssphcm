<?php
require_once("socio.class.php");
class Familiar extends Socio{
	protected $codigo_fam;
	
	
	public function beneficiarios(){
		return "SELECT f.codigo_fam, f.cedfam, f.apefam, f.nomfam, f.fecnacfam, f.edad, f.sexo, f.lugnacfam, f.cod_nexo, n.nom_corto, f.cuota, f.otrocont, f.descont, f.fecingfam FROM familiar AS f LEFT OUTER JOIN nexo AS n ON f.cod_nexo=n.cod_nexo WHERE codigo=?  AND activo!=''";		
	}
	public function listarfamiliares(){
		return "SELECT codigo_fam, cedfam, apefam, nomfam, fecnacfam, edad, sexo, lugnacfam, cod_nexo, cuota, otrocont, descont FROM familiar WHERE codigo=? AND activo!=''";
	}
	public function agregarfam(){
		return "INSERT INTO familiar (codigo, cedfam, apefam, nomfam, sexo, fecnacfam, edad, cod_nexo, lugnacfam, otrocont, descont, cuota, cuotaadic, fecingfam, codigo_fam) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	}
	public function modificarfam(){
		return "UPDATE familiar SET codigo=?, cedfam=?, apefam=?, nomfam=?, sexo=?, fecnacfam=?, edad=?, cod_nexo=?, lugnacfam=?, otrocont=?, descont=?, cuota=?, cuotaadic=?, fecrenov=? WHERE codigo_fam=?";
	
	}
	public function existecodigoFam(){
		return "SELECT codigo FROM familiar WHERE codigo_fam=?";
	}
	public function isotherTit(){
		return "SELECT codigo_fam FROM familiar WHERE cedfam=? AND codigo!=?";
	}
	public function deleteFam(){
		return "UPDATE familiar SET activo='', fecrenov=? WHERE codigo_fam=?";
	}
	public function familiaresTit(){
		return "SELECT codigo_fam FROM familiar WHERE codigo=?";
	}
	/*
	isssssiisssddsi
	codigo, cedfam, apefam, nomfam, sexo, fecnacfam, edad, cod_nexo, lugnacfam, otrocont, descont, cuota, cuotaadi, fecingfam, codigo_fam
	*/
}
?>


