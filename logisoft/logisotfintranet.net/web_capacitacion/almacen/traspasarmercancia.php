<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	$folio=$_POST[folio]; $fecha2=$_POST[fecha2];$sucdestino=$_POST[sucdestino];
	$id_remitente=$_POST[id_remitente];$rfc_remitente=$_POST[rfc_remitente];	$cliente_remitente=$_POST[cliente_remitente];$calle_remitente=$_POST[calle_remitente];	$numero_remitente=$_POST[numero_remitente];	$cp_remitente=$_POST[cp_remitente];	$colonia_remitente=$_POST[colonia_remitente];	$poblacion_remitente=$_POST[poblacion_remitente];	$telefono_remitente=$_POST[telefono_remitente];	$id_destinatario=$_POST[id_destinatario];$rfc_destinatario=$_POST[rfc_destinatario];	$cliente_destinatario=$_POST[cliente_destinatario];	$calle_destinatario=$_POST[calle_destinatario];$numero_destinatario=$_POST[numero_destinatario];
	$cp_destinatario=$_POST[cp_destinatario];	$colonia_destinatario=$_POST[colonia_destinatario];	$poblacion_destinatario=$_POST[poblacion_destinatario];	$telefono_destinatario=$_POST[telefono_destinatario];$registros=$_POST[registros];$motivos=$_POST[motivos];
	$usuario=$_SESSION[NOMBREUSUARIO];$idusuario=$_SESSION[IDUSUARIO];
	//$usuario="ADMIN";$idusuario="1"; 
	
	

	
