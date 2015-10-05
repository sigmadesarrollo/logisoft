<?
	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
	
	<link rel="stylesheet" href="../../tableroPermisos/javascript/estilosjs/form.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../../tableroPermisos/pages/css/reseter.css" />
	<link rel="stylesheet" type="text/css" href="../../tableroPermisos/pages/css/jquery-ui-1.7.2.custom.css" />
	<link href="../../tableroPermisos/css/styles.css" rel="stylesheet" type="text/css" />	
	<script type="text/javascript" src="../../tableroPermisos/pages/js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="../../tableroPermisos/pages/js/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
	<script type="text/javascript" src="../../tableroPermisos/javascript/ventanas/js/abrir-ventana-fija.js"></script>
	<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
    <script type="text/javascript" src="../../javascript/ajax.js"></script>
    <script type="text/javascript" src="../../tableroPermisos/javascript/estilosjs/custom-form-elements.js"></script>
    <link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
    <link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
	<script>
		$(document).ready(function(){
			$("#accordion").accordion({
				header: "h3",
				autoHeight: false,
				collapsible: true
			});
			
			setTimeout(function(){
				$(".mensajeflash").fadeOut("slow", function () {
						$(".flash").remove();
				});
			}, 3000);
		});
	</script>
<title>Documento sin t&iacute;tulo</title>
<style>
/*estilos para mostrar diferentes fondos*/
body{
	background: url(../../tableroPermisos/img/fondo1_tablero.jpg) #303030 no-repeat center top fixed;
}
#parafila{
	background:url(../../tableroPermisos/img/fondomenu.gif) repeat-x;
}
#paracombo{
	text-align:center;
	vertical-align:middle;
}
#paraboton{
	text-align:center;
	vertical-align:middle;
}
#textoCentrado{
	vertical-align:middle;
	text-align:left;
	font-size:14px; 
	font-family:Verdana, Geneva, sans-serif;
	padding-left:5px;
}
.seleccionada{
	width:91px;
	height:30px;
	background:url(../../tableroPermisos/img/seleccionada.png);
	text-align:center;
	vertical-align:middle;
	cursor:default;
}
.desseleccionada{
	width:91px;
	height:30px;
	background: url(../../tableroPermisos/img/sinseleccionar.png);
	text-align:center;
	vertical-align:middle;
	cursor:pointer;
}
#textoPestana{
	width:91px; 
	height:25px; 
	overflow:hidden; 
	padding-top:5px;
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
}
</style>
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>

<body link="#BA410D" vlink="#BA410D" alink="#BA410D" bgcolor="#ffffff">
<form id="form1" name="form1" method="post" action="">
<div id="wrap" style="width:1000px">
	<table border="0" cellpadding="0" cellspacing="0" style="background-color:none" width="100%">
<tr>
	<td height="10%" width="100%" align="center" valign="top">
    <?
		$s = "SELECT pt.id
		FROM permisos_grupos AS pt
		INNER JOIN catalogoempleado AS ce ON pt.id = ce.grupo
		WHERE ce.id = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l)or die($s);
		$f = mysql_fetch_object($r);
		switch ($f->id){
			case 1: //ADMINISTRACION
				$flash = "unidadAdministrativa";
				break;
			case 2: //COBRANZA
				$flash = "cobranza";
				break;
			case 3: //CORM
				$flash = "corm";
				break;
			case 4: //DIRECCION GENERAL
				$flash = "directorGeneral";
				break;
			case 5: //GERENTE SUCURSAL
				$flash = "gerenteGeneral";
				break;
			case 6: //OPERACIONES Y SERVICIOS
				$flash = "centroOperaciones";
				break;
			case 8: //PUNTO DE VENTA
				$flash = "puntoVenta";
				break;
			case 9: //VENTAS
				$flash = "ventas";
				break;
			case 10: //ADMINISTRADOR GENERAL
				$flash = "directorGeneral";
				break;
		}
	?>
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="1000" height="150" id="Header" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="../../tableroPermisos/css/<?=$flash?>.swf" /><param name="quality" value="high"/><param name="bgcolor" value="#ffffff" /><embed src="../../tableroPermisos/css/<?=$flash?>.swf" quality="high" bgcolor="#ffffff" width="1000" height="150" name="Header" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
</td>
</tr>
</table>

