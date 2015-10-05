<? 	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	$fecha = date('d/m/Y');

	$s="SELECT CONCAT_WS(' ',nombre,paterno,materno)AS nombre,
	CURRENT_TIME as hrs,(SELECT DATE_FORMAT(ADDDATE(CURRENT_DATE,7),'%d/%m/%Y'))
	AS fechaf FROM catalogocliente  WHERE id='".$_GET[cliente]."'";
	$s_q=mysql_query($s,$l) or die("Error en la liena ".mysql_error($l));
	$row=mysql_fetch_array($s_q);
	$cliente=$row[nombre];
	$hrs=$row[hrs];
	$idcliente=$_GET[cliente];
	//$fechaf = $row[fechaf];
	$fechaactual=$row[fechaf];
?>



<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>
<SCRIPT type="text/javascript" src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<style type="text/css">
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
-->
<!--
.Estilo4 {font-size: 12px}
.Estilo5 {
	font-size: 9px;
	font-family: tahoma;
	font-style: italic;
}
-->
</style>

<script>

var u = document.all;

	function obtenerDia(fecha){
		consultaTexto("mostrarDia","registrodecompromiso_con.php?accion=1&fecha="+fecha);
	}

	window.onload = function(){
		mostrardiasemana();
		u.hrs.value = obtenerHora();
	}
	
	
	function mostrarDia(datos){
		u.dia.value = datos;
	}

	function Validar(){
		var fech1 = u.fecha.value.split("/");
		var fech2 = u.fechaactual.value.split("/");		
		var fe1 = new Date( parseFloat(fech1[2]), parseFloat((fech1[1].substring(0,1)=="0")?fech1[1].substring(1,2):fech1[1]),
												parseFloat((fech1[0].substring(0,1)=="0")?fech1[0].substring(1,2):fech1[0]));
		
		var fe2 = new Date( parseFloat(fech2[2]), parseFloat((fech2[1].substring(0,1)=="0")?fech2[1].substring(1,2):fech2[1]),
												parseFloat((fech2[0].substring(0,1)=="0")?fech2[0].substring(1,2):fech2[0]));
		
		if(u.observaciones.value == ""){
			alerta('Debe capturar una Observaciones','Atención!','observaciones');
			return false;
		}
		
		if(u.fecha.value==""){
			alerta('Debe capturar una Fecha','Atención!','fecha');
			return false;
		}else if(fe1 <= fe2){
			alerta('La fecha compromiso debe ser mayor a la actual','Atención!','fecha');
			return false;
		}else{
			u.d_guardar.style.visibility = "hidden";
		consultaTexto("resultado","registrodecompromiso_con.php?accion=2&idcliente="+u.idcliente.value
				+"&fecha="+u.fecha.value
				+"&dia="+u.dia.value
				+"&hrs="+u.hrs.value
				+"&observaciones="+u.observaciones.value
				+"&factura="+u.factura.value
				+"&sid="+Math.random());
		}
	}
	
	function mostrardiasemana(){	
		consultaTexto("mostrardiasemanadetalle","registrodecompromiso_con.php?accion=6");
	}
	
	function mostrardiasemanadetalle(datos){
		var obj = eval(convertirValoresJson(datos));
		u.diasemana.value=obj[0].numero;
		
		consultaTexto("mostrarproximafecha","registrodecompromiso_con.php?accion=5&cliente="+u.idcliente.value);
	}
	
	function mostrarproximafecha(datos){
		if (datos!=0){
			var obj = eval(convertirValoresJson(datos));
			var dia = new Array();
			if (obj[0].todo!="1"){
				//dia[0] = obj[0].todo;		//0
				dia[1] = obj[0].lunes;		//2
				dia[2] = obj[0].martes;		//3
				dia[3] = obj[0].miercoles;	//4
				dia[4] = obj[0].jueves;		//5
				dia[5] = obj[0].viernes;	//6
				dia[6] = obj[0].sabado;		//7
				
				var contdias=0;
				var con=0;
				
				con=parseInt(u.diasemana.value) - 1; 	//+1;
				
				for(var i=0;i<6;i++){
					if (con>6){
						con=1;
					}
					
					if (dia[con]==1){
						break;
					}
					con++;
					contdias++;
				}
				
				consultaTexto("mostrardetallefecha","registrodecompromiso_con.php?accion=7&dia="+contdias);
			}else{
				u.fecha.value='<?=$fechaactual?>';
				obtenerDia(u.fecha.value);
			}
			
		}else{
			alerta("No existieron datos con los filtros seleccionados","Atencin!","idcliente");
		}
	}
	
	function mostrardetallefecha(datos){
		if (datos!=0){
			var obj = eval(convertirValoresJson(datos));
			u.fecha.value=obj[0].fecha;
			obtenerDia(u.fecha.value);
		}
	
	}
	
	function resultado(datos){
		if(datos.indexOf("ok")>-1){
			u.d_guardar.style.visibility = "visible";
			info('Los datos han sido guardados correctamente','');
			window.parent.<?=$_GET[funcion]; ?>(u.fecha.value,u.factura.value);
		}else{
			u.d_guardar.style.visibility = "visible";
			alerta3('Hubo un error al guardar '+datos,'Atencin!');
		}
	}

	function validarFecha(e,param,name){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 || tecla == 9){
			if(param!=""){
				var mes  =  parseInt(param.substring(3,5),10);
				var dia  =  parseInt(param.substring(0,2),10);
				if (!/^\d{2}\/\d{2}\/\d{4}$/.test(param)){
					alerta('La fecha no es valida', 'Atencin!',name);
					u.dia.value = "";
					return false;
				}
				if (dia>"31" || dia=="0" ){
					alerta('La fecha no es valida, capture correctamente el Dia', 'Atencin!',name);
					u.dia.value = "";
					return false;	
				}
				if (mes>"12" || mes=="0" ){
					alerta('La fecha no es valida, capture correctamente el Mes', 'Atencin!',name);
					u.dia.value = "";
					return false;	
				}
				consultaTexto("mostrarDia","registrodecompromiso_con.php?accion=1&fecha="+param);

			}	
		}
	}

