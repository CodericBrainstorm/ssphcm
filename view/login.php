<?php
session_start();
session_name("ssphcm");
require_once("../config.php");
require_once("../class/lib_funciones.php");
idleLogin($_SESSION['timestamp'], IDLE_TIMES);
if(!isset($_SESSION['timestamp'], $_SESSION['autorizado'], $_SESSION['id_usuario'])){
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Acceso</title>
<link rel="stylesheet" type="text/css" href="../css/flick/jquery-ui-1.9.0.custom.min.css" />
<script type="text/javascript" language="javascript" src="../js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui-1.9.0.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" language="javascript">
jQ=$.noConflict();
jQ(document).ready(function(){
	jQ("#login").attr('autocomplete','off').focus();
	jQ("#b_saved, #b_reset").button();
	var formValidate=jQ("#form_login").validate({ 
		rules:{	login:{required:true},  clave:{required:true} }, 
		messages:{ login:{required:'Escriba el login del usuario, es obligatorio'},  clave:{ required:"Escriba la clave de acceso, es obligatorio" } }, 
		onSubmit:false, errorContainer:'#box_validate', errorLabelContainer:'#box_validate ul', wrapper:'li',
		showErrors:function(errorMap, errorList){ 
			if(!jQ.isEmptyObject(errorMap))
				jQ("div#box_warning").css('display','none');
			this.defaultShowErrors(); 
		},
		submitHandler:function(form){
			jQ.ajax({url:'./login/acceso/autorizado', data:jQ("#form_login").serialize(),  dataType:'json', type:'POST', 
				beforeSend:function(){
					jQ("div#box_warning > p > span:last-child").empty();
					jQ("div#box_warning").css('display','none');
					jQ("#b_saved, #b_reset").button({disabled:true});
				},
				complete:function(jqXHR, textStatus){ jQ("#b_saved, #b_reset").button({disabled:false}); },
				error:function(jqXHR, textStatus, errorThrown){
					
				},
				success:function(data, textStatus, jqXHR){
					if(data.success && jQ.trim(data.mensaje)!=''){
							jQ("div#box_warning > p > span:last-child").text(data.mensaje);
							jQ("div#box_warning").css('display','block');					
					}else
						if(data.autorize){
							parent.jQ.fancybox.close();
						}
				}
			});
		} 
	});	
});
</script>
<style type="text/css">
body{
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
}
span.ui-button-text{
	font-size:12px;
}
div.rows{
	height:32px;
	float:left;
}
div.fLabel{
	width:85px;
}
div.fInputs{
	width:265px;
}
input#b_saved{
	background:url(img/login.png) no-repeat scroll left center transparent;
	
}
input#b_reset {
	background:url(img/edit_clear.png) no-repeat scroll left center transparent;
}
div#box_validate{
	display:none;
	font-size:13px;
	color:#000;
}
.error{
	color:#000;
}
</style>
</head>

<body>
<div id="pagefrom" style="width:350px; padding:15px 15px 15px 15px;" class="ui-widget ui-widget-content ui-corner-all">
	<form id="form_login" name="form_atc" action="" method="get" >
	<div class="rows fLabel"><label for="login" style="font-size:16px; font-weight:bold;">Login:</label></div>
	<div class="rows fInputs"><input type="text" name="login" id="login" placeholder="Usuario" style="width:108px;" /> &nbsp;</div>
    <div class="rows fLabel"><label for="clave" style="font-size:16px; font-weight:bold;">Clave:</label></div>
    <div class="rows fInputs"><input type="password" name="clave" id="clave" placeholder="Clave" style="width:108px;" /> &nbsp;</div>
    <div class="rows fLabel" style="text-align:right"><input type="checkbox" id="recordarme" name="recordarme"></div>
    <div class="rows fInputs"><label for="recordarme">Recordarme</label></div>

    <div id="box_warning" class="ui-state-highlight ui-corner-all" style="width:100%; display:none; float:left; margin-bottom:12px;">
        <p style="margin-bottom: 5px; margin-top: 5px;">
        	<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
        	<strong>Aviso:</strong>
            <span></span>
        </p>
    </div>
        
	<div id="bar-tools" style="width:350px; text-align:right">
		<input type="reset" class="geek_button" name="b_reset" id="b_reset" value=" &nbsp;Cancelar" />
		<input type="submit" class="geek_button" name="b_saved" id="b_saved" value=" &nbsp;Entrar" />
	</div>    
    </form>
</div>
<p>
<div style="width:100%">
    <div id="box_validate" class="ui-state-error ui-corner-all" style="width:100%; float:left; margin-bottom:12px;">
        <p style="margin-bottom: 5px; margin-top: 5px; color:#000;">
        	<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-alert"></span>
        	<strong>Aviso:</strong> Corriga los siguientes campos
        </p>
        <ul style="padding-left: 20px; margin-top: 5px;"/>
    </div>
</div>
</p>
    Desarrollado por: Oficinas en L&iacute;nea Falc&oacute;n C.A.
</body>
</html>
<?php
}else{
	$response_json=array();
	echo json_encode($response_json);
}
?>