<?	session_start();
	require_once('../../Conectar.php');	
	$l = Conectarse('webpmm');
	
	if($_POST[registros] > 0){
		$s = "DELETE FROM configuradorservicios";
		mysql_query($s,$l) or die($s);
		for($i=0; $i < $_POST[registros]; $i++){
			$s = "INSERT INTO configuradorservicios 
			(id,servicio,condicion,costo,costoextra,limite,porcada,usuario,fecha)VALUES
			('".$_POST["detalle_ID"][$i]."',
			 '".$_POST["detalle_IDSERVICIO"][$i]."', 
			 '".$_POST["detalle_CONDICION"][$i]."',
			 '".str_replace("$ ","",$_POST["detalle_COSTO"][$i])."',
			 '".str_replace("$ ","",$_POST["detalle_COSTO_EXTRA"][$i])."',
			 '".$_POST["detalle_LIMITE"][$i]."',	 
			 '".$_POST["detalle_POR_CADA"][$i]."',				 
			 UCASE('".$_SESSION[NOMBREUSUARIO]."'),
			 CURRENT_TIMESTAMP())";
			mysql_query($s,$l) or die($s);
			//echo $s;
		}
		$mensaje = "Los datos han sido guardados correctamente";
	}
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/funciones.js"></script>
<script>
	var v_modificar = '<div class="ebtn_modificar" onclick="agregarServicio()"></div>';
	var v_agregar = '<div class="ebtn_agregar" onclick="agregarServicio()"></div>';
	var tabla1 	= new ClaseTabla();
	var u = document.all;
	var mens = new ClaseMensajes();
	mens.iniciar("../../javascript");
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[			
			{nombre:"SERVICIO", 	medida:100, alineacion:"left",  datos:"servicio"},
			{nombre:"COSTO", 		medida:100, alineacion:"right", tipo:"moneda", datos:"costo"},
			{nombre:"LIMITE", 		medida:100, alineacion:"right", datos:"limite"},
			{nombre:"POR_CADA", 	medida:100, alineacion:"right", datos:"porcada"},
			{nombre:"COSTO_EXTRA", 	medida:100, alineacion:"right", tipo:"moneda", datos:"costoextra"},
			{nombre:"CONDICION", 	medida:4,   alineacion:"center",tipo:"oculto", datos:"condicion"},
			{nombre:"IDSERVICIO", 	medida:4, 	alineacion:"center",tipo:"oculto", datos:"idservicio"},
			{nombre:"ID", 	medida:4, 	alineacion:"center",tipo:"oculto", datos:"id"}
		],
		filasInicial:10,
		alto:180,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"modificarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtenerServicios();
	}
	
	function obtenerServicios(){
		consultaTexto("mostrarServicios","configuradorServicios_con.php?accion=1&mas="+Math.random());
	}
	
	function mostrarServicios(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(datos);
			tabla1.setJsonData(obj);
		}
		document.getElementById('registros').value = tabla1.getRecordCount();
	}
	
	function guardar(){
		document.getElementById('registros').value = tabla1.getRecordCount();				
		document.form1.submit();
	}
	
	function Habilitar(){
		if(document.getElementById('condicion').checked==false){			
			document.getElementById('limite').disabled=true
			document.getElementById('limite').value="";
			document.getElementById('porcada').disabled=true
			document.getElementById('porcada').value="";
			document.getElementById('costoextra').disabled=true
			document.getElementById('costoextra').value="";
			document.getElementById('limite').style.backgroundColor='#FFFF99';
			document.getElementById('porcada').style.backgroundColor='#FFFF99';
			document.getElementById('costoextra').style.backgroundColor='#FFFF99';
		}else{		
			document.getElementById('limite').style.backgroundColor='';
			document.getElementById('porcada').style.backgroundColor='';
			document.getElementById('costoextra').style.backgroundColor='';
			document.getElementById('limite').disabled=false
			document.getElementById('porcada').disabled=false
			document.getElementById('costoextra').disabled=false
			document.getElementById('limite').value=0;
			document.getElementById('porcada').value=1;
			document.getElementById('costoextra').value=0;				
		}
	}
	
	function agregarServicio(){
		if(u.slServicio.value==0){
			mens.show("A","Debe capturar Servicio","¡Atención!","slServicio");
			return false;
		}
		
		if(document.getElementById('fila').value == ""){
			if(tabla1.getRecordCount()>0){
				for(var i=0; i < tabla1.getRecordCount(); i++){
					if(u["detalle_IDSERVICIO"][i].value == u.slServicio.value){
						mens.show("A","El Servicio ya fue agregado","¡Atención!","slServicio");
						return false;
					}
				}
			}
		}
		
		if(document.getElementById('costo').value==""){
			mens.show("A",'Debe Capturar Costo','¡Atención!','costo');
			return false;	
		}
		
		if(parseFloat(document.getElementById('costo').value)<0){
			mens.show("A",'Costo debe ser Mayor a Cero','¡Atención!','costo');	
			return false;
		}
		
		if(u.condicion.checked==true){
			if(document.getElementById('limite').value==""){
				mens.show("A",'Debe Capturar Limite','¡Atención!','limite');
				return false;
			}else if(parseFloat(document.getElementById('limite').value)<0){
				mens.show("A",'Limite debe ser Mayor a Cero','¡Atención!','limite');
				return false;
			}else if(document.getElementById('porcada').value==""){
				mens.show("A",'Debe Capturar Por Cada','¡Atención!','porcada');
				return false;
			}else if(parseFloat(document.getElementById('porcada').value)<0){
				mens.show("A",'Por Cada debe ser Mayor a Cero','¡Atención!','porcada');
				return false;
			}else if(document.getElementById('costoextra').value==""){
				mens.show("A",'Debe Capturar Costo Extra','¡Atención!','costoextra');
				return false;
			}else if(parseFloat(document.getElementById('costoextra').value)<0){
				mens.show("A",'Costo Extra debe ser Mayor a Cero','¡Atención!','costoextra');
				return false;
			}
		}
		
		var obj = new Object();
		obj.servicio = u.slServicio.options[u.slServicio.selectedIndex].text;
		obj.costo = document.getElementById('costo').value;
		obj.limite = ((document.getElementById('limite').value!="")? document.getElementById('limite').value : 0);
		obj.porcada = ((document.getElementById('porcada').value!="")? document.getElementById('porcada').value : 0);
		obj.costoextra = ((document.getElementById('costoextra').value!="") ? document.getElementById('costoextra').value : 0 );
		obj.condicion = ((u.condicion.checked==true) ? 1 : 0);
		obj.idservicio = u.slServicio.value;
		
		if(document.getElementById('fila').value == ""){
			tabla1.add(obj);
		}else{
			tabla1.updateRowById(document.getElementById('fila').value, obj);
			u.slServicio.value = 0;
			u.condicion.checked = false;
			document.getElementById('costoextra').value = "";
			document.getElementById('porcada').value = "";
			document.getElementById('limite').value = "";
			document.getElementById('costo').value = "";
			document.getElementById('fila').value = "";
			Habilitar();
		}
	}
	
	function modificarFila(){
		if(tabla1.getRecordCount()>0){
			var obj = tabla1.getSelectedRow();
			u.condicion.checked = ((obj.condicion==1) ? true : false);
			Habilitar();
			u.slServicio.value = obj.idservicio;
			document.getElementById('costoextra').value = obj.costoextra;
			document.getElementById('porcada').value = obj.porcada;
			document.getElementById('limite').value = obj.limite;
			document.getElementById('costo').value = obj.costo;
			document.getElementById('fila').value = tabla1.getSelectedIdRow();
			u.td_agregar.innerHTML = v_modificar;
		}
	}
	
	function limpiar(){
		u.td_agregar.innerHTML = v_agregar;
		u.condicion.checked = false;
		u.slServicio.value = 0;
		document.getElementById('costo').value = "";
		document.getElementById('fila').value = "";
		document.getElementById('accion').value = "";
		document.getElementById('codigo').value = "";
		document.getElementById('servicio').value = "";
		Habilitar();
		obtenerServicios();
	}
	
	function eliminarServicio(){
		if(tabla1.getRecordCount()>0){
			tabla1.deleteById(tabla1.getSelectedIdRow());
		}
	}
	
