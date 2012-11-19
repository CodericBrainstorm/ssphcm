<?php 
session_start();
session_name("inventario");
session_destroy();
foreach($_SESSION as $indice){
	unset($_SESSION[$indice]);
	$_SESSION[$indice]=NULL;
}
header();
?>