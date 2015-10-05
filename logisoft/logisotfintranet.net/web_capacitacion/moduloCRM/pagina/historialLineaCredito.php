<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>

<script src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<script src="../../javascript/ajax.js"></script>

<script src="../../javascript/ClaseTabla.js"></script>

<script src="../../javascript/funciones.js"></script>

<script src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/jquery.js"></script>
<script src="../../javascript/jquery.maskedinput.js"></script>
<script>
	
	jQuery(function($){	
		$('#fechainicio').mask("99/99/9999");
		$('#fechafin').mask("99/99/9999");
	});

	
	var u = document.all;

	var tabla1 = new ClaseTabla();

	var mens 		= new ClaseMensajes();

	var inicio		= 30;

	var sepaso		= 0;

	var cont		= 0;

	var totalDatos	= 0;

	mens.iniciar('../../javascript',false);

	tabla1.setAttributes({

		nombre:"detalle",

		campos:[			

			{nombre:"FECHA CREDITO", medida:90, alineacion:"left", datos:"fechacredito"},

			{nombre:"MONTO AUTORIZADO", medida:90,tipo:"moneda", alineacion:"right", datos:"montoautorizado"},

			{nombre:"MODIFICO", medida:150, alineacion:"left",  datos:"modifico"},

			{nombre:"SOLICITUD", medida:150, alineacion:"left",  datos:"solicitud"}

		],

		filasInicial:20,

		alto:300,

		seleccion:true,

		ordenable:false,

		//eventoDblClickFila:"verRecoleccion()",

		nombrevar:"tabla1"

	});

	

	window.onload = function(){

		tabla1.create();

		u.paginado.style.visibility = "hidden";

		u.fechainicio.focus();

	}	

	function mostrarDetalle(datos){

		var obj = eval(convertirValoresJson(datos));

		tabla1.setJsonData(obj);

	}	

	function generarHistorial(){

		var f1 = u.fechainicio.value.split("/");

		var f2 = u.fechafin.value.split("/");

		v_fechaini	= new Date(f1[1]+"/"+f1[0]+"/"+f1[2]); 

		v_fechafin	= new Date(f2[1]+"/"+f2[0]+"/"+f2[2]);

		

		if(u.fechainicio.value=="" || u.fechafin.value==""){

			mens.show('A','Debe capturar Fecha '+((u.fechainicio.value=="")? 'inicio' : 'fin'),'�Atenci�n!',((u.fechainicio.value=="")? 'fechainicio' : 'fechafin'));

		}else if(v_fechaini > v_fechafin){

			mens.show('A','La fecha fin no debe ser menor que la fecha inicio','�Atenci�n!','fechafin');			

		}else{		

		consultaTexto("obtenerTotal","consultas_crm.php?accion=16&tipo=0&cliente=<?=$_GET[cliente] ?>&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value);

		}

	}

	function obtenerTotal(datos){

		u.contadordes.value = datos;

		u.mostrardes2.value = datos;

		u.totaldes.value = "00";

		if(u.contadordes.value > 30){

			u.paginado.style.visibility = "visible";

			u.paginado.style.visibility = "visible";

			u.d_atrasdes.style.visibility = "hidden";

			u.primero.style.visibility = "hidden";

			totalDatos = parseInt(u.contadordes.value / 30);

		}else{

			u.paginado.style.visibility = "hidden";

		}

		consultaTexto("mostrarDetalle","consultas_crm.php?accion=16&tipo=1&inicio=0&cliente=<?=$_GET[cliente] ?>&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value);

	}

	function paginacion(tipo){

		if(tipo == "atras"){

			u.d_sigdes.style.visibility = "visible";

			u.d_ultimo.style.visibility = "visible";

			u.totaldes.value = parseFloat(u.totaldes.value) - inicio;

			if(parseFloat(u.totaldes.value) <= "1"){

				u.totaldes.value = "00";

				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;

				if(parseFloat(u.mostrardes.value) < inicio){

					u.mostrardes.value = inicio;

				}

				if(u.ultimo.value == "SI"){

					var con = parseInt(u.contadordes.value / 30) - 1;

					u.totaldes.value = con * 30;

					u.ultimo.value = "";

					consultaTexto("mostrarDetalle","consultas_crm.php?accion=16&tipo=1&inicio="+u.totaldes.value+"&cliente=<?=$_GET[cliente] ?>&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value);

				}else{

					u.d_atrasdes.style.visibility = "hidden";

					u.primero.style.visibility = "hidden";

					consultaTexto("mostrarDetalle","consultas_crm.php?accion=16&tipo=1&inicio=0&cliente=<?=$_GET[cliente] ?>&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value);

				}

			}else{

				if(sepaso!=0){

					u.mostrardes.value = sepaso;

					sepaso = 0;

				}

				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;

				if(parseFloat(u.mostrardes.value) < inicio){

					u.mostrardes.value = inicio;

				}

				if(u.ultimo.value == "SI"){

					var con = parseInt(u.contadordes.value / 30) - 1;

					u.totaldes.value = con * 30;

					u.ultimo.value = "";

				}

				consultaTexto("mostrarDetalle","consultas_crm.php?accion=16&tipo=1&cliente=<?=$_GET[cliente] ?>&inicio="+u.totaldes.value+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value);

			}

		}else{

			cont++;

			u.d_atrasdes.style.visibility = "visible";

			u.primero.style.visibility = "visible";

			u.totaldes.value = inicio + parseFloat(u.totaldes.value);

			if(parseFloat(u.totaldes.value) > parseFloat(u.contadordes.value)){

				u.totaldes.value = parseFloat(u.totaldes.value) - inicio;

				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;

				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){

					u.mostrardes.value = u.contadordes.value;

				}

				u.d_sigdes.style.visibility = "hidden";

				u.d_ultimo.style.visibility = "hidden";

			}else{

				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;

				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){

					sepaso	=	u.mostrardes.value;

					u.mostrardes.value = u.contadordes.value;

				}

				if(cont>=totalDatos){

					u.d_sigdes.style.visibility = "hidden";

					u.d_ultimo.style.visibility = "hidden";

					cont = 0;

				}

				consultaTexto("mostrarDetalle","consultas_crm.php?accion=16&tipo=1&cliente=<?=$_GET[cliente] ?>&inicio="+u.totaldes.value+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value);

			}

		}

	}

	

	function obtenerPrimero(){

		u.totaldes.value = "00";

		u.d_sigdes.style.visibility = "visible";

		u.d_ultimo.style.visibility = "visible";

		consultaTexto("mostrarDetalle","consultas_crm.php?accion=16&tipo=1&cliente=<?=$_GET[cliente] ?>&inicio="+u.totaldes.value+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value);

	}

	function obtenerUltimo(){

		u.ultimo.value = "SI";

		u.d_sigdes.style.visibility = "hidden";

		u.d_ultimo.style.visibility = "hidden";

		u.d_atrasdes.style.visibility = "visible";

		u.primero.style.visibility = "visible";

		consultaTexto("mostrarDetalle","consultas_crm.php?accion=17&tipo=1&cliente=<?=$_GET[cliente] ?>&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value);

	}	

	

	function validarFecha(e,param,name){

		tecla = (u) ? e.keyCode : e.which;

		if(tecla == 13 || tecla == 9){

			if(param!=""){

				var mes  =  parseInt(param.substring(3,5),10);

				var dia  =  parseInt(param.substring(0,3),10);

				var year = 	parseInt(param.substring(6,10),10);

				if (!/^\d{2}\/\d{2}\/\d{4}$/.test(param)){

					mens.show('A','La fecha no es valida', '�Atenci�n!',name);

					return false;

				}				

				if(dia > 29 && (mes=="02" || mes==2)){

					if((year % 4 == 0 && year % 100 != 0) || year % 400 == 0){

						mens.show('A','La fecha '+((name=="fechainicio")?"inicio":"fin")+' no es valida, por que el a�o '+year+' es bisiesto su maximo dia es 29', '�Atenci�n!');

						return false;

					}else{

						mens.show('A','La fecha '+((name=="fechainicio")?"inicio":"fin")+' no es valida, por que el a�o '+year+' no es bisiesto su maximo dia es 28', '�Atenci�n!');

						return false;

					}

				}

				

				if(dia >= 29 && (mes=="02" || mes=="2")){

					if(!((year % 4 == 0 && year % 100 != 0) || year % 400 == 0)){

						mens.show('A','La fecha '+((name=="fechainicio")?"inicio":"fin")+' no es valida, por que el a�o '+year+' no es bisiesto su maximo dia es 28', '�Atenci�n!');

							return false;

					}

				}

				if(dia > "31" || dia=="0"){

					mens.show('A','La fecha no es valida, capture correctamente el Dia', '�Atenci�n!',name);

					return false;

				}

				if(mes > "12" || mes=="0"){

					mens.show('A','La fecha no es valida, capture correctamente el Mes', '�Atenci�n!',name);

					return false;	

				}

			}

		}

	}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />

</head>



<body>

<form id="form1" name="form1" method="post" action="">

  <table width="510" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td class="FondoTabla">Historial Linea de Cr�dito </td>

    </tr>

    <tr>

      <td><table width="500" border="0" cellspacing="0" cellpadding="0">

          <tr>

            <td><table width="500" border="0" cellspacing="0" cellpadding="0">

                <tr>

                  <td width="75">Fecha Inicio: </td>

                  <td width="124"><label>

                    <input name="fechainicio" type="text" class="formato_fuente" id="fechainicio" style="width:80px" maxlength="10" onkeyup="if(event.keyCode==13 && this.value!=''){document.all.fechafin.focus();}" onkeypress="validarFecha(event,this.value,this.name);" />

                    <img src="../../img/calendario.gif" width="20" height="20" align="absbottom" title="Calendario" onclick="displayCalendar(document.forms[0].fechainicio,'dd/mm/yyyy',this)" style="cursor:pointer" /></label></td>

                  <td width="18">&nbsp;</td>

                  <td width="53">Fecha Fin: </td>

                  <td width="113"><input name="fechafin" type="text" class="formato_fuente" id="fechafin" style="width:80px" maxlength="10" onkeypress="validarFecha(event,this.value,this.name); if(document.all.fechainicio.value!='' &amp;&amp; event.keyCode==13){ generarHistorial(); }" />

                      <img src="../../img/calendario.gif" width="20" height="20" align="absbottom" title="Calendario" onclick="displayCalendar(document.forms[0].fechafin,'dd/mm/yyyy',this)"  style="cursor:pointer" /></td>

                  <td width="117"><div class="ebtn_Generar" onclick="generarHistorial()"></div></td>

                </tr>

            </table></td>

          </tr>

          <tr>

            <td><table width="549" border="0" cellspacing="0" cellpadding="0" id="detalle">

            </table></td>

          </tr>

          <tr>

            <td><div id="paginado" align="center">

                <input name="totaldes" type="hidden" id="totaldes" value="00" />

                <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />

                <img src="../../img/first.gif" style="cursor:pointer" id="primero"  onclick="obtenerPrimero()" /> <img src="../../img/previous.gif" style="cursor:pointer" id="d_atrasdes" onclick="paginacion('atras')" /> <img src="../../img/next.gif" style="cursor:pointer" id="d_sigdes" onclick="paginacion('siguiente')" /> <img src="../../img/last.gif" style="cursor:pointer" id="d_ultimo" onclick="obtenerUltimo()" />

                <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" />

                <input name="mostrardes2" class="Tablas" type="hidden" id="mostrardes2" value="<?=$tdes; ?>" />

                <input name="ultimo" class="Tablas" type="hidden" id="ultimo" />

            </div></td>

          </tr>

      </table></td>

    </tr>

  </table>

</form>

</body>

</html>
