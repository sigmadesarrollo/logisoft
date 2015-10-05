<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	$fecha = date("d/m/Y");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></link>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script>
	var u 		= document.all;
	var tabla1 	= new ClaseTabla();
	var mens	= new ClaseMensajes();
	var btn_Agregar = '<div id="btnagregar" class="ebtn_agregar" onclick="agregar()"></div>';
	var btn_Modificar = '<img src="../img/Boton_Modificar.gif" alt="Agregar" style="cursor:pointer" onclick="agregar()" />';
	mens.iniciar('../javascript',false);
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"DEPOSITO", medida:50, tipo:"checkbox", onClick:"mostrarTotal", alineacion:"center", datos:"sel"},
			{nombre:"CLIENTE", medida:150, alineacion:"left", datos:"ncliente"},			
			{nombre:"CANTIDAD", medida:100, tipo:"moneda", alineacion:"right", datos:"cantidad"},
			{nombre:"FECHA", medida:60, alineacion:"center", datos:"fechacheque"},
			{nombre:"CHEQUE", medida:80, alineacion:"left", datos:"ncheque"},
			{nombre:"BANCO", medida:100, alineacion:"left", datos:"nbanco"},
			{nombre:"FICHA", medida:80, alineacion:"left", datos:"ficha"},
			{nombre:"IDBANCO", medida:4, tipo:"oculto", alineacion:"left", datos:"banco"},
			{nombre:"FECHAR", medida:4, tipo:"oculto", alineacion:"left", datos:"fecha"},
			{nombre:"IDCLIENTE", medida:4, tipo:"oculto", alineacion:"left", datos:"cliente"},
			{nombre:"AGREGO", medida:4, tipo:"oculto", alineacion:"left", datos:"agrego"}
			
		],
		filasInicial:20,
		alto:300,
		seleccion:true,
		ordenable:true,
		//eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});	
	
	window.onload = function(){
		tabla1.create();
		obtenerGeneral();		
	}
	
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","depositos_con.php?accion=1");
	}
	
	function mostrarGeneral(datos){
		var obj = eval(convertirValoresJson(datos));
		u.folio.value = obj.folio;
		u.fechaefectivo.value = obj.fechadeposito;		
		u.cantidad.value = ((u.fechaefectivo.value!="")?obj.efectivo:0);
		u.cantidad.value 	= convertirMoneda(u.cantidad.value);
		tabla1.setJsonData(obj.detalle);
	}
	
	function obtenerDeposito(folio){
		u.folio.value = folio;
		consultaTexto("mostrarDeposito","depositos_con.php?accion=5&folio="+folio);
	}
	
	function mostrarDeposito(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.cantidad.value	= obj.principal.cantidad;
			u.cantidad.value 	= convertirMoneda(u.cantidad.value);
			u.fechaefectivo.value		= obj.principal.fechaefectivo;
			u.fichaefectivo.value		= obj.principal.ficha;
			u.bancoefectivo.value		= obj.principal.banco;			
			u.total.value				= "$ "+obj.importe.importe;
			tabla1.setJsonData(obj.detalle);			
			u.btnGuardar.style.visibility = "hidden";
		}else{
			mens.show("A","El folio no existe","¡Atención!","folio");
		}
	}
	
	function guardar(){
		if(u.cantidad.value.replace("$ ","").replace(/,/g,"")=="0.00" && (u.fichaefectivo.value=="" || u.bancoefectivo.value=="0") && 
		u.total.value.replace("$ ","").replace(/,/g,"")=="0.00"){
			mens.show("A","Debe capturar por lo menos un monto de efectivo o un cheque","¡Atención!");
		}else{
			var cheques = "";
			var fichas = "";
			var v_cheque = "";
			for(var i=0;i<tabla1.getRecordCount();i++){
				if(u["detalle_DEPOSITO"][i].checked == true){
					cheques += u["detalle_CHEQUE"][i].value+",";
					fichas += u["detalle_FICHA"][i].value+","+u["detalle_CHEQUE"][i].value+":";
				}
				
				if(u["detalle_DEPOSITO"][i].checked == true && u["detalle_AGREGO"][i].value=="NO"){
					v_cheque += u["detalle_CHEQUE"][i].value+",";
				}				
			}
			
			if(v_cheque!=""){
				mens.show("A","Debe agregar ficha a los siguientes cheques: "+v_cheque.substring(0,v_cheque.length-1),"¡Atención!","fichacheque");
				return false;
			}
			
			fichas = fichas.substring(0,fichas.length-1);
			var v_silleva = ((fichas.indexOf(":")>-1)?"si":"no");
			
			u.btnGuardar.style.visibility = "hidden";			
			consultaTexto("registro","depositos_con.php?accion=4&cantidad="+u.cantidad.value.replace("$ ","").replace(/,/g,"")
			+"&ficha="+u.fichaefectivo.value
			+"&fecha="+u.fechaefectivo.value
			+"&banco="+u.bancoefectivo.value
			+"&cheques="+cheques.substring(0,cheques.length-1)
			+"&fichas="+fichas
			+"&lleva="+v_silleva
			+"&val="+Math.random());
		}
	}
	
	function registro(datos){
		if(datos.indexOf("ok")>-1){
			row = datos.split(",");			
			u.folio.value = row[1];
			mens.show("I","Los datos se guardaron satisfactoriamente","");			
		}else{
			mens.show("A","Hubo un error al registrar los datos "+datos,"¡Atención!");
			u.btnGuardar.style.visibility = "visible";
		}
	}
	
	function convertirMoneda(cad){
		var flag = false; 
		if(cad.indexOf('.') == cad.length - 1) flag = true; 
		var num = cad.split(',').join(''); 
		cad = Number(num).toLocaleString();
		if(cad!="0.00"){
			if(flag) cad += '.'; 
		}
		return cad;
	}
	
	function esNumeric(valor){
		valor = valor.replace("$ ","").replace(/,/g,"").replace(".","");
		var log	=	valor.length;
		var sw	=	"S"; 
		for (x=0; x<log; x++){
			v1	=	valor.substr(x,1);
			v2	= 	parseFloat(v1);
			//Compruebo si es un valor numérico
			if (isNaN(v2)){
				sw	= "N";
			} 
		} 
		if (sw=="S"){			
			return true;
		}else{			
			return false;
		}
	}
	
	function limpiar(){
		u.cantidad.value		= "$ 0.00";
		u.fichaefectivo.value	= "";
		u.bancoefectivo.value	= "0";
		u.btnGuardar.style.visibility = "visible";
		u.total.value = "$ 0.00";
		tabla1.clear();
		obtenerGeneral();
	}	
	
	function mostrarTotal(){
		var v_total = 0.00;
		u.total.value = "";
		for(i=0;i<tabla1.getRecordCount();i++){
			if(u["detalle_DEPOSITO"][i].checked==true){
				v_total = parseFloat(u["detalle_CANTIDAD"][i].value.replace("$ "," ").replace(/,/g,"")) + parseFloat(v_total);
			}
			
			if(u["detalle_DEPOSITO"][i].checked==false){
				u["detalle_AGREGO"][i].value = "NO";
				u["detalle_FICHA"][i].value = "";
			}
			
		}	
		u.total.value = convertirMoneda(v_total);
	}
	
	function convertirMoneda(valor){		
		valor = (valor=="")?"0.00":valor;		
		valor = Math.round(parseFloat(valor)*100)/100;
		valor = "$ "+numcredvar(valor.toLocaleString());
		return valor;
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		cadena = ((cadena=="NaN")?"0.00":cadena);
		return cadena;
	}
	
	function agregarFicha(){
		if(tabla1.getRecordCount()>0){
			for(var i=0;i<tabla1.getRecordCount();i++){
				if(u["detalle_DEPOSITO"][i].checked == true && u["detalle_AGREGO"][i].value == "NO"){
					u["detalle_FICHA"][i].value = u.fichacheque.value;
					u["detalle_AGREGO"][i].value = "SI";
				}
			}
			u.fichacheque.value = "";
		}else{
			mens.show("A","No existen datos en el detalle","");
		}
	}
	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">DEPOSITOS</td>
    </tr>
    <tr>
      <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="4"><table width="250" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td width="33">Folio:</td>
              <td width="83"><label>
           <input name="folio" class="Tablas" type="text" id="folio" onkeypress="if(event.keyCode==13){obtenerDeposito(this.value);}return solonumeros(event);" style="width:50px" />
                <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarDeposito.php', 600, 500, 'ventana', 'Busqueda')" /></label></td>
              <td width="44">Fecha:</td>
              <td width="90"><input name="fecha" type="text" class="Tablas" onkeydown="if(event.keyCode==8){return false;}" readonly="" id="fecha" style="width:70px" value="<?=date('d/m/Y') ?>" /></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" class="FondoTabla">EFECTIVO</td>
          </tr>
        
        <tr>
          <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="14%">Cantidad:</td>
              <td width="25%"><input name="cantidad" type="text" class="Tablas" id="cantidad" style="width:120px" value="$ 0.00" onkeypress="if(event.keyCode==13){document.all.fechaefectivo.focus();} return solonumeros2(event);" onfocus="if(this.value=='$ 0.00'){this.value=''; this.focus();}else{this.value=this.value.replace('$ ','').replace(/,/g,''); this.select();}" onblur="this.value = ((this.value=='')? '$ 0.00' : convertirMoneda(this.value));"/></td>
              <td width="5%">&nbsp;</td>
              <td width="8%">Fecha:</td>
              <td width="48%"><input name="fechaefectivo" type="text" class="Tablas" id="fechaefectivo" style="width:120px; background:#FFFF99" onkeydown="if(event.keyCode==8){return false;}" readonly="" /></td>
            </tr>
            <tr>
              <td># Ficha:</td>
              <td><input name="fichaefectivo" class="Tablas" type="text" id="fichaefectivo" style="width:120px" onkeypress="if(event.keyCode==13){document.all.bancoefectivo.focus();} return solonumeros2(event);"/></td>
              <td>&nbsp;</td>
              <td>Banco:</td>
              <td><select class="Tablas" name="bancoefectivo" id="bancoefectivo" style="width:125px; text-transform:uppercase">
                <option value="0" selected="selected">SELECCIONAR</option>
                <? 
						$s = "SELECT * FROM catalogobanco";
						$r = mysql_query($s,$l) or die($s);					
						while($f = mysql_fetch_object($r)){?>
                <option value="<?=$f->id?>">
                <?=cambio_texto($f->descripcion)?>
                </option>
                <? } ?>
              </select></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td width="80">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" class="FondoTabla">CHEQUE</td>
          </tr>
        
        <tr>
          <td># Ficha:</td>
          <td width="139"><input name="fichacheque" class="Tablas" type="text" id="fichacheque" style="width:120px;" onkeypress="return solonumeros2(event)" /></td>
          <td width="81"><div class="ebtn_agregar" onclick="agregarFicha()" ></div></td>
          <td width="296">* Seleccione el numero de cheque para agregar Ficha </td>
        </tr>
        
        <tr>
          <td colspan="4"><table id="detalle" width="100%" border="0" cellspacing="0" cellpadding="0">            
          </table></td>
          </tr>
        <tr>
          <td>Total:</td>
          <td><input name="total" type="text" class="Tablas" id="total" style="width:120px;background:#FFFF99" value="$ 0.00" readonly=""/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input name="fila" type="hidden" id="fila" />
            <input name="h_fecha" type="hidden" id="h_fecha" />
            <input name="modificar" type="hidden" id="modificar" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><div id="btnGuardar" class="ebtn_guardar" onclick="guardar()"></div></td>
              <td><div class="ebtn_nuevo"onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')"></div></td>
            </tr>
          </table></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
