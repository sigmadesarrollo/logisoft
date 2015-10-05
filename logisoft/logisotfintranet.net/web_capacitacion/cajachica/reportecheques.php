<?	session_start();
	require_once("../Conectar.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/ClaseMensajes.js"></script>
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<script language="javascript" src="../javascript/ajax.js"></script>
<title>Untitled Document</title>
</head>
<OBJECT ID="ValidarCheques" style="display:none"
CLASSID="CLSID:027F1AC1-9991-4863-B52B-F2D903D55AC7">
</OBJECT>
<script>
	var tabla1 = new ClaseTabla();
	var mens = new ClaseMensajes();
	
	tabla1.setAttributes({
		nombre:"reportecheques",
		campos:[
			{nombre:"ID", medida:4, alineacion:"left", tipo:"oculto", datos:"idpagocheque"},
			{nombre:"SUCURSAL", medida:60, alineacion:"center", datos:"prefijo"},
			{nombre:"# CUENTA", medida:90, alineacion:"left", datos:"nocuenta"},
			{nombre:"# CHEQUE", medida:90, alineacion:"left", datos:"nocheque"},
			{nombre:"FECHA", medida:90, alineacion:"center", datos:"fechacheque"},
			{nombre:"GERENTE", medida:140, alineacion:"left", datos:"gerente"},
			{nombre:"SEL", medida:50, alineacion:"center", tipo:"checkbox", datos:"seleccion"}
		],
		filasInicial:9,
		alto:140,
		seleccion:false,
		ordenable:true,
		nombrevar:"tabla1"				 
	});
	
	window.onload = function(){
		tabla1.create();
		mens.iniciar("../javascript");
		consultaTexto("mostrarCheques","reportecheques_con.php?accion=1");
	}
	
	function mostrarCheques(datos){
		var obj = eval(convertirValoresJson(datos));
		
		tabla1.setJsonData(obj);
	}
	
	function descargarCheques(){
		var losids = tabla1.getValSelFromField("idpagocheque","SEL");
		if(losids==""){
			mens.show("A","Seleccione los cheques","¡ATENCIÓN!");
			return false;
		}
		window.open("descargarlistaarchivo.php?ids="+losids);
	}
	
	function verificarCheques(){
		mens.show("C","¿Desea verificar los cheques que ya se han registrado?","¡Atencion!","","solicitarCheques()");
	}
	
	function solicitarCheques(){
		consultaTexto("mandarCheques","reportecheques_con.php?accion=2");
	}
	
	function mandarCheques(datos){
		var ids = "";
		var lector = new ActiveXObject("CheqPAQ.ValidarCheques");
		var idsenviados = datos.replace("\r\n","");
		var ids = lector.ConciliarCheques(idsenviados);	
		if(ids!="ERROR" && ids!=""){
			consultaTexto("respuestaActualizar","reportecheques_con.php?accion=3&ids="+ids);
		}else if(ids!="ERROR"){
			mens.show("I","Los cheques ya fueron actualizados","¡Atencion!");
		}
	}
	
	function respuestaActualizar(datos){
		if(datos.indexOf("actualizo")>-1){
			mens.show("I","Los cheques ya fueron actualizados","¡Atencion!");
			consultaTexto("mostrarCheques","reportecheques_con.php?accion=1");
		}else{
			mens.show("A",datos,"Error al Actualizar");
		}
	}
</script>
<body>
	<table width="560" border="1" align="center" cellpadding="0" cellspacing="0"  bordercolor="#016193">
		<tr>
	        <td width="577" style="color:#FFF;background-color:#016193; font-size:12px">Reporte De Cheques Generados</td>
		</tr>
        <tr>
	        <td height="186" valign="top">
            	<table width="556" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td colspan="3" align="center" height="150px" valign="top">
                        	<table border="0" cellpadding="0" cellspacing="0" id="reportecheques"></table>
                        </td>
					</tr>
                  <tr>
      	<td>
       	  <table width="537" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="75" align="left"></td>
              <td width="126" align="right">&nbsp;</td>
              <td width="82" align="center"><div class="ebtn_Generar" onClick="descargarCheques()"> </div></td>
              <td width="82" align="center"><div class="ebtn_Actualizar" onClick="verificarCheques()"></div></td>
              <td width="172">&nbsp;</td>
            </tr>
          </table>        </td>
      </tr>
                </table>          </td>
      </tr>
    </table>
</body>
	<script>
		parent.frames[1].document.getElementById('titulo').innerHTML = 'REPORTE DE CHEQUES';
	</script>
</html>
