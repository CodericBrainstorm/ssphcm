<?php
require_once("empleado.class.php");
class Usuario extends empleado{
	protected $login;
	protected $clave;
	
	public function __construct(){}
	
	public function agregusuario(){
	  return "INSERT INTO usuario (id_empleado, id_perfil, login, password, activo, id_usuario) VALUES (?, ?, ?, ?, ?, ?)";
	}
	public function actualizarusuario(){
		return "UPDATE usuario SET id_empleado=?, id_perfil=?, login=?, password=?, activo=? WHERE id_usuario=?";
	}

	public function access_login(){
		return "SELECT id_usuario, password, is_root FROM usuario WHERE login=? AND activo=1";
	}
	public function eliminarusuario(){
		return "DELETE FROM usuario WHERE id_usuario=?";
	}
	
	public function viewUsuario(){
		return "SELECT * FROM usuario WHERE id_usuario=?";
	}
	public function consultarCedula(){
		return "SELECT * FROM usuario WHERE cedusu=?";
	}
	
	public function existsUsuarioEmpleado(){
		return "SELECT * FROM usuario WHERE id_empleado=? AND id_usuario!=?";
	}
	public function cedulausuarioexist(){
		return "SELECT ced_usuario FROM usuario WHERE ced_usuario=? AND id_usuario!=?";
	}
		
	public function listarUsuario(){
		return "SELECT * FROM usuario WHERE is_root='0'";
	}
	
	public function cantidadUsuario(){
		return "SELECT COUNT(id_usuario) FROM usuario WHERE is_root='0'";
	}
	
	public function loginDisponible(){
		return "SELECT id_usuario FROM usuario WHERE login=? AND id_usuario!=?";
	}

	public function eliminarPermisos(){
		return "DELETE FROM permisos_usuarios WHERE id_usuario=?";
	}
	
	public function addPermisos(){
		return "INSER INTO permisos_usuarios (id_usuario, id_ventana, event_open, event_view, event_add, event_update, event_delete) VALUES (?, ?, ?, ?, ?, ?, ?)";
	}
	
	public function usuariosEmpleados(){
		return "SELECT u.id_usuario, e.ced_empleado, e.apenom_empleado, u.login, e.activo FROM usuario AS u LEFT OUTER JOIN empleados AS e ON e.id_empleado=u.id_empleado WHERE u.is_root=0";
	}
	
	public function viewUsuarioEmpleado(){
		return "SELECT e.id_empleado, e.ced_empleado, e.apenom_empleado, u.id_perfil, u.login, u.activo FROM usuario AS u LEFT OUTER JOIN empleados AS e ON e.id_empleado=u.id_empleado WHERE u.id_usuario=?";
	}
}

?>