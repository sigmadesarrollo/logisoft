<? 
	session_start(); 
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');

	$s = "SELECT * from catalogosucursal where id = $_SESSION[IDSUCURSAL]";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$idsucursal = $f->id;
	$nombresucursal = $f->descripcion;

	$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion,':',cs.id) AS descripcion
	FROM catalogosucursal cs ORDER BY cs.descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
		}
		$desc = substr($desc, 0, -1);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/DataSet.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/jquery-1.4.2.min.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" />
<link href="../javascript/estiloclasetablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<script>
	var tabla1 = new ClaseTabla();
	var DS1 = new DataSet();
	var u = document.all;
	var mens = new ClaseMensajes();
	
	mens.iniciar('../javascript');
	
	function generarTXT(){
		var datos = $("form").serialize();
		window.open("deposito_txt.php?"+datos);
	}
		
	var desc = new Array(<?php echo $desc; ?>);
</script>
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
  <table width="630" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla" >GENERAR DEPOSITO</td>
    </tr>
    <tr>
      <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
        	<td>
            <form id="form1" name="form1" method="post" action="">
            <table border="0" cellpadding="0" cellspacing="0">
        	  <tr>
        	    <td width="56">Sucursal
        	      <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$idsucursal?>" /></td>
        	    <td width="22">&nbsp;</td>
        	    <td width="30">&nbsp;</td>
        	    <td width="269"><input name="sucursal" type="text" id="sucursal" style="width:200px;" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.sucursal_hidden.value = this.codigo; if(u.sucursal_hidden.value=='undefined'){ this.codigo=''; u.sucursal_hidden.value='';}}" onblur="document.all.sucursal_hidden.value = this.codigo; if(u.sucursal_hidden.value=='undefined'){ this.codigo=''; u.sucursal_hidden.value=''; }" value="<?=$nombresucursal?>" />
        	      <img src="../img/Buscar_24.gif" id="imagenBuscarSucursal" width="24" height="23" align="absbottom" style="cursor:pointer;" onclick="mens.popup('../buscadores_generales/buscarsucursal.php', 600, 450, 'ventana', 'Busqueda');" /></td>
        	    <td width="34">&nbsp;&nbsp;</td>
        	    <td width="26">&nbsp;</td>
        	    <td width="39">&nbsp;</td>
        	    <td width="150">&nbsp;</td>
      	    </tr>
      	  </table>
       	  <table width="624" border="0" cellpadding="0" cellspacing="0">
               	  <tr>
                    	<td width="88">Fechas</td>
                        <td width="93" align="right">
                        	<input name="inicio" type="text" class="Tablas" id="inicio" readonly="readonly" 
                            style="width:65px; background-color:#FFFF99; border:1px #CCC solid;" value="<?=date('d/m/Y') ?>" />
                            &nbsp;
                        </td>
                      <td width="28"><img src="../img/calendario.gif" id="calendarioInicio" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer;" title="Calendario" onclick="displayCalendar(document.all.inicio,'dd/mm/yyyy',this)" /></td>
                        <td width="65" align="center">Y</td>
                        <td width="102"align="right">
                        	<input name="fin" type="text" class="Tablas" id="fin" readonly="readonly" 
                            style="width:65px; background-color:#FFFF99; border:1px #CCC solid;" value="<?=date('d/m/Y') ?>" />
                            &nbsp;
                        </td>
                        <td width="72">
                        <img src="../img/calendario.gif" id="calendarioFin" alt="Alta" width="20" height="20" 
                        align="absbottom" style="cursor:pointer;" title="Calendario" 
                        onclick="displayCalendar(document.all.fin,'dd/mm/yyyy',this)" />
                        </td>
                        <td width="88"><div class="ebtn_guardar" onclick="generarTXT()"></div></td>
						<td width="88">&nbsp;</td>
                    </tr>
                </table>
                </form>
          </td>
        </tr>
        <tr>
    	<td align="right">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
</body>
</html>
