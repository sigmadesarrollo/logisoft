<?	session_start();	
	if(!$_SESSION[IDUSUARIO]!=""){		
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	$fecha = date('d/m/Y');	
	$s = "SELECT folioinicial FROM configuradorfoliosbolsas";
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
	
	$s = "SELECT MAX(foliofinal) AS foliofinal FROM configuradorfoliosbolsas";
	$r = mysql_query($s,$l) or die($s);
	$ff= mysql_fetch_object($r);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script language="javascript" src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<script>
	var u = document.all;
	var tabla1 = new ClaseTabla();
	var nav4 = window.Event ? true : false;
	tabla1.setAttributes({
	nombre:"detalle",
	campos:[
			{nombre:"FOLIO_INICIAL", medida:150, alineacion:"left", datos:"finicial"},
			{nombre:"FOLIO_FINAL", medida:150, alineacion:"left", datos:"ffinal"},
			{nombre:"CANTIDAD", medida:4, alineacion:"left", tipo:"oculto", datos:"cantidad"},
			{nombre:"FECHA", medida:80, alineacion:"left", datos:"fecha"},
			{nombre:"X", medida:4, tipo:"oculto", alineacion:"left", datos:"x"}
		],
		filasInicial:10,
		alto:150,
		seleccion:true,
		ordenable:false,
		//eventoDblClickFila:"modificarFila()",
		//eventoClickFila:"ObtDetalleIzq()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		u.sucursal.focus();
		obtenerCantidadPrecintos();
	}

	function obtenerCantidadPrecintos(datos){
		consultaTexto("obtenerCantidad","configuradorFoliosBolsas_con.php?accion=4");
	}

	function obtenerCantidad(datos){
		var obj = eval(datos);
		u.precintos.value = obj[0].cantidad;
		u.h_inicial.value = obj[0].finicial;
		u.h_final.value = obj[0].ffinal;
	}

	function pedirPrecintos(){
		if(u.finicial.value == "" || u.ffinal.value == ""){
			alerta("Debe capturar Folio "+((u.finicial.value=="")? " Inicial":" Final"),"¡Atención!",((u.finicial.value=="")?"finicial":"ffinal"));
			return false;
		}
		
		if(parseFloat(u.finicial.value) >= parseFloat(u.ffinal.value)){
			alerta("El folio final debe ser mayor al folio inicial","¡Atención!","ffinal");
			return false;
		}
		
		if(parseFloat(u.finicial.value) > parseFloat(u.h_foliofinal2.value)){
			alerta("El folio inicial debe ser menor al folio final configurado","¡Atención!","finicial");
			return false;
		}
		
		if(parseFloat(u.ffinal.value) > parseFloat(u.h_foliofinal2.value)){
			alerta("El folio final debe ser menor al folio final configurado","¡Atención!","ffinal");
			return false;
		}
		
		u.cantidad.value = parseFloat(u.ffinal.value) - parseFloat(u.finicial.value) + 1;
		if(parseInt(u.precintos.value) < parseInt(u.cantidad.value)){
			alerta('No cuenta con Bolsas de Empaque suficientes en Existencia','¡Atención!','ffinal');
		}
		/*else if(parseFloat(u.h_final.value) >= parseFloat(u.finicial.value)){
			alerta("El folio inicial debe ser mayor al folio final ya registrado","¡Atención!","finicial");
		}else if(u.h_final.value != "000000000"){
			if(parseFloat(u.h_final.value) >= parseFloat(u.ffinal.value)){
				alerta("El folio final debe ser mayor al folio final ya registrado","¡Atención!","ffinal");
				return false;
			}
			if(parseFloat(u.finicial.value) >= parseFloat(u.ffinal.value)){
				alerta("El folio final debe ser mayor al folio inicial","¡Atención!","ffinal");
				return false;
			}
			u.btnAgregar.style.visibility = "hidden";
			consultaTexto("mostrarPrecintos","configuradorFoliosBolsas_con.php?accion=2&cantidad="+u.cantidad.value
			+"&finicial="+u.finicial.value+"&ffinal="+u.ffinal.value+"&s="+Math.random());
		}else{
			u.btnAgregar.style.visibility = "hidden";
			consultaTexto("mostrarPrecintos","configuradorFoliosBolsas_con.php?accion=2&cantidad="+u.cantidad.value
			+"&finicial="+u.finicial.value+"&ffinal="+u.ffinal.value+"&s="+Math.random());
		}*/
		u.btnAgregar.style.visibility = "hidden";
		consultaTexto("mostrarPrecintos","configuradorFoliosBolsas_con.php?accion=2&cantidad="+u.cantidad.value
		+"&finicial="+u.finicial.value+"&ffinal="+u.ffinal.value+"&s="+Math.random());
	}

	function mostrarPrecintos(datos){
		if(datos.indexOf("folio inicial")>-1){
			alerta("El folio incial ya fue registrado","¡Atención!","finicial");
			return false;
		}
		
		if(datos.indexOf("folio final")>-1){
			alerta("El folio final ya fue registrado","¡Atención!","ffinal");
			return false;
		}
		
		if(datos.indexOf("folio inicial entre")>-1){
			alerta("Existen folios usados entre los rangos seleccionados","¡Atención!","ffinal");
			return false;
		}
		
		if(datos.indexOf("folio final entre")>-1){
			alerta("Existen folios usados entre los rangos seleccionados","¡Atención!","ffinal");
			return false;
		}
		
		registros = tabla1.getRecordCount();
		if(parseInt(u.canthistorial.value) < registros){
			var eli = parseInt(registros)-1;
			tabla1.setSelectedById('detalle_id'+eli);
			tabla1.deleteById(tabla1.getSelectedIdRow());
		}else{
			if(tabla1.getRecordCount()>0){
				if(u.registro.value==""){
					var eli = parseInt(registros)-1;
					tabla1.setSelectedById('detalle_id'+eli);
					tabla1.deleteById(tabla1.getSelectedIdRow());
				}
			}
		}

		var obj = datos.split(",");
		var objeto = Object();
		objeto.finicial = obj[0];
		objeto.ffinal 	= obj[1];
		objeto.cantidad = u.cantidad.value;
		objeto.fecha	= u.fecha.value;
		u.inicial.value = obj[0];
		u.final.value = obj[1];
		tabla1.add(objeto);
		u.btnAgregar.style.visibility = "visible";
		u.d_guardar.style.visibility = "visible";
	}

	function guardar(){
		if(u.sucursal.value == 0){
			alerta('Debe capturar Sucursal','¡Atención!','sucursal');
		}else if(tabla1.getValuesFromField("cantidad",",") > parseFloat(u.precintos.value)){
			alerta('No cuenta con Bolsas de Empaque suficientes en Existencia','¡Atención!','finicial');
		}else if(tabla1.getRecordCount()==0){
			alerta('Debe capturar por lo menos una Asignación de Bolsas de Empaque','¡Atención!','finicial');
		}else{
			confirmar('Se guardara la informacion ¿Desea continuar?', '', 'confirmarRegistro();', '')
		}
	}
	
	function confirmarRegistro(){
		u.d_guardar.style.visibility = "hidden";
		consultaTexto("registroPrecinto","configuradorFoliosBolsas_con.php?accion=3&cantidad="+u.cantidad.value
		+"&sucursal="+u.sucursal.value+"&finicial="+u.inicial.value+"&ffinal="+u.final.value);
	}
	
	function registroPrecinto(datos){
		if(datos.indexOf("ok")>-1){
			if(datos.indexOf("faltaron")>-1){
				alerta3('No cuenta con Bolsas de Empaque suficientes en Existencia','¡Atención!');
			}else{
				var row = datos.split(",");
				info('Los datos han sido guardados correctamente', '');
				u.h_inicial.value = row[1];
				u.h_final.value = row[2];
				u.registro.value = "si";
			}
			//u.d_guardar.style.visibility = "visible";
		}else{
			alerta3('No cuenta con Bolsas de Empaque suficientes en Existencia','¡Atención!');
			//u.d_guardar.style.visibility = "visible";
		}
	}

	function Numeros(evt){
		var key = nav4 ? evt.which : evt.keyCode; 
		return (key <= 13 || (key >= 48 && key <= 57));
	}

	function obtenerHistorialPrecintos(sucursal){
		consultaTexto("mostrarHistorialPrecintos","configuradorFoliosBolsas_con.php?accion=5&sucursal="+sucursal
		+"&s"+Math.random());
	}

	function mostrarHistorialPrecintos(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(datos);
			for(var i=0;i<obj.length;i++){
				var registro 	   = new Object();
				registro.finicial  = obj[i].folioinicial;
				registro.ffinal    = obj[i].foliofinal;
				registro.fecha	   = obj[i].fecha;
				registro.cantidad  = obj[i].cantidad;
				tabla1.add(registro);
				tabla1.setColorByIndex('#FF0000',i);
				u.registro.value="si";
			}
			u.canthistorial.value = tabla1.getRecordCount();
		}else{
			tabla1.clear();
			u.registro.value="";
		}
	}
	function limpiar(){
		u.sucursal.value = 0;
		u.cantidad.value = "";
		u.d_guardar.style.visibility = "hidden";
		u.inicial.value = "";
		u.precintos.value = "";
		u.canthistorial.value = "";
		u.registro.value = "";
		u.finicial.value = "";
		u.ffinal.value = "";
		tabla1.clear();	
		obtenerCantidadPrecintos();	
	}
	
	function validarInicial(valor){
		if(u.h_inicial.value == "000000000" && parseFloat(u.finicial.value) < parseFloat(u.h_folioinicial.value)){
			alerta('Los Folios de Bolsas de Empaque deben empezar en '+u.h_folioinicial.value,'¡Atención!','finicial');
			return false;
		}
		if(u.h_inicial.value == "000000000" && parseFloat(u.finicial.value) > parseFloat(u.h_folioinicial.value)){
		alerta('Los Folios de Bolsas de Empaque deben de empezar con el folio '+u.h_folioinicial.value,'¡Atención!','finicial');
			return false;
		}
		u.ffinal.focus();
	}
	
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

<link href="Tablas.css" rel="stylesheet" type="text/css">

<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css">

</head>



<body>

<form name="form1" method="post" action="">

  <table width="450" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td class="FondoTabla">ASIGNACI&Oacute;N DE BOLSAS DE EMPAQUE</td>

    </tr>

    <tr>

      <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">

        <tr>

          <td width="87"><input name="h_inicial" type="hidden" id="h_inicial" value="<?=$_POST[h_inicial] ?>">
            <input name="h_final" type="hidden" id="h_final" value="<?=$_POST[h_final] ?>">
            <input name="cantidad" type="hidden" class="Tablas" id="cantidad" style="width:80px" >
            <input name="h_folioinicial" type="hidden" id="h_folioinicial" value="<?=str_pad($f->folioinicial,9,0,STR_PAD_LEFT) ?>"></td>

          <td width="194" align="right">Fecha:</td>

          <td width="114"><input name="fecha" class="Tablas" type="text" id="fecha" style="background:#FFFF99" value="<?=$fecha ?>" readonly=""></td>
        </tr>

        <tr>

          <td>&nbsp;</td>

          <td><input name="h_foliofinal2" type="hidden" id="h_foliofinal2" value="<?=$ff->foliofinal ?>"></td>

          <td>&nbsp;</td>
        </tr>

        <tr>

          <td>Sucursal:</td>

          <td><label>

            <?

	  		$s = "SELECT id, descripcion FROM catalogosucursal WHERE id>1 ORDER BY descripcion ASC";

			$ro = mysql_query($s,$l) or die($s);

	  ?>

            <select name="sucursal" id="sucursal" class="Tablas" style="width:200px" onChange="obtenerHistorialPrecintos(this.value)">

			<option selected="selected" value="0">SELECCIONAR SUCURSAL</option>

              <?

			while($row = mysql_fetch_array($ro)){ ?>

              <option value="<?=$row[0]; ?>">

                <?=$row[1]; ?>
                </option>

              <? } ?>
            </select>

          </label></td>

          <td><input name="inicial" type="hidden" id="inicial" value="<?=$_POST[finicial] ?>">
            <input name="final" type="hidden" id="final" value="<?=$_POST[ffinal] ?>">
            <input name="precintos" type="hidden" id="precintos" value="<?=$_POST[precintos] ?>">
            <input name="canthistorial" type="hidden" id="canthistorial">
            <input name="registro" type="hidden" id="registro"></td>
        </tr>

        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="20%">Folio Inicial:</td>
              <td width="23%"><input name="finicial" type="text" class="Tablas" id="finicial" style="width:80px" onKeyDown="if(event.keyCode==9){validarInicial(this.value);}" onKeyPress="if(event.keyCode==13){ validarInicial(this.value);}return Numeros(event)" maxlength="9"></td>
              <td width="4%">&nbsp;</td>
              <td width="16%">Folio Final: </td>
              <td width="37%"><input name="ffinal" type="text" class="Tablas" id="ffinal" style="width:80px" onKeyPress="return Numeros(event)" onKeyDown="if(event.keyCode==13){ pedirPrecintos();}" maxlength="9">
                <img src="../../img/Boton_Agregari.gif" width="70" height="20" align="absbottom" style="cursor:pointer" onClick="pedirPrecintos()" id="btnAgregar"></td>
            </tr>
          </table></td>
          </tr>

        <tr>

          <td colspan="3"><table id="detalle" width="390" border="0" cellspacing="0" cellpadding="0">
          </table></td>
        </tr>

        <tr>

          <td colspan="3" align="right"><table width="200" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><div id="d_guardar" style="visibility:hidden" class="ebtn_guardar" onClick="guardar()"></div></td>
              <td><div class="ebtn_nuevo" onClick="confirmar('Perdera la información capturada ¿Desea continuar?', '', 'limpiar();', '')"></div></td>
            </tr>
          </table></td>
        </tr>

        <tr>

		  <td colspan="3" align="center"></td>
        </tr>

      </table></td>

    </tr>

  </table>

</form>

</body>

</html>

