<?

	session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

	require_once("../Conectar.php");

	$l = Conectarse("webpmm");

	

	$sql = mysql_query("SELECT idcliente FROM generacionconvenio WHERE idcliente='".$_GET[cliente]."'",$l);

	$s = mysql_num_rows($sql);



		

	$p=mysql_query("SELECT pagominimocheques FROM configuradorgeneral",$l);

	$min=mysql_fetch_array($p);

	$pagominimocheques=$min['pagominimocheques'];

	

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script>

	var nav4 = window.Event ? true : false;

	var pagominimocheque = 0;

	var paraconvenio = 0;

	var u = document.all;

	



	function Numeros(evt){ 

		// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46 

		var key = nav4 ? evt.which : evt.keyCode; 

		return (key <= 13 || (key >= 48 && key <= 57) || key==46);

	}

	

	function saltar(valor){

		var objeto = Array("efectivo","tarjeta","transferencia","cheque","banco","ncheque");

		for(i=valor;i<6;i++){

			if(u[objeto[i]].readOnly==false){

				u[objeto[i]].focus();

				break;

			}

		}

	}

	

	function blockear(objeto,valor){

			objeto.readOnly = valor

			objeto.style.backgroundColor = (valor)?"#FFFF99":"";

			objeto.value="";

	}

	

	function validarEfectivo(){

		calcular();

		var total = parseFloat(u.total.value.replace("$ ","").replace(/,/,""));

		var acumulado = parseFloat(u.acumulado.value.replace("$ ","").replace(/,/,""));

		if(acumulado>=total){

			if(acumulado>total){

				u.efectivo.value = Math.round( ( parseFloat(u.efectivo.value)-(acumulado-total) )*100)/100;

				calcular()

			}

			blockear(u.tarjeta,true);

			blockear(u.transferencia,true);

			blockear(u.cheque,true);

			u.tarjeta.focus();

		}else{

			//blockear(u.tarjeta,false);

			//blockear(u.transferencia,false);

			//blockear(u.cheque,false);

			saltar(1);

		}

	}

	

	function validarTarjeta(){

		calcular();

		var total = parseFloat(u.total.value.replace("$ ","").replace(/,/,""));

		var acumulado = parseFloat(u.acumulado.value.replace("$ ","").replace(/,/,""));

		if(acumulado>=total){

			//blockear(u.transferencia,true);

			//blockear(u.efectivo,true);

			//blockear(u.cheque,true);

			if(acumulado>total){

				u.tarjeta.value = Math.round( ( parseFloat(u.tarjeta.value)-(acumulado-total) )*100)/100;

				calcular();

			}

		}else{

			//blockear(u.transferencia,false);

			//blockear(u.efectivo,false);

			//blockear(u.cheque,false);

			saltar(5);

		}

		u.transferencia.focus();

	}

	

	function validarTransferencia(){

		calcular();

		var total = parseFloat(u.total.value.replace("$ ","").replace(/,/,""));

		var acumulado = parseFloat(u.acumulado.value.replace("$ ","").replace(/,/,""));

		if(acumulado>=total){

			//blockear(u.efectivo,true);

			//blockear(u.tarjeta,true);

			//blockear(u.cheque,true);

			if(acumulado>total){

				u.transferencia.value = Math.round( ( parseFloat(u.transferencia.value)-(acumulado-total) )*100)/100;

				calcular();

			}

		}else{

			//blockear(u.tarjeta,false);

			//blockear(u.efectivo,false);

			//blockear(u.cheque,false);

			saltar(5);

		}

		u.cheque.focus();

	}

	

	function validarCheque(){

		if (parseFloat(u.cheque.value.replace("$ ","").replace(/,/,""))==0){

				

		}else{

			if( u.cheque.value!="" && parseFloat(u.cheque.value.replace("$ ","").replace(/,/,"")) < pagominimocheque){

			alerta3("El Pago Debe Ser Mayor o Igual al Configurado para Cheque");

			u.cheque.value = pagominimocheque;

			return false;

			}	

		}

		

		calcular();

		var total = parseFloat(u.total.value.replace("$ ","").replace(/,/,""));

		var acumulado = parseFloat(u.acumulado.value.replace("$ ","").replace(/,/,""));

		

		if(acumulado>=total){

			///blockear(u.efectivo,true);

			//blockear(u.tarjeta,true);

			//blockear(u.transferencia,true);

			calcular();

		}else{

			//blockear(u.efectivo,false);

			//blockear(u.tarjeta,false);

			//blockear(u.transferencia,false);

			saltar(3);

		}

		u.banco.disable=true;

		u.banco.focus();

	}

	

	function validarbanco(){

		if (u.banco.value=="0" && u.cheque.value!=""){

			alerta3("Debe seleccionar un banco");

			return false;

		}else{

			u.ncheque.focus();

		}

	}

	

	function numcredvar(cadena){ 

		var flag = false; 

		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 

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

		var total			= 	parseFloat((u.total.value!="")?u.total.value.replace("$ ","").replace(/,/g,""):"0");

		var acumulado		= efectivo+cheque+tarjeta+transferencia;

		u.acumulado.value	= "$ "+numcredvar(acumulado.toLocaleString());

		

		if(efectivo>total){

			u.cambio.value 	=   "$ "+numcredvar((Math.round((efectivo-total)*100)/100).toLocaleString());	

		}else{

			u.cambio.value 	=   "$ 0.00";

		}

	}

	

	function verificarTotales(){

		$total=0;

		

		if(parseFloat(u.cheque.value.replace("$ ","").replace(/,/,""))==0 || u.cheque.value==""){

			

			}else{

			if( u.cheque.value!="" && parseFloat(u.cheque.value.replace("$ ","").replace(/,/,"")) < pagominimocheque){

				alerta3("El Pago Debe Ser Mayor o Igual al Configurado para Cheque");

				u.cheque.value = pagominimocheque;

				return false;

			}

			

			if (u.ncheque.value=="" && u.cheque.value!=""){

				alerta3("El Numero de Cheque es Obligatorio");

				return false;

			}

		}

		

		if (u.efectivo.value==""){

			u.efectivo.value=0;

		}

		

		if (u.tarjeta.value==""){

			u.tarjeta.value=0;

		}

		

		if (u.transferencia.value==""){

			u.transferencia.value=0;

		}

		

		if (u.cheque.value==""){

			u.cheque.value=0;

		}

		

		u.acumulado.value=parseFloat(u.efectivo.value.replace("$ ","").replace(/,/,""))+parseFloat(u.tarjeta.value.replace("$ ","").replace(/,/,""))+parseFloat(u.transferencia.value.replace("$ ","").replace(/,/,""))+parseFloat(u.cheque.value.replace("$ ","").replace(/,/,""));

		u.acumulado.value	= "$ "+numcredvar(u.acumulado.value.toLocaleString());

	

		

		$total=parseFloat(u.efectivo.value.replace("$ ","").replace(/,/,""))+parseFloat(u.tarjeta.value.replace("$ ","").replace(/,/,""))+parseFloat(u.transferencia.value.replace("$ ","").replace(/,/,""))+parseFloat(u.cheque.value.replace("$ ","").replace(/,/,""));

		

		if ($total>parseFloat(u.total.value.replace("$ ","").replace(/,/,""))){

			alerta3("La sumatoria es mayor que el total a pagar");

			return false;

		}else if($total<parseFloat(u.total.value.replace("$ ","").replace(/,/,""))){

			alerta3("La sumatoria es menor que el total a pagar");

			return false;

		}else{

			validarTotales();

		}	

	}

	

	function validarTotales(){

		if(u.total.value != u.acumulado.value){

			var total 		= parseFloat(u.total.value.replace("$ ","").replace(/,/,""));

			var acumulado	= parseFloat(u.acumulado.value.replace("$ ","").replace(/,/,""));

			

			var comparacion	= total - acumulado;

			 if(comparacion>0){

				alerta("Faltan "+(Math.round(comparacion*100)/100).toLocaleString().replace("-","")+" pesos para alcanzar el total","¡Atencion!","efectivo");	

			}else{

				up = parent.document.all;	

				up.efectivo.value				= u.efectivo.value;

				up.cheque.value					= u.cheque.value;

				up.ncheque.value				= u.ncheque.value;

				up.tarjeta.value				= u.tarjeta.value;

				up.transferencia.value			= u.transferencia.value;

				parent.VentanaModal.cerrar();

				parent.ejecutarSubmit();

			}

		}else{

			up = parent.document.all;	

			up.efectivo.value				= u.efectivo.value;

			up.cheque.value					= u.cheque.value;

			up.banco.value					= u.banco.value;

			up.ncheque.value				= u.ncheque.value;

			up.tarjeta.value				= u.tarjeta.value;

			up.transferencia.value			= u.transferencia.value;

			parent.VentanaModal.cerrar();

			parent.ejecutarSubmit();

		}

	}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Forma de Pago</title>

