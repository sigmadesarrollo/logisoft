<?
	if($_GET[accion]==1){
		header('Content-type: text/xml');
		
		require_once("../Conectar.php");
		$l = Conectarse("webpmm");
		
		$s = "select cc.cp, cpa.descripcion as pais,
		ce.descripcion as estado,
		cm.descripcion as municipio,
		cc.descripcion as colonia,
		cpo.descripcion as poblacion
		from catalogocolonia as cc
		inner join catalogopoblacion as cpo on cc.poblacion = cpo.id
		inner join catalogomunicipio as cm on cpo.municipio = cm.id
		inner join catalogoestado as ce on cm.estado = ce.id
		inner join catalogopais as cpa on ce.pais = cpa.id
		where cc.cp = '$_GET[codigopostal]'";
		$r 		= mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$encon	= mysql_num_rows($r);
			
			$f 		= mysql_fetch_object($r);
			$xml 	= "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
				<encontro>$encon</encontro>
				<cp>".cambio_texto($f->cp)."</cp>
				";
			
			$s = "select * from catalogocolonia as cc where cc.cp = '$_GET[codigopostal]'";
			$rx = mysql_query($s,$l) or die($s);
			$encol 	= mysql_num_rows($rx);
			$xml 	.= "<encontrocolonia>$encol</encontrocolonia>";
			
			while($fx = mysql_fetch_object($rx)){
				$xml 	.= "<colonia>".cambio_texto($fx->descripcion)."</colonia>";
			}
			$xml 	.= "<poblacion>".cambio_texto($f->poblacion)."</poblacion>
			<municipio>".cambio_texto($f->municipio)."</municipio>
			<estado>".cambio_texto($f->estado)."</estado>
			<pais>".cambio_texto($f->pais)."</pais>
			</datos>
			</xml>";
		}else{
			$xml 	= "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
				<encontro>0</encontro>
			</datos>
			</xml>";
		}
		echo $xml;
	}elseif($_GET[accion]==2){
		header('Content-type: text/xml');
		require_once("../Conectar.php");
		$l = Conectarse("webpmm");
		
		$s = "insert into direccion set
		origen = 'cl', codigo='$_GET[idcliente]', calle='$_GET[calle]', numero='$_GET[numero]',
		crucecalles = '$_GET[crucecalles]', cp='$_GET[cp]', colonia='$_GET[colonia]', 
		poblacion='$_GET[poblacion]', municipio='$municipio', estado='$_GET[estado]',
		telefono='$_GET[telefono]', fax='$_GET[fax]', facturacion='$_GET[facturado]'";
		$r = mysql_query($s,$l) or die($s);
		
		$xml 	= "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
				<guardado>1</guardado>
				<facturado>$_GET[facturado]</facturado>
			</datos>
			</xml>";
		echo $xml;
	}else{
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ajax.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Direcci&oacute;n Cliente</title>
<style type="text/css">
.Tablas {
	font-family: tahoma;
	font-size: 9px;
	font-style: normal;
	font-weight: bold;
}
</style>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css">
</head>
<script>
	var u = document.all;
	var	cajacolonia	= '<input name="colonia" type="text" class="Tablas"  id="colonia" size="20" readonly="" value="" style=" background:#FFFF99; text-transform:uppercase" onDblClick="javascript:popUp(\'buscarcolonia2.php\')"  />';
	
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
	function trim(cadena,caja){
		for(i=0;i<cadena.length;){
			if(cadena.charAt(i)==" ")
				cadena=cadena.substring(i+1, cadena.length);
			else
				break;
		}
	
		for(i=cadena.length-1; i>=0; i=cadena.length-1){
			if(cadena.charAt(i)==" ")
				cadena=cadena.substring(0,i);
			else
				break;
		}
		document.getElementById(caja).value=cadena;
	}
	function limpiarCampos(){		
		ui = document;
		
		u.calle.value 			= "";
		u.numero.value 			= "";
		u.entrecalle.value 		= "";
		u.cp.value 				= "";
		u.chfacturacion.checked	= false;
		if(ui.getElementById("colonia")){
			u.colonia.value		= "";
		}else{
			u.caja.innerHTML = cajacolonia;
		}
		u.telefono.value 		= "";
		u.fax.value 			= "";
	}
	
	function buscarCodigo(){
		
		consulta("mostrarDirecciones","agregarDireccion.php?accion=1&codigopostal="+u.cp.value+"&randm="+Math.random());
	}
	function mostrarDirecciones(datos){
		encontro		= datos.getElementsByTagName('encontro').item(0).firstChild.data;
		
		if(encontro>0){
			encontrocolonia	= datos.getElementsByTagName('encontrocolonia').item(0).firstChild.data;
			cp				= datos.getElementsByTagName('cp').item(0).firstChild.data;
			poblacion		= datos.getElementsByTagName('poblacion').item(0).firstChild.data;
			municipio		= datos.getElementsByTagName('municipio').item(0).firstChild.data;
			estado			= datos.getElementsByTagName('estado').item(0).firstChild.data;
			pais			= datos.getElementsByTagName('pais').item(0).firstChild.data;

			u.cp.value			= cp;
			u.poblacion.value	= poblacion;
			u.municipio.value	= municipio;
			u.estado.value		= estado;
			u.pais.value		= pais;
			if(encontrocolonia>1){
				var combocaja = '<select name="colonia" class="Tablas" style=" text-transform:uppercase; width:120px" onkeypress="document.all.telefono.focus();">';
				for(i=0; i<encontrocolonia; i++){
					col			= datos.getElementsByTagName('colonia').item(i).firstChild.data
					combocaja  += '<option value="'+col+'">'+col+'</option>';
				}
				combocaja	+= '</select>';
				u.caja.innerHTML 	= combocaja;
				u.colonia.focus();
			}else if(encontrocolonia==1){
				u.caja.innerHTML 	= cajacolonia;
				u.colonia.value 	= datos.getElementsByTagName('colonia').item(0).firstChild.data;
				u.telefono.focus();
			}
		}else{
			alerta("No se encontro el código postal","¡Atención!","cp");
			limpiarCodigos();
		}
	}
	function guardarDireccion(){
		
		consulta("seGuardo","agregarDireccion.php?accion=2&idcliente=<?=$_GET[idcliente]?>&calle="+u.calle.value+
				 "&numero="+u.numero.value+
				 "&crucecalles="+u.entrecalles.value+
				 "&cp="+u.cp.value+
				 "&colonia="+u.colonia.value+
				 "&poblacion="+u.poblacion.value+
				 "&municipio="+u.municipio.value+
				 "&estado="+u.estado.value+
				 "&pais="+u.pais.value+
				 "&telefono="+u.telefono.value+
				 "&fax="+u.fax.value+
				 "&facturado="+((u.chfacturacion.checked==true)?"SI":"NO")+
				 "&randm="+Math.random());
	}
	function seGuardo(datos){
		
		encontro		= datos.getElementsByTagName('guardado').item(0).firstChild.data;
		facturado		= datos.getElementsByTagName('facturado').item(0).firstChild.data;
		if(encontro==1){
			<?
				if($_GET[funcion]!=""){
					echo "parent.$_GET[funcion];";
				}
			?>
			alerta("La direccion ha sido guardada","","calle");
			if(facturado=="SI"){
				valor =  '<input name="chfacturacion" type="checkbox" id="chfacturacion" style="width:10px" value="SI" onClick=\'alerta("Ya esta registrada una dirección como facturación","¡Atención!","calle"); this.checked=false;\' /> Facturaci&oacute;n';
				u.celdaFacturacion.innerHTML = valor;
			}
			
			u.calle.value			="";
			u.numero.value			="";
			u.entrecalles.value		="";
			u.cp.value				="";
			u.caja.innerHTML 		= cajacolonia;
			u.poblacion.value		="";
			u.municipio.value		="";
			u.estado.value			="";
			u.pais.value			="";
			u.telefono.value		="";
			u.fax.value				="";
		}
	}
	function popUp(URL) {
		if(URL!=""){
			if(document.getElementById('abierto').value==""){
			document.getElementById('abierto').value="abierto";
		day = new Date();
		id = day.getTime();
		eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1 scrollbars=0 location=0 statusbar=0 menubar=1 resizable=1 width=550 height=400 left = 312,top = 184');");
			}else{
				alerta('Ya se encuentra abierta la ventana de busqueda por colonia','¡Atención!','cp');
			}
		}
	}
	function limpiarCodigos(){
		u.caja.innerHTML 		= cajacolonia;
			u.poblacion.value		="";
			u.municipio.value		="";
			u.estado.value			="";
			u.pais.value			="";
	}
	function validarDatos(){
		
		if(u.calle.value==""){
			alerta("Debe capturar la calle", "¡Atención!", "calle");
			return false;
		}
		if(u.numero.value==""){
			alerta("Debe capturar el numero", "¡Atención!", "numero");
			return false;
		}
		if(u.cp.value==""){
			alerta("Debe capturar el codigo postal", "¡Atención!", "cp");
			return false;
		}
		if(u.poblacion.value==""){
			alerta("Debe capturar el codigo postal", "¡Atención!", "cp");
			return false;
		}
		if(u.telefono.value==""){
			alerta("Debe capturar el telefono", "¡Atención!", "telefono");
			return false;
		}
		return true;
	}
	
	//idcliente
</script>
<body onLoad="document.getElementById('calle').focus()">
<form id="form1" name="form1" method="post" action=""><br><br>
  <table width="410" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="3" height="3" background="img/Ccaf1.jpg"></td>
      <td bgcolor="dee3d5"></td>
      <td width="3"  background="img/Ccaf2.jpg"></td>
    </tr>
    <tr bgcolor="dee3d5">
      <td height="26"></td>
      <td ><table width="407" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="Tablas">&nbsp;</td>
          <td width="136">&nbsp;</td>
          <td width="53" class="Tablas">&nbsp;</td>
          	<?
				require_once("../Conectar.php");
				$l = Conectarse("webpmm");
				
				$s  = "select id from direccion where origen='cl' and codigo = $_GET[idcliente] and facturacion = 'SI'";
				$rx = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rx)>0)
					$checkado = "onClick='alerta(\"Ya esta registrada una dirección como facturación\",\"¡Atención!\",\"calle\"); this.checked=false;'";
				else
					$checkado = "";
				
			?>
          <td width="157" class="Tablas" id="celdaFacturacion">
            <input name="chfacturacion" type="checkbox" id="chfacturacion" value="SI" />
            Facturaci&oacute;n</td>
        </tr>
        <tr>
          <td width="59" class="Tablas">Calle:</td>
          <td colspan="3" class="Tablas"><label>
            <input name="calle" type="text" class="Tablas"  id="calle" size="38" onBlur="trim(this.value,'calle');" value="" style=" text-transform:uppercase" onKeyPress="if(event.keyCode==13){document.all.numero.focus();}"/>
            </label>
            Numero:
            <input name="numero" type="text" class="Tablas"  id="numero" size="8" onBlur="trim(this.value,'numero');"  onKeyPress="if(event.keyCode==13){document.all.entrecalles.focus();}" value="" style=" text-transform:uppercase"/></td>
        </tr>
        <tr>
          <td class="Tablas">Cruces Calles:</td>
          <td colspan="3"><input name="entrecalles" type="text" class="Tablas"  id="entrecalles" size="60" onBlur="trim(this.value,'entrecalles');" value="" style=" text-transform:uppercase" onKeyPress="if(event.keyCode==13){document.all.cp.focus();}"/></td>
        </tr>
        <tr>
          <td colspan="4"><div id="txtCP">
              <table width="403" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="57" class="Tablas">C.P.:</td>
                  <td width="125"><input name="cp" type="text" class="Tablas"  id="cp" onBlur="if(this.value==''){limpiarCodigos()}" value="" size="10" maxlength="5" style=" text-transform:uppercase" onKeyPress="if(event.keyCode==13){buscarCodigo()}else{return solonumeros(event)}" /></td>
                  <td width="57" class="Tablas">Colonia:</td>
                  <td width="140" id="caja"><input name="colonia" type="text" class="Tablas"  id="colonia" size="20" readonly="" value="" style=" background:#FFFF99; text-transform:uppercase"  /></td>
                  <td width="24"><img src="../img/Buscar_24.gif" style="cursor:pointer" onClick="javascript:popUp('../catalogos/cliente/buscarcolonia2.php')"></td>
                </tr>
                <tr>
                  <td class="Tablas">Poblaci&oacute;n:</td>
                  <td><input name="poblacion" type="text" class="Tablas"  id="poblacion" size="20"  style=" background:#FFFF99;  text-transform:uppercase" readonly="true"  value="" /></td>
                  <td class="Tablas">Mun./Del.:</td>
                  <td colspan="2"><input name="municipio" type="text" class="Tablas"  id="municipio" size="20"  style="background:#FFFF99; text-transform:uppercase"  readonly="true" value="" /></td>
                </tr>
                <tr>
                  <td class="Tablas">Estado:</td>
                  <td><input name="estado" type="text" class="Tablas"  id="estado" size="20" value="" style="background:#FFFF99; text-transform:uppercase"  readonly="true" /></td>
                  <td class="Tablas">Pa&iacute;s:</td>
                  <td colspan="2"><input name="pais" type="text" class="Tablas"  id="pais" size="20" value="" style="background:#FFFF99; text-transform:uppercase"  readonly="true"/></td>
                </tr>
              </table>
          </div></td>
        </tr>
        <tr>
          <td class="Tablas">Telefono:</td>
          <td><input name="telefono" type="text" class="Tablas"  id="telefono" onKeyPress="if(event.keyCode==13){document.all.fax.focus();}else{return solonumeros(event);}" size="20" onBlur="trim(this.value,'telefono');" value="" style=" " /></td>
          <td colspan="2" class="Tablas">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fax:
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="fax" type="text" class="Tablas"  id="fax" size="20" onBlur="trim(this.value,'fax');" value="" style="font:tahoma;font-size:9px" onKeyPress="return solonumeros(event);" /></td>
        </tr>
        <tr>
          <td class="Tablas">&nbsp;</td>
          <td><input name="abierto" type="" id="abierto" value="<?=$abierto ?>"></td>
          <td colspan="2" class="Tablas">&nbsp;&nbsp;&nbsp;&nbsp;
              <table width="15" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td><img src="../img/Boton_Agregari.gif" alt="Agregar" width="70" height="20" align="absbottom" onClick="if(validarDatos()){guardarDireccion()};" style="cursor:pointer" /></td>
                </tr>
            </table></td>
        </tr>
        <tr>
          <td colspan="4" class="Tablas"><label></label></td>
        </tr>
      </table></td>
      <td></td>
    </tr>
    <tr>
      <td width="3" height="3"  background="img/Ccaf3.jpg"></td>
      <td bgcolor="dee3d5"></td>
      <td width="3"  background="img/Ccaf4.jpg"></td>
    </tr>
  </table>
</form>
</body>
</html>
<?
	}
?>