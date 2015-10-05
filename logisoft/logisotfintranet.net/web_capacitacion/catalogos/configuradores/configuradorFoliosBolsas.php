<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');

	if($_POST[accion]=="grabar"){
		for($i=0;$i<$_POST[registros];$i++){
			if($_POST["detalle_EXISTE"][$i]!="x"){
				$s = "INSERT INTO configuradorfoliosbolsas 
				(folioinicial, foliofinal,cantidad, restante, usuario, fecha) VALUES
				(".$_POST["detalle_FOLIO_INICIAL"][$i].",
				 ".$_POST["detalle_FOLIO_FINAL"][$i].",
				 ".$_POST["detalle_CANTIDAD"][$i].",
				 (".$_POST["detalle_FOLIO_FINAL"][$i]."-".$_POST["detalle_FOLIO_INICIAL"][$i].")+1,				 
				 '".$_SESSION[NOMBREUSUARIO]."',current_timestamp())";
				 
				$r = mysql_query($s, $l) or die($s);
			}
		}
			$mensaje ='Los datos han sido guardados correctamente';
	}
	
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
			{nombre:"CANTIDAD", medida:50, alineacion:"left", datos:"cantidad"},
			{nombre:"EXISTE", medida:4, alineacion:"left", tipo:"oculto", datos:"existe"}
		],
		filasInicial:14,
		alto:150,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"modificarFila()",
		//eventoClickFila:"ObtDetalleIzq()",
		nombrevar:"tabla1"
	});
	window.onload = function(){
		tabla1.create();
		u.inicial.focus();
		obtenerDetalles();
	}
	
	function obtenerDetalles(){
		consultaTexto("mostrarDetalle","configuradorFoliosBolsas_con.php?accion=1");	
	}
	function mostrarDetalle(datos){
		if(datos.indexOf("no encontro")<0){
			var objeto = eval(datos);
			tabla1.setJsonData(objeto);
		}
	}
	function agregar(){		
		if(u.inicial.value==""){
			alerta('Debe capturar Folio inicial','¡Atención!','inicial');
			return false;
		}
		if(u.final.value==""){
			alerta('Debe capturar Folio final','¡Atención!','final');
			return false;
		}
		if(parseFloat(u.inicial.value) < 0){
			alerta('El folio inicial debe ser mayor a Cero','¡Atención!','inicial');
			return false;
		}
		if(parseFloat(u.final.value) < 0){
			alerta('El folio final debe ser mayor a Cero','¡Atención!','final');
			return false;
		}
		if(parseInt(u.final.value) < parseInt(u.inicial.value)){
		alerta('El folio final no debe ser menor al folio inicial','¡Atención!','final');
			return false;
		}
		
		if(tabla1.getRecordCount()>0){
			var i = tabla1.getRecordCount() - 1;
			if(parseFloat(u["detalle_FOLIO_FINAL"][i].value) >= parseFloat(u.inicial.value)){
				alerta("El folio inicial debe ser mayor al folio final ya registrado","¡Atención!","inicial");
				return false;
			}
		}
		
		var objeto = Object();
		objeto.finicial = u.inicial.value;
		objeto.ffinal = u.final.value;
		objeto.cantidad = parseFloat(u.final.value) - parseFloat(u.inicial.value) + 1;
		if(u.modificar.value==""){
			tabla1.add(objeto);
		}else{
			tabla1.updateRowById(tabla1.getSelectedIdRow(), objeto);
			u.modificar.value="";
		}
		u.inicial.value = "";
		u.final.value	= "";
	}
	function guardar(){
		if(tabla1.getRecordCount()==0){
			alerta('Debe agregar por lo menos un folio al detalle','¡Atención!','inicial');		
		}else{			
			u.modificar.value="";
			u.registros.value = tabla1.getRecordCount();
			u.accion.value = "grabar";
			document.form1.submit();
		}
	}
	function limpiar(){
		tabla1.clear();
	}	
	function modificarFila(){
		if(tabla1.getValSelFromField('finicial','FOLIO_INICIAL')!=""){
			u.inicial.value = tabla1.getValSelFromField('finicial','FOLIO_INICIAL');
			u.final.value = tabla1.getValSelFromField('ffinal','FOLIO_FINAL');
			u.modificar.value = "SI";
		}
	}
	function Numeros(evt){ 
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
	var key = nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 48 && key <= 57));
	}
	function tabular(e,obj){
		tecla=(u) ? e.keyCode : e.which;
		if(tecla!=13) return;
		frm=obj.form;
		for(i=0;i<frm.elements.length;i++) 
			if(frm.elements[i]==obj){ 
				if (i==frm.elements.length-1) 
					i=-1;
				break 
			}

		if (frm.elements[i+1].disabled ==true )    
			tabular(e,frm.elements[i+1]);
		else if (frm.elements[i+1].readOnly ==true )    
			tabular(e,frm.elements[i+1]);						
		else frm.elements[i+1].focus();
		return false;
	} 
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <table width="360" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">CONFIGURADOR DE FOLIOS BOLSAS DE EMPAQUE</td>
    </tr>
    <tr>
      <td><table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
        
        <tr>
          <td width="69">Folio Inicial: </td>
          <td colspan="2"><label>
            <input name="inicial" type="text" class="Tablas" id="inicial" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="<?=$inicial ?>" maxlength="9">
          </label></td>
        </tr>
        <tr>
          <td>Folio Final: </td>
          <td width="154"><input name="final" type="text" class="Tablas" id="final" onKeyPress="return Numeros(event)" value="<?=$final ?>" maxlength="9"></td>
          <td width="127"><div class="ebtn_agregar" onClick="agregar()">
            <input name="accion" type="hidden" id="accion" value="<?=$_POST[accion] ?>">
            <input name="registros" type="hidden" id="registros" value="<?=$_POST[registros] ?>">
            <input name="modificar" type="hidden" id="modificar" value="<?=$_POST[modificar] ?>">
          </div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3"><table width="320" id="detalle" border="0" cellspacing="0" cellpadding="0">
           
          </table></td>
        </tr>
        <tr>
          <td colspan="3" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" align="right"><div class="ebtn_guardar" onClick="guardar();"></div></td>
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
<? 
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
}
?>
