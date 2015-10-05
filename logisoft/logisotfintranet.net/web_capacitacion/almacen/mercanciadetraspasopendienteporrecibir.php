<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../Conectar.php");
	$fecha = date('d/m/Y');
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />

<script src="../javascript/shortcut.js"></script>
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<script language="JavaScript" src="../javascript/ajax.js"></script>


<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
</head>


<script>
var u = document.all;

var tabla1 = new ClaseTabla();
	
	tabla1.setAttributes({
		nombre:"tabladetalle",
		campos:[
			{nombre:"NO_GUIA", medida:75, alineacion:"left", datos:"noguia"},
			{nombre:"CANTIDAD", medida:39, alineacion:"center", datos:"cantidad"},
			{nombre:"DESCRIPCION", medida:70, alineacion:"left", datos:"descripcion"},
			{nombre:"CONTENIDO", medida:70, alineacion:"center", datos:"contenido"},
			{nombre:"REMITENTE", medida:150, alineacion:"left", datos:"remitente"},
			{nombre:"DESTINATARIO", medida:150, alineacion:"left", datos:"destinatario"},
			{nombre:"SUCURSAL", medida:60, alineacion:"center", datos:"sucursal"},
			{nombre:"S", medida:20, tipo:"checkbox",alineacion:"center", datos:"s"}
		],
		filasInicial:10,
		alto:100,
		seleccion:false,
		ordenable:true,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		ObtenerGuias();
	}
	
	
function ObtenerGuias(){
	consulta("mostrarGuias","traspasarmercancia_con.php?accion=2&sid="+Math.random());
}
	
function mostrarGuias(datos){
	tabla1.setXML(datos);
}


function Validar(){
	<?=$cpermiso->verificarPermiso("319",$_SESSION[IDUSUARIO]);?>
	if(tabla1.getValSelFromField("noguia","S")==""){
			alerta3("No hay guias seleccionadas","¡Atencion!");
			return false;
	}
	var folios = tabla1.getValSelFromField("noguia","S");
	consulta("mostrarValidar","traspasarmercancia_con.php?accion="+3+"&folios="+folios+"&sid="+Math.random());
}

function mostrarValidar(datos){
	var guardado =datos.getElementsByTagName('guardado').item(0).firstChild.data;
	if(guardado==1){
			info("La informacion ha sido guardada","");
			//u.guardar.style.visibility="hidden";
			ObtenerGuias();
			
	}else{
		alerta3("Hubo un error "+datos,"¡Atencion!");
	}
}	


	
</script>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="620" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193" class="Tablas">
    <tr>
      <td width="544" class="FondoTabla Estilo4">MERCANC&Iacute;A DE TRASPASO PENDIENTE POR RECIBIR</td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="Tablas">
        <tr>
          <td align="right">Fecha
            <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/></td>
        </tr>
        <tr>
          <td><table id="tabladetalle"  border="0" cellspacing="0" cellpadding="0">

          </table></td>
        </tr>
        <tr>
          <td><br>
            <table width="158" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td><div class="ebtn_aceptar" onclick="Validar()"></div></td>
                <td><div class="ebtn_nuevo" onclick="ObtenerGuias()"></div></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>