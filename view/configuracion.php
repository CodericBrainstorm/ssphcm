<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" language="javascript" src="../js/modernizr.custom.js" ></script>
<script type="text/javascript" language="javascript">
Modernizr.load([
	{load:['http://code.jquery.com/jquery-1.8.2.min.js', 'http://code.jquery.com/ui/1.9.0/jquery-ui.js', 'http://code.jquery.com/ui/1.9.0/themes/flick/jquery-ui.css'],
	callback:function(){
		if(!window.jQuery)
			Modernizr.load(['../js/jquery-1.8.2.min.js','../js/jquery-ui-1.9.0.min.js','../css/flick/jquery-ui-1.9.0.custom.min.css']);
	}
	},
	{
		load:['../js/jquery.MessageBox-4.2.4.js'],
		complete:function(){
			jQ=$.noConflict();
			jQ(document).ready(function(){
				jQ("#boxmessage").MessageBox({cTitleBarText:'SOFTHCM 2.0'});
				jQ("#boxmessage").MessageBox("option",{messageText:'', nDialgoBoxType:16});
				jQ("#boxmessage").MessageBox("show");
			});	
		}
	}
]);
</script>
<title>Documento sin título</title>
</head>
<body>
<div id="boxmessage"></div>
</body>
</html>