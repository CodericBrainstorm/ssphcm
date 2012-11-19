<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Registro de Planes</title>
<!--link rel="stylesheet" href="../css/flick/jquery-ui-1.9.0.custom.min.css" >
<link rel="stylesheet" href="../css/ui.jqgrid.css" -->

<!--script type="text/javascript" language="javascript" src="../js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui-1.9.0.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/i18n/grid.locale-es.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery.jqGrid.src.js"></script>
<script type="text/javascript" language="javascript">
jQ=$.noConflict();
jQ(document).ready(function(){
	jQ("#planes").jqGrid({url:'./planes/datagrid/planes', datatype:'json', mtype:'POST', colNames:['ID', 'Descripción', 'Cobertura', 'Mto. Maternidad', 'Mto. Ambulatorio', 'Mto. Prenatal', 'Mto. Medicamentos', 'Mto. Lentes', 'Mto. Odontologia', 'Indemnización por Muerte'],
		colModel:[{name:'id_plan', align:'center', key:true, resizable:false, width:80},{name:'desc_plan', resizable:false, width:150},{name:'cobertura', align:'right', resizable:false, width:110}, {name:'mto_mat', align:'right', resizable:false, width:110},{name:'mto_ambul', align:'right', resizable:false, width:110},{name:'mto_prenatal', align:'right', resizable:false, width:110},{name:'mto_medici', align:'right', resizable:false, width:110}, {name:'mto_lentes', align:'right', resizable:false, width:110},{name:'mto_odonto', align:'right', resizable:false, width:110},{name:'indem_muert', align:'right', resizable:false, width:170}], multiselect:true, pager:"#pager_planes"
	});
});
</script-->
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
		load:['../js/i18n/grid.locale-es.js','../js/jquery.jqGrid.src.js', '../css/ui.jqgrid.css','../css/jquery.fancybox.css','../js/jquery.fancybox.js'],
		complete:function(){
			jQ=$.noConflict();
			jQ(document).ready(function(){
				jQ("#planes").jqGrid({url:'./planes/datagrid/planes', datatype:'json', mtype:'POST', colNames:['ID', 'Descripcion', 'Cobertura', 'Mto. Maternidad', 'Mto. Ambulatorio', 'Mto. Prenatal', 'Mto. Medicamentos', 'Mto. Lentes', 'Mto. Odontologia', 'Indemnizacion por Muerte'],
				colModel:[{name:'id_plan', align:'center', key:true, resizable:false, width:50},{name:'desc_plan', resizable:false, width:150},{name:'cobertura', align:'right', resizable:false, width:110}, {name:'mto_mat', align:'right', resizable:false, width:110},{name:'mto_ambul', align:'right', resizable:false, width:110},{name:'mto_prenatal', align:'right', resizable:false, width:110},{name:'mto_medici', align:'right', resizable:false, width:110}, {name:'mto_lentes', align:'right', resizable:false, width:110},{name:'mto_odonto', align:'right', resizable:false, width:110},{name:'indem_muert', align:'right', resizable:false, width:240}], multiselect:true, pager:"#pager_planes"
				});
				jQ("#planes").jqGrid('navGrid','#pager_planes',{edit:false,add:false,del:false});
				jQ("#planes").jqGrid('navButtonAdd','#pager_planes',{caption:'', buttonicon:'ui-icon-plus', position:'last', title:'Nuevo', 
					onClickButton:function(){
						jQ.fancybox.open({href:'./fplanes.html', title:'Registro de Plan', type:'iframe', autoWidth:false, autoHeight:false, width:700, height:599, minHeight:300, maxHeight:799});
						//jQ.fancybox({modal:true, href:'./planes.html', title:'Registro de Plan', type:'iframe'});
						//jQ.fancybox.showLoading();
					}
				});
			});
		}
	}
]);
</script>
</head>

<body>

<table id="planes"><tr><td/></tr></table> 
<div id="pager_planes"></div> 

</body>
</html>