</script>
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="550" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">CONFIGURADOR DE SERVICIOS </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="14%"><span class="Tablas">Servicio:</span></td>
          <td width="33%"><select name="slServicio" id="slServicio" class="Tablas">
            <option selected="selected" value="0">SELECCIONAR SERVICIO</option>
            <? $sql=@mysql_query("SELECT * FROM catalogoservicio",$l);
				  while($row=mysql_fetch_array($sql)){
				   ?>
            <option value="<?=$row[0];?>" <? if($row[0]==$slServicio){ echo 'selected'; } ?>>
              <?=$row[1];?>
              </option>
            <? } ?>
          </select></td>
          <td width="16%"><span class="Tablas">
            <input name="condicion" type="checkbox" id="condicion" onclick="Habilitar();" value="1" <? if($condicion==1){ echo 'checked'; } ?> />
Condici&oacute;n</span></td>
          <td width="37%">&nbsp;</td>
        </tr>
        <tr>
          <td><span class="Tablas">Costo:</span></td>
          <td><span class="Tablas">
            <input name="costo" type="text" class="Tablas" id="costo" onkeypress="if(event.keyCode==13){if(document.all.condicion.checked==true){document.all.limite.focus()}}else{return tiposMoneda(event,this.value);}" value="<?=$costo ?>" size="20" />
          </span></td>
          <td><span class="Tablas">Limite: </span></td>
          <td><span class="Tablas">
            <input name="limite" type="text" disabled="disabled" class="Tablas" id="limite" onkeypress="if(event.keyCode==13){document.all.porcada.focus()}else{return solonumeros2(event);}" style="font:tahoma;background:#FFFF99"  value="<?=$limite ?>" size="20" />
          </span></td>
        </tr>
        <tr>
          <td><span class="Tablas">Por Cada:</span></td>
          <td><input name="porcada" type="text" disabled="disabled" class="Tablas" id="porcada" onkeypress="if(event.keyCode==13){document.all.costoextra.focus()}else{return solonumeros2(event);}" style="font:tahoma;background:#FFFF99"  value="<?=$porcada ?>" size="20" /></td>
          <td><span class="Tablas">Costo Extra: </span></td>
          <td><input name="costoextra" type="text" disabled="disabled" class="Tablas" id="costoextra" onkeypress="return tiposMoneda(event,this.value);" style="font:tahoma;background:#FFFF99" value="<?=$costoextra ?>" size="20" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="59%" align="right"><div class="ebtn_eliminar" onclick="mens.show('C','¿Se encuentra seguro de eliminar el servicio seleccionado?','','','eliminarServicio()');"></div></td>
              <td width="41%" align="right" id="td_agregar"><div class="ebtn_agregar" onclick="agregarServicio()"></div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">           
          </table></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input name="accion" type="hidden" id="accion" value="<?=$accion; ?>" />
            <input name="codigo" type="hidden" id="codigo" value="<?=$codigo; ?>" />
            <input name="servicio" type="hidden" id="servicio" value="<?=$servicio; ?>" />
            <input name="fila" type="hidden" id="fila" />
            <input name="registros" type="hidden" id="registros" /></td>
          <td>&nbsp;</td>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="59%" align="right"><div class="ebtn_guardar" onclick="mens.show('C','Se guardaran los servicios configurados ¿Desea continuar?','','','guardar()');"></div></td>
              <td width="41%" align="right"><div class="ebtn_nuevo" onclick="limpiar()"></div></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
<? 
	if ($mensaje!=""){
		echo "<script language='javascript' type='text/javascript'>mens.show('I','".$mensaje."', '');</script>";
	}
?>