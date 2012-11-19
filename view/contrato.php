<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Solicitud de P&oacute;liza H.C.M.</title>
<?php
echo "<base href='http://".$_SERVER['SERVER_NAME']."'>";
?>
<script type="text/javascript" language="javascript" src="../js/modernizr.custom.js"></script>
<script type="text/javascript" language="javascript">
Modernizr.load([
	{
		load:['http://code.jquery.com/jquery-1.8.2.min.js', 'http://code.jquery.com/ui/1.9.0/jquery-ui.js', 'http://code.jquery.com/ui/1.9.0/themes/flick/jquery-ui.css'], 
		callback:function(){
			if(!window.jQuery){
				Modernizr.load(['../js/jquery-1.8.2.min.js','../js/jquery-ui-1.9.0.min.js','../css/flick/jquery-ui-1.9.0.custom.min.css']);
			}
		}
	},
	{
		load:['../js/i18n/grid.locale-es.js','../js/jquery.jqGrid.src.js', '../css/ui.jqgrid.css','../css/jquery.fancybox.css','../js/jquery.fancybox.js', '../js/jquery.validate.min.js','../js/jquery.formatCurrency-1.4.0.min.js','../js/i18n/jquery.formatCurrency.es-VE.js'],
		complete:function(){
			jQ=$.noConflict();
			idfam=0;
			jQ(document).ready(function(){
				jQ.fn.serializeElements=function(){
					var o = {}, a=this.serializeArray();
					this.each(function(){
						if(o[this.name]){
							if(!o[this.name].push)
								o[this.name]=[o[this.name]];
							o[this.name].push(this.value || '');
						}else
							o[this.name]=this.value || '';
					});
					return o;
				}

				jQ(":text").attr({autocomplete:'off'});
				jQ.each(jQ(":text.formatdecimal"), function(i, e){ 
					jQ(this).attr({readonly:'readonly', value:'0'});
					jQ(this).formatCurrency({symbol:'', colorize:true});
					jQ(this).bind('blur', function(){ jQ(this).formatCurrency().toNumber(); }).trigger('blur');
				});
				
				jQ("#fecnactit, #fecinglab, #fecnacfam").datepicker({onChangeMonthYear:function(year, month, inst){
					jQ(this).blur(function(e){e.preventDefault(); e.stopPropagation();});
				},changeMonth:true, changeYear:true, dayNames:["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin:["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], dayNamesShort:["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNames:["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"], monthNamesShort:["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"], yearRange:"c-50:c0", dateFormat:"dd/mm/yy "});
				
				jQ("#fecnactit, #fecnacfam").datepicker("option",{onSelect:function(dateText, inst){
					jQ(this).data('oldvalue',dateText);
					var returnElement=jQ("#"+jQ(this).data("input"));
					var dataSend=jQ.parseJSON('{"'+inst.id+'":"'+dateText+'"}')
					jQ.ajax({url:'./solicitud/funcion/calcularedad', beforeSend:function(jqXHR, settings){
						returnElement.val('');
					}, complete:function(jqXHR, textStatus){}, dataType:'json', error:function(jqXHR, textStatus, errorThrown){ 
						returnElement.val('.NULL.');
					}, success:function(data, textStatus, jqXHR){ if(data.success){returnElement.val(data.edad);} }, type:'POST', data:dataSend});
					jQ(this).blur(function(e){
						e.preventDefault();
						e.stopPropagation();
					});
					if(jQ(this).attr('id')=="fecnactit")
						primaTitular();
					else if(jQ(this).attr('id')=="fecnacfam")
						primaFamiliar();
				}});
				jQ("#cod_nexo").bind('change', function(e){
					if(jQ(this).val()!="" && jQ("#fecnacfam").val()!="" && jQ("#sexo").val()!="" && jQ("#sexofam").val()!=""){
						if(validBeneficiario()){
							jQ.ajax({url:'./solicitud/validar/beneficiario', type:'POST', dataType:'json', data:jQ("#fecnacfam, #cod_nexo, #codtit, #codigo_fam, #sexo, #sexofam").serialize(), success:function(data, textStatus, jqXHR){
									if(!data.isvalid)
										if(confirm(data.mensaje))
											primaFamiliar();
										else{
											e.preventDefault();
											e.stopPropagation();
										}
									else
										primaFamiliar();
								}
							});					
						}
					}
				});
				jQ.ajax({url:'./solicitud/nexo/listado', type:'POST', dataType:'json', context:jQ("#cod_nexo"), beforeSend:function(){
					jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && jQ.isArray(data.obj_form)){ 
						var _this=this;
						jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); }); 
					}
				}, error:function(jqXHR, textStatus, errorThrown){}});
				jQ.ajax({url:'./solicitud/profesion/listado', type:'POST', dataType:'json', context:jQ("#codprof"), beforeSend:function(){
					jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && jQ.isArray(data.obj_form)){ 
						var _this=this;
						jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); }); 
					}
				}, error:function(jqXHR, textStatus, errorThrown){}});

				jQ.ajax({url:'./solicitud/planes/listado', type:'POST', dataType:'json', context:jQ("#codpla"), beforeSend:function(){
					jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && jQ.isArray(data.obj_form)){ 
						var _this=this;
						jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); });
					}
				}, error:function(jqXHR, textStatus, errorThrown){}});
				jQ.ajax({url:'./solicitud/estados/listado', type:'POST', dataType:'json', context:jQ("#codest"), beforeSend:function(){
					jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && jQ.isArray(data.obj_form)){ 
						var _this=this;
						jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); });
					}
				}});
				
				jQ.ajax({url:'./solicitud/nominas/listado', type:'POST', dataType:'json', context:jQ("#codnom"), beforeSend:function(){
					jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && jQ.isArray(data.obj_form)){ 
						var _this=this;
						jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); });
					}
				}});
				jQ("#codnom").bind("change", function(){
					if(jQ(this).val()!=""){
						jQ.ajax({url:'./solicitud/datosnomina/consultar', type:'POST', dataType:'json', data:{codnom:function(){return jQ("#codnom").val()}},
						beforeSend:function(){
							jQ("div#pager_tab").tabs("option","disabled",[2]);
						},
						success:function(data, textStatus, jqXHR){
							if(data.success)
								jQ("div#pager_tab").tabs("option","disabled",data.tabs_disabled);
						}
						});
					}
				});
				jQ.ajax({url:'./solicitud/direccionsuperior/listado', type:'POST', dataType:'json', context:jQ("#coddir"), beforeSend:function(){
					jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && jQ.isArray(data.obj_form)){ 
						var _this=this;
						jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); });
					}
				}});
				jQ.ajax({url:'./solicitud/secretaria/listado', type:'POST', dataType:'json', context:jQ("#codsec"), beforeSend:function(){
					jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && jQ.isArray(data.obj_form)){ 
						var _this=this;
						jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); });
					}
				}});
				jQ.ajax({url:'./solicitud/coordinacion/listado', type:'POST', dataType:'json', context:jQ("#codcor"), beforeSend:function(){
					jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && jQ.isArray(data.obj_form)){ 
						var _this=this;
						jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); });
					}
				}});
				jQ.ajax({url:'./solicitud/division/listado', type:'POST', dataType:'json', context:jQ("#coddiv"), beforeSend:function(){
					jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && jQ.isArray(data.obj_form)){ 
						var _this=this;
						jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); });
					}
				}});
				jQ.ajax({url:'./solicitud/direccion/listado', type:'POST', dataType:'json', context:jQ("#coddirec"), beforeSend:function(){
					jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && jQ.isArray(data.obj_form)){ 
						var _this=this;
						jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); });
					}
				}});
				
				jQ.ajax({url:'./solicitud/oficina/listado', type:'POST', dataType:'json', context:jQ("#codofi"), beforeSend:function(){
					jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && jQ.isArray(data.obj_form)){ 
						var _this=this;
						jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); });
					}
				}});
				jQ.ajax({url:'./solicitud/departamento/listado', type:'POST', dataType:'json', context:jQ("#cod_dpto"), beforeSend:function(){
					jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && jQ.isArray(data.obj_form)){ 
						var _this=this;
						jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); });
					}
				}});
				jQ.ajax({url:'./solicitud/cargo/listado', type:'POST', dataType:'json', context:jQ("#codcar"), beforeSend:function(){
					jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && jQ.isArray(data.obj_form)){ 
						var _this=this;
						jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); });
					}
				}});
				jQ.ajax({url:'./solicitud/puesto/listado', type:'POST', dataType:'json', context:jQ("#codpues"), beforeSend:function(){
					jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
				}, success:function(data, textStatus, jqXHR){
					if(data.success && jQ.isArray(data.obj_form)){ 
						var _this=this;
						jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); });
					}
				}});

				jQ("#codest").bind("change", function(){
					if(jQ(this).val()!=""){
						jQ.ajax({url:'./solicitud/listmunicipios/listado', type:'POST', dataType:'json', data:{codest:function(){ return jQ("#codest").val();}},context:jQ("#codmun"), beforeSend:function(){
									jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
								}, success:function(data, textStatus, jqXHR){
									if(data.success && jQ.isArray(data.obj_form)){ 
										var _this=this;
										jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); });
									}
								}
						});
					}
				});
				jQ("#codmun").bind("change", function(){
					if(jQ(this).val()!=""){
						jQ.ajax({url:'./solicitud/listparroquias/listado', type:'POST', dataType:'json', data:{codmun:function(){ return jQ("#codmun").val();}},context:jQ("#codpar"), beforeSend:function(){
									jQ(this).children(':not(:first-child)').remove(); jQ(this).attr('disabled'); 
								}, success:function(data, textStatus, jqXHR){
									if(data.success && jQ.isArray(data.obj_form)){ 
										var _this=this;
										jQ.each(data.obj_form, function(e,a){ jQ(_this).append(jQ('<option/>',a)); });
									}
								}
						});
					}
				});
				
				jQ("#codpla").bind('change', function(){
					if(jQ(this).val()!=""){
						jQ.ajax({url:'./solicitud/datosplan/consultar', type:'POST', dataType:'json', data:{codpla:function(){return jQ("#codpla").val();}}, success:function(data, textStatus, jqXHR){
								if(data.success && jQ.isArray(data.obj_form)){ 
									var _this=this;
									jQ.each(data.obj_form, function(e,a){ jQ("#cobertura").val(a.cobertura);});
									jQ("#cobertura").trigger('blur');
								}
						}});
						if(jQ("#grid_familiar").jqGrid('getGridParam',"records")==0){
							primaTitular();
						}else{
							o=[];jsonBenef={codigo_fam:'', fecnacfam:'', sexofam:'', cod_nexo:''};
							jQ.each(jQ("#grid_familiar").jqGrid('getRowData'), function(i, e){
								o.push(jQ.extend({},jsonBenef, e));
							});
							var paramPost=jQ.extend({},jQ("#adicional, #codnom, #codpla, #fecnactit, #sexo").serializeElements(), {familiares:o});
							jQ.ajax({url:'./solicitud/calcularprimas/consultar', type:'POST', dataType:'json', data:paramPost, success:function(data, textStatus, jqXHR){
								
								}
							});
						}
					}else{
						
					}
				});
				jQ("#fecnactit, #fecnacfam").bind('blur', function(){
					if(jQ(this).val()!="" && jQ(this).data("oldvalue")!=jQ(this).val()){
						jQ(this).data("oldvalue", jQ(this).val());
						var returnElement=jQ("#"+jQ(this).data("input"));
						var dataSend=jQ.parseJSON('{"'+jQ(this).attr('id')+'":"'+jQ(this).val()+'"}')
						jQ.ajax({url:'./solicitud/funcion/calcularedad', beforeSend:function(jqXHR, settings){
							returnElement.val('');
						}, complete:function(jqXHR, textStatus){}, dataType:'json', error:function(jqXHR, textStatus, errorThrown){ 
							returnElement.val('.NULL.');
						}, success:function(data, textStatus, jqXHR){ if(data.success){returnElement.val(data.edad);} }, type:'POST', data:dataSend});
						if(jQ(this).attr('id')=="fecnactit")
							primaTitular();
						else
							primaFamiliar();
					}
				});
				jQ("div#pager_tab").tabs();
				jQ("#grid_familiar").jqGrid({url:'./solicitud/datagrid/planes', datatype:'json', mtype:'POST', colNames:['ID', 'Cedula', 'Apellidos', 'Nombres', 'F. Nacimiento', 'Edad', 'Sexo', 'L. Nacimiento', 'Parentesco', 'Prima', 'Otro HCM', 'Nombre Aseguradora'],
				colModel:[
					{name:'codigo_fam', align:'center', key:true, resizable:false, hidden:true}, 
					{name:'cedfam', resizable:false, width:80, align:'center'}, 
					{name:'apefam', resizable:false, width:110}, 
					{name:'nomfam', resizable:false, width:110}, 
					{name:'fecnacfam',resizable:false, width:90, datefmt:"d-m-Y", align:'center'}, 
					{name:'edad', resizable:false, width:40, align:'center'}, 
					{name:'sexofam', resizable:false, width:40, edittype:'select', editoptions:{value:"M:M;F:F"}, align:'center'}, 
					{name:'lugnacfam', resizable:false, hidden:true}, 
					{name:'cod_nexo', resizable:false, width:70, edittype:'select', formatter:'select',editoptions:{value:"1:C;2:H;3:P;4:M"}, align:'center'}, 
					{name:'cuota_fam', align:'right', resizable:false, width:70},
					{name:'otrocontfam', align:'center', resizable:false, width:60},
					{name:'descontfam', resizable:false, hidden:true}
				], multiselect:true, pager:"#pagerfam"
				});
				jQ("#grid_familiar").jqGrid('navGrid','#pagerfam',{refresh:false, view:false, del:false, edit:false, add:false, search:false});
				jQ("#grid_familiar").jqGrid('navButtonAdd','#pagerfam',{
					onClickButton:function(){
						var s=jQ("#grid_familiar").jqGrid('getGridParam','selarrrow');
						if(s.length==1){
							jQ.each(jQ("#grid_familiar").jqGrid('getRowData',s), function(a, b){ jQ("#"+a).val(b); });
							jQ("#grid_familiar").jqGrid('delRowData', s);
						}else
							alert("Seleccione un registro y luego presione la opcion de Editar");
					},title:'Editar', caption:'', buttonicon:'ui-icon-pencil', position:'last'
				});
				jQ("#grid_familiar").jqGrid('navButtonAdd','#pagerfam',{
					onClickButton:function(){
						var s=jQ("#grid_familiar").jqGrid('getGridParam','selarrrow');
						if(s.length==1){
							if(confirm("Esta seguro de Quitar el registro de este familiar?"))
								jQ("#grid_familiar").jqGrid('delRowData', s);
						}else
							alert("Seleccione un registro y luego presione la opcion de Editar");
					},title:'Quitar',  caption:'', buttonicon:'ui-icon-trash', position:'last'
				});
				
				
				jQ("#form_socio").validate({onSubmit:false, 
					rules:{apetit:{required:true}, nomtit:{required:true}, lugnactit:{required:true}, fecnactit:{required:true, date:true}, anos:{required:true, digits:true}, edocivil:{required:true}, sexo:{required:true}, dirtit:{required:true}, codpla:{required:true}, cuota:{required:true, number:true}}
				});
				jQ("#sexo, #adicional").bind('change', function(){ primaTitular(); });
				jQ("#addfam").bind('click', function(e){
					e.preventDefault();e.stopPropagation();
					var defaultBenef={codigo_fam:'', cedfam:'', apefam:'', nomfam:'', fecnacfam:'', edad:'', sexofam:'', lugnacfam:'', cod_nexo:'', cuota_fam:'', otrocontfam:'N', descontfam:''};
					var rowBenef=jQ("#codigo_fam, #cedfam, #apefam, #nomfam, #fecnacfam, #edad, #sexofam, #lugnacfam, #cod_nexo, #cuota_fam, #otrocontfam, #descontfam").serializeElements();
					if(jQ("#codigo_fam").val()==""){
						var newId="TMP"+(++idfam).toString();
						jQ.extend(true, rowBenef,{codigo_fam:newId});
					}
					jQ("#grid_familiar").addRowData(rowBenef.codigo_fam, rowBenef,'last');
					jQ.each(defaultBenef, function(i, j){ jQ("#"+i).val(j); });
				});
				function primaTitular(){
					if(jQ("#fecnactit").val()!="" && jQ("#sexo").val()!="" && jQ("#adicional").val()!="" && jQ("#codnom").val()!="" && jQ("#codpla").val()!=""){
						jQ.ajax({url:'./solicitud/primatitular/calcular',type:'POST', dataType:'json', data:jQ("#fecnactit, #sexo, #adicional, #codnom, #codpla").serialize(), success:function(data, textStatus, jqXHR){
							if(data.success)
								jQ("#cuota").val(data.cuota);
						}});
					}
				}
				function primaFamiliar(){
					if(jQ("#fecnacfam").val()!="" && jQ("#sexofam").val() && jQ("#cod_nexo").val()!="" && jQ("#adicional") && jQ("#sexo" && jQ("#codpla").val()!="" && jQ("#codnom").val()!="")){
						jQ.ajax({url:'./solicitud/primafamiliar/calcular', type:'POST', dataType:'json', data:jQ("#fecnacfam, #sexofam, #cod_nexo, #adicional, #sexo, #codpla, #codnom").serialize(), success:function(data, textStatus, jqXHR){
								if(data.success)
									jQ("#cuota_fam").val(data.cuota_fam);
							}
						});
					}
				}
				function validBeneficiario(){
					if(jQ("#grid_familiar").jqGrid('getGridParam',"records")>0){
						var familiares={datosfam:jQ("#grid_familiar").jqGrid('getCol','cod_nexo', false)}
						var info=jQ.extend({},jQ("#cod_nexo, #fecnacfam").serializeElements(), familiares);
						jQ.ajax({url:'./solicitud/validar/familiares', type:'POST', dataType:'json', data:info, 
							success:function(data, textStatus, jqXHR){
								if(!data.isvalid){
									if(confirm(data.mensaje)){
										primaFamiliar();
										return false;
									}else{
										primaFamiliar();
										return false;
									}
								}else{
									primaFamiliar();
									return true;
								}
							}
						});
					}else
						return true;
				}
			});
		}
	}
]);
</script>
<style type="text/css">
body{
	font-size:13px;
	font-family:Arial, Helvetica, sans-serif;
}
label{
	font-weight:bold;
	color:#C30;
}
div#tabs1 > div.cells, div#tabs2 > div.cells, div#tabs3 > div.cells{
	height:25px;
	float:left;
}
div.label1{
	width:160px;
}
textarea{
	resize:none;
}
.formatdecimal{
	width:80px;
	text-align:right;
}
</style>
</head>

