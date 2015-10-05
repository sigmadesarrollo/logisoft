<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/

	require_once("../Conectar.php");
	$l	= Conectarse("webpmm");	

	if($_GET[limpiar]==1){
		$s = "delete from convenio_servicios where idusuario = '$_SESSION[IDUSUARIO]' and isnull(idconvenio) and tipo = '$_GET[tipo]'";
		mysql_query($s,$l) or die($s);
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ajax.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
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
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css">
</head>
<script>
	var u = document.all;
	
	function solonumeros(evnt){
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
		}
	}

	function agregar(){
		if(u.servicio.value == "0"){
			alerta("Debe capturar Servicio","¡Atención!","servicio");
		}else{
			if(u.servicio.options[u.servicio.options.selectedIndex].descripcion=="VALOR DECLARADO" || 
				u.servicio.options[u.servicio.options.selectedIndex].descripcion=="ACUSE DE RECIBO"){
				consultaTexto("resGuardar","propuestaconvenio_con.php?accion=3&valor=1&servicio="
					+u.servicio.options[u.servicio.selectedIndex].descripcion+"&cobro=NO&precio=0&idservicio="+u.servicio.value+"&tipo=<?=$_GET[tipo]?>&azar"+Math.random());			
			}else{			
				consultaTexto("resGuardar","propuestaconvenio_con.php?accion=3&valor=1&servicio="
					+u.servicio.options[u.servicio.selectedIndex].descripcion+"&cobro=NO&precio=0&idservicio="+u.servicio.value+"&tipo=<?=$_GET[tipo]?>&azar"+Math.random());
			}
			
		}
	}

	function resGuardar(resp){
		if(resp.indexOf("guardo")>-1){				
			var objeto = new Object();
			objeto.idservicio = u.servicio.value;
			objeto.servicio = u.servicio.options[u.servicio.selectedIndex].descripcion;
			objeto.precio = 0;
			objeto.cobro = "NO";
			parent.<?=$_GET[fagregar]?>(objeto);
			info("El servicio fue agregado","¡Atencion!");					
			u.servicio.value 	= "";				
		}else{
			alerta3("Error al guardar",resp);
		}
	}

	function modificar(){
		if(u.servicio.value == "0"){
			alerta("Debe capturar Servicio","¡Atención!","servicio");
		}else{
			if(u.servicio.options[u.servicio.options.selectedIndex].descripcion=="VALOR DECLARADO" || 
				u.servicio.options[u.servicio.options.selectedIndex].descripcion=="ACUSE DE RECIBO"){
					consultaTexto("resModificar","propuestaconvenio_con.php?accion=3&valor=2&servicio="+u.servicio.options[u.servicio.selectedIndex].descripcion+"&tipo=<?=$_GET[tipo]?>&cobro=NO&precio=0&azar"+Math.random());				
			}else{
					consultaTexto("resModificar","propuestaconvenio_con.php?accion=3&valor=2&servicio="+u.servicio.options[u.servicio.selectedIndex].descripcion+"&tipo=<?=$_GET[tipo]?>&cobro=NO&precio=0&azar"+Math.random());
			}
		}
	}	

	function resModificar(resp){
		if(resp.indexOf("modifico")>-1){
			var objeto = new Object();
			objeto.servicio = u.servicio.options[u.servicio.selectedIndex].descripcion;				

			parent.<?=$_GET[fmodificar]?>(objeto);				
		}else{
			alerta3("Error al modificar",resp);
		}
	}

	function borrar(){
		//alerta3("propuestaconvenio_con.php?accion=3&valor=3&servicio="+u.servicio.options[u.servicio.selectedIndex].descripcion+"&tipo=<?=$_GET[tipo]?>&cobro=NO&precio=0&azar"+Math.random());
		consultaTexto("resBorrar","propuestaconvenio_con.php?accion=3&valor=3&servicio="+u.servicio.options[u.servicio.selectedIndex].descripcion+"&tipo=<?=$_GET[tipo]?>&cobro=NO&precio=0&azar"+Math.random());
	}	

	function resBorrar(resp){
		if(resp.indexOf("elimino")>-1){
			parent.<?=$_GET[fborrar]?>();
		}else{
			alerta3("Error al modificar",resp);
		}
	}

	/*function cambiar(combo){
		if(combo.options[combo.options.selectedIndex].descripcion != 'Valor Declarado' && combo.options[combo.options.selectedIndex].costo!=""){
			u.precio.style.backgroundColor = "#FFFF99";
			u.precio.readOnly = true;
			u.precio.value = combo.options[combo.options.selectedIndex].costo;
			u.cobro.onclick = null;
		}else{
			u.precio.style.backgroundColor = "";
			u.precio.readOnly = false;
			u.cobro.checked = true;
			u.precio.value = "";
			u.cobro.onclick = function (){
				if(!this.checked){
					u.precio.style.backgroundColor = "#FFFF99";
					u.precio.readOnly = true;
				}else{
					u.precio.style.backgroundColor = "";
					u.precio.readOnly = false;
				}
			}
		}
	}*/
</script>
<body>
<form id="form1" name="form1" method="post" action="" onSubmit="return false;">
  <br>
<table width="267" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="207" class="FondoTabla Estilo4">SERVICIOS</td>
  </tr>

  <tr>

    <td><table width="265" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="3" class="Tablas">Servicio:</td>
        <td>&nbsp;&nbsp;&nbsp;
          <select class="Tablas" name="servicio" style="width:150px" <? if($_GET[servicio]){echo "disabled";} ?> >
            <option value="0">SELECCIONAR</option>
            <?

			$s = "select catalogoservicio.*, costo
			from catalogoservicio
			left join configuradorservicios on catalogoservicio.id = configuradorservicios.servicio";
			$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
			?>
            <option value="<?=$f->id?>" costo="<?=$f->costo?>" descripcion="<?=$f->descripcion?>" <? if($_GET[servicio]==$f->descripcion){echo "selected";} ?>>
            <?=$f->descripcion?>
            </option>
            <?	
			}
		?>
          </select></td>
      </tr>
      <tr>
        <td colspan="3" class="Tablas">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" class="Tablas">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>

        <td colspan="3" class="Tablas">&nbsp;</td>
        <td width="199">&nbsp;</td>
      </tr>

      <tr>

        <td colspan="4" align="center">

        <?

			if($_GET["servicio"]){

		?>

        <table>

        	<tr>

            	<td><div class="ebtn_eliminar" onClick="borrar()"></div></td>

                <td><div class="ebtn_cerrarventana" onClick="parent.VentanaModal.cerrar()"></div></td>
            </tr>
        </table>

        <?

			}else{

		?>

        <table>

        	<tr>

       <td> <div class="ebtn_agregar" onClick="agregar()"></div></td>

        <td><div class="ebtn_cerrarventana" onClick="parent.VentanaModal.cerrar()"></div></td>
            </tr>
        </table>        </td>

        <?

			}

		?>
      </tr>

    </table></td>

  </tr>

</table>

<p>&nbsp;</p>

</form>

</body>

</html>





