<?	session_start();
	require_once("../Conectar.php");
	$link = Conectarse('webpmm');	
	$fecha = date("d/m/Y");	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ajax.js"></script>
<script>
	var u = document.all;
	var mensaje = "";
	var nav4 = window.Event ? true : false;
	
	window.onload = function(){
		u.efectivo.select();
		obtenerParcial();
		obtenerTotales();
	}
	
	function obtenerTotales(){
		consultaTexto("mostrarTotales","cajaabono_con.php?accion=1&fecha="+u.fecha.value+"&rd="+Math.random());
	}
	
	function mostrarTotales(datos){
		//alert(datos);
		var obj = eval(datos);
		u.tefectivo.value		= obj.principal.efectivo;
		u.tcheque.value			= obj.principal.cheque;
		u.ttarjeta.value		= obj.principal.tarjeta;
		u.ttransferencia.value	= obj.principal.transferencia;
		u.iniciocaja.value		= obj.iniciocaja;
	}
	
	function Numeros(evt){
		var key = nav4 ? evt.which : evt.keyCode;
		return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
	}

	function validar(cierre){
		if(u.iniciocaja.value==""){
			alerta('Debe Iniciar Caja antes de Cerrar Caja','¡Atención!','efectivo');
			return false;
		/*}else if(u.parcial.value=="parcial" && cierre=="parcial"){
			alerta('Ya se ha realizado un Cierre Parcial','¡Atención!','efectivo');
			return false;*/
		}else if(u.parcial.value=="definitivo"){
			alerta('Ya no puede Cerrar Caja por que ya se realizo el Cierre definitivo','¡Atención!','efectivo');
			return false;
		}else if(u.efectivo.value == ""){
			alerta('Debe Capturar Monto Efectivo','¡Atención!','efectivo');
			return false;
		}else if(parseFloat(u.efectivo.value) < 0){
			alerta('Monto Efectivo debe ser mayor a Cero','¡Atención!','efectivo');
			return false;
		}else if(u.tarjeta.value == ""){
			alerta('Debe Capturar Monto Tarjeta','¡Atención!','efectivo');
			return false;
		}else if(parseFloat(u.tarjeta.value) < 0){
			alerta('Monto Tarjeta debe ser mayor a Cero','¡Atención!','tarjeta');
			return false;
		}else if(u.transferencia.value == ""){
			alerta('Debe Capturar Monto Transferencia','¡Atención!','transferencia');
			return false;
		}else if(parseFloat(u.transferencia.value) < 0){
			alerta('Monto Transferencia debe ser mayor a Cero','¡Atención!','transferencia');
			return false;
		}else if(u.cheque.value == ""){
			alerta('Debe Capturar Monto Cheque','¡Atención!','cheque');
			return false;
		}else if(parseFloat(u.cheque.value) < 0){
			alerta('Monto Cheque debe ser mayor a Cero','¡Atención!','cheque');
			return false;
		}

		mensaje ="";
		if(parseFloat(u.efectivo.value)!=parseFloat(u.tefectivo.value)){
			mensaje = "Efectivo, ";
		}
		if(parseFloat(u.tarjeta.value)!=parseFloat(u.ttarjeta.value)){
			mensaje += "Tarjeta, ";
		}
		if(parseFloat(u.transferencia.value)!=parseFloat(u.ttransferencia.value)){
			mensaje += "Transferencia, ";
		}
		if(parseFloat(u.cheque.value)!=parseFloat(u.tcheque.value)){
			mensaje += "Cheque, ";
		}
		if(mensaje!=""){
			if(cierre=="parcial"){
				alerta('Existen diferencias en '+ mensaje.substring(0,mensaje.length-2),'¡Atención!','efectivo');
			}else{
				confirmar('Existen diferencias en '+ mensaje.substring(0,mensaje.length-2)+' ¿Desea continuar?','','cierreCaja(\'definitivo\');', '');
			}
		}else{
			if(cierre=="parcial"){
				u.d_parcial.style.visibility = "hidden";
				confirmar('Se realizara el cierre de caja parcial, ¿Desea continuar?','', 'registroParcial();', '');
			}else{
				confirmar('Se realizara el cierre de caja definitivo, ¿Desea continuar?','','cierreCaja(\'definitivo\');', '');
			}
		}
	}

	function cierreCaja(cierre){
		u.d_definitivo.style.visibility = "hidden";
		consultaTexto("registroDefinitivo","cajaabono_con.php?accion=2&tipo=definitivo&efectivo="+u.efectivo.value
		+"&cheque="+u.cheque.value+"&tarjeta="+u.tarjeta.value+"&transferencia="+u.transferencia.value
		+"&codigo="+u.codigo.value+"&fecha="+u.fecha.value+"&iniciocaja="+u.iniciocaja.value+"&val="+Math.random());		
	}
	function registroParcial(){
		u.d_parcial.style.visibility = "visible";
		info('Se ha realizado el cierre Parcial correctamente', 'Operación realizada correctamente');
	}
	function registroDefinitivo(datos){
		if(datos.indexOf("ok")>-1){
			var row = datos.split(",");
			u.parcial.value = row[2];
			u.codigo.value = row[3];

			if(row[1] == "SI"){
				//setTimeout("reporteIncongruencias()",3000);
			}
			u.d_definitivo.style.visibility = "visible";
			info('Se ha realizado el cierre Definitivo correctamente', 'Operación realizada correctamente');
		}else{
			u.d_definitivo.style.visibility = "visible";
			alerta3("Hubo un error al tratar de hacer el cierre definitivo "+datos,"¡Atención!");
		}
	}
	function obtenerParcial(){
		consultaTexto("mostrarParcial","cajaabono_con.php?accion=3&fechacierrecaja="+u.fecha.value+"&s="+Math.random());
	}

	function mostrarParcial(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(datos);
			u.parcial.value = obj.principal.tipocierre;
		}
	}

	function reporteIncongruencias(){
		var miArray = new Array();
		miArray[0] = u.efectivo.value;
		miArray[1] = u.tefectivo.value;
		miArray[2] = u.tarjeta.value;
		miArray[3] = u.ttarjeta.value;
		miArray[4] = u.transferencia.value;
		miArray[5] = u.ttransferencia.value;
		miArray[6] = u.cheque.value;
		miArray[7] = u.tcheque.value;		

		window.open("reporteIncongruencia.php?miArray="+miArray+"&fecha="+u.fecha.value+"&usuario="+<?=$_SESSION[IDUSUARIO]?>);

	}

	function tabular(e,obj){
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

            /*ACA ESTA EL CAMBIO*/
            if (frm.elements[i+1].disabled ==true )    
                tabular(e,frm.elements[i+1]);
            else frm.elements[i+1].focus(); frm.elements[i+1].select();
            return false;
	} 

