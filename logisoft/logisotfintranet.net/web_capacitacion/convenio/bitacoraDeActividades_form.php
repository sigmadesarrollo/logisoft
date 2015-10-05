<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Datos Evaluaci&oacute;n</title>
<script type="text/javascript" src="js/ajax-dynamic-list.js"></script>
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112">
<script language="javascript" src="../javascript/funciones.js"></script>
<script type="text/javascript" src="../javascript/ClaseTabla.js"></script>
<script type="text/javascript" src="../javascript/DataSet.js"></script>
<script type="text/javascript" src="../javascript/ajax.js"></script>
<script type="text/javascript" src="../javascript/jquery-1.4.2.min.js"></script>
<script>
	var u = document.all;

	function traerAlcliente(valor){
		ocultarBuscador();
		obtenerClienteBusqueda(valor)
	}
	
	function obtenerClienteBusqueda(id){
		u.idcliente.value = id;
		consultaTexto("mostrarCliente","../guias/guia_consultajson.php?accion=2&idcliente="+id+"&valrandom="+Math.random());
	}
	
	function mostrarCliente(datos){
		try{
			var dcliente = eval(datos);
		}catch(e){
			alerta3(datos);
		}
		var u = document.all;
		if(dcliente.cliente!="0"){
			u.nombre.value	= dcliente.cliente.ncliente;	
		}else{			
			alerta('El numero de cliente no existe','¡Atención!','cliente');			
		}
	}
	
	function cambio(valor){
		if(valor==6){
			u.logro.value = "";
			u.logro.disabled = true;
		}else{
			u.logro.disabled = false;
		}
		if(valor==3 || valor==4){
			u.folio.disabled = false;
		}else{
			u.folio.value = "";
			u.folio.disabled = true;
		}
	}
	
	function guardarDatos(){
		var datos = $('#form1').serialize();
		crearLoading();
		$.ajax({
		   type: "POST",
		   url: "bitacoraDeActividades_con.php",
		   data: "accion=2&"+datos,
		   success: respuestaGuardar
		 });
		return false;
	}
	
	function respuestaGuardar(datos){
		ocultarLoading();
		if(datos.indexOf("muybien")>-1){
			parent.cerrarVentana();
		}else{
			alert(datos);
		}
	}
</script>
<style type="text/css">	
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:175px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:0.9em;
		z-index:100;
	}
	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */
		margin:1px;		
		padding:1px;
		cursor:pointer;
		font-size:0.9em;
	}
	#ajax_listOfOptions .optionDiv{	/* Div for each item in list */

	}
	#ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */
		background-color:#317082;
		color:#FFF;
	}
	#ajax_listOfOptions_iframe{
		background-color:#F00;
		position:absolute;
		z-index:5;
	}
	form{
		display:inline;
	}
	</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
</head>
<BODY>
<br>
<form id="form1" name="form1" method="post" action="">
<center>
  <table width="200" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><table width="363" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="1" height="3" background="../img/Ccaf1.jpg"></td>
          <td width="348" bgcolor="dee3d5"></td>
          <td width="1"  background="../img/Ccaf2.jpg"></td>
        </tr>
        <tr bgcolor="dee3d5">
          <td height="26"></td>
          <td ><table width="360" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="74" class="Tablas">FECHA:</td>
                <td width="119" class="Tablas"><label>
                  <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=date('d/m/Y')?>"/>
                </label></td>
                <td width="167" class="Tablas"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"></div></td>
              </tr>
              <tr>
                <td class="Tablas">ACTIVIDAD</td>
                <td colspan="2" class="Tablas">
                	<select name="actividades" onchange="cambio(this.value)">
                    	<?
							$s = "select * from catalogoactividades order by actividad";
							$r = mysql_query($s,$l) or die($s);
							while($f = mysql_fetch_object($r)){
						?>
								<option value="<?=$f->id?>"><?=$f->actividad?></option>
                        <?
							}
						?>
                    </select>
                </td>
              </tr>
              <tr>
                <td class="Tablas">CLIENTE</td>
                <td colspan="2" class="Tablas">
                	<table width="258" border="0" cellpadding="0" cellspacing="0" cols="0">
                    	<tr>
                        	<td width="58"><input type="text" name="idcliente" style="width:50px" /></td>
                            <td width="34"><img src="../img/Buscar_24.gif" name="img_cliente" width="24" height="23" align="absbottom" id="img_cliente" style="cursor:pointer" onClick="mostrarBuscador();"></td>
                            <td width="166"><input type="text" name="nombre" style="width:200px" /></td>
                        </tr>
                    </table>
                </td>
              </tr>
              <tr>
                <td class="Tablas">FOLIO PROP/CONV</td>
                <td colspan="2" class="Tablas">
                	<input type="text" name="folio" disabled="disabled"></textarea>
                </td>
              </tr>
              <tr>
                <td class="Tablas">LOGRO</td>
                <td colspan="2" class="Tablas">
                	<textarea name="logro" disabled="disabled"></textarea>
                </td>
              </tr>
              <tr>
                <td class="Tablas">TIEMPO INVERTIDO</td>
                <td colspan="2" class="Tablas">
                	<input type="text" name="tiempoinvertido" />
                </td>
              </tr>
              <tr>
                <td class="Tablas">PROXIMA CITA</td>
                <td class="Tablas">
                  <input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=date('d/m/Y')?>"/></td>
                <td class="Tablas"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)"></div></td>
              </tr>
              <tr>
                <td colspan="3" class="Tablas" align="center">
                	<div class="ebtn_guardar" onclick="guardarDatos()"></div>
                </td>
                </tr>
          </table></td>
          <td></td>
        </tr>
        <tr>
          <td width="1" height="3"  background="../img/Ccaf3.jpg"></td>
          <td bgcolor="dee3d5"></td>
          <td width="1"  background="../img/Ccaf4.jpg"></td>
        </tr>
      </table></td>
    </tr>
  </table>
  </center><?
	  $raiz = "../";
	$funcion = "traerAlcliente";
	$nombreBuscador = "buscadorClientes";
	$funcionMostrar = "mostrarBuscador";
	$funcionOcultar = "ocultarBuscador";
	include("../buscadores_generales/buscadorIncrustado.php");
	?>
  </form>
</body>
</html>