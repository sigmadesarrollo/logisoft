<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");	
	$sql = mysql_query("SELECT idcliente FROM generacionconvenio WHERE idcliente='".$_GET[cliente]."'",$l);
	$s = mysql_num_rows($sql);
	$p=mysql_query("SELECT pagominimocheques FROM configuradorgeneral",$l);
	$min=mysql_fetch_array($p);
	$pagominimocheques=$min['pagominimocheques'];
	$efectivo=$_GET[total];
	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script>
	var nav4 = window.Event ? true : false;
	var pagominimocheque = 0;
	var paraconvenio = 0;
	var u = document.all;	function Numeros(evt){ 
		var key = nav4 ? evt.which : evt.keyCode; 
		return (key <= 13 || (key >= 48 && key <= 57) || key==46);
	}	
	function numcredvar(cadena){ 
		var flag = false; 		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}	
	
	function calcular(){
		var efectivo		=	parseFloat((u.efectivo.value!="")?u.efectivo.value.replace("$ ","").replace(/,/g,""):"0");
		var cheque			=	parseFloat((u.cheque.value!="")?u.cheque.value.replace("$ ","").replace(/,/g,""):"0");
		var tarjeta			=	parseFloat((u.tarjeta.value!="")?u.tarjeta.value.replace("$ ","").replace(/,/g,""):"0");
	var transferencia	=	parseFloat((u.transferencia.value!="")?u.transferencia.value.replace("$ ","").replace(/,/g,""):"0");
		var nc				=	parseFloat((u.nc.value!="")?u.nc.value.replace("$ ","").replace(/,/g,""):"0");
		var total			= 	parseFloat((u.total.value!="")?u.total.value.replace("$ ","").replace(/,/g,""):"0");
		var acumulado		= 	efectivo+cheque+tarjeta+transferencia+nc;
		u.acumulado.value	= 	"$ "+numcredvar(acumulado.toLocaleString());		
		
		if(acumulado>total){
			var rest = acumulado-total;
			
			if(efectivo>0){
				if(efectivo>rest){
					u.cambio.value 	=   "$ "+numcredvar((Math.round((rest)*100)/100).toLocaleString());	
				}else{
					u.cambio.value 	=   "$ "+numcredvar((Math.round((efectivo)*100)/100).toLocaleString());	
				}
			}else{
				u.cambio.value 	=   "$ "+numcredvar((Math.round((efectivo)*100)/100).toLocaleString());	
			}
		}else{
			u.cambio.value 	=   "$ 0.00";
		}
	}
	
	function enfocar(obj){
		obj.value = obj.value.replace("$ ","").replace(/,/g,"");
		obj.select();
	}
	function desenfocar(obj){
		if(obj.value!="" && parseFloat(obj.value)>0){
			if(obj.name=="cheque")
				validarCheque(obj)
			obj.value = "$ "+numcredvar(obj.value.toString());
		}else{
			obj.value = "";
		}
	}
	function validarCheque(obj){
		if(obj.value!="" && parseFloat(obj.value)>0 && parseFloat(obj.value.replace("$ ","").replace(/,/,"")) < pagominimocheque){
			alerta3("El Pago Debe Ser Mayor o Igual al Configurado para Cheque");
			obj.value = pagominimocheque;
			document.all.banco.disabled = false;
			document.all.ncheque.readOnly = false;
			document.all.ncheque.style.backgroundColor = '';
		}else if(obj.value!="" && parseFloat(obj.value)>0){
			document.all.ncheque.readOnly = false;
			document.all.banco.disabled = false;
			document.all.ncheque.style.backgroundColor = '';
		}else{
			document.all.ncheque.readOnly = true;
			document.all.banco.disabled = true;
			document.all.ncheque.style.backgroundColor = '#FFFF99';
		}
	}
	function tabular(evt){
		if(evt.keyCode==13){
			evt.keyCode=9;
			return 9;
		}
	} 
	
	function validarTotales(){
		var cambio 		= parseFloat(u.cambio.value.replace("$ ","").replace(/,/g,""));
		var efectivo 	= parseFloat(u.efectivo.value.replace("$ ","").replace(/,/g,""));
		
		if(u.cheque.value!=""){
			if(u.banco.value==0){
				alerta("Por favor proporcione el banco","Atencion","banco");
				return false;
			}
			if(u.ncheque.value==0){
				alerta("Por favor proporcione el nocheque","Atencion","ncheque");
				return false;
			}
		}
		if(u.total.value != u.acumulado.value){
			var total 		= parseFloat(u.total.value.replace("$ ","").replace(/,/,""));
			var acumulado	= parseFloat(u.acumulado.value.replace("$ ","").replace(/,/,""));
			var comparacion	= total - acumulado;			 if(comparacion>0){
			alerta("Faltan "+(Math.round(comparacion*100)/100).toLocaleString().replace("-","")+" pesos para alcanzar el total","¡Atencion!","efectivo");	
			 }
			 /*else if(comparacion<0 && u.cambio.value == "$ 0.00"){
				alerta("Sobrepasaste por "+(Math.round(comparacion*100)/100).toLocaleString().replace("-","")+" pesos el total","¡Atencion!","efectivo");
			}*/
			else{
				up = parent.document.all;	
				if (cambio>0){
					var efec=0;
					efec= parseFloat(efectivo)-parseFloat(cambio);
					if (efec>0){
						up.efectivo.value=parseFloat(efec);
					}else{
						up.efectivo.value=0;
					}
				}else{
					up.efectivo.value				= u.efectivo.value;
				}
				up.cheque.value					= u.cheque.value;
				up.banco.value					= u.banco.value;
				up.ncheque.value				= u.ncheque.value;
				up.tarjeta.value				= u.tarjeta.value;
				up.transferencia.value			= u.transferencia.value;
				up.nc.value						= u.nc.value;
				up.nc_folio.value				= u.folio.value;
				parent.VentanaModal.cerrar();
				parent.ejecutarSubmit();
			}
		}else{
			up = parent.document.all;	
			if (cambio>0){
					var efec=0;
					efec= parseFloat(efectivo)-parseFloat(cambio);
					if (efec>0){
						up.efectivo.value=parseFloat(efec);
					}else{
						up.efectivo.value=0;
					}
			}else{
				up.efectivo.value				= u.efectivo.value;
			}			up.cheque.value					= u.cheque.value;
			up.banco.value					= u.banco.value;
			up.ncheque.value				= u.ncheque.value;
			up.tarjeta.value				= u.tarjeta.value;
			up.transferencia.value			= u.transferencia.value;
			up.nc.value						= u.nc.value;
			up.nc_folio.value				= u.folio.value;
			parent.VentanaModal.cerrar();
			parent.ejecutarSubmit();
		}
	}
	function obtenerFolionotascredito(folio){
		u.folio.value = folio;
		consultaTexto("mostrarnc","../entregas/liquidaciondemercancia_con.php?accion=14&folio="+folio+"&suerte="+Math.random());
	}	
	function mostrarnc(datos){
		if (datos!=0){
			var obj = eval(convertirValoresJson(datos));
			u.nc.value= obj[0].importe;
			u.nc.value="$ "+numcredvar(u.nc.value.toLocaleString());
			calcular();
		}
	}