</script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cierre de Caja</title>
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
</head>

<body>

<form id="form1" name="form1" method="post" action="">

  <br>

  <table width="450" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

    <td width="275" class="FondoTabla Estilo4">CIERRE DE CAJA</td>

  </tr>

  <tr>

    <td><table width="400" align="center" cellpadding="0" cellspacing="0" >

      <tr>

        <td colspan="4"><table width="200" align="right" cellpadding="0" cellspacing="0">

          <tr>

            <td width="117" align="right">Fecha:</td>

            <td width="81" align="right" ><span class="Tablas">

              <input name="fecha" type="text" class="Tablas" id="fecha" readonly style="background:#FF9; text-align:center" value="<?=$fecha ?>" size="15" />

            </span></td>
          </tr>

        </table></td>
      </tr>

      <tr>

        <td colspan="4">&nbsp;</td>
      </tr>

      <tr>

        <td width="109">Efectivo:          </td>

        <td width="89"><input name="efectivo" type="text" class="Tablas" id="efectivo" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="0" size="10" maxlength="14" style="text-align:right"/></td>

        <td width="132">Tarjeta: </td>

        <td width="68"><input name="tarjeta" type="text" class="Tablas" id="tarjeta" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="0" size="10" maxlength="14" style="text-align:right" /></td>
      </tr>

      <tr>
        <td><input name="tefectivo" type="" id="tefectivo" value="<?=$tefectivo ?>" /></td>
        <td>&nbsp;</td>
        <td><input name="ttarjeta" type="" id="ttarjeta" value="<?=$ttarjeta ?>" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>

        <td>Transferencia:</td>

        <td><input name="transferencia" type="text" class="Tablas" id="transferencia" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="0" size="10" maxlength="14" style="text-align:right" /></td>

        <td>Cheque: </td>

        <td><input name="cheque" type="text" class="Tablas" id="cheque" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="0" size="10" maxlength="14" style="text-align:right" /></td>
      </tr>

      <tr>
        <td><input name="ttransferencia" type="" id="ttransferencia" value="<?=$ttransferencia ?>" /></td>
        <td>&nbsp;</td>
        <td><input name="tcheque" type="" id="tcheque" value="<?=$tcheque ?>" /></td>
        <td>&nbsp;</td>
      </tr>
      

      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      
      <tr>

        <td colspan="4"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>" />

          <input name="codigo" type="hidden" id="codigo" value="<?=$codigo ?>" />

          <input name="parcial" type="hidden" id="parcial" value="<?=$parcial ?>" />

          <input name="iniciocaja" type="hidden" id="iniciocaja" value="<?=$iniciocaja ?>" /></td>
      </tr>

      <tr>

        <td colspan="4"><table width="100" align="right" cellpadding="0" cellspacing="0">

          <tr>

            <td><div class="ebtn_parcial" id="d_parcial" onClick="validar('parcial');"></div></td>

            <td>&nbsp;&nbsp;</td>

            <td><div class="ebtn_Cierre_Definitivo" id="d_definitivo" onClick="validar('definitivo');"></div></td>
          </tr>

        </table></td>
      </tr>

      <tr>

        <td colspan="4">&nbsp;</td>
      </tr>

      <tr>

        <td colspan="4"></td>
      </tr>

    </table></td>

  </tr>

</table>

</form>

</body>


</html>

