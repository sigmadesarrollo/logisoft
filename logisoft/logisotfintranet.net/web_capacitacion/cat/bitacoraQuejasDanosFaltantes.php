<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	$fechaactual = date('m/d/Y');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script>
	var tabla1 	= new ClaseTabla();
	var	u		= document.all;
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
				
			{nombre:"SUCURSAL", medida:70, alineacion:"left", datos:"sucursal"},			
			{nombre:"FOLIO", medida:50,onDblClick:"MostrarModulo", alineacion:"left", datos:"folio"},
			{nombre:"FECHA", medida:60, alineacion:"left", datos:"fecha"},			
			{nombre:"RESPONSABLE", medida:150, alineacion:"left",  datos:"responsable"},
			{nombre:"FECHA_SOLUCION",onDblClick:"MostrarFecha", medida:60, alineacion:"center", datos:"solucion"},
			{nombre:"OBSERVACIONES", medida:150, alineacion:"left", datos:"observaciones"},
			{nombre:"OS", medida:4,tipo:"oculto",alineacion:"left", datos:"os"}
		],
		filasInicial:15,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtenerDetalle();
	}
	function obtenerDetalle(){
		consultaTexto("mostrarDetalle","bitacoraQuejasDanosFaltantes_con.php?accion=1");
	}
	function mostrarDetalle(datos){	
		var objeto = eval(convertirValoresJson(datos));
		for(var i=0;i<objeto.length;i++){
			var obj		 	   	= new Object();
			obj.sucursal 		= objeto[i].sucursal;
			obj.folio	 	   	= objeto[i].folio;
			obj.fecha 	  		= objeto[i].fecha;
			obj.responsable	   	= objeto[i].nombre;
			obj.observaciones  	= objeto[i].observaciones;
			obj.solucion 	   	= objeto[i].fechaposible;
			obj.os		 	   	= objeto[i].observacionfechaposible;
			tabla1.add(obj);
			
			fecha1=objeto[i].fechaparacomparar; 
			fecha2=u.fechaactual.value; 
			f1=new Date(fecha1); 
			f2=new Date(fecha2);
			if(f1<=f2){
			 tabla1.setColorById('#FF0000','detalle_id'+i); 
			}
	
		}
	}
	
	function MostrarModulo(){
		var id=tabla1.getValSelFromField('folio','FOLIO');
		abrirVentanaFija('moduloQuejasDanosFaltantes.php?mostrarvalores=1&id='+id, 625, 500, 'ventana', 'Busqueda')
	}
	
	function MostrarFecha(){
		var folio=tabla1.getValSelFromField('folio','FOLIO');
		var os=tabla1.getValSelFromField('os','OS');
		var fecha=tabla1.getValSelFromField('solucion','FECHA_SOLUCION');
		abrirVentanaFija('agregarPosibleFecha.php?bitacoraquejas=1&funcion=OptenerFechaPosible&folio='+folio+"&comentarios="+os+"&fecha="+fecha, 450, 418, 'ventana', 'Busqueda')
	}
	
	function OptenerFechaPosible(fecha,observaciones){
		var arr 			= tabla1.getSelectedRow();
		var obj 			= Object();
		obj.sucursal 		= arr.sucursal;
		obj.folio	 	   	= arr.folio;
		obj.fecha 	  		= arr.fecha;
		obj.responsable	   	= arr.responsable;
		obj.observaciones  	= arr.observaciones;
		obj.solucion 	   	= fecha;
		obj.os		 	   	= observaciones;
		tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);
	}
	
</script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script src="../javascript/ajax.js"></script>

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
  <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">BITACORA DE QUEJAS DA&Ntilde;OS Y FALTANTES</td>
    </tr>
	
    <tr>
      <td><table width="599" border="0" align="center" cellpadding="0" cellspacing="0">
	  <tr>
      <td >&nbsp;</td>
    </tr>
        <tr>
          <td align="center" ><table id="detalle" border="0" cellpadding="0" cellspacing="0">
          </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="579" align="right">&nbsp;</td>
        </tr>
		<tr>
			<td align="center"><input name="fechaactual" type="hidden" id="fechaactual" value="<?=$fechaactual ?>"></td>
		</tr>
      </table></td>
    </tr>
  </table>
  </td>
  </tr>
  </table>
</form>
</body>
</html>
