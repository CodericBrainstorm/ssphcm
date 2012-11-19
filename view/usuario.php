<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<script type="text/javascript" language="javascript" src="../js/modernizr.custom.js"></script>
<script type="text/javascript" language="javascript">
Modernizr.load([
		{
			load:['http://code.jquery.com/jquery-1.8.2.min.js', 'http://code.jquery.com/ui/1.9.0/jquery-ui.js', 'http://code.jquery.com/ui/1.9.0/themes/flick/jquery-ui.css'], 
			callback:function(){
			if(!window.jQuery)
				Modernizr.load(['../js/jquery-1.8.2.min.js','../js/jquery-ui-1.9.0.min.js','../css/flick/jquery-ui-1.9.0.custom.min.css']);
			}
		},{
			load:['../js/jquery.validate.min.js'],
			complete:function(){
				jQ=$.noConflict();
				jQ(document).ready(function(){
					jQ(":reset").button({icons:{primary:'ui-icon-disk'}}).next().button({icons:{primary:'ui-icon-cancel'}});
					jQ(":reset").bind('click', function(e){
						e.preventDefault();
						e.stopPropagation();
						jQ(":text:not('#cedusu'), :password, #edocivil, #sexo, #observa, #dirusu").each(function(index, Element){ jQ(Element).val('').attr({disabled:'disabled'}); });
					}).trigger('click');
					jQ("#cedusu").focus().bind('blur', function(e){
						if(jQ(this).val()!=""){
							jQ.ajax({url:'./usuario/consultar/cedula', data:jQ("#cedusu, #cod_user").serialize(), dataType:'json', type:'POST', 
								success:function(data, textStatus, jqXHR){
									
								}
							});
						}
					});
				});
			}
		}
]);
</script>
<style type="text/css">
body{ font-size:13px; font-family:Arial, Helvetica, sans-serif;  }

label{ font-weight:bold; color:#C30;}
div.cells{
	height:25px;
	float:left;
}
div.label1{ width:160px; }
</style>
</head>

<body>
<div>
	<div id"box_form" style="width:500px; height:400px; padding:10px;" class="ui-widget ui-widget-content ui-corner-all" >
	    <form method="post" action="#" id="form_usuario" name="form_usuario">
		<div class="cells label1"><label>C&eacute;dula:</label></div>
	    <div class="cells" style="width:340px;">
        	<input type="hidden" id="cod_user" name="cod_user" value="">
            <input type="text" id="cedusu" name="cedusu" value="" style="width:100px;">
        </div>
    	<div class="cells label1"><label>Apellidos:</label></div>
	    <div class="cells" style="width:340px;">
        	<input type="text" id="apeusu" name="nomusu" value="">
        </div>
    	<div class="cells label1"><label>Nombres:</label></div>
	    <div class="cells" style="width:340px;">
        	<input type="text" id="nomusu" name="nomusu" value="">
        </div>
    	<div class="cells label1"><label>Lugar de Nacimiento:</label></div>
	    <div class="cells" style="width:340px;">
        	<input type="text" id="lugnacusu" name="lugnacusu" value="" style="width:240px;">
        </div>
    	<div class="cells label1"><label>Fecha de Nacimiento:</label></div>
	    <div class="cells" style="width:120px;"><input type="text" id="fecnacusu" name="fecnacusu" value="" style="width:90px;"></div>
    	<div class="cells" style="width:60px;"><label>Edad:</label></div>
	    <div class="cells" style="width:160px;"><input type="text" id="edad" name="edad" value="" style="width:45px;" ></div>
	    <div class="cells label1"><label>Sexo:</label></div>
    	<div class="cells" style="width:120px;">
        <select id="sexo" name="sexo" style="width:100px;">
	    	<option value="" selected>Seleccionar</option>
    	    <option value="M">Masculino</option>
	        <option value="F">Femenino</option>
    	</select></div>
	    <div class="cells" style="width:95px;">
        	<label>Estado Civil:</label>
        </div>
    	<div class="cells" style="width:125px;">
        <select id="edocivil" name="edocivil" style="width:110px;">
	    	<option value="" selected>Seleccionar</option>
    	    <option value="S">Soltero</option>
        	<option value="C">Casado</option>
	        <option value="D">Divorciado</option>
    	    <option value="V">Viudo</option>
        	<option value="O">Concubino</option>
	    </select>
        </div>
    	<div class="cells label1" style="height:60px;"><label>Direcci&oacute;n:</label></div>
        <div class="cells" style="height:60px; width:340px;"><textarea id="dirusu" name="dirusu" cols="20" rows="2" style="resize:none; height:48px; width:280px;"></textarea></div>
	    <div class="cells label1"><label>Login:</label></div>
        <div class="cells" style="width:340px;"><input type="text" id="login" name="login" value="" style="width:110px;"></div>
    	<div class="cells label1"><label>Contrase&ntilde;a:</label></div>
        <div class="cells" style="width:340px;"><input type="password" id="login" name="login" value=""></div>
	    <div class="cells label1"><label>Repetir Contrase&ntilde;a:</label></div>
        <div class="cells" style="width:340px;"><input type="password" id="login" name="login" value=""></div>
    	<div class="cells label1" style="height:60px;" ><label>Observaci&oacute;n:</label></div>
	    <div class="cells" style="width:340px; height:60px;">
        	<textarea id="observa" name="observa" rows="2" cols="45" style="resize:none; width:280px; height:48px;"></textarea>
        </div>
        <div class="cells" style="width:500px; height:40px; text-align:center">
        	<input type="reset" id="btreset" name="btreset" value="Cancelar">&nbsp;
            <input type="submit" id="btsaved" name="btsaved" value="Guardar" >
        </div>
        </form>
	</div>
</body>
</html>