if($_POST[accion]==""){	
	$fecha = date('d/m/Y');
}else if($_POST[accion]=="grabar"){
			$result=mysql_query("insert into traspasarmercancia  (id, folio, idremitente, remitente, iddestinatario,destinatario, 
	sucursaldestino, idusuario, usuario, fecha) values 	(NULL, '$folio', '$id_remitente', '$cliente_remitente', '$id_destinatario',
 	'$cliente_destinatario','$sucdestino', '$idusuario', '$usuario', CURRENT_DATE)",$link) or die (mysql_error($link));
			$codigo=mysql_insert_id();
			
			$update_empresariales=mysql_query("UPDATE guiasempresariales SET ubicacion='TRASPASO PENDIENTE' where id='$folio'",$link) or die (mysql_error($link));
			$update_ventanilla=mysql_query("UPDATE guiasventanilla SET ubicacion='TRASPASO PENDIENTE' where id='$folio'",$link) or die (mysql_error($link));
			
			
	if($registros>0){	
		for($i=0;$i<$registros;$i++){		
			$detalle .= "{
				idmercancia:'".$_POST["tablaconteva_IDM"][$i]."',
				cantidad:'".$_POST["tablaconteva_Cant"][$i]."',
				descripcion:'".$_POST["tablaconteva_Descripcion"][$i]."',
				contenido:'".$_POST["tablaconteva_Contenido"][$i]."',
				peso:'".$_POST["tablaconteva_Peso"][$i]."',
				volumen:'".$_POST["tablaconteva_Vol"][$i]."',
				importe:'".$_POST["tablaconteva_Importe"][$i]."'},";
		}$detalle = substr($detalle,0,strlen($detalle)-1);
	}
		$msg ='Los datos han sido guardados correctamente.';
		$accion="modificar";
		$fecha = $_POST[fecha];
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script src="../javascript/shortcut.js"></script>
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<script language="JavaScript" src="../javascript/ajax.js"></script>


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
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
<script>
var u = document.all;
var tabla1 = new ClaseTabla();
	
	tabla1.setAttributes({
		nombre:"tablaconteva",
		campos:[
			{nombre:"IDM", medida:4, alineacion:"left", tipo:"oculto", datos:"idmercancia"},
			{nombre:"CANT", medida:50, alineacion:"left", datos:"cantidad"},
			{nombre:"DESCRIPCION", medida:120, alineacion:"left", datos:"descripcion"},
			{nombre:"CPNTENIDO", medida:100, alineacion:"left", datos:"contenido"},
			{nombre:"PESO", medida:70, alineacion:"right", datos:"peso"},
			{nombre:"VOL", medida:70, alineacion:"right", datos:"volumen"},
			{nombre:"IMPORTE", medida:100, tipo:"moneda", alineacion:"right", datos:"importe"}
			
		],
		filasInicial:10,
		alto:100,
		seleccion:true,
		ordenable:true,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtenerDetalles();
	}
	
	function obtenerDetalles(){
	var datosTabla = <? if($detalle!=""){echo "[".$detalle."]";}else{echo "0";} ?>;
		if(datosTabla!=0){			
			for(var i=0; i<datosTabla.length;i++){
				tabla1.add(datosTabla[i]);
			}
		}
	}
	

function obtenerGuias(folio){
	consulta("mostrarGuias","traspasarmercancia_con.php?accion=1&folio="+folio+"&sid="+Math.random());
}
	function mostrarGuias(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var u = document.all;
		if(con>0){
			u.folio.value		=datos.getElementsByTagName('folio').item(0).firstChild.data;
			u.fecha2.value		=datos.getElementsByTagName('fecha2').item(0).firstChild.data;
			u.sucdestino.value	=datos.getElementsByTagName('sucdestino').item(0).firstChild.data;
			u.id_remitente.value=datos.getElementsByTagName('id_remitente').item(0).firstChild.data;
			u.rfc_remitente.value		=datos.getElementsByTagName('rfc_remitente').item(0).firstChild.data;
			u.cliente_remitente.value	=datos.getElementsByTagName('cliente_remitente').item(0).firstChild.data;
			u.calle_remitente.value		=datos.getElementsByTagName('calle_remitente').item(0).firstChild.data;
			u.numero_remitente.value	=datos.getElementsByTagName('numero_remitente').item(0).firstChild.data;
			u.cp_remitente.value		=datos.getElementsByTagName('cp_remitente').item(0).firstChild.data;
			u.colonia_remitente.value	=datos.getElementsByTagName('colonia_remitente').item(0).firstChild.data;
			u.poblacion_remitente.value	=datos.getElementsByTagName('poblacion_remitente').item(0).firstChild.data;
			u.telefono_remitente.value	=datos.getElementsByTagName('telefono_remitente').item(0).firstChild.data;
			u.id_destinatario.value		=datos.getElementsByTagName('id_destinatario').item(0).firstChild.data;
			u.rfc_destinatario.value	=datos.getElementsByTagName('rfc_destinatario').item(0).firstChild.data;
			u.cliente_destinatario.value=datos.getElementsByTagName('cliente_destinatario').item(0).firstChild.data;
			u.calle_destinatario.value	=datos.getElementsByTagName('calle_destinatario').item(0).firstChild.data;
			u.numero_destinatario.value	=datos.getElementsByTagName('numero_destinatario').item(0).firstChild.data;
			u.cp_destinatario.value		=datos.getElementsByTagName('cp_destinatario').item(0).firstChild.data;
			u.colonia_destinatario.value=datos.getElementsByTagName('colonia_destinatario').item(0).firstChild.data;
			u.poblacion_destinatario.value	=datos.getElementsByTagName('poblacion_destinatario').item(0).firstChild.data;
			u.telefono_destinatario.value	=datos.getElementsByTagName('telefono_destinatario').item(0).firstChild.data;
			tabla1.setXML(datos);
			u.guardar.style.visibility="visible";
		}else{
			alerta("El numero de Guia no existe","¡Atención!","folio");
			u.folio.value = "";			
			u.fecha2.value		="";
			u.sucdestino.value	="";
			u.id_remitente.value="";
			u.rfc_remitente.value		="";
			u.cliente_remitente.value	="";
			u.calle_remitente.value		="";
			u.numero_remitente.value	="";
			u.cp_remitente.value		="";
			u.colonia_remitente.value	="";
			u.poblacion_remitente.value	="";
			u.telefono_remitente.value	="";
			u.id_destinatario.value		="";
			u.rfc_destinatario.value	="";
			u.cliente_destinatario.value="";
			u.calle_destinatario.value	="";
			u.numero_destinatario.value	="";
			u.cp_destinatario.value		="";
			u.colonia_destinatario.value="";
			u.poblacion_destinatario.value	="";
			u.telefono_destinatario.value	="";
			tabla1.clear();
		}
	}


	function Validar(){
		<?=$cpermiso->verificarPermiso(318,$_SESSION[IDUSUARIO]);?>
		u.registros.value = tabla1.getRecordCount();
		if(u.folio.value==""){
			alerta('Debe capturar Folio','¡Atención!','folio');
			return false;
		}if(u.motivos.value=="0"){
			alerta('Debe capturar Motivos','¡Atención!','motivos');
			return false;
		}
		
		if(document.getElementById('accion').value==""){
				document.getElementById('accion').value = "grabar";
				document.form1.submit();
		}else if(document.getElementById('accion').value=="modificar"){
				document.form1.submit();
		}
	}



function limpiar(){
			u.folio.value		="";
			u.fecha2.value		="";
			u.sucdestino.value	="";
			u.id_remitente.value="";
			u.rfc_remitente.value		="";
			u.cliente_remitente.value	="";
			u.calle_remitente.value		="";
			u.numero_remitente.value	="";
			u.cp_remitente.value		="";
			u.colonia_remitente.value	="";
			u.poblacion_remitente.value	="";
			u.telefono_remitente.value	="";
			u.id_destinatario.value		="";
			u.rfc_destinatario.value	="";
			u.cliente_destinatario.value="";
			u.calle_destinatario.value	="";
			u.numero_destinatario.value	="";
			u.cp_destinatario.value		="";
			u.colonia_destinatario.value="";
			u.poblacion_destinatario.value	="";
			u.motivos.value =0;
			u.telefono_destinatario.value	="";
			tabla1.clear();
			u.guardar.style.visibility="visible";
}
</script>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
  <table width="548" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="544" class="FondoTabla Estilo4">TRASPASAR MERCANC&Iacute;A</td>
    </tr>
    <tr>
      <td><table width="544" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="544" colspan="8"><div align="right">Fecha
              <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
            </div></td>
          </tr>
          
          <tr>
            <td colspan="8"><table width="544" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="36">Guia:<br /></td>
                    <td width="122"><input name="folio" type="text" class="Tablas" id="folio" style="width:115px" value="<?=$folio ?>" maxlength="13" onkeypress="if(event.keyCode==13){obtenerGuias(this.value);}"/></td>
                    <td width="38"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarGuiasEmpresariales_VentanillaGen.php?funcion=obtenerGuias&amp;tipo=2', 550, 450, 'ventana', 'Busqueda')"></div></td>
                    <td width="39">Fecha:</td>
                    <td width="91"><input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:80px;background:#FFFF99" value="<?=$fecha2 ?>" readonly=""/></td>
                    <td width="68">Suc Destino:</td>
                    <td width="150"><input name="sucdestino" type="text" class="Tablas" id="sucdestino" style="width:150px;background:#FFFF99" value="<?=$sucdestino ?>" readonly=""/></td>
                  </tr>
                </table></td>
              </tr>

            </table></td>
          </tr>
          <tr>
            <td colspan="8"><table width="538" border="0" cellpadding="0" cellspacing="0">
              
              <tr>
                <td width="302"><table width="292" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="298" class="FondoTabla">Remitente</td>
                    </tr>
                    <tr>
                      <td><table width="290" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="52"># Cliente<br /></td>
                            <td width="80"><input name="id_remitente" type="text" class="Tablas" id="id_remitente" style="width:70px;background:#FFFF99" value="<?=$id_remitente ?>" readonly=""/></td>
                            <td width="21">RFC</td>
                            <td width="137"><input name="rfc_remitente" type="text" class="Tablas" id="rfc_remitente" style="width:133px;background:#FFFF99" value="<?=$rfc_remitente ?>" readonly=""/></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="290" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="52">Cliente</td>
                            <td width="238"><input name="cliente_remitente" type="text" class="Tablas" id="cliente_remitente" style="width:235px;background:#FFFF99" value="<?=$cliente_remitente ?>" readonly=""/></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="290" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="50">Calle<br /></td>
                            <td width="97"><input name="calle_remitente" type="text" class="Tablas" id="calle_remitente" style="width:93px;background:#FFFF99" value="<?=$calle_remitente ?>" readonly=""/></td>
                            <td width="44">N&uacute;mero</td>
                            <td width="99"><input name="numero_remitente" type="text" class="Tablas" id="numero_remitente" style="width:93px;background:#FFFF99" value="<?=$numero_remitente ?>" readonly=""/></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="290" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="50">CP</td>
                            <td width="97"><input name="cp_remitente" type="text" class="Tablas" id="cp_remitente" style="width:93px;background:#FFFF99" value="<?=$cp_remitente ?>" readonly=""/></td>
                            <td width="45">Colonia<br /></td>
                            <td width="98"><input name="colonia_remitente" type="text" class="Tablas" id="colonia_remitente" style="width:93px;background:#FFFF99" value="<?=$colonia_remitente ?>" readonly=""/></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="289" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="49">Poblacion<br /></td>
                            <td width="121"><input name="poblacion_remitente" type="text" class="Tablas" id="poblacion_remitente" style="width:117px;background:#FFFF99" value="<?=$poblacion_remitente?>" readonly=""/></td>
                            <td width="44">Telefono</td>
                            <td width="75"><input name="telefono_remitente" type="text" class="Tablas" id="telefono_remitente" style="width:68px;background:#FFFF99" value="<?=$telefono_remitente ?>" readonly=""/></td>
                          </tr>
                      </table></td>
                    </tr>
                </table></td>
                <td width="236"><table width="235" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="235" class="FondoTabla">Destinatario</td>
                    </tr>
                    <tr>
                      <td><table width="235" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="75"><input name="id_destinatario" type="text" class="Tablas" id="id_destinatario" style="width:75px;background:#FFFF99" value="<?=$id_destinatario ?>" readonly=""/></td>
                            <td width="20">RFC</td>
                            <td width="195"><input name="rfc_destinatario" type="text" class="Tablas" id="rfc_destinatario" style="width:140px;background:#FFFF99" value="<?=$rfc_destinatario ?>" readonly=""/></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="235" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="235"><input name="cliente_destinatario" type="text" class="Tablas" id="cliente_destinatario" style="width:240px;background:#FFFF99" value="<?=$cliente_destinatario ?>" readonly=""/></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="235" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="99"><input name="calle_destinatario" type="text" class="Tablas" id="calle_destinatario" style="width:98px;background:#FFFF99" value="<?=$calle_destinatario ?>" readonly=""/></td>
                            <td width="43">N&uacute;mero</td>
                            <td width="93"><input name="numero_destinatario" type="text" class="Tablas" id="numero_destinatario" style="width:98px;background:#FFFF99" value="<?=$numero_destinatario ?>" readonly=""/></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="235" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="98"><input name="cp_destinatario" type="text" class="Tablas" id="cp_destinatario" style="width:98px;background:#FFFF99" value="<?=$cp_destinatario ?>" readonly=""/></td>
                            <td width="39">Colonia<br /></td>
                            <td width="98"><input name="colonia_destinatario" type="text" class="Tablas" id="colonia_destinatario" style="width:100px;background:#FFFF99" value="<?=$colonia_destinatario ?>" readonly=""/></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="235" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="126"><input name="poblacion_destinatario" type="text" class="Tablas" id="poblacion_destinatario" style="width:122px;background:#FFFF99" value="<?=$poblacion_destinatario ?>" readonly=""/></td>
                            <td width="44">Telefono</td>
                            <td width="65"><input name="telefono_destinatario" type="text" class="Tablas" id="telefono_destinatario" style="width:70px;background:#FFFF99" value="<?=$telefono_destinatario ?>" readonly=""/></td>
                          </tr>
                      </table></td>
                    </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="8"><table width="542" border="0" cellpadding="0" cellspacing="0" id="tablaconteva"></table></td>
          </tr>
          <tr>
            <td colspan="8">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="8"><table width="542" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="2" class="FondoTabla">Traspaso</td>
              </tr>
              <tr>
                <td width="87">Motivo Traspaso:</td>
                <td width="455"><label>
                  <select class="Tablas" name="motivos" id="motivos" style="width:450px">
                    <option value="0">SELECCIONAR MOTIVO</option>
					<?
					$s=mysql_query("SELECT id,descripcion FROM catalogomotivos WHERE clasificacion='TRASPASO MERCANCIA CORM'",$link);
					while($c=mysql_fetch_array($s)){
					?>
					 <option value="<?=$c[id] ?>"  <? if($motivos==$c[id]){echo "selected";}?> ><?=$c[descripcion] ?> </option>
					 <? } ?>
                  </select>
                </label></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="8">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="8"><table width="29%" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td><div id="guardar" class="ebtn_guardar" onclick="Validar()" style="<? if($accion==''){echo 'visibility:visible';}else{echo 'visibility:hidden';} ?>"></div></td>
                <td><div class="ebtn_nuevo" onclick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')"></div></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="8"><div align="center">
              <input name="accion" type="hidden" id="accion" />
              <input name="registros" type="hidden" id="registros" />
              </div></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>