<table width="1000" border="0" cellpadding="0" cellspacing="0" id="posicionPagina">
	<tr id="parafila">	
    	<td width="218" height="30px" id="paracombo">
        
        <?
		$s = "SELECT pt.nombre, ce.sucursal
		FROM permisos_grupos AS pt
		INNER JOIN catalogoempleado AS ce ON pt.id = ce.grupo
		WHERE ce.id = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		if($f->nombre=="DIRECCION GENERAL" || $f->nombre=="ADMINISTRADOR GRAL" || $f->sucursal == 1){
		?>
		  <select name="sucursal" style="width:203px; font-family:Verdana, Geneva, sans-serif; font-size:12px" class="styled" alcambiar="cambiarSucursal(this.value); var pg = devolverIframe().src;  devolverIframe().src='';  devolverIframe().src=pg;">
		<?
			$s = "select * from catalogosucursal order by descripcion";
			$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
		?>
				<option <? if($_SESSION[IDSUCURSAL]==$f->id){echo "selected";}?> value="<?=$f->id?>"><?=strtoupper($f->descripcion)?></option>		
		<?
			}
			
		?>
			</select>
	 <? }else{
			$s = "select descripcion from catalogosucursal where id = $_SESSION[IDSUCURSAL]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			echo strtoupper($f->descripcion);
		}
		
	?>
        
        </td>
    <td valign="top" style="padding:0; vertical-align:top">
    	<table border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td height="30" class="seleccionada" id="pest0" onclick="seleccionarTab(0)"><div id="textoPestana">Pest 1</div></td>
              <td>&nbsp;</td>
                <td id="pest1" class="desseleccionada" onclick="seleccionarTab(1)"><div id="textoPestana">Pest 2</div></td>
                <td>&nbsp;</td>
                <td id="pest2" class="desseleccionada" onclick="seleccionarTab(2)"><div id="textoPestana">Pest 3</div></td>
                <td>&nbsp;</td>
                <td id="pest3" class="desseleccionada" onclick="seleccionarTab(3)"><div id="textoPestana">Pest 4</div></td>
                <td>&nbsp;</td>
                <td id="pest4" class="desseleccionada" onclick="seleccionarTab(4)"><div id="textoPestana">Pest 5</div></td>
            </tr>
       	</table>
    </td>
        <td width="240" id="paraboton">
        <table width="270">
        <tr>
        <td>
          <table width="245" cellpadding="0" cellspacing="0" border="0" align="center">
            <tr style="cursor:hand">
              <td width="104">&nbsp;</td>
              <td width="44">&nbsp;</td>
              <td width="34" id="textoCentrado">
                <img src="../img/buscar.png" title="Localizador Guías" onclick="abrirVentanaFija('../../guias/localizadorGuia.php', 400, 400, 'ventana', 'Localizador Guías');" style="cursor:pointer" />
                </td>
              <td width="33" align="left" id="textoCentrado"><span style="font-size:14px; font-family:Verdana, Geneva, sans-serif">
                <input type="hidden" name="modificando2" value="0" />
                </span><img src="../img/configuracion.png" title="Configuración" onclick="abrirVentanaFija('configuracion.php', 400, 400, 'ventana', 'Configuraci&oacute;n');" style="cursor:pointer" /></td>
              <td width="30" align="left" id="textoCentrado"><span style="font-size:14px; font-family:Verdana, Geneva, sans-serif">
                <input type="hidden" name="modificando" value="0" />
                </span><img src="../../img/salir8.gif" title="Cerrar Sesión" onclick="cerrarVentana()" style="cursor:pointer" /></td>
              </tr>
          </table>        </td>
        </tr>
        </table>
      </td>
    </tr>
    <tr>	
    	<td rowspan="2" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td> 
			<?
				$s = "SELECT CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
				d.telefono, cc.celular, cc.email, ct.descripcion AS tipocliente,
				cc.clasificacioncliente, FORMAT(IFNULL(sc.montoautorizado,0),2) AS limitecredito
				FROM catalogocliente cc
				INNER JOIN direccion d ON cc.id = d.codigo
				INNER JOIN catalogotipocliente ct ON cc.tipocliente = ct.id
				LEFT JOIN solicitudcredito sc ON cc.id = sc.cliente
				WHERE cc.id = 1 AND d.facturacion='SI'";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
			?>
			<div id="accordion" style="width:250px; margin:0px 0px 0px 5px">
                <div>
						<h3><a href="#">CLIENTE</a></h3>
						<div class="menu_acord_cont" id="accordions" style="overflow:hidden">
							<ul>
								<li class="Tablas"><?=cambio_texto($f->cliente) ?></li>	
								<li class="Tablas">TEL: <?=$f->telefono ?> </li>
								<li class="Tablas">CEL: <?=$f->celular ?></li>
								<li class="Tablas">EMAIL: <?=cambio_texto($f->email) ?></li>
								<li class="Tablas">Tipo cliente: <?=$f->tipocliente ?> </li>
								<li class="Tablas">Clasificación: <?=$f->clasificacioncliente ?></li>
								<li class="Tablas">Limite de Crédito: <?="$ ".$f->limitecredito ?></li>
								<li class="Tablas">Consumo vs Pagos</li>
								<li class="Tablas">Compromiso mensual vs compras mensuales</li>
								<li><a href="#">ver mas...</a></li>								
							</ul>
											
						</div>
						<h3><a href="#">DOCUMENTOS</a></h3>
						<div class="menu_acord_cont" id="accordions" style="overflow:hidden">
							<ul>
								<li class="Tablas">Propuestas de convenios (0) </li>	
								<li class="Tablas">Convenio por vencer (0)</li>
								<li class="Tablas">Convenio</li>
								<li class="Tablas">Solicitud de Crédito</li>
								<li class="Tablas">CAT </li>
								<li class="Tablas">Estado de Cuenta</li>
								<li class="Tablas">Estado de Cobranza</li>
								<li class="Tablas">Solicitud de guías empresariales (#)</li>
								<li class="Tablas">Historial de Movimientos</li>
							</ul>
											
						</div>		
				</div>
						
			</div>
			</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table></td>
    	<script>
		var idsasolicitar = "<?=$idsconsulta?>";
	</script>
        <td colspan="2" valign="top" height="0px"></td>
    </tr>
    <tr>
      <td colspan="2" valign="top">
	         <iframe onblur="document.all.modificando.value=1;" name="pagina0" id="pagina0" scrolling="auto" 
        			align="top" width="770" height="770" src="<?=$pagina1?>" frameborder="0" style="display:''"></iframe>
                 
      				
      </td>
    </tr>
  </table>
</div>
</form>
</body>
</html>
