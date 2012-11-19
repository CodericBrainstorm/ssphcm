<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Registro de Plan</title>
<script type="text/javascript" language="javascript" src="../js/modernizr.custom.js"></script>
<script type="text/javascript" language="javascript">
Modernizr.load([{load:['http://code.jquery.com/jquery-1.8.2.min.js', 'http://code.jquery.com/ui/1.9.0/jquery-ui.js', 'http://code.jquery.com/ui/1.9.0/themes/flick/jquery-ui.css'], callback:function(){
			if(!window.jQuery)
				Modernizr.load(['../js/jquery-1.8.2.min.js', '../js/jquery-ui-1.9.0.min.js', '../css/flick/jquery-ui-1.9.0.custom.min.css']);
	}}, {load:['../css/tipsy.css','../js/jquery.validate.min.js', '../js/jquery.tipsy.js', '../js/jquery.formatCurrency-1.4.0.min.js', '../js/i18n/jquery.formatCurrency.es-VE.js'], complete:function(){
		jQ=$.noConflict();
		jQ(document).ready(function(){
			jQ(":text").attr('autocomplete','off');
			jQ.each(jQ(":text.decimal"), function(e){
				jQ(this).val(0).formatCurrency({symbol:''});
				jQ(this).bind('focus', function(){ jQ(this).select(); });
				jQ(this).bind('blur', function(){ jQ(this).formatCurrency().toNumber(); });
			});
			jQ(":button#bt_close").bind('click', function(e){ parent.jQ.fancybox.close(); });
			
			var formValidate=jQ("#form_plan").validate({ onSubmit:false, wrapper:"li", errorContainer:'div#boxerror', errorLabelContainer:'div#containererror'
				,submitHandler:function(form){
					jQ.ajax({ url:"./empresa/guardar/codigo", dataType:'json', data:jQ(form).serialize(), type:'POST',
						beforeSend:function(jqXHR, settings){},error:function(jqXHR, textStatus, errorTrown){},
						complete:function(jqXHR, textStatus){}, success:function(data, textStatus, jqXHR){}
					});
				},rules:{ 
					despla:{required:true}, 
					cobertura:{required:true, min:0, number:true}, mto_maternidad:{required:true, min:0, number:true}, 
					mto_amb:{required:true, min:0, number:true}, mto_prenat:{required:true, min:0, number:true}, 
					mto_medic:{required:true, min:0, number:true}, mto_lentes:{required:true, min:0, number:true}
				},messages:{
					despla:{required:'Indique una descripcion del plan'}, 
					cobertura:{required:"Indique el monto de la cobertura", min:jQ.validator.format("El monto minimo debe ser {0}"), number:"Ingrese un valor numerico (Ejm 1.00)"}, 
					mto_maternidad:{required:"Indique el monto por maternidad", min:jQ.validator.format("El monto minimo debe ser {0}"), number:"Ingrese un valor numerico (Ejm 1.00)"}, 
					mto_amb:{required:"Indique el monto para gastos por ambulatorio", min:jQ.validator.format("El monto minimo debe ser {0}"), number:"Ingrese un valor numerico (Ejm 1.00)"}, 
					mto_prenat:{required:"Indique el monto para gastos por Maternidad", min:jQ.validator.format("El monto minimo debe ser {0}"), number:"Ingrese un valor numerico (Ejm 1.00)"}, 
					mto_medic:{required:"Indique el monto para gastos por Medicamentos", min:jQ.validator.format("El monto minimo debe ser {0}"), number:"Ingrese un valor numerico (Ejm 1.00)"}, 
					mto_lentes:{required:"Indique el monto para gastos por Lentes", min:jQ.validator.format("El monto minimo debe ser {0}"), number:"Ingrese un valor numerico (Ejm 1.00)"}
				}
	});			
		});
	}}]);
</script>
<style type="text/css">
body{
	font-size:12px;
	font-family:Arial, Helvetica, sans-serif;
}
div{ height:25px; float:left; }
div.label{
	width:185px;
}
#form-container{
	width:670px;
}
.decimal{
	text-align:right;
	width:120px;
}
li{
	list-style:url(../img/warning_img.png);
}
#containererror{
	padding-left:25px;
	height:auto;
}
</style>
</head>

<body>
<div id="form-container" class="ui-widget ui-widget-content ui-corner-all" style="padding:5px; float:left; height:auto;">
	<form id="form_plan" action="#" method="post" enctype="multipart/form-data" >
    	<div class="label"><label>C&oacute;digo:</label></div>
        <div style="width:475px;"><input type="text" style="width:60px;" value="" id="codpla" name="codpla" ></div>
        <div class="label"><label>Descripci&oacute;n:</label></div>
        <div style="width:475px;"><input type="text" style="width:370px;" value="" id="despla" name="despla" ></div>
		<div class="label"><label>Cobertura:</label></div>
        <div style="width:475px;"><input type="text" class="decimal" value="" id="cobertura" name="cobertura"></div>
		<div class="label"><label>Mto. Maternidad:</label></div>
        <div style="width:145px;"><input type="text" class="decimal" value="" id="mto_maternidad" name="mto_maternidad"></div>
        <div class="label"><label>Mto. Ambulatorio:</label></div>
        <div style="width:145px;"><input type="text" class="decimal" value="" id="mto_amb" name="mto_amb"></div>
		<div class="label"><label>Mto. Prenatal:</label></div>
        <div style="width:145px;"><input type="text" class="decimal" value="" id="mto_prenat" name="mto_prenat"></div>
		<div class="label"><label>Mto. Medicina:</label></div>
        <div style="width:145px;"><input type="text" class="decimal" value="" id="mto_medic" name="mto_medic"></div>
		<div class="label"><label>Mto. Lentes:</label></div>
        <div style="width:145px;"><input type="text" class="decimal" value="" id="mto_lentes" name="mto_lentes"></div>
		<div class="label"><label>Mto. Odonotologia:</label></div>
        <div style="width:145px;"><input type="text" class="decimal" value="" id="mto_odonto" name="mto_odonto"></div>
		<div class="label"><label>Indeminizaci&oacute;n por Muerte:</label></div>
        <div style="width:475px;"><input type="text" class="decimal" value="" id="indem_muer" name="indem_muer"></div>
        <div style="width:auto; height:auto;">
        	<input type="submit" id="bt_guardar" name="bt_guardar" value="Guardar"> 
            <input type="reset" id="bt_reset" name="bt_reset" value="Limpiar" > 
            <input type="button" id="bt_close" name="bt_close" value="Cerrar" >
			<div id="boxerror" class="ui-state-highlight ui-corner-all" style="height:auto; width:670px; display:none">
        		<p style="margin-bottom: 5px; margin-top: 5px;">
        			<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
	        		<strong>Se consiguieron lo(s) siguiente(s) problemas al procesar el formulario:</strong>
	    	        <div id="containererror"></div>
    	    	</p>
			</div>
        </div>
	</form>
</div>
</body>
</html>
