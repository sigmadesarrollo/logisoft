<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');	
	$link=Conectarse('webpmm'); 
	
	if($_POST['accion'] == ""){
		$fecha = date('d/m/Y');
		$s = "SELECT obtenerFolio('preliquidaciondebitacora',".$_SESSION[IDSUCURSAL].") AS folio";
		$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);		
		$folio = $fo->folio;
		
	}else if($_POST['accion'] == "grabar"){		
		$sqlIns =mysql_query("insert into preliquidaciondebitacora 
		(folio,foliobitacora, afavorencontra, cantidad,entrego,recibio,usuario,fecha,sucursal)
		values
		(obtenerFolio('preliquidaciondebitacora',".$_SESSION[IDSUCURSAL]."),'".$_POST['foliobitacora']."',
		'".$_POST[r]."', '".$_POST[cantidad]."','".$_POST[entrego]."','".$_POST[recibio]."',
		'".$_SESSION[NOMBREUSUARIO]."',CURRENT_DATE,".$_SESSION[IDSUCURSAL].")",$link) or die(mysql_error($link));		
		$folio = mysql_insert_id();
		
		$s = "SELECT folio FROM preliquidaciondebitacora WHERE id = ".$folio;
		$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);		
		$folio = $fo->folio;
		
		$update=mysql_query("UPDATE bitacorasalida SET preliquidaciondebitacora='1' 
		WHERE folio='".$_POST['foliobitacora']."'",$link)or die(mysql_error($link));
		$mensaje ='Los datos han sido guardados correctamente';
		$accion = "modificar";
		$fecha=$_POST[fecha];
	
	}else if($_POST['accion'] == "modificar"){
	
		$sqlUpd = mysql_query("UPDATE preliquidaciondebitacora SET 
		foliobitacora='".$_POST['foliobitacora']."', 
		afavorencontra='".$_POST[r]."',cantidad='".$_POST[cantidad]."',entrego='".$_POST[entrego]."',
		recibio='".$_POST[recibio]."',usuario='".$_SESSION[NOMBREUSUARIO]."',fecha='CURRENT_DATE' 
		WHERE folio='".$_POST['folio']."' AND sucursal = ".$_SESSION[IDSUCURSAL]."",$link)or die(mysql_error($link));	
		
		$update=mysql_query("UPDATE bitacorasalida SET preliquidaciondebitacora='1' 
		WHERE folio='".$_POST['foliobitacora']."'",$link)or die(mysql_error($link));
		$mensaje ='Los cambios han sido guardados correctamente';
		$fecha=$_POST[fecha];
	}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/shortcut.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<script>
var u = document.all;

	function tabular(e,obj) 
			{
				tecla=(document.all) ? e.keyCode : e.which;
				if(tecla!=13) return;
				frm=obj.form;
				for(i=0;i<frm.elements.length;i++) 
					if(frm.elements[i]==obj) 
					{ 
						if (i==frm.elements.length-1) 
							i=-1;
						break 
					}
				if (frm.elements[i+1].disabled ==true )    
					tabular(e,frm.elements[i+1]);
				else if (frm.elements[i+1].readOnly ==true )    
					tabular(e,frm.elements[i+1]);
				else frm.elements[i+1].focus();
				return false;
	}  

	function obtener(folio){
	//obtener folio bitacora salida
		consulta("ObtenerFolio","consultaCORM.php?folio="+folio+"&accion="+11+"&sid="+Math.random());
	}
	
	function ObtenerFolio(datos){
			var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
			if(con>0){
				u.foliobitacora.value	=datos.getElementsByTagName('foliobitacora').item(0).firstChild.data;
				u.unidad.value			=datos.getElementsByTagName('unidad').item(0).firstChild.data;
				u.gastos.value			=convertirMoneda(datos.getElementsByTagName('gastos').item(0).firstChild.data);
				u.gastos2.value			=datos.getElementsByTagName('gastos').item(0).firstChild.data;
				u.conductor.value		=datos.getElementsByTagName('conductor').item(0).firstChild.data;
			}else{
				u.foliobitacora.value	="";
				u.unidad.value			="";
				u.gastos.value			="";
				u.gastos2.value			="";
				u.conductor.value		="";
			}
	}
	
	function obtenerEmpleadoEntrego(id){
		consulta("mostrarEmpleadoEntrego","consultaCORM.php?id="+id+"&accion="+12+"&sid="+Math.random());
	}
	
	function mostrarEmpleadoEntrego(datos){
			var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
			if(con>0){
				u.entrego.value			=datos.getElementsByTagName('id').item(0).firstChild.data;
				u.empleadoentrego.value	=datos.getElementsByTagName('empleado').item(0).firstChild.data;
			}else{
				u.entrego.value			="";
				u.empleadoentrego.value	="";
			}
	}
	
	function obtenerEmpleadoRecibio(id){
		consulta("mostrarEmpleadoRecibio","consultaCORM.php?id="+id+"&accion="+12+"&sid="+Math.random());
	}
	
	function mostrarEmpleadoRecibio(datos){
			var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
			if(con>0){
				u.recibio.value			=datos.getElementsByTagName('id').item(0).firstChild.data;
				u.empleadorecibio.value	=datos.getElementsByTagName('empleado').item(0).firstChild.data;
			}else{
				u.recibio.value			="";
				u.empleadorecibio.value	="";
			}
	}
	
	function Validar(){
		if(u.conductor.value==""){
				alerta('Debe Capturar Folio Bitácora','¡Atención!','foliobitacora');
		}else if(u.cantidad.value==""){
				alerta('Debe Capturar cantidad','¡Atención!','cantidad');
		}else if(u.empleadoentrego.value==""){
				alerta('Debe Capturar Entrego','¡Atención!','empleadoentrego');			
		}else if(u.empleadorecibio.value==""){
				alerta('Debe Capturar Recibio','¡Atención!','empleadorecibio');	
		}else if(parseFloat(u.cantidad.value) > parseFloat(u.gastos2.value)){				
				alerta('La Cantidad es Mayor que el Gasto','¡Atención!','cantidad');			
		}else{
			if(u.accion.value==""){
				u.accion.value = "grabar";
				document.form1.submit();			
			}else{
				u.accion.value = "modificar";
				document.form1.submit();			
			}		
		}
	}
	
	function Limpiar(){
		u.folio.value			="";
		u.fecha.value			="";
		u.foliobitacora.value	="";
		u.unidad.value			="";
		u.gastos.value			="";
		u.gastos2.value			="";
		u.conductor.value		="";
		u.r.value				="";
		u.cantidad.value		="";
		u.entrego.value			="";
		u.empleadoentrego.value	="";
		u.recibio.value			="";
		u.empleadorecibio.value	="";
		u.accion.value			="";
		u.tb_guardar.innerHTML  ="<div class=\"ebtn_guardar\" onClick=\"Validar();\"></div>";
		consulta("mostrarfolio","consultaCORM.php?accion="+14+"&sid="+Math.random());	
	}
	
	function mostrarfolio(datos){
				u.folio.value			=datos.getElementsByTagName('folio').item(0).firstChild.data;
				u.fecha.value			=datos.getElementsByTagName('fecha').item(0).firstChild.data;
	}
	
	function OptenerFolioPreliberacion(folio){
		consulta("mostrarFolioPreliberacion","consultaCORM.php?folio="+folio+"&accion="+13+"&sid="+Math.random());
	}

	function mostrarFolioPreliberacion(datos){
			var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
			if(con>0){
				u.folio.value			=datos.getElementsByTagName('folio').item(0).firstChild.data;
				u.fecha.value			=datos.getElementsByTagName('fecha').item(0).firstChild.data;
				u.foliobitacora.value	=datos.getElementsByTagName('foliobitacora').item(0).firstChild.data;
				u.unidad.value			=datos.getElementsByTagName('unidad').item(0).firstChild.data;
				u.gastos.value			=convertirMoneda(datos.getElementsByTagName('gastos').item(0).firstChild.data);
				u.gastos2.value			=datos.getElementsByTagName('gastos').item(0).firstChild.data;
				u.conductor.value		=datos.getElementsByTagName('conductor').item(0).firstChild.data;
				var radio	= datos.getElementsByTagName('r').item(0).firstChild.data;
				if(radio==1){
					u.r[0].checked=true;
				}else{
					u.r[1].checked=true;
				}
				u.cantidad.value		=datos.getElementsByTagName('cantidad').item(0).firstChild.data;
				u.entrego.value			=datos.getElementsByTagName('entrego').item(0).firstChild.data;
				u.empleadoentrego.value	=datos.getElementsByTagName('empleadoentrego').item(0).firstChild.data;
				u.recibio.value			=datos.getElementsByTagName('recibio').item(0).firstChild.data;
				u.empleadorecibio.value	=datos.getElementsByTagName('empleadorecibio').item(0).firstChild.data;
				u.tb_guardar.innerHTML="<div class=\"ebtn_imprimir\" onClick=\"Imprimir()\"></div>";
			}
	}

	function Imprimir(){
		alert('Funcion Imprimir');
	}

	function convertirMoneda(valor){
		valorx = (valor=="")?"0.00":valor;
		valor1 = Math.round(parseFloat(valorx)*100)/100;
		valor2 = "$ "+numcredvar(valor1.toLocaleString());
		return valor2;
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	
</script>
<style type="text/css">
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.Estilo5 {
	font-size: 9px;
	font-family: tahoma;
	font-style: italic;
}
-->
</style>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="550" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="600" class="FondoTabla Estilo4">PRELIQUIDACI&Oacute;N DE BIT&Aacute;CORA</td>
  </tr>
  <tr>
    <td ><div align="center">
      <table width="540" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="4" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" align="right"><table width="60%" height="18" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="10%">Folio:</td>
              <td width="33%"><input name="folio" type="text" id="folio" style="width:100px; background:#FF9" value="<?=$folio ?>" readonly="readonly"></td>
              <td width="7%"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarPreliberaciondeGastosGen.php?funcion=OptenerFolioPreliberacion', 550, 450, 'ventana', 'Busqueda')" ></div></td>
              <td width="12%">Fecha:</td>
              <td width="38%"><input name="fecha" type="text" id="fecha" style="width:100px; background:#FF9" value="<?=$fecha ?>" readonly="readonly"></td>
            </tr>
          </table>
            
            </td>
        </tr>
        <tr>
          <td colspan="4" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td style="width:75px">Folio Bit&aacute;cora<br></td>
              <td width="10%"><input name="foliobitacora" type="text" id="foliobitacora" style="width:100px" onKeyDown="if(event.keyCode=='13'){obtener(this.value);};return tabular(event,this)" value="<?=$_POST[foliobitacora] ?>" onKeyPress="" ></td>
              <td><div class="ebtn_buscar" onClick="abrirVentanaFija('buscarBitacora_Preliquidacion.php', 550, 450, 'ventana', 'Busqueda')"></div></td>
              <td>Unidad<br></td>
              <td><input name="unidad" type="text" id="unidad" style="width:100px; background:#FF9" onKeyDown="return tabular(event,this)" value="<?=$_POST[unidad] ?>" readonly="readonly" ></td>
              <td>Gastos<br></td>
              <td><input name="gastos" type="text" id="gastos" style="text-align:right;width:100px; background:#FF9" onKeyDown="return tabular(event,this)" value="<?=$_POST[gastos] ?>" readonly="readonly"  ></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td style="width:75px">Conductor</td>
              <td ><input name="conductor" type="text" id="conductor" style="width:443px; background:#FF9" onKeyDown="return tabular(event,this)" value="<?=$_POST[conductor] ?>" readonly="readonly"  ></td>
            </tr>
          </table>            </td>
        </tr>
        <tr>
          <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="13%"><label>
                <input name="r" type="radio" onKeyDown="return tabular(event,this)" value="1" <? if($_POST[r]!='0'){echo "checked"; }?> >
                A Favor</label></td>
              <td width="56%"><label>
                <input name="r" type="radio" value="0" onKeyDown="return tabular(event,this)" <? if($_POST[r]=='0'){echo "checked"; }?> >
                En Contra</label></td>
              <td width="8%">Cantidad<br></td>
              <td width="23%"><input name="cantidad" type="text" id="cantidad" style="width:100px" onKeyDown="return tabular(event,this)" value="<?=$_POST[cantidad] ?>"  ></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td style="width:60px">Entrego:</td>
          <td style="width:70px"><input name="entrego" type="text" id="entrego" style="width:70px" onKeyDown=" if(event.keyCode==13){obtenerEmpleadoEntrego(this.value);};return tabular(event,this)" value="<?=$_POST[entrego] ?>"  ></td>
          <td style="width:25px"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarEmpleado.php?funcion=obtenerEmpleadoEntrego', 550, 450, 'ventana', 'Busqueda')"></div></td>
          <td  style="width:390px"><span class="Tablas">
            <input name="empleadoentrego" type="text" class="Tablas" id="empleadoentrego" style="width:365px;background:#FFFF99" onKeyDown="return tabular(event,this)" value="<?=$_POST[empleadoentrego] ?>" readonly="" />
          </span></td>
        </tr>
        <tr>
          <td>Recibi&oacute;:</td>
          <td><input name="recibio" type="text" id="recibio" style="width:70px" onKeyDown="if(event.keyCode=='13'){obtenerEmpleadoRecibio(this.value)};return tabular(event,this)" value="<?=$_POST[recibio] ?>" ></td>
          <td><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarEmpleado.php?funcion=obtenerEmpleadoRecibio', 550, 450, 'ventana', 'Busqueda')"></div></td>
          <td><span class="Tablas">
            <input name="empleadorecibio" type="text" class="Tablas" id="empleadorecibio" style="width:365px;background:#FFFF99" onKeyDown="return tabular(event,this)" value="<?=$_POST[empleadorecibio] ?>" readonly="" />
          </span></td>
        </tr>
        <tr>
          <td colspan="4"></td>
        </tr>
        <tr>
          <td colspan="4"></td>
        </tr>
        
        <tr>
          <td colspan="4" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">            </td>
        </tr>
        <tr>
          <td colspan="4"><input name="accion" type="hidden" id="accion">
            <input name="gastos2" type="hidden" id="gastos2" style="text-align:right;width:100px; background:#FF9" onKeyDown="return tabular(event,this)" value="<?=$_POST[gastos2] ?>" readonly="readonly"  >
            <table width="27%" height="13" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td id="tb_guardar"><? if($_POST[accion]==""){?><div class="ebtn_guardar" onClick="Validar();"></div><? }else{?><div class="ebtn_imprimir" onClick="Imprimir()"></div> <? } ?></td>
              <td><div class="ebtn_nuevo" onClick="Limpiar()"></div></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div></td>
  </tr>
</table>
</form>
</body>

</html>