</script>

</head>

<body>

<form id="form1" name="form1" method="post" action="">

  <br>

<table width="351" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr>

    <td width="398" class="FondoTabla Estilo4">REGISTRO DE COMPROMISOS</td>

  </tr>

  <tr>

    <td height="60"><div align="center">

      <table width="259" border="0" cellpadding="0" cellspacing="0">

        

        

        <tr>

          <td width="352"><table width="350" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td width="76" height="24"># Cliente:</td>

              <td colspan="2"><label><span class="Tablas">

              <input name="idcliente" type="text" class="Tablas" id="idcliente" style="width:100px;background:#FFFF99" value="<?=$idcliente ?>" readonly=""/>

              <input name="diasemana" type="hidden" id="diasemana" value="<?=$diasemana ?>">
              </span></label></td>
            </tr>

            <tr>

              <td height="11">Nombre:</td>

              <td colspan="2"><span class="Tablas">

                <input name="cliente" type="text" class="Tablas" id="cliente" style="width:250px;background:#FFFF99" value="<?=$cliente ?>"/>

              </span></td>
            </tr>

            <tr>

              <td height="11">Fecha:</td>

              <td width="112"><span class="Tablas">
                <input name="fecha" type="text" class="Tablas" id="fecha" style="width:80px;" onChange="obtenerDia(this.value)" onKeyPress="validarFecha(event,this.value,this.name)" value="<?=$fechaf ?>"/>
                <span class="Estilo6 Tablas"><img src="../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onClick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)" /></span></span></td>

              <td width="162"><span class="Tablas">Dia:

                  <input name="dia" type="text" class="Tablas" id="dia" style="width:100px;background:#FFFF99" value="<?=$dia ?>" readonly=""/>

              </span></td>
            </tr>

            <tr>

              <td height="11">Hora:</td>

              <td colspan="2"><span class="Tablas">

                <label></label>

                <input name="hrs" type="text" class="Tablas" id="hrs" style="width:100px;background:#FFFF99" readonly=""/>

                <input name="factura" type="hidden" id="factura" value="<?=$_GET[factura] ?>">

                <input name="fechaactual" type="hidden" id="fechaactual" value="<?=$fecha ?>">

              </span></td>
            </tr>

            <tr>

              <td height="11" valign="top">Observaciones:</td>

              <td colspan="2"><textarea name="observaciones" class="Tablas" style="text-transform:uppercase; width:250px; height:80px" id="observaciones"></textarea></td>
            </tr>

            <tr align="right">

              <td height="11" colspan="3">
			  <table width="172" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="right"><div class="ebtn_guardar" id="d_guardar" onClick="Validar()"></div></td>
                  <td align="right"><div class="ebtn_cerrarventana" id="d_cerrar" onClick="parent.VentanaModal.cerrar()"></div></td>
                </tr>
              </table>
			  </td>
              </tr>

            

            

            

          </table></td>

          </tr>

      </table>

    </div></td>

  </tr>

</table>

<p class="Tablas">&nbsp;</p>

<p>&nbsp;</p>

</form>

</body>

<script>

//	parent.frames[1].document.getElementById('titulo').innerHTML = 'REGISTRO DE COMPROMISO';

</script>

</html>