</script><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Forma de Pago</title>
<script src="../javascript/ajax.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="puntovta.css" rel="stylesheet" type="text/css">
<style type="text/css"><!--@import url("Tablas.css");
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style3 {	font-size: 9px;
	color: #464442;
}
.style5 {color: #FFFFFF ; font-size:9px}
-->
</style>
</head><body onLoad="document.form1.efectivo.focus()">
<form name="form1" method="post" action="">
<br><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
    <table width="405" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
            <tr>
              <td width="401" class="FondoTabla">Datos Generales </td>
            </tr>
            <tr>
          <td height="147"><br><table width="362" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="Tablas">Total:</td>
                    <td colspan="3"><input tabindex="0" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event)" name="total" type="text" id="total" class="Tablas"  style="font:tahoma; font-size:9px; background:#FFFF99; text-align:right" readonly="true" value="<?=$_GET[total] ?>" size="15"   ></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Efectivo:</td>
                    <td width="120"><input name="efectivo" tabindex="1" type="text" class="Tablas"  style="font:tahoma; font-size:9px; text-align:right" onFocus="enfocar(this)" onBlur="calcular(); desenfocar(this)" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){calcular(); return tabular(event);}" value="<?=$efectivo ?>" size="15" maxlength="15"  ></td>
                    <td width="84" class="Tablas">Cheque:</td>
                    <td width="100"><input name="cheque" tabindex="4" type="text" class="Tablas"  style="font:tahoma; font-size:9px; text-align:right; text-align:right" onFocus="enfocar(this)" onBlur="desenfocar(this)" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){validarCheque(this); calcular(); return tabular(event);}" value="<?=$cheque ?>" size="15" maxlength="15" ></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Tarjeta:</td>
                    <td><input name="tarjeta" type="text" tabindex="2" class="Tablas"  style="font:tahoma; font-size:9px; text-align:right" onFocus="enfocar(this)" onBlur="desenfocar(this)" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){calcular(); return tabular(event);}" value="<?=$tarjeta ?>" size="15" maxlength="15"  ></td>
                    <td class="Tablas">Banco:</td>
                    <td><select name="banco" id="banco" tabindex="5" disabled style="font:tahoma; font-size:9px; text-align:right;text-align:right;width:100px;<? if($s<=0){echo "background:#FFFF99;";}else{echo "background:'';";} ?>" onKeyDown="if(event.keyCode==13){return tabular(event);}">
                      <option value="0" style="text-transform:none" >....</option>
                      <?            
                    $sl = "SELECT id,descripcion FROM catalogobanco";
                    $sq = mysql_query($sl,$l) or die($sl);
					
					while($row = mysql_fetch_row($sq))
					{ 
					?>
                      <option value="<?=$row[0]?>" <?=$row[descripcion] == $row[0] ? "selected" : "" ?>>
                      <?=$row[1]?>
                      </option>
                      <?
					}
					?>
                    </select></td>
                  </tr>
                  <tr>
                    <td width="73" class="Tablas">Transferencia:</td>
                    <td><input name="transferencia" tabindex="3" type="text" class="Tablas" style="font:tahoma; font-size:9px; text-align:right" onFocus="enfocar(this)" onBlur="desenfocar(this)" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){calcular(); return tabular(event);}" value="<?=$transferencia ?>" size="15" maxlength="15" ></td>
                    <td class="Tablas">#Cheque:</td>
                    <td><input onKeyPress="return Numeros(event)" tabindex="6" class="Tablas" onBlur="calcular()" name="ncheque" type="text"  style="font:tahoma; font-size:9px; text-align:right;text-align:right;background:#FFFF99;" onKeyDown="if(event.keyCode==13){calcular(); return tabular(event);}" readonly value="<?=$ncheque?>" size="15" ></td>
                  </tr>
                  <tr>
                  	<td class="Tablas">Notas Credito:</td>
                  	<td><input name="folio" tabindex="7" type="text" class="Tablas" id="folio" onKeyDown="if(event.keyCode==13){return tabular(event);}" style="width:70px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
               	    <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarFolionotacredito.php?cliente=<?=$_GET[cliente] ?>&funcion=obtenerFolionotascredito', 300, 300, 'ventana', 'Busqueda')" /></td>
                  	<td class="Tablas"><input tabindex="8" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event)" name="nc" type="text" id="nc" class="Tablas"  style="font:tahoma; font-size:9px; background:#FFFF99; text-align:right" readonly="true" value="<?=$nc ?>" size="15"   ></td>
                  	<td>&nbsp;</td>
                  </tr>
                  <tr>
                  	<td>&nbsp;</td>
                  	<td><img src="../img/boton_limpiar.gif" style="cursor:hand" onClick="document.all.folio.value=''; document.all.nc.value=''; calcular(); "></td>
                  	<td class="Tablas">Acumulado:</td>
                  	<td><input tabindex="9"  onKeyDown="if(event.keyCode==13){calcular(); return tabular(event);}" class="Tablas" name="acumulado" type="text"  style="font:tahoma; font-size:9px; background:#FFFF99; text-align:right" readonly="true" value="<?=$acumulado ?>" size="15"  ></td>
                  </tr>
                  <tr>
                  	<td></td>
                  	<td class="Tablas">Su cambio es:</td>
                  	<td></td>
                  	<td></td>
                  </tr>
                  <tr>
                  	<td height="71">&nbsp;</td>
                  	<td colspan="3">
                    <input type="text" name="cambio" readonly style="width:303px; font-family:Tahoma; font-size:48px; text-align:right" value="$ 0.00"></td>
				  </tr>
                  <tr>
                    <td height="45" colspan="4"><span class="Tablas">
                  <input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
                    </span>
                        <table width="83" border="0" align="right">
                          <tr>
                            <td width="77"><img src="../img/Boton_Guardar.gif" alt="E" width="70" height="20" style="cursor:pointer" onClick="validarTotales()"></td>
</tr>
                    </table></td>
                  </tr>
              </table><br></td>
            </tr>
        </table></td>
    </tr>
  </table>
</form>
</body>
</html>
<script>
	pagominimocheque 	= <?=$pagominimocheques ?>;
	paraconvenio 		= <?=$s ?>;
	calcular();
</script>
<?
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
	}
//	}
?>