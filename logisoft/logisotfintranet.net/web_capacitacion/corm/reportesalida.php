<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
include('../Conectar.php');	
$link=Conectarse('webpmm');
$gcomprobar=$_POST['gcomprobar'];$empleado1=$_GET['empleado1'];$empleadob1=$_GET['empleadob1'];$empleado2=$_GET['empleado2'];$empleadob2=$_GET['empleadob2'];
?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="select.js"></script>
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
<script>
var u = document.all;
function obtenerEmpleadoBusqueda(id,caja){
		if(id!=""){
			switch(caja){
				case "1":
					u.empleado1.value = id;
				break;
				case "2":		
					u.empleado2.value = id;
				break;
			}
consulta("mostrarEmpleado","consultaCORM.php?accion=10&id="+id+"&caja="+caja);
		}
	}
	function obtenerEmpleado(e,id,caja){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 && id!=""){
consulta("mostrarEmpleado","consultaCORM.php?accion=10&id="+id+"&caja="+caja);
		}
	}
	function mostrarEmpleado(datos){
	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
	var caja = datos.getElementsByTagName('caja').item(0).firstChild.data;
		if(con>0){
		switch(caja){
		case "1":
u.empleadob1.value = datos.getElementsByTagName('nombre').item(0).firstChild.data;
		break;
		case "2":		
u.empleadob2.value = datos.getElementsByTagName('nombre').item(0).firstChild.data;
		break;
		}
		
		}else{
			alerta3('La persona no existe','¡Atención!','recibe'+caja);
			switch(caja){
				case "1":
					u.empleadob1.value = "";
				break;
				case "2":		
					u.empleadob2.value = "";
				break;
			}
		}
	}
	var nav4 = window.Event ? true : false;
	function Numeros(evt){ 
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46, ',' = 44 
	var key = nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
	}
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
				else if(frm.elements[i+1].readOnly ==true )
					tabular(e,frm.elements[i+1]);
				else frm.elements[i+1].focus();
				return false;
	}
	function foco(nombrecaja){
	if(nombrecaja=="empleado1"){
		u.oculto.value="1";
	}else if(nombrecaja=="empleado2"){
		u.oculto.value="2";
	}
}
	function borrarDescripciones(nombrecaja){
		if(nombrecaja =="empleado1" && u.empleado1.value ==""){
			u.empleadob1.value = "";			
		}else if(nombrecaja =="empleado2" && u.empleado2.value ==""){
			u.empleadob2.value = "";
		}
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
<table width="604" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="600" class="FondoTabla Estilo4">REPORTE DE SALIDA</td>
  </tr>
  <tr>
    <td height="13"><div align="center">
      <table width="592" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="592"><table width="590" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="485"><div align="right">Fecha</div></td>
              <td width="105"><label>
                <select name="select" style="width:100px">
                </select>
              </label></td>
            </tr>
          </table></td>
        </tr>
        
        <tr>
          <td><table width="592" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="40">Elaboro<br></td>
              <td width="100"><label><span class="Tablas">
              <input name="empleado1" type="text" class="Tablas" id="empleado1" style="width:100px" onKeyDown="obtenerEmpleado(event,this.value,1); return tabular(event,this)" value="<?=$empleado1 ?>" onKeyPress="return Numeros(event); " onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''; borrarDescripciones(this.name)" />
              </span></label></td>
              <td width="24"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarEmpleado.php?funcion=obtenerEmpleadoBusqueda&caja=1', 550, 450, 'ventana', 'Busqueda')"></div></td>
              <td width="428"><span class="Tablas">
                <input name="empleadob1" type="text" class="Tablas" id="empleadob1" style="width:300px;background:#FFFF99" value="<?=$empleadob1 ?>" readonly=""/>
              </span></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="592" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="41">Revis&oacute;<br></td>
              <td width="100"><label><span class="Tablas">
              <input name="empleado2" type="text" class="Tablas" id="empleado2" style="width:100px" onKeyDown="obtenerEmpleado(event,this.value,2); if(event.keyCode==13){borrarDescripciones(this.name)}" value="<?=$empleado2 ?>" onKeyPress="return Numeros(event); " onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''; borrarDescripciones(this.name)" />
              </span></label></td>
              <td width="24"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarEmpleado.php?funcion=obtenerEmpleadoBusqueda&caja=2', 550, 450, 'ventana', 'Busqueda')"></div></td>
              <td width="427"><span class="Tablas">
                <input name="empleadob2" type="text" class="Tablas" id="empleadob2" style="width:300px;background:#FFFF99" value="<?=$empleadob2 ?>" readonly=""/>
              </span></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="302" border="0" align="left" cellpadding="0" cellspacing="0">
            <tr>
              <td width="3" height="16" class="formato_columnas_izq"></td>
              <td width="52" class="formato_columnas" align="center">Unidad</td>
              <td width="47" class="formato_columnas" align="center">Remolques </td>
              <td width="56" class="formato_columnas" align="center">Folio Bitacora</td>
              <td width="63" class="formato_columnas" align="center">Conductores</td>
              <td width="41" class="formato_columnas" align="center">Ruta</td>
              <td width="33" class="formato_columnas" align="center">Hora</td>
              <td width="49" class="formato_columnas" align="center">Fecha</td>
              <td width="49" class="formato_columnas" align="center">Licencia</td>
              <td width="49" class="formato_columnas" align="center">T.Unidad</td>
              <td width="62" class="formato_columnas" align="center">T.Remolque</td>
              <td width="45" class="formato_columnas" align="center">Poliza</td>
              <td width="40" class="formato_columnas" align="center">VRF</td>
              <td width="1"  class="formato_columnas_der"></td>
            </tr>
            <tr>
              <td colspan="15" align="right"><div id="div" name="detalle" style="width:590px; height:80px; overflow:auto" align="left">
                  <? $line = 0; ?>
                  <table width="574" border="0" cellspacing="0" cellpadding="0">
                    <?		
			while($line<=200){?>
                    <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >
                      <td height="16" width="22" ><input name="id2" type="hidden" value="<?=$row[id] ?>" /></td>
                     <td width="164" align="center" class="style31"><input name="unidad" type="text" class="style2" id="unidad" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
					  <td width="164" align="center" class="style31"><input name="remolque" type="text" class="style2" id="remolque" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
					  <td width="164" align="center" class="style31"><input name="foliob" type="text" class="style2" id="foliob" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
					  <td width="164" align="center" class="style31"><input name="conductores" type="text" class="style2" id="conductores" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
					  <td width="164" align="center" class="style31"><input name="ruta" type="text" class="style2" id="ruta" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td><td width="164" align="center" class="style31"><input name="hora" type="text" class="style2" id="hora" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
					  <td width="164" align="center" class="style31"><input name="fecha" type="text" class="style2" id="fecha" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
					  <td width="164" align="center" class="style31"><input name="licencia" type="text" class="style2" id="licencia" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
					  <td width="164" align="center" class="style31"><input name="tunidad" type="text" class="style2" id="tunidad" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
					  <td width="164" align="center" class="style31"><input name="tremolque" type="text" class="style2" id="tremolque" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
					  <td width="164" align="center" class="style31"><input name="poliza" type="text" class="style2" id="poliza" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
					  <td width="164" align="center" class="style31"><input name="saldo" type="text" class="style2" id="saldo" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
                      <td width="12" align="center" class="style31">&nbsp;</td>
                    </tr>
                    <?
		$line ++ ; }			
	?>
                  </table>
              </div></td>
            </tr>
          </table></td>
        </tr>
        
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
            <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>"></td>
        </tr>
      </table>
    </div></td>
  </tr>
</table>
 <p>&nbsp;</p>
</form>
</body>
<script>
	//parent.frames[1].document.getElementById('titulo').innerHTML = 'REPORTE SALIDA';
</script>
</html>