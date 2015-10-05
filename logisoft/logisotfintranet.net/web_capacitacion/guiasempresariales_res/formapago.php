<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script>

	var nav4 = window.Event ? true : false;

	var pagominimocheque = 0;

	var paraconvenio = 0;

	

	function Numeros(evt){ 

		// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46 

		var key = nav4 ? evt.which : evt.keyCode; 

		return (key <= 13 || (key >= 48 && key <= 57) || key==46);

	

	}

	

	function saltar(valor){

		var objeto = Array("efectivo","banco","cheque","ncheque","tarjeta","transferencia");

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

		u = document.all;

		calcular();

		var total = parseFloat(u.total.value.replace("$ ","").replace(/,/,""));

		var acumulado = parseFloat(u.acumulado.value.replace("$ ","").replace(/,/,""));

		if(acumulado>=total){

			//blockear(u.banco,true);

			//blockear(u.cheque,true);

			//blockear(u.ncheque,true);

			blockear(u.tarjeta,true);

			blockear(u.transferencia,true);

			calcular();

		}else{

			//blockear(u.banco,false);

			//blockear(u.cheque,false);

			//blockear(u.ncheque,false);

			blockear(u.tarjeta,false);

			blockear(u.transferencia,false);

			saltar(1);

		}

	}

	function validarBanco(){

		u = document.all;

		calcular();

		var total = parseFloat(u.total.value.replace("$ ","").replace(/,/,""));

		var acumulado = parseFloat(u.acumulado.value.replace("$ ","").replace(/,/,""));

		if(acumulado>=total){

			//blockear(u.cheque,true);

			//blockear(u.ncheque,true);

			blockear(u.tarjeta,true);

			blockear(u.transferencia,true);

			if(acumulado>total){

				u.banco.value = Math.round( ( parseFloat(u.banco.value)-(acumulado-total) )*100)/100;

				calcular();

			}

		}else{

			//blockear(u.cheque,false);

			//blockear(u.ncheque,false);

			blockear(u.tarjeta,false);

			blockear(u.transferencia,false);

			saltar(2);

		}

	}

	function validarCheque(){

		u = document.all;

		calcular();

		var total = parseFloat(u.total.value.replace("$ ","").replace(/,/,""));

		var acumulado = parseFloat(u.acumulado.value.replace("$ ","").replace(/,/,""));

		if(acumulado>=total){

			blockear(u.tarjeta,true);

			blockear(u.transferencia,true);

			if(acumulado>total){

				u.cheque.value = Math.round( ( parseFloat(u.cheque.value)-(acumulado-total) )*100)/100;

				calcular();

				u.ncheque.focus();

			}

		}else{

			blockear(u.tarjeta,false);

			blockear(u.transferencia,false);

			saltar(3);

		}

	}

	function validarTarjeta(){

		u = document.all;

		calcular();

		var total = parseFloat(u.total.value.replace("$ ","").replace(/,/,""));

		var acumulado = parseFloat(u.acumulado.value.replace("$ ","").replace(/,/,""));

		if(acumulado>=total){

			blockear(u.transferencia,true);

			if(acumulado>total){

				u.tarjeta.value = Math.round( ( parseFloat(u.tarjeta.value)-(acumulado-total) )*100)/100;

				calcular();

			}

		}else{

			blockear(u.transferencia,false);

			saltar(5);

		}

	}

	function validarTransferencia(){

		u = document.all;

		calcular();

		var total = parseFloat(u.total.value.replace("$ ","").replace(/,/,""));

		var acumulado = parseFloat(u.acumulado.value.replace("$ ","").replace(/,/,""));

		if(acumulado>=total){

			if(acumulado>total){

				u.transferencia.value = Math.round( ( parseFloat(u.transferencia.value)-(acumulado-total) )*100)/100;

				calcular();

			}

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

		u = document.all;

		

		var efectivo		=	parseFloat((u.efectivo.value!="")?u.efectivo.value.replace("$ ","").replace(/,/g,""):"0");

		var cheque			=	parseFloat((u.cheque.value!="")?u.cheque.value.replace("$ ","").replace(/,/g,""):"0");

		var banco			=	parseFloat((u.banco.value!="")?u.banco.value.replace("$ ","").replace(/,/g,""):"0");

		var tarjeta			=	parseFloat((u.tarjeta.value!="")?u.tarjeta.value.replace("$ ","").replace(/,/g,""):"0");

		var transferencia	=	parseFloat((u.transferencia.value!="")?u.transferencia.value.replace("$ ","").replace(/,/g,""):"0");

		var total			= 	parseFloat((u.total.value!="")?u.total.value.replace("$ ","").replace(/,/g,""):"0");

		var acumulado		= efectivo+cheque+banco+tarjeta+transferencia;

		u.acumulado.value	= "$ "+numcredvar(acumulado.toLocaleString());

		

		if(efectivo>total){

			

			u.cambio.value 	=   "$ "+numcredvar((Math.round((efectivo-total)*100)/100).toLocaleString());	

		}else{

			u.cambio.value 	=   "$ 0.00";

		}

	}

	function validarTotales(){

		u = document.all;

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

				up.banco.value					= u.banco.value;

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

                    <td width="77" class="Tablas">Banco:</td>

                    <td width="99"><input name="banco" type="text" class="Tablas"  style="font:tahoma; font-size:9px; text-align:right; background:#FFFF99; text-align:right" onFocus="this.value=this.value.replace('$ ','').replace(/,/g,''); this.select()" onBlur="if(this.value!=''){this.value='$ '+numcredvar(this.value.replace('$ ',''));calcular();}" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){validarBanco()}" value="<?=$banco ?>" size="15" maxlength="15" readonly="true"  ></td>

                  </tr>

                  <tr>

                    <td class="Tablas">Cheque:</td>

                    <td><input name="cheque" type="text" class="Tablas"  style="font:tahoma; font-size:9px; text-align:right;background:#FFFF99; text-align:right" onFocus="this.value=this.value.replace('$ ','').replace(/,/g,''); this.select()" onBlur="if(this.value!=''){this.value='$ '+numcredvar(this.value.replace('$ ',''));calcular();}" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){validarCheque()}" value="<?=$cheque ?>" size="15" maxlength="15" readonly="true"  ></td>

                    <td class="Tablas">#Cheque:</td>

                    <td><input onKeyPress="return Numeros(event)" class="Tablas" onBlur="calcular();" onKeyDown="if(event.keyCode==13){document.all.tarjeta.focus();}" name="ncheque" type="text"  style="font:tahoma; font-size:9px; text-align:right;background:#FFFF99; text-align:right" readonly="true" value="<?=$ncheque?>" size="15"  ></td>

                  </tr>

                  <tr>

                    <td width="59" class="Tablas">Tarjeta:</td>

                    <td><input name="tarjeta" type="text" class="Tablas"  style="font:tahoma; font-size:9px; text-align:right" onFocus="this.value=this.value.replace('$ ','').replace(/,/g,''); this.select()" onBlur="if(this.value!=''){this.value='$ '+numcredvar(this.value.replace('$ ',''));calcular();}" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){validarTarjeta()}" value="<?=$tarjeta ?>" size="15" maxlength="15"  ></td>

                    <td class="Tablas">Transferencia:</td>

                    <td><input name="transferencia" type="text" class="Tablas" style="font:tahoma; font-size:9px; text-align:right" onFocus="this.value=this.value.replace('$ ','').replace(/,/g,''); this.select()" onBlur="if(this.value!=''){this.value='$ '+numcredvar(this.value.replace('$ ',''));calcular();}" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){validarTransferencia()}" value="<?=$transferencia ?>" size="15" maxlength="15" ></td>

                  </tr>

                  <tr>

                  	<td>&nbsp;</td>

                  	<td>&nbsp;</td>

                  	<td class="Tablas">Acumulado:</td>

                  	<td><input onKeyPress="return Numeros(event)" class="Tablas" name="acumulado" type="text"  style="font:tahoma; font-size:9px; background:#FFFF99; text-align:right" readonly="true" value="<?=$acumulado ?>" size="15"  ></td>

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

	pagominimocheque 	= parent.document.all.pagominimocheque.value;

	paraconvenio 			= parent.document.all.convenioaplicado.value;

	calcular();

</script>

<?

if ($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";

	}

//	}

?>