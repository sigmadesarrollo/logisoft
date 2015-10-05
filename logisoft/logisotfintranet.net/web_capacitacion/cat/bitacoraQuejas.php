<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	$fecha = date('d/m/Y');
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
			{nombre:"SEL", medida:50, tipo:"checkbox", alineacion:"center", datos:"seleccion"},
			{nombre:"FOL_ATEN", medida:60, alineacion:"left", datos:"folio"},
			{nombre:"FECHA", medida:80, alineacion:"left", datos:"fecha"},
			{nombre:"FOLIO_DOC", medida:80, alineacion:"left", datos:"foliodoc"},
			{nombre:"SUCURSAL", medida:170, alineacion:"left", datos:"sucursal"},
			{nombre:"QUEJA", medida:150, alineacion:"left",  datos:"queja"},
			{nombre:"RESPONSABLE", medida:150, alineacion:"left",  datos:"responsable"},
			{nombre:"OBSERVACIONES", medida:150, alineacion:"left", datos:"observaciones"},
			{nombre:"STATUS", medida:100, alineacion:"left", datos:"estqueja"},
			{nombre:"ESTADO", medida:4, tipo:"oculto", alineacion:"left", datos:"estado"},
			{nombre:"POSIBLE_FECHA_SOLUCION", medida:120, alineacion:"left", datos:"solucion"},
			{nombre:"COMENTARIOS", medida:4, tipo:"oculto", alineacion:"left", datos:"comentarios"},
			{nombre:"FOLIOACTIVIDAD", medida:4, tipo:"oculto", alineacion:"left", datos:"folioactividad"}
		],
		filasInicial:30,
		alto:270,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"agregarFecha()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtenerDetalle();
	}
	function obtenerDetalle(){
		consultaTexto("mostrarDetalle","centroAtencionTelefonica_con.php?accion=7&random="+Math.random());
	}
	function mostrarDetalle(datos){
		tabla1.clear();
		datos = convertirValoresJson(datos);
		var objeto = eval(datos);
		for(var i=0;i<objeto.length;i++){
			var obj		 	   = new Object();
			obj.folio 		   = objeto[i].folio;
			obj.fecha	 	   = objeto[i].fecha;
			obj.sucursal 	   = objeto[i].sucursal;
			obj.queja		   = objeto[i].queja;
			obj.observaciones  = objeto[i].observaciones;
			obj.solucion 	   = objeto[i].solucion;
			obj.comentarios    = objeto[i].comentarios;
			obj.responsable    = objeto[i].responsable;
			obj.foliodoc	   = objeto[i].foliodoc;
			obj.estado		   = objeto[i].estado;
			obj.folioactividad = objeto[i].folioactividad;
			obj.estqueja 	   = objeto[i].estqueja;
			tabla1.add(obj);				
			
			if(objeto[i].comparar!=""){
				var f1 = objeto[i].comparar.split("/");
				var f2 = u.fechaactual.value.split("/");
				
				if(f1[0].substr(0,1)=="0"){
					f1[0] = f1[0].substr(1,1);
				}
				if(f1[1].substr(0,1)=="0"){
					f1[1] = f1[1].substr(1,1);
				}
				
				if(f2[0].substr(0,1)=="0"){
					f2[0] = f2[0].substr(1,1);
				}
				if(f2[1].substr(0,1)=="0"){
					f2[1] = f2[1].substr(1,1);
				}
				
				f1 = new Date(f1[2],f1[1],f1[0]);
				f2 = new Date(f2[2],f2[1],f2[0]);
				
				if(f1<=f2){
					tabla1.setColorById('#FF0000','detalle_id'+i);
				}
			}
		}
	}
	function agregarFecha(){
		var arr = tabla1.getSelectedRow();
		if(tabla1.getValSelFromField('solucion','POSIBLE_FECHA_SOLUCION')==""){
			abrirVentanaFija('agregarPosibleFecha.php?funcion=obtenerDatos&folio='+arr.folio, 450, 418, 'ventana', 'Busqueda');
		}else{
			var fecha = arr.solucion.replace(RegExp("/","g"),"-");
			abrirVentanaFija('agregarPosibleFecha.php?funcion=obtenerDatos&fecha='+fecha+'&comentarios='+arr.comentarios+'&folio='+arr.folio, 450, 418, 'ventana', 'Busqueda');
		}
	}
	function obtenerDatos(fecha,observ){
		var arr 			= tabla1.getSelectedRow();
		var obj 			= Object();
		obj.seleccion		= 1;
		obj.folio			= arr.folio;
		obj.fecha			= arr.fecha;
		obj.sucursal		= arr.sucursal;
		obj.queja			= arr.queja;
		obj.observaciones	= arr.observaciones;
		obj.responsable     = arr.responsable;
		obj.foliodoc	    = arr.foliodoc;
		obj.solucion		= fecha;
		obj.comentarios		= observ;
		obj.estado		    = arr.estado;
		obj.folioactividad  = arr.folioactividad;
		tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);
	}
	function solucionados(){
		<?=$cpermiso->verificarPermiso(295,$_SESSION[IDUSUARIO]);?>
		if(tabla1.getRecordCount()!=0){
			u.d_solucionados.style.visibility = "hidden";
			var folios = "";
			var actividad = "";
			for(var i=0;i<tabla1.getRecordCount();i++){			
				if(u["detalle_SEL"][i].checked==true){
					if(u["detalle_QUEJA"][i].value!="RECOLECCION" && u["detalle_QUEJA"][i].value!="EAD MAL EFECTUADAS"){
						folios += u["detalle_FOL_ATEN"][i].value+",";
						actividad += u["detalle_FOLIOACTIVIDAD"][i].value+",";
						//tabla1.deleteById("detalle_id"+i);
					}
					
					if(u["detalle_ESTADO"][i].value=="CANCELADO"){
						folios += u["detalle_FOL_ATEN"][i].value+",";
						actividad += u["detalle_FOLIOACTIVIDAD"][i].value+",";
						//tabla1.deleteById("detalle_id"+i);
					}					
				}
			}
			if(folios!=""){
				folios = folios.substring(0,folios.length-1);
				actividad = actividad.substring(0,actividad.length-1);
				consultaTexto("registrarSolucionados","centroAtencionTelefonica_con.php?accion=12&folios="+folios
				+"&actividad="+actividad);
			}else{
				u.d_solucionados.style.visibility = "visible";
				alerta3("Debe seleccionar folios a solucionar que no sean RECOLECCION ni EAD MAL EFECTUADAS","¡Atención!");
			}
		}
	}
	function registrarSolucionados(datos){
		if(datos.indexOf("ok")>-1){
			u.d_solucionados.style.visibility = "visible";
			info('Los datos han sido guardados correctamente','');
			obtenerDetalle();
		}else{
			alerta3("Hubo un error al guardar "+datos,"¡Atención!");
			u.d_solucionados.style.visibility = "visible";
		}
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
      <td class="FondoTabla">BIT&Aacute;CORA DE QUEJAS</td>
    </tr>
	
    <tr>
      <td><table width="599" border="0" align="center" cellpadding="0" cellspacing="0">
	  <tr>
      <td >&nbsp;</td>
    </tr>
        <tr>
          <td><div id="txtDir" style=" height:300px; width:806px; overflow:auto" align=left><table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">
          </table></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="579" align="right"><div id="d_solucionados" class="ebtn_Solucionado" onClick="solucionados()"></div>
            <input name="fecha" type="hidden" id="fecha">
            <input name="observaciones" type="hidden" id="observaciones">
           </td>
        </tr>
		<tr>
			<td align="center">
			  <input name="fechaactual" type="hidden" id="fechaactual" value="<?=$fecha ?>">
			</a></td>
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
