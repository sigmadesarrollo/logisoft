<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
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
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/moautocomplete.js"></script>
<script src="../../javascript/jquery-1.4.2.min.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" />
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<script>
	
	var tabla1 = new ClaseTabla();
	var u = document.all;
	var mens = new ClaseMensajes();
	mens.iniciar('../../javascript');
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"I", medida:4, alineacion:"center", tipo:'oculto',  datos:"id"},
			{nombre:"SUCURSAL", medida:80, alineacion:"center",  datos:"sucursal"},
			{nombre:"TIPO", medida:80, alineacion:"center",  datos:"tipo"},
			{nombre:"DESDE", medida:90, alineacion:"center", datos:"desde"},
			{nombre:"HASTA", medida:90, alineacion:"center",  datos:"hasta"},
			{nombre:"GRAT EAD", medida:80, alineacion:"center", datos:"gratisead"},
			{nombre:"GRAT REC", medida:80, alineacion:"center", datos:"gratisrec"},
			{nombre:"KG", medida:80, alineacion:"center",  datos:"valpeso"},
			{nombre:"COSTO", medida:100, alineacion:"center", datos:"descuento"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		traerDatos();
	}
	
	function bloquearSucursal(valor){
		u.sucursal.readOnly=valor;
		u.sucursal.style.backgroundColor=(valor)?"#FFFF99":"";
		u.sucursal.value = "";
		u.sucursal_hidden.value = "";
		document.getElementById('imagenBuscarSucursal').style.display = (valor)?'none':'';
	}
	
	var desc = new Array(<?php echo $desc; ?>);
	
	function f_tipoGuia(valor){
		u.tipoPromo[0].checked = false;
		u.tipoPromo[1].checked = false;
		u.tipoPromo[2].checked = false;
		u.peso.value = "";
		u.descuento.value = "";
		f_tipoPromo(1);
		if(valor=='V'){
			document.getElementById('filaEAD').style.display='';
			document.getElementById('filaRecoleccion').style.display='';
			$("#celdaPeso").css({'display':'none'});
			$("input[name='peso']").css({'display':'none'});
		}
		if(valor=='E'){
			document.getElementById('filaEAD').style.display='none';
			document.getElementById('filaRecoleccion').style.display='none';
			$("#celdaPeso").css({'display':''});
			$("input[name='peso']").css({'display':''});
		}
	}
	
	function f_tipoPromo(valor){
		if(valor==2){
			u.peso.value = "";
			u.descuento.value = "";
			u.peso.disabled = false;
			u.descuento.disabled = false;
			u.peso.style.backgroundColor = "";
			u.descuento.style.backgroundColor = "";
		}else{
			u.peso.value = "";
			u.descuento.value = "";
			u.peso.disabled = true;
			u.descuento.disabled = true;
			u.peso.style.backgroundColor = "#FFFF99";
			u.descuento.style.backgroundColor = "#FFFF99";
		}
	}
	
	function guardar(){
		
		if(document.all.tipoguia[0].checked==false && document.all.tipoguia[1].checked==false){
			mens.show("A","Seleccione a que aplica la promoción","¡ATENCION!");
			return false;
		}
		
		if(document.all.tipoPromo[0].checked==false && document.all.tipoPromo[1].checked==false && document.all.tipoPromo[2].checked==false){
			mens.show("A","Seleccione el tipo de promoción","¡ATENCION!");
			return false;
		}
		
		
		var datosA = $("form[name='formarriba']").serialize();
		var datosB = $("form[name='formabajo']").serialize();
		
		$.ajax({
			type: "POST",
			url: "promociones_con.php",
			data: "accion=1&"+datosA+"&"+datosB,
			success: function(msg){
				if(msg.indexOf("datosGuardados")>-1){
					mens.show("I","Los datos han sido guardados","¡ATENCION!");
					traerDatos();
				}else{
					mens.show("A","Error al guardar. "+msg, "ATENCION");
				}
			}
		});
	}
	
	function traerDatos(valor){
		if(valor==null){
			valor = "";
		}else{
			valor = "&ano="+valor;
		}
		$.ajax({
			type: "POST",
			url: "promociones_con.php",
			data: "accion=2"+valor,
			success: function(msg){
				try{
					var obj = eval(msg);
				}catch(e){
					mens.show("A","Error "+msg,"Atencion");
					return false;
				}
				tabla1.setJsonData(obj.filas);
				
				$("#anos").html = "";
				var opciones = "<option value='0'>.::Seleccione::.</option>";
				for(var i=0; i<obj.anos.length; i++){
					opciones += "<option value='"+obj.anos[i]+"'>"+obj.anos[i]+"</option>";
				}
				$("#anos").val(obj.anoactual);
				$("#anos").html = opciones;
			}
		});
	}
	
	function limpiar(){
		$("form input:radio").removeAttr("checked");
		f_tipoPromo(1);
		bloquearSucursal(true);
		$("input[name='checktodas']").attr("checked",true);
	}
	
	function eliminar(){
		$.ajax({
			type: "POST",
			url: "promociones_con.php",
			data: "accion=3&idpromociones="+tabla1.getSelectedRow().id,
			success: function(msg){
				if(msg.indexOf('borradoExistoso')>-1){
					mens.show("I","Se ha eliminado la promoción","ATENCION");
					tabla1.deleteById(tabla1.getSelectedIdRow());
				}else{
					mens.show("A","Error "+msg,"ATENCION");
				}
			}
		});
	}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
  <table width="603" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Configuración de promociones</td>
    </tr>
    <tr>
      <td><table width="76%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            	<form name="formarriba">
            	<table width="601" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="53">Sucursal
                      <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="" /></td>
                    <td width="22"><input type="checkbox" name="checktodas" checked="checked" onclick="bloquearSucursal(this.checked);" /></td>
                    <td width="42">Todas</td>
                    <td width="268"><input name="sucursal" type="text" id="sucursal" style="width:200px; background:#FFFF99" readonly="readonly" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.sucursal_hidden.value = this.codigo; if(u.sucursal_hidden.value=='undefined'){ this.codigo=''; u.sucursal_hidden.value='';}}" onblur="document.all.sucursal_hidden.value = this.codigo; if(u.sucursal_hidden.value=='undefined'){ this.codigo=''; u.sucursal_hidden.value=''; }" />
                      <img src="../../img/Buscar_24.gif" id="imagenBuscarSucursal" width="24" height="23" align="absbottom" style="cursor:pointer; display:none" onclick="abrirVentanaFija('../../buscadores_generales/buscarsucursal.php', 600, 450, 'ventana', 'Busqueda');" /></td>
                    <td width="46">&nbsp;</td>
                    <td width="96">&nbsp;</td>
                    <td width="74">&nbsp;</td>
                </tr>
                  <tr>
                    <td colspan="7">
                    	<table width="384" border="0" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td width="71">Desde</td>
                               <td width="87" align="right">
                        	<input name="inicio" type="text" class="Tablas" id="inicio" readonly="readonly" 
                            style="width:65px; background-color:#FFFF99; border:1px #CCC solid;" value="<?=date('d/m/Y') ?>" />
                            &nbsp;
                        </td>
                      <td width="30"><img src="../../img/calendario.gif" id="calendarioInicio" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer;" title="Calendario" onclick="displayCalendar(document.all.inicio,'dd/mm/yyyy',this)" /></td>
                        <td width="42" align="center">Y</td>
                        <td width="114"align="right">
                        	<input name="fin" type="text" class="Tablas" id="fin" readonly="readonly" 
                            style="width:65px; background-color:#FFFF99; border:1px #CCC solid;" value="<?=date('d/m/Y') ?>" />
                            &nbsp;
                        </td>
                        <td width="40">
                        <img src="../../img/calendario.gif" id="calendarioFin" alt="Alta" width="20" height="20" 
                        align="absbottom" style="cursor:pointer;" title="Calendario" 
                        onclick="displayCalendar(document.all.fin,'dd/mm/yyyy',this)" />
                        </td>
                            </tr>
                        </table>
                    </td>
                  </tr>
              </table>
              	</form>
            </td>
          </tr>
          <tr>
          	<td>
            	<form name="formabajo">
            	<table width="710" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td class="FondoTabla" colspan="5">Promoci&oacute;n para:</td>
                    </tr>
                	<tr>
                    	<td width="12">&nbsp;</td>
                        <td width="24">
                        	<input type="radio" name="tipoguia" value="VENTANILLA" onclick="f_tipoGuia('V')" />
                        </td>
                        <td width="118">
                        	Guias de ventanilla
                        </td>
                        <td width="26">
                        	<input type="radio" name="tipoguia" value="EMPRESARIAL" onclick="f_tipoGuia('E')" />
                        </td>
                        <td width="420">
                        	Guias Empresariales
                        </td>
                    </tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td colspan="4">
                      	<table width="696">
                        	<tr id="filaEAD" style="display:none">
                            	<td width="23"><input type="radio" name="tipoPromo" value="ead" onclick="f_tipoPromo(0)" /></td>
                                <td width="111">Gratis EAD</td>
                                <td width="48"></td>
                                <td width="78"></td>
                                <td width="77"></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr id="filaRecoleccion" style="display:none">
                            	<td width="23"><input type="radio" name="tipoPromo" value="rec" onclick="f_tipoPromo(1)"  /></td>
                                <td width="111">Gratis Recolecci&oacute;n</td>
                                <td width="48"></td>
                                <td width="78"></td>
                                <td width="77"></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr id="filaEmpresarial">
                            	<td width="23"><input type="radio" name="tipoPromo" value="desc" onclick="f_tipoPromo(2)" /></td>
                                <td width="111">Descuento Flete</td>
                                <td width="48" id="celdaPeso">Si pesa</td>
                                <td width="78" id="celdaPeso"><input type="text" name="peso" style="width:50px;background:#FFFF99" disabled="disabled" /></td>
                                <td width="77">Costo</td>
                                <td colspan="2">$
                                  <input type="text" name="descuento" style="width:50px;background:#FFFF99" disabled="disabled" /></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td width="73" align="right"><div class="ebtn_guardar" onclick="guardar()"></div></td>
                              <td width="147" align="right"><div class="ebtn_nuevo" onclick="limpiar()"></div></td>
                            </tr>
                        </table>
                      </td>
               	  </tr>
                </table>
              </form>
            </td>
          </tr>
          <tr>
            <td class="FondoTabla">Promociones Registradas:</td>
        </tr>
		  <tr>
		  	<td>
            	<table width="300" height="18" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="126">
                        	Seleccione el año:
                        </td>
                        <td width="85">
                        	<select id="anos" style="width:110px;" onchange="if(this.value!=0){traerDatos(this.value);}">
                        				<option value="0">.::Seleccione::.</option>
                            	<?
									$s = "
									SELECT ano FROM (
									SELECT YEAR(desde) ano
									FROM configuracion_promociones
									) t1
									GROUP BY ano
									order by ano";
									$r = mysql_query($s,$l) or die($s);
									while($f = mysql_fetch_object($r)){
								?>
                            			<option value="<?=$f->ano?>"><?=$f->ano?></option>
								<?		
									}
								?>
                            </select></td>
                    </tr>
                </table>
            </td>
		  </tr>
          <tr>
            <td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">              
            </table></td>
          </tr>
		  <tr>
		  	<td>&nbsp;</td>
		  </tr>
          <tr>
            <td id="detalle_pag"></td>
          </tr>
          <tr>
            <td align="right"><div class="ebtn_eliminar" onclick="mens.show('C','¿Desea eliminar la promoción seleccionar?','ATENCION',null,'eliminar()'); "></div></td>
          </tr>
      </table></td>
    </tr>
  </table>
</body>
</html>
