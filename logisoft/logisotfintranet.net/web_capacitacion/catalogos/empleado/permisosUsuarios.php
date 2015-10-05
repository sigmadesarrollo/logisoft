<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ajax.js"></script>
<script>
	var tabla1 	= new ClaseTabla();
	var u = document.all;
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"MODULO", medida:320, alineacion:"left", datos:"modulo"},			
			{nombre:"SEL", medida:40, tipo:"checkbox", alineacion:"center", datos:"seleccion"}
		],
		filasInicial:10,
		alto:150,
		seleccion:true,
		ordenable:false,
		//eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtenerDetalle();
	}
	
	function obtenerDetalle(){
		consultaTexto("mostrarDetalle","catalogoempleado_con.php?accion=5&s="+Math.random());
	}
	function mostrarDetalle(datos){
		var obj = eval(convertirValoresJson(datos));
		tabla1.setJsonData(obj);
	}
	function seleccionarTodo(){
		if(tabla1.getRecordCount()!=0){			
			var count = tabla1.getRecordCount();
			if(u.todos.checked == true){
				for(var i=0;i<count;i++){
					u["detalle_SEL"][i].checked = true;
				}
			}else{
				for(var i=0;i<count;i++){
					u["detalle_SEL"][i].checked = false;
				}
			}
		}
	}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="500" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Permisos</td>
    </tr>
    <tr>
      <td><table width="499" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2">Usuario:
            <label>
            <input type="text" name="textfield" />
            </label></td>
        </tr>
        <tr>
          <td width="270">&nbsp;</td>
          <td width="229"><label>
            <input name="todos" type="checkbox" id="todos" style="width:12px" onclick="seleccionarTodo()" value="checkbox" />
          Seleccionar Todo </label></td>
        </tr>
        <tr>
          <td colspan="2"><table id="detalle" width="498" border="0" cellspacing="0" cellpadding="0">
            
          </table></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>

</body>
</html>
