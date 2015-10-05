<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	$s = mysql_query("SELECT CONCAT_WS(':',descripcion,id) AS descripcion FROM catalogodescripcion",$l);
	if(mysql_num_rows($s)>0){
		while($f=mysql_fetch_array($s)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
		}	
		$desc=substr($desc, 0, -1);
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us">
<head> 
	<title>DL Demo</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style type="text/css">
	body{
		font-family:Verdana, Geneva, sans-serif;
		font-size:12px;
	}
	.titulos{
		padding-top:5px;
		height:20px; 
		background-color:#225E99; 
		text-align:center; 
		vertical-align:middle; 
		color:#FFF; font-weight:bold;
	}
</style>
<script src="jquery.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<link href="../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css" />
<script>
	var tabla1 	= new ClaseTabla();
	var mens	= new ClaseMensajes();
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"CANT", medida:45, alineacion:"right", datos:"cantidad"},			
			{nombre:"DESCRIPCION", medida:100, alineacion:"left", datos:"descripcion"},			
			{nombre:"PESO", medida:4, tipo:"oculto", alineacion:"right", datos:"peso"},
			{nombre:"LARGO", medida:4, tipo:"oculto", alineacion:"right",  datos:"largo"},
			{nombre:"ANCHO", medida:4, tipo:"oculto", alineacion:"right",  datos:"ancho"},
			{nombre:"ALTO", medida:4, tipo:"oculto", alineacion:"right",  datos:"alto"},
			{nombre:"P_VOLU", medida:4, tipo:"oculto", alineacion:"right", datos:"volumen"},
			{nombre:"P_TOTAL", medida:4, tipo:"oculto", alineacion:"right", datos:"pesototal"}
		],
		filasInicial:5,
		alto:100,
		seleccion:true,
		ordenable:false,		
		nombrevar:"tabla1",
		eventoDblClickFila:"eliminarFila()"
	});
	
	function cotizar(valores){
		document.getElementById('paginaRastreo').src = "../../axfbt5asd/cotizar.php?"+valores;
		//alert(valores);
	}
	
	function imprimir(valores){
		window.open("../../axfbt5asd/cotizarImprimir.php?"+valores,null,"width=5px height=5px top=5000 left=5000");
		//alert(valores);
	}
	
	function devolverCliente(valor){
		$.ajax({
		   type: "GET",
		   url: "cotizarguia_con.php",
		   data: "accion=1&idcliente="+valor,
		   success: function(msg){
			  var obj = eval(msg);
			  $("#nombrecliente").val(obj.nombre);
			  $("#idcliente").val(obj.id);
		   }
		});
	}
	
	$(document).ready(function(){
		$('#idcliente').keypress(function(event) {
		  	if (event.keyCode == '13') {
			 	event.preventDefault();
				devolverCliente( $("#idcliente").val());
		   	}
		});
		
		$('#idcliente').blur(function() {
			devolverCliente( $("#idcliente").val());
		});
		$('#agregar').click(function() {
			agregarDetalle();
		});
		tabla1.create();
		mens.iniciar("../javascript");
	})
	
	function agregarDetalle(){
		if($('#descripcion').val()==""){
			mens.show("A","Debe capturar Descripcion","¡Atención!","descripcion");
			return false;
		}
		if($('#largo').val()==""){
			mens.show("A","Debe capturar Largo","¡Atención!","largo");
			return false;
		}
		if(parseFloat($('#largo').val())==0){
			mens.show("A","Largo debe ser mayor a Cero","¡Atención!","largo");
			return false;
		}
		if($('#alto').val()==""){
			mens.show("A","Debe capturar Alto","¡Atención!","alto");
			return false;
		}
		if(parseFloat($('#alto').val())==0){
			mens.show("A","Alto debe ser mayor a Cero","¡Atención!","alto");
			return false;
		}
		if($('#ancho').val()==""){
			mens.show("A","Debe capturar Ancho","¡Atención!","ancho");
			return false;
		}
		if(parseFloat($('#ancho').val())==0){
			mens.show("A","Ancho debe ser mayor a Cero","¡Atención!","ancho");
			return false;
		}
		if($('#cantidad').val()==""){
			mens.show("A","Debe capturar Cantidad","¡Atención!","cantidad");
			return false;
		}
		if(parseFloat($('#cantidad').val())==0){
			mens.show("A","Cantidad debe ser mayor a Cero","¡Atención!","cantidad");
			return false;
		}
		if($('#peso').val()==""){
			mens.show("A","Debe capturar Peso","¡Atención!","peso");
			return false;
		}
		if(parseFloat($('#peso').val())==0){
			mens.show("A","Peso debe ser mayor a Cero","¡Atención!","peso");
			return false;
		}
		var obj = new Object();
		obj.cantidad	= $('#cantidad').val();
		obj.descripcion	= $('#descripcion').val();
		obj.peso		= $('#peso').val();
		obj.largo		= $('#largo').val();
		obj.ancho		= $('#ancho').val();
		obj.alto		= $('#alto').val();
		obj.pesototal	= parseFloat($('#peso').val());
		obj.volumen		= (parseFloat($('#largo').val()) * parseFloat($('#ancho').val()) * parseFloat($('#alto').val()) / 4000);
		tabla1.add(obj);
		$('#cantidad').val("");
		$('#descripcion').val("");
		$('#peso').val("");
		$('#largo').val("");
		$('#ancho').val("");
		$('#alto').val("");
	}
	
	function eliminarFila(){
		if(tabla1.getRecordCount()>0){
			tabla1.deleteById(tabla1.getSelectedIdRow());
		}
	}
	
	var desc 	= new Array(<?php echo $desc; ?>);
