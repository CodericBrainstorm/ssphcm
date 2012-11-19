<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Reporte de Actualizaci&oacute;n</title>
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
		load:['../css/jquery.fancybox.css','../js/jquery.fancybox.js', '../js/jquery.validate.min.js'],
		complete:function(){
			$(document).ready(function(){
				$(":text").datepicker({changeMonth:true, changeYear:true, dayNames:["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin:["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], dayNamesShort:["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNames:["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"], monthNamesShort:["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"], dateFormat:"dd/mm/yy "}).attr({disabled:'disabled'});
				$(":radio").bind('change', function(e){
					$(":text").attr({disabled:$(this).val()=="todos"});
				});
				$.ajax({url:'./solicitud/planes/listado', type:'POST', dataType:'json', context:$("#codpla"), beforeSend:function(){
					$(this).children(':not(:first-child)').remove(); $(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && $.isArray(data.obj_form)){ var _this=this;
						$.each(data.obj_form, function(e,a){ $(_this).append($('<option/>',a)); });
					}
				}
				});
				$.ajax({url:'./solicitud/nominas/listado', type:'POST', dataType:'json', context:$("#codnom"), beforeSend:function(){
					$(this).children(':not(:first-child)').remove(); $(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && $.isArray(data.obj_form)){ 
						var _this=this;
						$.each(data.obj_form, function(e,a){ $(_this).append($('<option/>',a)); });
					}
				}});
				$("#btEnviar").button().bind('click', function(e){
					e.preventDefault(); e.stopPropagation();
					$.ajax({});
				});
			});
		}
	}
]);
</script>
<style type="text/css">
body{ font-size:13px; font-family:Arial, Helvetica, sans-serif;  }
legend{
	font-weight:bold; color:#069;
}
label.texth{
	font-weight:bold; font-size:14px;
}
</style>
</head>
<body>
<div style="width:450px;">
<fieldset>
<legend>Reporte de Afiliados (Titulares y Beneficiarios)</legend>
<form>
	<div style="float:left; width:450px;">
		<input type="radio" name="tipo_report" value="todos" checked ><label>Todos</label>
    </div>
    <div style="float:left; width:450px;">
    <input type="radio" name="tipo_report" value="fecha" >
    <label>Fecha de Renovaci&oacute;n:</label> 
    Desde:<input type="text" id="fecdesde" name="fecdesde" value="" style="width:80px;" > 
    Hasta:<input type="text" id="fechasta" name="fechasta" value="" style="width:80px;" >
    </div>
    <div style="float:left; width:450px;">
    	<label class="texth">N&oacute;mina de Personal</label>
    </div>
    <div style="float:left; width:450px; ">
    	<select id="codnom" name="codnom" style="width:370px;"><option value="" selected>Seleccionar...</option></select>
    </div>
    <div style="float:left; width:450px;">
    	<label class="texth">Plan de Cobertura</label>
    </div>
    <div style="float:left; width:450px; ">
		<select id="codpla" name="codpla" style="width:200px;"><option value="" selected>Seleccionar...</option></select>
    </div>
    <div style="float:left; width:450px;">
    	<label class="texth">Mostrar Familiares?</label><input type="checkbox" id="view_fam" name="view_fam" >
    </div>
    <div style="float:left; width:450px; text-align:center;">
    	<input type="submit" name="btEnviar" id="btEnviar" value="Consultar" >
    </div>
</form>
</fieldset>
</div>
</body>
</html>