<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">

<link href="../FondoTabla.css" rel="stylesheet" type="text/css">

<link href="puntovta.css" rel="stylesheet" type="text/css">

<style type="text/css">

<!--

@import url("Tablas.css");

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

</head>



<body onLoad="document.form1.efectivo.focus()">

<form name="form1" method="post" action="">

<br>



<table width="100%" border="0" cellspacing="0" cellpadding="0">

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

                    <td colspan="3"><input onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" name="total" type="text" id="total" class="Tablas"  style="font:tahoma; font-size:9px; background:#FFFF99; text-align:right" readonly="true" value="<?=$_GET[total] ?>" size="15"   ></td>

                  </tr>

                  <tr>

                    <td class="Tablas">Efectivo:</td>

                    <td width="127"><input name="efectivo" type="text" class="Tablas"  style="font:tahoma; font-size:9px; text-align:right" onFocus="this.value=this.value.replace('$ ','').replace(/,/g,''); this.select()" onBlur="if(this.value!=''){this.value='$ '+numcredvar(this.value);calcular();}" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){validarEfectivo()}" value="<?=$efectivo ?>" size="15" maxlength="15"  ></td>

                    <td width="77" class="Tablas">Cheque:</td>

                    <td width="99"><input name="cheque" type="text" class="Tablas"  style="font:tahoma; font-size:9px; text-align:right;<? if($s<=0){echo "background:#FFFF99;";}else{echo "background:'';";} ?> text-align:right" onFocus="this.value=this.value.replace('$ ','').replace(/,/g,''); this.select()" onBlur="if(this.value!=''){this.value='$ '+numcredvar(this.value.replace('$ ',''));}else if(this.value==''){blockear(u.ncheque,true);}" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){validarCheque();blockear(u.ncheque,false);}" value="<?=$cheque ?>" size="15" maxlength="15" <? if($s<=0){echo "readonly=''";} ?> ></td>

                  </tr>

                  <tr>

                    <td class="Tablas">Tarjeta:</td>

                    <td><input name="tarjeta" type="text" class="Tablas"  style="font:tahoma; font-size:9px; text-align:right" onFocus="this.value=this.value.replace('$ ','').replace(/,/g,''); this.select()" onBlur="if(this.value!=''){this.value='$ '+numcredvar(this.value.replace('$ ',''));calcular();}" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){validarTarjeta()}" value="<?=$tarjeta ?>" size="15" maxlength="15"  ></td>

                    <td class="Tablas">Banco:</td>

                    <td><select name="banco" id="banco" style="font:tahoma; font-size:9px; text-align:right;text-align:right;width:100px;<? if($s<=0){echo "background:#FFFF99;";}else{echo "background:'';";} ?>" onKeyDown="if(event.keyCode==13){validarbanco();}">

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

                    <td width="59" class="Tablas">Transferencia:</td>

                    <td><input name="transferencia" type="text" class="Tablas" style="font:tahoma; font-size:9px; text-align:right" onFocus="this.value=this.value.replace('$ ','').replace(/,/g,''); this.select()" onBlur="if(this.value!=''){this.value='$ '+numcredvar(this.value.replace('$ ',''));calcular();}" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){validarTransferencia()}" value="<?=$transferencia ?>" size="15" maxlength="15" ></td>

                    <td class="Tablas">#Cheque:</td>

                    <td><input onKeyPress="return Numeros(event)" class="Tablas" onBlur="calcular()" onKeyDown="" name="ncheque" type="text"  style="font:tahoma; font-size:9px; text-align:right; background:#FFFF99;text-align:right"  value="<?=$ncheque?>" size="15" readonly='' ></td>

                  </tr>

                  <tr>

                  	<td>&nbsp;</td>

                  	<td>&nbsp;</td>

                  	<td class="Tablas">Acumulado:</td>

                  	<td><input onKeyPress="return Numeros(event)" class="Tablas" name="acumulado" type="text"  style="font:tahoma; font-size:9px; background:#FFFF99; text-align:right" readonly="true" value="<?=$acumulado ?>" size="15"  ></td>

                  </tr>

                  <tr>

                  	<td class="Tablas">Notas Credito:</td>

                  	<td>&nbsp;</td>

                  	<td class="Tablas">&nbsp;</td>

                  	<td>&nbsp;</td>

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

                    <input type="text" name="cambio" readonly style="width:303px; font-family:Tahoma; font-size:48px; text-align:right" value="$ 0.00">

                    </td>

</tr>

                  <tr>

                    <td height="45" colspan="4"><span class="Tablas">

                  <input name="accion" type="hidden" id="accion" value="<?=$accion ?>">

                    </span>

                        <table width="83" border="0" align="right">

                          <tr>

                            <td width="77"><img src="../img/Boton_Guardar.gif" alt="E" width="70" height="20" style="cursor:pointer" onClick="verificarTotales()"></td>

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