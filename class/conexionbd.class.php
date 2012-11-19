<?php
require_once("../config.php");
class Conexionbd extends MySQLi {
	public function __construct(){
		@parent::connect(NAMESERVER, USERDB, PASSWDB, NAMEDB);
		if($this->connect_errno)
			throw new Exception(date('d/m/Y G:i:s T')." Error#:".$this->connect_errno." [ ".$this->connect_error." ]");
	}
		
	public function __destruct(){
		if(!$this->connect_errno)
			$this->close();
	}
	
	public function logError(){
		if($this->errno)
			throw new Exception(date('d/m/Y G:i:s T')." Error#:".$this->errno." [ ".$this->error." ]");
	}
	public function prepareLike(){
		//{eq=equal, ne=notequal, lt=menorque, le=menorigualque, gt=mayorque, ge=mayorigualque, }
		$expr_search=array('eq'=>'%s = ?','ne'=>"%s != ? ",'lt'=>"%s < ?",'le'=>'%s <= ?','gt'=>'%s > ?','ge'=>'%s >= ?','bw'=>"%s LIKE ?",'bn'=>"%s NOT LIKE ?",'ew'=>'%s LIKE ?','en'=>'%s NOT LIKE ?','cn'=>'%s LIKE ?','nc'=>'%s NOT LIKE ?');
		$findString=array('bw'=>'%s%%','bn'=>'%s%%','ew'=>'%%%s','en'=>'%%%s','cn'=>'%%%s%%','nc'=>'%%%s%%');
		return $searchLike;
	}
}

?>