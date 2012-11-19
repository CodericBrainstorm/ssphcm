<?php
class Persona{
	protected $ced_tit; 
	protected $ape_tit; 
	protected $nom_tit; 
	protected $lug_nac_tit; 
	protected $telf; 
	protected $cel; 
	protected $fec_nac_tit; 
	protected $anos; 
	protected $edo_civil; 
	protected $sexo; 
	protected $dir_tit; 
	protected $email; 

	public function __construct($ar= false){
		$this->ced_tit= "";
		$this->ape_tit= "";
		$this->nom_tit= "";
		$this->lug_nac_tit= "";
		$this->telf= "";
		$this->cel= "";
		$this->fec_nac_tit= "";
		$this->anos= "";
		$this->edo_civil= "";
		$this->sexo= "";
		$this->dir_tit= "";
		$this->email= "";

	}
}


class Socio extends Persona{
	protected $id; //
	protected $id_pla; //Instancia clase de Plan
	protected $adicional;//Si posee maternidad
	protected $ingreso; //fecha de ingreso al sistema
	protected $fec_ren; //fecha de renovacion de contrato hcm
	protected $otro_cont; //posee contrato con otra empresa de hcm (SN)
	protected $des_cont; //nombre de la otra empresa de hcm

	/*
	protected $id_parr;// Instancia de clase parroquia
	protected $id_nom; //Instancia clase de Nomina
	protected $id_dir;//Instancia de clase Direccion
	protected $id_sec; //Instancia de clase secretaria
	protected $id_cor; //Instancia de Coordinacion
	protected $id_div; //Instancia de Division
	protected $id_direc; //Instancia de Direccion
	protected $id_ofi; //Instancia de Oficina
	protected $id_dpto; //Instancia de Departamento
	protected $id_car; //Instancia de Cargo
	protected $id_pues; //Instancia de Puesto
	*/
	protected $id_prof; //Instancia de Profesion
	protected $activo; 
	
	public function __construct(){
		$this->id= "";
		$this->id_pla="";
		$this->cuota= "";
		$this->adicional= "";
		$this->ingreso= "";
		$this->fec_ren= "";
		$this->otro_cont="";
		$this->des_cont="";
		$this->fec_ing_lab= "";
		$this->activo= "";
	}
	public function contrato(){
		return "SELECT s.cedtit, s.apetit, s.nomtit, s.lugnactit, s.tlf, s.celular, s.email, s.fecnactit, s.edocivil, s.sexo, s.fecinglab, s.codprof, s.dirtit, s.adicional, s.codnom, tn.desnom, tn.abrev, s.codpla, p.despla, p.cobertura, s.cuota, s.cuotaadi, s.otrocont, s.descont, s.codest, s.codmun, s.codpar, s.coddir, s.codsec, s.codcor, s.coddiv, s.coddirec, s.codofi, s.cod_dpto, s.codcar, s.codpues, s.activo, s.fecren, s.ingreso, u.nomusu, u.apeusu FROM socio AS s LEFT OUTER JOIN planes AS p ON p.codpla=s.codpla LEFT OUTER JOIN tip_nomina AS tn ON tn.codnom=s.codnom LEFT OUTER JOIN usuario AS u ON u.cod_user=s.cod_user WHERE codigo=? ";	
	}
	public function existeCodigo(){
		return "SELECT cedtit, activo FROM socio WHERE codigo=?";
	}
	public function buscarCedula(){
		return "SELECT codigo, cedtit, activo FROM socio WHERE cedtit=?";
	}

	public function consultarCodigo(){
		return "SELECT s.cedtit, s.apetit, s.nomtit, s.lugnactit, s.tlf, s.celular, s.email, s.fecnactit, s.edocivil, s.sexo, s.fecinglab, s.codprof, s.dirtit, s.adicional, s.codnom, s.codpla, p.cobertura, s.cuota, s.otrocont, s.descont, s.codest, s.codmun, s.codpar, s.coddir, s.codsec, s.codcor, s.coddiv, s.coddirec, s.codofi, s.cod_dpto, s.codcar, s.codpues, s.activo FROM socio AS s LEFT OUTER JOIN planes AS p ON p.codpla=s.codpla WHERE codigo=? ";
	}
	public function nuevoSocio(){
		return "INSERT INTO socio (cedtit, apetit, nomtit, lugnactit, tlf, celular, fecnactit, anos, edocivil, sexo, dirtit, codpla, cuota, adicional, cuotaadi, ingreso, otrocont, descont, codest, codmun, codpar, codnom, coddir, codsec, codcor, coddiv, coddirec, codofi, cod_dpto, codcar, codpues, codprof, fecinglab, email, cod_user, codigo) VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	}
	public function modificarSocio(){
		return "UPDATE socio SET cedtit=?, apetit=?, nomtit=?, lugnactit=?, tlf=?, celular=?, fecnactit=?, anos=?, edocivil=?, sexo=?, dirtit=?, codpla=?, cuota=?, adicional=?, cuotaadi=?, fecren=?, otrocont=?, descont=?, codest=?, codmun=?, codpar=?, codnom=?, coddir=?, codsec=?, codcor=?, coddiv=?, coddirec=?, codofi=?, cod_dpto=?, codcar=?, codpues=?, codprof=?, fecinglab=?, email=?, cod_user=? WHERE codigo=?";
	}
}
/*
sssssssisssidsdssssiiiiiiiiiiiiiissi
cedtit, apetit, nomtit, lugnactit, tlf, celular=, fecnactit, anos, edocivil, sexo, dirtit, codpla, cuota, adicional, cuotaadi, ingreso, fecren, otrocont, descont, codest, codmun, codpar, codnom, coddir, codsec, codcor, coddiv, coddirec, codofi, cod_dpto, codcar, codpues, codprof, fecinglab, email, codigo
*/
?>

