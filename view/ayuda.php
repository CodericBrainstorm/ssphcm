<?php
if(isset($_REQUEST['cmd'], $_REQUEST['res'])){
$cmd = $_REQUEST['cmd'];    
$res = $_REQUEST['res'];
echo "PARAM=$cmd";
echo "<br>";
echo "RES=$res";
echo "<br>";
echo "<br>";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Forms in ui Dialog</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/redmond/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
<link rel="stylesheet" href="http://jqueryui.com/resources/demos/style.css" />
<script>
$(document).ready(function(){
        var dialogOpts = {
          bgiframe: true,
          modal: true,
          overlay: {
                    backgroundColor: '#000',
                    opacity: 0.5
                },
            buttons: {
                "Delete all items": function() {
					$("#cmd").val('delbutton');
					$.ajax({url:'formdialog2.php', beforeSend:function(){console.log("Procesando...");}, data:$("#form1").serialize(), dataType:'json', success:function(data, textStatus, jqXHR){
							console.log("Resultado", data);
						}, type:'post', complet:function(){
							$("#myDialog").dialog("close");
						}
					});
                },
                "Cancel": function() {
					$("#cmd").val('');
                    $("#myDialog").dialog("close");
                }
            },
          autoOpen: false,
        };
        $("#myDialog").dialog(dialogOpts);
        $("#btopenDial").bind('click', function(e){
             e.preventDefault();
             e.stopPropagation();
	         $("#myDialog").dialog("open");
        });  
    });
</script>
</head>
<body>
<form id="form1" name='form1' method='post' ACTION='formdialog2?cmd=<? echo $cmd; ?>' enctype='application/x-www-form-urlencoded'> 
	<input type='hidden' value='change' name='res'>
    <input type="hidden" value="" name="cmd" id="cmd" >
    <!--button type='submit' id='btopenDial' name='cmd'  value='delbutton' >Enviar</button-->
    <input type="submit" value="Enviar" id="btopenDial" name="btopenDial" >
</form> 
<div id="myDialog" title="Cabecera Dialog">
    Los recursos seleccionado van a ser eliminados!
</div>
</body>
</html>