</script>
</head>
<body>

<table width="813" border="0" cellpadding="0" cellspacing="0" height="483">
  <tbody>
    <tr>
      <td rowspan="4" width="1" valign="top"></td>
      <td width="152" valign="top"><form id="cotizador" name="cotizador" method="post" action="">
        <span>
        <div class="titulos">Servicios</div><br />
          <label>
          Cliente <img src="../img/Buscar_24.gif" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=devolverCliente', 725, 418, 'ventana', 'Busqueda')" /><br />
          <input type="text" name="idcliente" id="idcliente" style="width:78px" />
          <input type="text" name="nombrecliente" id="nombrecliente" readonly="readonly" style="width:148px" />
          </label><br /><br />
          <label>
          <input type="checkbox" name="chk1" />
          Entrega a Domicilio</label>
          <br />
          <label>
            <input type="checkbox" name="chk2" />
            Recoleccion</label>
        </span>
        <br />
        <br />
        <span class="style7">Tipo:</span><br />
        <select name="tipopaquete" size="1">
          <option selected="selected" value="Envase">Envase</option>
          <option value="Paquete">Paquete</option>
          </select>
        </label>
        <p><span class="style7">Origen:</span><br />
          <select name="desde" id="desde" size="1" style="width:150px">
            <?
			 	$s = "select * from catalogodestino where id > 0 order by descripcion";
				$r = mysql_query($s,$l) or die($s);
				while($f = mysql_fetch_object($r)){
			?>
            <option value="<?=$f->id?>"><?=cambio_texto($f->descripcion)?></option>
            <?
				}
			?>
            </select>
          <br />
          <br />
          <span class="style7">Destino:</span><br />
          <select name="hasta" id="hasta" size="1" style="width:150px">
            <?
			 	$s = "select * from catalogodestino where id > 0 order by descripcion";
				$r = mysql_query($s,$l) or die($s);
				while($f = mysql_fetch_object($r)){
			?>
            <option value="<?=$f->id?>"><?=cambio_texto($f->descripcion)?></option>
            <?
				}
			?>
            </select>
          </p>
        <table width="152" border="0" cellpadding="0" cellspacing="0">
          <!--DWLayoutTable-->
          <tr>
            <td height="20" colspan="4" valign="middle" bordercolor="#BCD4E0" bgcolor="#336699" style="padding-left:4px">
            	<div class="titulos">Medidas</div>            </td>
            </tr>
          <tr>
            <td height="6" colspan="4"><strong><span class="style7">Descripción:</span></strong></td>
            </tr>
          <tr>
            <td height="6" colspan="4"><input style="width:150px" name="descripcion" type="text" id="descripcion" autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.iddescripcion.value=this.codigo; document.all.largo.focus();}" onKeyDown="if(event.keyCode==9){document.all.iddescripcion.value=this.codigo;}"/><input type="hidden" name="iddescripcion" id="iddescripcion" /></td>
            </tr>
          <tr>
            <td width="56" height="56" valign="top"><strong> <span class="style7">Largo:</span><br />
              <input style="width:40px" name="largo" type="text" id="largo" onkeypress="if(event.keyCode==13){document.getElementById('alto').focus()}" />
              </strong></td>
            <td colspan="2" valign="top"><strong> <span class="style7">Alto:</span><br />
              <input style="width:40px" name="alto" type="text" id="alto" onkeypress="if(event.keyCode==13){document.getElementById('ancho').focus()}"/>
              </strong></td>
            <td width="65" valign="top"><strong> <span class="style7">Ancho:</span><br />
              <input style="width:40px" name="ancho" type="text" id="ancho" onkeypress="if(event.keyCode==13){document.getElementById('cantidad').focus()}"/>
              </strong></td>
            </tr>
          <tr>
            <td height="37" colspan="2" valign="top"><span class="style7"><strong>Cantidad</strong></span><span class="style5"><strong>:<br />
              <input style="width:65px" name="cantidad" type="text" id="cantidad" onkeypress="if(event.keyCode==13){document.getElementById('peso').focus()}"/>
              </strong></span></td>
            <td colspan="2" valign="top"><span class="style7"><strong>Peso</strong></span><span class="style5"><strong>:<br />
              <input style="width:45px" name="peso" type="text" id="peso"/>
              KG<br />
              &nbsp; </strong></span></td>
            </tr>
			<tr>
			  <td colspan="4" align="right"><img src="../img/Boton_Agregari.gif" style="cursor:pointer" name="agregar" id="agregar" /></td>
		    </tr>
			<tr>
				<td colspan="4">
					<div style="background-color:#282828">
						<table id="detalle" width="100%" border="0" cellspacing="0" cellpadding="0">
						</table>
					</div>			</td>
			</tr>
          <tr>
            <td height="37" colspan="4" valign="top"><span class="style7"><strong>Valor declarado:</strong></span>
              <input type="text" name="valordeclarado" value="" /></td>
            </tr>
          </table>
        <p>
        	<img src="../img/Boton_Generar.gif" style="cursor:pointer" onclick="cotizar($('#cotizador').serialize())"/>
          <br />
          </p>
        <label></label>
      </form></td>
      <td rowspan="4" width="699" align="center"><iframe name="paginaRastreo" id="paginaRastreo" frameborder="0" style="width:660px; height:600px;"></iframe>
      <br /><img src="../img/Boton_Imprimir.gif" style="cursor:pointer" onclick="imprimir($('#cotizador').serialize())" /></td>
    </tr>
    <tr>
      <td height="14" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    </tr>
  </tbody>
</table>
</body>
</html>
