<? session_start(); 
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS empleado FROM catalogoempleado WHERE id = ".$_SESSION[IDUSUARIO]."";
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script>
	var u = document.all;
	var mens = new ClaseMensajes();
	mens.iniciar("../javascript");
	
	function ubi(id){
		return document.getElementById(id);
	}
	
	window.onload = function(){
		obtenerDatosGuia('<?=$_GET[guia] ?>');		
	}

	function obtenerDatosGuia(guia){
		consultaTexto("mostrarDatosGuia","reporteFaltante_con.php?accion=1&guia="+guia
		+"&bitacora="+u.h_bitacora.value+"&s="+Math.random());
	}
	
	function mostrarDatosGuia(datos){
		if(datos.indexOf("noencontro")<0){
			var obj = eval(datos);
			
			
			ubi('in_fechaylugar').innerHTML = obj.bitacora.sucursal+", <?=date("d/m/Y")?>";
			ubi('in_noguia').innerHTML = '<?=$_GET[guia] ?>';
			ubi('in_fecha').innerHTML = obj.principal.fecha
			ubi('in_origen').innerHTML = obj.principal.origen;
			ubi('in_destino').innerHTML = obj.principal.destino;
			ubi('in_importe').innerHTML = "$ "+obj.principal.importe;
			ubi('in_contenido').innerHTML = obj.principal.contenido;
			
			ubi('in_remitente').innerHTML = obj.principal.remitente;
			ubi('in_destinatario').innerHTML = obj.principal.destinatario;
			ubi('in_operador1').innerHTML = obj.bitacora.empleado1;
			ubi('in_operador2').innerHTML = obj.bitacora.empleado2;
			
			ubi('in_unidad').innerHTML = obj.bitacora.unidad;
			ubi('in_ruta').innerHTML = obj.bitacora.ruta;
			enviarImpresion();
		}
	}
	
	function obtenerEmpleado(empleado){
		u.empleado.value = empleado;
		consultaTexto("mostrarEmpleado","reporteFaltante_con.php?accion=2&empleado="+empleado);
	}
	
	function mostrarEmpleado(datos){
		if(datos.indexOf("noencontro")<0){
			var obj = eval(datos);
			u.nombre.value = obj.empleado;
		}else{
			mens.show("A","El empleado no existe","¡Atención!","empleado");
			u.empleado.value = "";
			u.nombre.value = "";
		}
	}
	
	function validar(){
		if(u.empleado.value==""){
			mens.show("A","Debe capturar quien Autoriza","¡Atención!","empleado");
			return false;
		}
		enviarImpresion();
		u.d_guardar.style.visibility = "hidden";
		u.d_nuevo.style.visibility = "hidden";
		consultaTexto("registro","reporteFaltante_con.php?accion=3&guia=<?=$_GET[guia] ?>&autorizo="+u.empleado.value
		+"&observaciones="+u.observaciones.value+"&bitacora="+u.h_bitacora.value+"&s="+Math.random());
	}
	
	function registro(datos){
		if(datos.indexOf("ok")>-1){
			mens.show("I","Se registro el faltante satisfactoriamente","");
			//u.d_guardar.style.visibility = "hidden";
			parent.mostrarGuiaArreglo();
		}else{
			mens.show("A","Hubo un error al guardar "+datos,"¡Atención!");
			u.d_guardar.style.visibility = "visible";
		}
	}
	
	function limpiar(){
		u.empleado.value = "";
		u.nombre.value = "";
		u.observaciones.value = "";
	}
	
	
</script>
<title>Documento sin t&iacute;tulo</title>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>
<style type="text/css" media="all">
	.titulo1{
		font-size:20px;
	}
	.titulo2{
		font-size:12px;
	}
	.textos{
		text-decoration:underline;
	}
