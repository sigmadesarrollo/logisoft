<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "select * from catalogosucursal where id = $_SESSION[IDSUCURSAL]";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$nombresucursal = $f->descripcion;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../javascript/ajax.js"></script>
<script language="javascript" src="../javascript/ClaseMensajes.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<script language="javascript" src="../javascript/funciones.js"></script>
<style type="text/css">
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
.Estilo4 {font-size: 12px}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
</style>
<script>
	var mens = new ClaseMensajes();
	var tabla1 = new ClaseTabla();
	
	mens.iniciar("../javascript");
	tabla1.setAttributes({
		nombre:"tabladetalle",
		campos:[
			{nombre:"Id", medida:4, alineacion:"center", tipo:"oculto", datos:"idconcepto"},			
			{nombre:"Concepto", medida:90, alineacion:"left", datos:"concepto"},
			{nombre:"Cant", medida:70,tipo:"moneda", alineacion:"right", datos:"cantidad"},
			{nombre:"Ajus", medida:70, alineacion:"center", datos:"tipoajuste"}
		],
		filasInicial:8,
		alto:100,
		seleccion:true,
		ordenable:true,
		eventoDblClickFila:"borrarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();	
		consultaTexto("respuestaDatos","auditoriasucursal_con.php?accion=1&val="+Math.random());
	}
	
	function borrarFila(){
		if(ge('celdaguardar').style.display!='none'){
			tabla1.deleteById(tabla1.getSelectedIdRow());
			calcularAjustes();
		}
	}
	
	function ge(id){
		return document.getElementById(id);
	}

	function respuestaDatos(datos){
		var obj = eval(datos);
		
		if(obj.anterior.fecha==""){
			ge('label1').innerHTML = "INVENTARIO ANTERIOR";
			ge('label2').innerHTML = "CARTERA ANTERIOR";
			ge('fechadel').innerHTML = "ANTERIOR";
			//ge('fechaal').innerHTML = obj.anterior.factual;
		}else{
			ge('label1').innerHTML = "INVENTARIO AL "+obj.anterior.fechaanterior;
			ge('label2').innerHTML = "CARTERA AL "+obj.anterior.fechaanterior;
			ge('fechadel').innerHTML = obj.anterior.fecha;
			//ge('fechaal').innerHTML = obj.anterior.factual;
		}
		
		ge('folioauditoria').innerHTML = obj.anterior.folioauditoria;
		
		ge('saldoanterior').innerHTML = "$ " + numcredvar(obj.anterior.saldoanterior);
		ge('inventarioal').innerHTML = "$ " + numcredvar(obj.anterior.inventarioal);
		ge('carteraal').innerHTML = "$ " + numcredvar(obj.anterior.carteraal);
		
		ge('liquidaciones').innerHTML = "$ " + numcredvar(obj.liquidaciones);
		
		ge('depositos').innerHTML = "$ " + numcredvar(obj.depositos);
		ge('facturascanceladas').innerHTML = "$ " + numcredvar(obj.facturascanceladas);
		ge('guiascanceladas').innerHTML = "$ " + numcredvar(obj.guiascanceladas);
		ge('notascredito').innerHTML = "$ " + numcredvar(obj.notacredito);
		
		var saldocontable = parseFloat(obj.anterior.saldoanterior)+
			parseFloat(obj.anterior.inventarioal)+
		  	parseFloat(obj.anterior.carteraal)+
		   	parseFloat(obj.liquidaciones)-
			
			parseFloat(obj.depositos)-
			parseFloat(obj.facturascanceladas)-
			parseFloat(obj.guiascanceladas)-
			parseFloat(obj.notacredito);
		
		ge('saldocontable').innerHTML = "$ " + numcredvar(saldocontable.toString());

	}
	
	function pedirRecolectado(){
		consultaTexto("respuestaRec","auditoriasucursal_con.php?accion=2&val="+Math.random());
	}
	
	function respuestaRec(datos){
		var obj = eval(datos);
		ge('saldoactual').innerHTML = "$ " + numcredvar(obj.cartera);
		ge('inventarioactual').innerHTML = "$ " + numcredvar(obj.inventario);
		
		var saldocontable = parseFloat(ge('saldoanterior').innerHTML.replace("$ ","").replace(/,/g,""))+
			parseFloat(ge('inventarioal').innerHTML.replace("$ ","").replace(/,/g,""))+
		  	parseFloat(ge('carteraal').innerHTML.replace("$ ","").replace(/,/g,""))+
		   	parseFloat(ge('liquidaciones').innerHTML.replace("$ ","").replace(/,/g,""))-
			
			parseFloat(ge('depositos').innerHTML.replace("$ ","").replace(/,/g,""))-
			parseFloat(ge('facturascanceladas').innerHTML.replace("$ ","").replace(/,/g,""))-
			parseFloat(ge('guiascanceladas').innerHTML.replace("$ ","").replace(/,/g,""))-
			parseFloat(ge('notascredito').innerHTML.replace("$ ","").replace(/,/g,""))-
			
			parseFloat(obj.cartera)-
			parseFloat(obj.inventario);
			
		ge('saldofinal').innerHTML = "$ " + numcredvar(saldocontable.toFixed(2).toString());
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	
	function validaConsultado(){
		if(ge('celdaguardar').style.display=='none')
			return "?folio="+ge('folioauditoria').innerHTML;
		else
			return "";
	}
	
	function mostrarLiquidaciones(){
		var agregarfolio = validaConsultado();
		mens.popup("auditoriasucursal_liquidaciones.php"+agregarfolio,700,400,'','Liquidaciones');
	}
	
	function mostrarDepositos(){
		var agregarfolio = validaConsultado();
		mens.popup("auditoriasucursal_depositos.php"+agregarfolio,500,400,'','Deposito');
	}
	
	function mostrarNotascredito(){
		var agregarfolio = validaConsultado();
		mens.popup("auditoriasucursal_notascredito.php"+agregarfolio,500,400,'','Nota Crédito');
	}
	
	function mostrarFacturascanceladas(){
		var agregarfolio = validaConsultado();
		mens.popup("auditoriasucursal_facturascanceladas.php"+agregarfolio,600,400,'','Facturas Canceladas');
	}
	
	function mostrarGuiascanceladas(){
		var agregarfolio = validaConsultado();
		mens.popup("auditoriasucursal_guiascanceladas.php"+agregarfolio,600,400,'','Guias Canceladas');
	}
	
	function mostrarSaldoFinal(){
		var agregarfolio = validaConsultado();
		mens.popup("auditoriasucursal_saldofinal.php"+agregarfolio,600,400,'','Saldo final');
	}
	
	function guardar(){
		var saldoanterior = ge('saldoanterior').innerHTML.replace("$ ","").replace(/,/g,"");
		var inventarioal = ge('inventarioal').innerHTML.replace("$ ","").replace(/,/g,"");
		var carteraal = ge('carteraal').innerHTML.replace("$ ","").replace(/,/g,"");
		
		var liquidaciones = ge('liquidaciones').innerHTML.replace("$ ","").replace(/,/g,"");
		var depositos = ge('depositos').innerHTML.replace("$ ","").replace(/,/g,"");
		var facturascanceladas = ge('facturascanceladas').innerHTML.replace("$ ","").replace(/,/g,"");
		var guiascanceladas = ge('guiascanceladas').innerHTML.replace("$ ","").replace(/,/g,"");
		var notasdecredito = ge('notascredito').innerHTML.replace("$ ","").replace(/,/g,"");
		var saldocontable = ge('saldocontable').innerHTML.replace("$ ","").replace(/,/g,"");
		
		var inventarioalcierre = ge('inventarioactual').innerHTML.replace("$ ","").replace(/,/g,"");
		var carteraalcierre = ge('saldoactual').innerHTML.replace("$ ","").replace(/,/g,"");
		var saldofinal = ge('saldofinal').innerHTML.replace("$ ","").replace(/,/g,"");
		
		var totalajustes = ge('totalajustes').innerHTML.replace("$ ","").replace(/,/g,"");
		var saldoconajustes = ge('saldoconajustes').innerHTML.replace("$ ","").replace(/,/g,"");
		
		/*alert("auditoriasucursal_con.php?accion=9"+
					  "&saldoanterior="+saldoanterior+"&inventarioal="+inventarioal+"&carteraal="+carteraal+
					  "&liquidaciones="+liquidaciones+"&depositos="+depositos+"&facturascanceladas="+facturascanceladas+
					  "&guiascanceladas="+guiascanceladas+"&notasdecredito="+notasdecredito+"&saldocontable="+saldocontable+
					  "&inventarioalcierre="+inventarioalcierre+"&carteraalcierre="+carteraalcierre+"&saldofinal="+saldofinal+
					  "&totalajustes="+totalajustes+"&saldoconajustes="+saldoconajustes+
					  "&val="+Math.random());*/
		
		consultaTexto("resGuardar","auditoriasucursal_con.php?accion=9"+
					  "&saldoanterior="+saldoanterior+"&inventarioal="+inventarioal+"&carteraal="+carteraal+
					  "&liquidaciones="+liquidaciones+"&depositos="+depositos+"&facturascanceladas="+facturascanceladas+
					  "&guiascanceladas="+guiascanceladas+"&notasdecredito="+notasdecredito+"&saldocontable="+saldocontable+
					  "&inventarioalcierre="+inventarioalcierre+"&carteraalcierre="+carteraalcierre+"&saldofinal="+saldofinal+
					  "&totalajustes="+totalajustes+"&saldoconajustes="+saldoconajustes+
					  "&val="+Math.random());
	}
	
	function resGuardar(datos){
		mens.show("I","Información registrada con exito", "¡Atención!");
	}
	
	function pedirDatosAuditoria(valor){
		consultaTexto("resDatosAuditorias","auditoriasucursal_con.php?accion=10&folio="+valor+
					  "&val="+Math.random());
	}
	
	function resDatosAuditorias(datos){
		var obj = eval(convertirValoresJson(datos));
		
		if(obj.anterior.fecha==""){
			ge('label1').innerHTML = "INVENTARIO ANTERIOR";
			ge('label2').innerHTML = "CARTERA ANTERIOR";
			ge('fechadel').innerHTML = "ANTERIOR";
			//ge('fechaal').innerHTML = obj.anterior.factual;
		}else{
			ge('label1').innerHTML = "INVENTARIO AL "+obj.anterior.fechaanterior;
			ge('label2').innerHTML = "CARTERA AL "+obj.anterior.fechaanterior;
			ge('fechadel').innerHTML = obj.anterior.fecha;
			//ge('fechaal').innerHTML = obj.anterior.factual;
		}
		
		ge('folioauditoria').innerHTML = obj.anterior.folioauditoria;
		
		ge('saldoanterior').innerHTML = "$ " + numcredvar(obj.anterior.saldoanterior);
		ge('inventarioal').innerHTML = "$ " + numcredvar(obj.anterior.inventarioal);
		ge('carteraal').innerHTML = "$ " + numcredvar(obj.anterior.carteraal);
		
		ge('liquidaciones').innerHTML = "$ " + numcredvar(obj.liquidaciones);
		
		ge('depositos').innerHTML = "$ " + numcredvar(obj.depositos);
		ge('facturascanceladas').innerHTML = "$ " + numcredvar(obj.facturascanceladas);
		ge('guiascanceladas').innerHTML = "$ " + numcredvar(obj.guiascanceladas);
		ge('notascredito').innerHTML = "$ " + numcredvar(obj.notacredito);
		
		var saldocontable = parseFloat(obj.anterior.saldoanterior)+
			parseFloat(obj.anterior.inventarioal)+
		  	parseFloat(obj.anterior.carteraal)+
		   	parseFloat(obj.liquidaciones)-
			
			parseFloat(obj.depositos)-
			parseFloat(obj.facturascanceladas)-
			parseFloat(obj.guiascanceladas)-
			parseFloat(obj.notacredito);
		
		tabla1.setJsonData(obj.ajustes);
		ge('totalajustes').innerHTML = "$ " + numcredvar(obj.anterior.totalajustes.toString());
		ge('saldoconajustes').innerHTML = "$ " + numcredvar(obj.anterior.saldoconajustes.toString());
		
		ge('saldocontable').innerHTML = "$ " + numcredvar(saldocontable.toString());
		
		ge('inventarioactual').innerHTML	= "$ " + numcredvar(obj.anterior.inventariocierre);
		ge('saldoactual').innerHTML			= "$ " + numcredvar(obj.anterior.carteracierre);
		ge('saldofinal').innerHTML			= "$ " + numcredvar(obj.anterior.saldofinal);
		
		ge('celdaactualizar').style.display='none';
		ge('celdaguardar').style.display='none';
		ge('botonagregar').style.display='none';
	}
	
	function agregarAjuste(){
		if(ge('concepto').value==""){
			mens.show("A","Proporcione el concepto", "¡ATENCIÓN!","concepto");
			return false;
		}
		
		if(ge('txtcantidad').value==""){
			mens.show("A","Proporcione la cantidad", "¡ATENCIÓN!","txtcantidad");
			return false;
		}
		
		if(ge('saldofinal').innerHTML=="" || ge('saldofinal').innerHTML=="&nbsp;"){
			mens.show("A","Debe dar click en el boton actualizar para considerar las guias y facturas leidas","¡ATENCIÓN!")
			return false;
		}
		
		var concepto 		= ge('concepto').value;
		var cantidad 		= ge('txtcantidad').value;
		var tipoajuste 		= ge('concepto')[ge('concepto').selectedIndex].tipoajuste;
		/*mens.show("A","auditoriasucursal_con.php?accion=11&concepto="+concepto+
						"&cantidad="+cantidad+"&tipoajuste="+tipoajuste+"&valr="+Math.random());*/
						//return false;
		consultaTexto("resAgregarAjuste","auditoriasucursal_con.php?accion=11&concepto="+concepto+
						"&cantidad="+cantidad+"&tipoajuste="+tipoajuste+"&valr="+Math.random());
	}
	
	function resAgregarAjuste(datos){
		if(datos.indexOf("ok")>-1){
			var obj = new Object();
			obj.concepto = ge('concepto').value;
			obj.cantidad = ge('txtcantidad').value;
			obj.tipoajuste = ge('concepto')[ge('concepto').selectedIndex].tipoajuste;
			tabla1.add(obj);
			calcularAjustes();
			ge('txtcantidad').value	=	"";
			ge('concepto').value	=	"";
		}else{
			mens.show("A","Error al guardar"+datos,"¡ATENCIÓN!");
			return false;
		}
	}
	
	function calcularAjustes(){
		var total = 0;
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(document.all['tabladetalle_Ajus'][i].value=='P'){
				total += parseFloat(document.all['tabladetalle_Cant'][i].value.replace("$ ","").replace(/,/g,""));
			}else{
				total -= parseFloat(document.all['tabladetalle_Cant'][i].value.replace("$ ","").replace(/,/g,""));
			}
		}
		ge('totalajustes').innerHTML = "$ " + numcredvar(total.toString());
		calcularSaldoAjustes();
	}
	
	function calcularSaldoAjustes(){
		var sfinal = ge('saldofinal').innerHTML.replace("$ ","").replace(/,/g,"");
		var tajustes = ge('totalajustes').innerHTML.replace("$ ","").replace(/,/g,"");
		var sconajustes = parseFloat(sfinal)+parseFloat(tajustes);
		
		ge('saldoconajustes').innerHTML = "$ " + numcredvar(sconajustes.toString());
	}
</script>
</head>
<body>


<form id="form1" name="form1" method="post" action="">
  <br>
  <table width="404" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="503" class="FondoTabla Estilo4">AUDITORIA</td>
    </tr>
    <tr>
      <td>
      	<table width="388" border="0" cellpadding="0" cellspacing="0" align="center">
        	<tr>
            	<td colspan="3">
                	<table width="368" border="0" cellpadding="0" cellspacing="0">
                    	<tr>
                        	<td width="57">FOLIO:</td>
                        	<td width="79" id="folioauditoria"></td>
                        	<td width="36">
                            <div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarFolioAuditoria.php?funcion=pedirDatosAuditoria', 570, 470, 'ventana', 'Busqueda')"></div>
                            </td>
                        	<td width="19"></td>
                            <td width="84">SUCURSAL:</td>
                            <td width="93"><?=$nombresucursal?></td>
                        </tr>
                    </table>
                </td>
           	</tr>
            <tr>
            	<td width="139">&nbsp;</td>
            	<td width="285"></td>
            	<td width="1"></td>
            </tr>
        	<tr>
            	<td width="139">&nbsp;SALDO INICIAL:</td>
            	<td width="285"></td>
            	<td width="1"></td>
            </tr>
        	<tr>
        	  <td colspan="3">
              	<table width="382" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="45">&nbsp;</td>
                        <td width="172">SALDO ANTERIOR:</td>
                        <td width="127" id="saldoanterior" style="text-align:right"></td>
                        <td width="18"></td>
                        <td width="20"></td>
                    </tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td id="label1">INVENTARIO AL</td>
                	  <td id="inventarioal" style="text-align:right"></td>
                	  <td></td>
                	  <td></td>
              	  </tr>
                	<tr>
                	  <td>&nbsp;</td>
                	  <td id="label2">CARTERA AL</td>
                	  <td id="carteraal" style="text-align:right"></td>
                	  <td></td>
                	  <td></td>
              	  </tr>
                </table>
              </td>
       	  </tr>
        	<tr>
        	  <td>&nbsp;</td>
        	  <td></td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td colspan="2">
              	<table width="365" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="46">&nbsp;FECHA:</td>
                    	<td width="133" id="fechadel"></td>                        
                    	<td width="26">&nbsp;</td>
                    	<td width="160" id="fechaal"></td>
                    </tr>
                </table>
              </td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td>&nbsp;MAS CARGOS:</td>
        	  <td></td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td colspan="3"><table width="381" border="0" cellpadding="0" cellspacing="0">
        	    <tr>
        	      <td width="44">&nbsp;</td>
        	      <td width="170">LIQUIDACIONES</td>
        	      <td width="124" id="liquidaciones" style="text-align:right; text-decoration:underline; cursor:pointer" onclick="mostrarLiquidaciones()"></td>
        	      <td width="19"></td>
        	      <td width="17"></td>
      	      </tr>
      	    </table></td>
       	  </tr>
        	<tr>
        	  <td>&nbsp;</td>
        	  <td></td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td>&nbsp;MENOS ABONOS</td>
        	  <td></td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td colspan="3">
              		<table width="382" border="0" cellpadding="0" cellspacing="0">
        	    <tr>
        	      <td width="44">&nbsp;</td>
        	      <td width="170">DEPOSITOS</td>
        	      <td width="124" id="depositos" style="text-align:right; text-decoration:underline; cursor:pointer"  onclick="mostrarDepositos()"></td>
        	      <td width="19"></td>
        	      <td width="18"></td>
      	      </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td>FACTURAS CANCELADAS</td>
        	      <td id="facturascanceladas" style="text-align:right; text-decoration:underline; cursor:pointer" onclick="mostrarFacturascanceladas()"></td>
        	      <td></td>
        	      <td></td>
      	      </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td>GUIAS CANCELADAS</td>
        	      <td id="guiascanceladas" style="text-align:right; text-decoration:underline; cursor:pointer" onclick="mostrarGuiascanceladas()"></td>
        	      <td></td>
        	      <td></td>
      	      </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td>NOTAS DE CR&Eacute;DITO</td>
        	      <td id="notascredito" style="text-align:right; text-decoration:underline; cursor:pointer" onclick="mostrarNotascredito()"></td>
        	      <td></td>
        	      <td></td>
      	      </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td colspan="2"><HR /></td>
        	      <td></td>
        	      <td></td>
      	      </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td>SALDO CONTABLE</td>
        	      <td id="saldocontable" style="text-align:right"></td>
        	      <td></td>
        	      <td></td>
      	      </tr>
                </table>
              </td>
       	  </tr>
        	<tr>
        	  <td>&nbsp;</td>
        	  <td></td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td colspan="2">&nbsp;COMPROBACI&Oacute;N DE SALDO</td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td colspan="2">
			  <table width="381" border="0" cellpadding="0" cellspacing="0">
        	    <tr>
        	      <td width="42">&nbsp;</td>
        	      <td width="178">INVENTARIO AL CIERRE</td>
        	      <td width="122" id="inventarioactual" style="text-align:right"></td>
        	      <td width="20"></td>
        	      <td width="19"></td>
      	      </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td>CARTERA AL CIERRE</td>
        	      <td id="saldoactual" style="text-align:right"></td>
        	      <td></td>
        	      <td></td>
      	      </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td colspan="2"><hr /></td>
        	      <td></td>
        	      <td></td>
      	      </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td>SALDO FINAL</td>
        	      <td id="saldofinal" style="text-align:right; text-decoration:underline; cursor:pointer" onclick="mostrarSaldoFinal()"></td>
        	      <td></td>
        	      <td></td>
      	      </tr>
        	    <tr>
        	      <td>&nbsp;</td>
        	      <td>&nbsp;</td>
        	      <td></td>
        	      <td></td>
        	      <td></td>
      	      </tr>
      	    </table></td>
        	  <td></td>
      	  </tr>
		  	<tr>
				<td colspan="2">AJUSTES</td>
				<td></td>
			</tr>
        	<tr>
        	  <td colspan="2"><table width="381" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="3" rowspan="5"><table cellpadding="0" cellspacing="0" id="tabladetalle">
                  </table></td>
                  <td width="100" height="13">CANTIDAD</td>
                </tr>
                <tr>
                  <td height="13"><input type="text" name="txtcantidad" style="width:100px;" onkeypress="return tiposMoneda(event,this.value)" /></td>
                </tr>
                <tr>
                  <td height="13">CONCEPTO</td>
                </tr>
                <tr>
                  <td height="13"><select name="concepto" style="width:100px;">
                      <option value="">.::Seleccione::.</option>
                      <?
							$s = "select descripcion, if(positivo=1,'P','N') as tipoajuste
							from catalogotipoajuste";
							$r = mysql_query($s,$l) or die($s);
							while($f = mysql_fetch_object($r)){
						?>
                      <option tipoajuste="<?=$f->tipoajuste?>" value="<?=$f->descripcion?>">
                        <?=$f->descripcion?>
                      </option>
                      <?	
							}
						?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td height="26"><img src="../img/Boton_Agregari.gif" id="botonagregar" 
				  style="cursor:pointer" onclick="agregarAjuste()" /></td>
                </tr>
                <tr>
                  <td width="19" height="15">&nbsp;</td>
                  <td width="119" style="text-align:right">TOTAL:</td>
                  <td width="126" style="text-align:right" id="totalajustes">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td height="15">&nbsp;</td>
                  <td style="text-align:right">SALDO CON AJUSTES</td>
                  <td style="text-align:right" id="saldoconajustes">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
        	  <td></td>
      	  </tr>
		  <tr>
		  	<td colspan="2">&nbsp;</td>
			<td></td>
		  </tr>
        	<tr>
        	  <td colspan="2" align="center">
        	    <table>
        	      <tr>
        	        <td width="85" id="celdaactualizar"><img src="../img/Boton_Actualizar2.gif" style="cursor:pointer" onclick="pedirRecolectado()"/></td>
        	        <td width="75" id="celdaguardar"><img src="../img/Boton_Guardar.gif" style="cursor:pointer" onclick="guardar()"/></td>
        	        <td width="78" ><img src="../img/boton_limpiar.gif" style="cursor:pointer" onclick="document.location.href = '';document.location.href = 'auditoriasucursal.php'" /></td>
       	          </tr>
   	          </table>      	    </td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td colspan="2">&nbsp;</td>
        	  <td></td>
      	  </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
</body>
</html>