<body>
	<div id="pager_tab" style="height:500px;">
    <form id="form_socio" action="#" method="post" >
    	<ul>
        	<li><a href="#tabs1">Datos Personales</a></li>
        	<li><a href="#tabs2">Informaci&oacute;n del Cargo &frasl; RRHH</a></li>
        	<li><a href="#tabs3">Beneficiarios</a></li>
        </ul>
        <div id="tabs1" style="width:700px; height:600px;">
        	<div class="label1 cells"><label>Codigo:</label></div>
            <div class="cells" style="width:120px;"><input type="text" id="codtit" name="codtit" value="" style="width:90px;" ></div>
            <div class="cells" style="width:70px;"><label>C&eacute;dula</label></div>
            <div class="cells" style="width:350px;"><input type="text" id="cedtit" name="cedtit" value="" style="width:130px;" ></div>
        	<div class="label1 cells"><label>Apellidos:*</label></div>
            <div class="cells" style="width:540px;"><input type="text" id="apetit" name="apetit" value="" style="width:290px;" ></div>
            <div class="label1 cells"><label>Nombres:*</label></div>
            <div class="cells" style="width:540px;"><input type="text" id="nomtit" name="nomtit" value="" style="width:290px;" ></div>
            <div class="label1 cells"><label>Lugar de Nacimiento:*</label></div>
            <div class="cells" style="width:540px;"><input type="text" id="lugnactit" name="lugnactit" value="" ></div>
            <div class="label1 cells"><label>Tel&eacute;fono:</label></div>
            <div class="cells" style="width:180px;"><input type="text" id="tlf" name="tlf" value="" ></div>
            <div class="cells" style="width:90px;"><label>Celular:</label></div>
            <div class="cells" style="width:270px;"><input type="text" id="celular" name="celular" value="" ></div>
            <div class="label1 cells"><label>Email:</label></div>
            <div class="cells" style="width:540px;"><input type="text" id="email" name="email" value="" style="width:220px;" ></div>
            <div class="label1 cells"><label>Fecha Nacimiento:*</label></div>
            <div class="cells" style="width:180px;"><input type="text" id="fecnactit" name="fecnactit" value="" style="width:90px;" data-input="anos" data-oldvalue="" ></div>
            <div class="cells" style="width:60px;"><label>Edad:</label></div>
            <div class="cells" style="width:85px;"><input type="text" id="anos" name="anos" value="" readonly style="width:40px; text-align:right;" ></div>
            <div class="cells" style="width:80px;"><label>Edo. Civil:*</label></div>
            <div class="cells" style="width:135px;"><select id="edocivil" name="edocivil"><option value="" selected>Seleccionar...</option><option value="S">Soltero</option><option value="C">Casado</option><option value="V">Viudo</option><option value="O">Otro</option></select></div>
            <div class="label1 cells"><label>Sexo:*</label></div>
            <div class="cells" style="width:540px;"><select id="sexo" name="sexo"><option value="M">Masculino</option><option value="F" selected>Femenino</option></select></div>
            <div class="label1 cells"><label>Ingreso Laboral:</label></div>
            <div class="cells" style="width:540px;"><input type="text" id="fecinglab" name="fecinglab" value="" style="width:90px;" ></div>
            <div class="label1 cells"><label>Profesi&oacute;n:</label></div>
            <div class="cells" style="width:540px;"><select id="codprof" name="codprof"><option value="" selected>Seleccionar...</option></select></div>
            <div class="label1 cells" style="height:65px;"><label>Direcci&oacute;n Habitaci&oacute;n:*</label></div>
            <div class="cells" style="width:540px; height:65px;"><textarea id="dirtit" name="dirtit" cols="48" rows="1" ></textarea></div>
            <div class="label1 cells"><label>Maternidad:</label></div>
            <div class="cells" style="width:540px;"><select id="adicional" name="adicional"><option value="S">Si</option><option value="N" selected>No</option></select></div>
            <div class="label1 cells"><label>Tipo de N&oacute;mina:</label></div>
            <div class="cells" style="width:540px;"><select id="codnom" name="codnom" style="width:370px;"><option value="" selected>Seleccionar...</option></select></div>
            <div class="label1 cells"><label>Plan de P&oacute;liza:*</label></div>
            <div class="cells" style="width:220px;"><select id="codpla" name="codpla" style="width:200px;"><option value="" selected>Seleccionar...</option></select></div>
            <div class="cells" style="width:320px;"><input type="text" id="cobertura" name="cobertura" value="" class="formatdecimal" ></div>
            <div class="label1 cells"><label>Prima:</label></div>
            <div class="cells" style="width:540px;"><input type="text" id="cuota" name="cuota" value="" class="formatdecimal" ></div>
            <div class="label1 cells"><label>Otro H.C.M.:</label></div>
            <div class="cells" style="width:90px;"><select id="otroconttit" name="otroconttit"><option value="N" selected>No</option><option value="S">Si</option></select></div>
            <div class="cells" style="width:180px;"><label>Nombre de Aseguradora:</label></div>
            <div class="cells" style="width:270px;"><input type="text" id="desconttit" name="desconttit" value="" ></div>
        </div>
        <div id="tabs2" style="height:auto; width:700px;">
        	<div class="label1 cells"><label>Estado:</label></div>
            <div class="cells" style="width:540px;"><select id="codest" name="codest"><option value="" selected>Seleccionar...</option></select></div>
            <div class="label1 cells"><label>Municipio:</label></div>
            <div class="cells" style="width:540px;"><select id="codmun" name="codmun"><option value="" selected>Seleccionar...</option></select></div>
            <div class="label1 cells"><label>Parroquia:</label></div>
            <div class="cells" style="width:540px;"><select id="codpar" name="codpar"><option value="" selected>Seleccionar...</option></select></div>
            <div class="label1 cells"><label>Direcci&oacute;n Superior:</label></div>
            <div class="cells" style="width:540px;"><select id="coddir" name="coddir"><option value="" selected>Seleccionar...</option></select></div>
            <div class="label1 cells"><label>Secretaria:</label></div>
            <div class="cells" style="width:200px;"><select id="codsec" name="codsec" style="width:170px;"><option value="" selected>Seleccionar...</option></select></div>
            <div class="cells" style="width:140px;"><label>Coordinaci&oacute;n:</label></div>
            <div class="cells" style="width:200px;"><select id="codcor" name="codcor" style="width:170px;"><option value="" selected>Seleccionar...</option></select></div>
            <div class="label1 cells"><label>Divisi&oacute;n:</label></div>
            <div class="cells" style="width:200px;"><select id="coddiv" name="coddiv" style="width:170px;"><option value="" selected>Seleccionar...</option></select></div>
            <div class="cells" style="width:140px;"><label>Direcci&oacute;n:</label></div>
            <div class="cells" style="width:200px;"><select id="coddirec" name="coddirec" style="width:170px;"><option value="" selected>Seleccionar...</option></select></div>
            <div class="label1 cells"><label>Oficina:</label></div>
            <div class="cells" style="width:200px;"><select id="codofi" name="codofi" style="width:170px;"><option value="" selected>Seleccionar...</option></select></div>
            <div class="cells" style="width:140px;"><label>Departamento:</label></div>
            <div class="cells" style="width:200px;"><select id="cod_dpto" name="cod_dpto" style="width:170px;"><option value="" selected>Seleccionar...</option></select></div>
            <div class="label1 cells"><label>Cargo:</label></div>
            <div class="cells" style="width:200px;"><select id="codcar" name="codcar" style="width:170px;"><option value="" selected>Seleccionar...</option></select></div>
            <div class="cells" style="width:140px;"><label>Puesto:</label></div>
            <div class="cells" style="width:200px;"><select id="codpues" name="codpues" style="width:170px;"><option value="" selected>Seleccionar...</option></select></div>
        </div>
        <div id="tabs3" style="height:auto; width:700px;">
        	<div class="label1 cells"><label>C&oacute;digo:</label></div>
            <div class="cells" style="width:80px;"><input type="text" id="codigo_fam" name="codigo_fam" value="" style="width:70px;" ></div>
        	<div class="cells" style="width:80px;"><label>C&eacute;dula:</label></div>
            <div class="cells" style="width:380px;"><input type="text" id="cedfam" name="cedfam" value="" ></div>
        	<div class="cells label1"><label>Apellidos:</label></div>
            <div class="cells" style="width:540px;"><input type="text" id="apefam" name="apefam" value="" ></div>
        	<div class="cells label1"><label>Nombres:</label></div>
            <div class="cells" style="width:540px;"><input type="text" id="nomfam" name="nomfam" value="" ></div>
        	<div class="cells label1"><label>Fecha Nacimiento:</label></div>
            <div class="cells" style="width:180px;"><input type="text" id="fecnacfam" name="fecnacfam" value="" style="width:110px;" data-input="edad"></div>
        	<div class="cells" style="width:60px;"><label>Edad:</label></div>
            <div class="cells" style="width:90px;"><input type="text" id="edad" name="edad" value="" readonly style="width:80px;" ></div>
            <div class="cells" style="width:50px;"><label>Sexo:</label></div>
            <div class="cells" style="width:160px;"><select id="sexofam" name="sexofam"><option value="" selected>Seleccionar...</option><option value="M">Masculino</option><option value="F">Femenino</option></select></div>
        	<div class="cells label1"><label>Lugar de Nacimiento:</label></div>
            <div class="cells" style="width:540px;"><input type="text" id="lugnacfam" name="lugnacfam" value="" ></div>
        	<div class="cells label1"><label>Parentesco:</label></div>
            <div class="cells" style="width:180px;"><select id="cod_nexo" name="cod_nexo"><option value="" selected>Seleccionar...</option></select></div>
        	<div class="cells" style="width:60px;"><label>Prima:</label></div>
            <div class="cells" style="width:300px;"><input type="text" id="cuota_fam" name="cuota_fam" value="" class="formatdecimal"></div>
            <div class="cells label1"><label>Otro H.C.M.:</label></div>
            <div class="cells" style="width:110px;"><select id="otrocontfam" name="otrocontfam"><option value="N" selected>No</option><option value="S">Si</option></select></div>
            <div class="cells" style="width:180px;"><label>Nombre de Aseguradora:</label></div>
            <div class="cells" style="width:250px;"><input type="text" id="descontfam" name="descontfam" value="" style="width:230px;" ></div>
            <div class="cells" style="width:700px; text-align:center;"><input type="button" value="Agregar" id="addfam" name="addfam"></div>
            <div style="float:left; width:500px;" >
	    	    <table id="grid_familiar"></table>
				<div id="pagerfam"></div>            	
            </div>
        </div>
	    </form>
    </div>
</body>
</html>