</style>
<body>
<form id="form1" name="form1" method="post" action="">
</form>
<table width="668" height="239" align="center" style="border:1px solid #88D2FF" cellpadding="0" cellspacing="0">
	<tr style="">
   	  <td width="153" height="54" rowspan="2" class="titulo2" style="text-align:center; color:#FFF;background-color:#039">
        	REPORTE DE DAÑOS <br />
       	O FALTANTES<br />
       	EMBARQUE</td>
        <td width="423" height="41" style="vertical-align:top; text-align:center" class="titulo1">PAQUETERIA Y MENSAJERIA</td>
        <td width="76" rowspan="2" style="vertical-align:top; text-align:center"><img src="../img/logoPMM.png" width="50" height="52" /></td>
    </tr>
	<tr>
	  <td height="15" style="text-align:right">FOLIO <?=str_pad($_GET[folioreporte],7,"0",STR_PAD_LEFT)?></td>
  </tr>
	<tr>
	  <td height="30" colspan="3" class="titulo2"  style="border:1px solid #88D2FF">
      	<table width="665" height="59" border="0" cellpadding="0" cellspacing="1px">
        	<tr>
            	<td colspan="2">FECHA Y LUGAR DE EXPED.</td>
                <td colspan="8" class="textos" id="in_fechaylugar"></td>
            </tr>
        	<tr>
        	  <td width="69">No. DE GUIA:</td>
        	  <td width="95" class="textos" id="in_noguia"></td>
        	  <td width="54">FECHA:</td>
        	  <td width="69" class="textos" id="in_fecha"></td>
        	  <td width="53">ORIGEN:</td>
        	  <td width="60" class="textos" id="in_origen"></td>
        	  <td width="56">DESTINO:</td>
        	  <td width="65" class="textos" id="in_destino">&nbsp;</td>
        	  <td width="65">IMPORTE:</td>
        	  <td width="74" class="textos" id="in_importe"></td>
      	  </tr>
        	<tr>
        	  <td>CONTENIDO:</td>
        	  <td colspan="9" class="textos" id="in_contenido">&nbsp;</td>
       	  </tr>
        </table>
      </td>
  </tr>
	<tr>
	  <td height="30" colspan="3" class="titulo2" style="border:1px solid #88D2FF">
      	<table width="666" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="146" height="33"></td>
                <td width="73" class="titulo2">DA&Ntilde;O</td>
                <td width="97" style="text-align:center"><input type="checkbox" name="dano" style="width:25px; height:25px;" /></td>
                <td width="93" class="titulo2">FALTANTE</td>
                <td width="94" style="text-align:center"><input type="checkbox" name="faltante" checked="checked" style="width:25px; height:25px;" /></td>
                <td width="129"></td>
            </tr>
        </table>
      </td>
  </tr>
	<tr>
	  <td height="30" colspan="3"  style="border:1px solid #88D2FF">
      	<table width="666" height="46" border="0" cellpadding="0" cellspacing="1px">
        	<tr>
            	<td width="109">CLIENTE ORIGEN:</td>
                <td width="539" class="textos" id="in_remitente">&nbsp;</td>
            </tr>
        	<tr>
        	  <td>CLIENTE DESTINO:</td>
        	  <td class="textos" id="in_destinatario">&nbsp;</td>
      	  </tr>
        </table>
      </td>
  </tr>
	<tr>
	  <td height="30" colspan="3" class="titulo2">
      	<table width="665" height="100" border="0" cellpadding="0" cellspacing="1">
        	<tr>
            	<td width="120">PERSONA QUE RECIBE:</td>
                <td width="526" class="textos"><?=ucwords(utf8_decode($f->empleado))?></td>
            </tr>
        	<tr>
        	  <td>OPERADORES:</td>
        	  <td class="textos"></td>
      	  </tr>
        	<tr>
        	  <td colspan="2">
              	<table border="0" cellpadding="0" cellspacing="0">
                	<tr class="textos">
                    	<td width="332" id="in_operador1">&nbsp;</td>
                        <td width="324" id="in_operador2"></td>
                    </tr>
                </table>
              </td>
       	  </tr>
        	<tr>
        	  <td colspan="2">
              	<table width="655" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="51">UNIDAD:</td>
                        <td width="124" class="textos" id="in_unidad"></td>
                        <td width="40">RUTA</td>
                        <td width="440" class="textos" id="in_ruta"></td>
                    </tr>
                </table>
              </td>
      	  </tr>
        	<tr>
        	  <td colspan="2"><table width="655" border="0" cellpadding="0" cellspacing="0">
        	    <tr>
        	      <td width="92">COMENTARIOS:</td>
        	      <td width="563" class="textos"><?=$_GET[observaciones]?></td>
       	        </tr>
      	    </table></td>
      	  </tr>
	    </table>
      </td>
  </tr>
</table>
<input name="h_ruta" type="hidden" id="h_ruta" value="<?=$_GET[idruta] ?>" />
            <input name="h_bitacora" type="hidden" id="h_bitacora" value="<?=$_GET[bitacora] ?>" />
</body>
</html>
<object id=factory viewastext style="display:none"
classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"
  codebase="https://www.pmmintranet.net/software/smsx.cab#Version=6,5,439,30">
</object>
<script>
	function enviarImpresion(){
		factory.printing.header = "";
		factory.printing.footer = "";
		factory.printing.portrait = false;
		factory.printing.leftMargin = 2.0;
		factory.printing.topMargin = 5.0;
		factory.printing.rightMargin = 1.0;
		factory.printing.bottomMargin = 1.0;
	  	factory.printing.Print(false);
		window.close();
	}
	
</script>