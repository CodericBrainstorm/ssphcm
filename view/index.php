<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>SOFTHCM WEB 2.0</title>
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
			jQ=$.noConflict();
			jQ(document).ready(function(){
				jQ("#btnupdate").button({text:'Buscar Registro'}).bind('click',function(e){
					jQ("#dialog_find").dialog("open");
				});
				jQ("#btnnew").button({text:'Nuevo Registro'}).bind('click', function(e){
					jQ.fancybox.open({href:"./ftitular.html?codigo=nuevo", title:'Registro Beneficiarios', type:'iframe', autoWidth:false, autoHeight:true, width:800, height:900, minHeight:300, maxHeight:900});					
				});
				jQ("#btnclose").button({text:'Cerrar Sesion'}).bind('click', function(e){
					jQ.ajax({url:'./logout.html', success:function(data, textStatus, jqXHR){
							jQ.fancybox.open({href:"./login.html", title:'SOFTHCM - FUNDAMUTUAL', type:'iframe', 
						modal:true, autoWidth:false, autoHeight:true, width:400, height:500, minHeight:300, maxHeight:900});
						}
					});				
				});
				jQ("#dialog_find").dialog({autoOpen:false, height:170, width:350, title:'SSPHCM - Buscar C&eacute;dula', resizable:false, modal:true, draggable:false, closeOnEscape:true, closeText:'Cerrar'});
				jQ("#form_search").validate({
					onSubmit:false, rules:{cedula:{required:true}}, messages:{cedula:{required:'*'}},
					submitHandler:function(form){
						jQ.ajax({url:'./solicitud/buscar/titular', data:jQ(form).serialize(), dataType:'json', type:'POST', 
							success:function(data, textStatus, jqXHR){
								if(data.success){
									if(jQ.isNumeric(data.codigo)){
										var pagehtml="ftitular.html?codigo="+data.codigo;
										if(data.activo=="0")
											if(!confirm("El titular se encuentra inactivo esta seguro realizar la actualización")){pagehtml=false; }
										jQ("#dialog_find").dialog("close");
										if(pagehtml)
											jQ.fancybox.open({codigo:'adfasdf'},{href:pagehtml, title:'Registro Beneficiarios', type:'iframe', autoWidth:false, autoHeight:true, width:800, height:900, minHeight:300, maxHeight:900});
									}else
										alert("La cédula que indico no pertenece a ningun titular\nHaga click en Nuevo Registro para nuevos titulares");
								}else
									alert("Vaya ocurrio un problema con nuestro servidor.\n Notifiquelo a Soporte Tecnico");
							}
						});
					}
				});
				jQ.ajax({url:'./login.html', dataType:'json', 
					error:function(jqXHR, textStatus, errorThrow){
						jQ.fancybox.open({href:"./login.html", title:'SOFTHCM - FUNDAMUTUAL', type:'iframe', 
						modal:true, autoWidth:false, autoHeight:true, width:400, height:500, minHeight:300, maxHeight:900});
					},
					success:function(data, textStatus, jqXHR){
						
					}
				});
				jQ("div#dialog_find, #box_cente, #textLoading").toggle();
			});		
		}
	}
]);
</script>
<style type="text/css">
.centrarH{
    width:270px;
    height:150px;
    margin:0 auto;
}
body{
	background-color:#36F;
}
</style>
</head>

<body>
<div id="textLoading">Espere...</div>
<div id="dialog_find" style="display:none;">
	<form id="form_search" action="#" method="get" style="width:320px;" >
	    <table style="width:300px;">
        	<tr>
            	<td style="width:100px;">C&eacute;dula:</td>
                <td ><input type="text" id="cedula" name="cedula" style="width:120px;"></td>
            </tr>
            <tr>
            	<td colspan="2" style="text-align:right;"><input type="reset" id="btreset" name="btreset" value="Limpiar"> &nbsp;<input type="submit" id="btsend" name="btsend" value="Buscar"></td>
            </tr>
        </table>
    </form>
</div>
	<div id="box_cente" class="ui-widget ui-widget-content ui-corner-all centrarH" style="width:800px; height:600px; display:none;">
    	<div style="margin:20px;">
	    	<div id="btnupdate">
    	    	<img src="../img/buscar.png" height="64" width="64" alt="Buscar" dir="ltr" title="Buscar Registro">
        	</div>
	    	<div id="btnnew">
    	    	<img src="../img/nuevos.png" height="64" width="64" alt="Nuevo" dir="ltr" title="Nuevo Registro">
        	</div>
	    	<div id="btnclose">
    	    	<img src="../img/exit.png" height="64" width="64" alt="Nuevo" dir="ltr" title="Cerrar sesi&oacute;n">
        	</div>
        </div>
	</div>
</body>
</html>
