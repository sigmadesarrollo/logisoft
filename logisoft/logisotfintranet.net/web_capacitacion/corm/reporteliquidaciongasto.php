<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');	
	$link=Conectarse('webpmm'); $gcomprobar=$_POST['gcomprobar']; $empleado1=$_GET['empleado1'];
	$empleadob1=$_GET['empleadob1']; $empleado2=$_GET['empleado2']; $empleadob2=$_GET['empleadob2'];

	if($accion == ""){
	$fecha = date("d/m/Y");		
	}else if($accion == "grabar"){
		
	}else if($accion == "modificar"){
		
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/shortcut.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/ClaseTabla.js"></script>

<script>
var tabla1 	= new ClaseTabla();
var u = document.all;

	tabla1.setAttributes({
		nombre:"treporte",
		campos:[
		{nombre:"F_BITACORA", medida:70, alineacion:"left", datos:"bitacora"},
		{nombre:"CONCEPTO", medida:190, alineacion:"left", datos:"concepto"},
		{nombre:"CARGO", medida:80,  tipo:"moneda", alineacion:"left", datos:"cargo"},
		{nombre:"ABONO", medida:80,  tipo:"moneda", alineacion:"left", datos:"abono"},
		{nombre:"SALDO", medida:80,  tipo:"moneda", alineacion:"left", datos:"saldo"}
		],
		filasInicial:10,
		alto:150,
		seleccion:true,
		ordenable:false,
		//eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
	}

function obtenerEmpleadoBusqueda(id,caja){
		if(id!=""){
			switch(caja){
				case "1":
					u.empleado1.value = id;
				break;
				case "2":		
					u.empleado2.value = id;
				break;
			}
consulta("mostrarEmpleado","consultaCORM.php?accion=10&id="+id+"&caja="+caja);
		}
	}
	function obtenerEmpleado(e,id,caja){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 && id!=""){
consulta("mostrarEmpleado","consultaCORM.php?accion=10&id="+id+"&caja="+caja);
		}
	}
	function mostrarEmpleado(datos){
	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
	var caja = datos.getElementsByTagName('caja').item(0).firstChild.data;
		if(con>0){
		switch(caja){
		case "1":
u.empleadob1.value = datos.getElementsByTagName('nombre').item(0).firstChild.data;
		break;
		case "2":		
u.empleadob2.value = datos.getElementsByTagName('nombre').item(0).firstChild.data;
		break;
		}
		
		}else{
			alerta3('La persona no existe','¡Atención!','recibe'+caja);
			switch(caja){
				case "1":
					u.empleadob1.value = "";
				break;
				case "2":		
					u.empleadob2.value = "";
				break;
			}
		}
	}
	var nav4 = window.Event ? true : false;
	function Numeros(evt){ 
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46, ',' = 44 
	var key = nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
	}
	function tabular(e,obj) 
			{
				tecla=(document.all) ? e.keyCode : e.which;
				if(tecla!=13) return;
				frm=obj.form;
				for(i=0;i<frm.elements.length;i++) 
					if(frm.elements[i]==obj) 
					{ 
						if (i==frm.elements.length-1) 
							i=-1;
						break
					}
	
				if (frm.elements[i+1].disabled ==true )    
					tabular(e,frm.elements[i+1]);
				else if(frm.elements[i+1].readOnly ==true )
					tabular(e,frm.elements[i+1]);
				else frm.elements[i+1].focus();
				return false;
	}
	function foco(nombrecaja){
	if(nombrecaja=="empleado1"){
		u.oculto.value="1";
	}else if(nombrecaja=="empleado2"){
		u.oculto.value="2";
	}
}
	function borrarDescripciones(nombrecaja){
		if(nombrecaja =="empleado1" && u.empleado1.value ==""){
			u.empleadob1.value = "";			
		}else if(nombrecaja =="empleado2" && u.empleado2.value ==""){
			u.empleadob2.value = "";
		}
	}
</script>
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
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.Estilo5 {
	font-size: 9px;
	font-family: tahoma;
	font-style: italic;
}
-->
</style>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="550" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="600" class="FondoTabla Estilo4">REPORTE LIQUIDACI&Oacute;N DE GASTOS</td>
  </tr>
  <tr>
    <td height="13"><div align="center">
      <table width="540" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="4" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" align="right">Fecha:
            <input name="fecha" type="text" id="fecha" style="width:100px; background:#FF9" value="<?=$fecha ?>" readonly="readonly"></td>
        </tr>
        <tr>
          <td width="116">Gastos a Comprobar:</td>
          <td colspan="3"><input name="gcomprobar" type="text" id="gcomprobar" style="width:100px" value="<?=$gcomprobar ?>"></td>
        </tr>
        <tr>
          <td>Entrego:</td>
          <td width="63"><span class="Tablas">
            <input name="empleado1" type="text" class="Tablas" id="empleado1" style="width:50px" onKeyDown="obtenerEmpleado(event,this.value,1); return tabular(event,this)" value="<?=$empleado1 ?>" onKeyPress="return Numeros(event); " onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''; borrarDescripciones(this.name)" />
          </span></td>
          <td width="37"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarEmpleado.php?funcion=obtenerEmpleadoBusqueda&caja=1', 550, 450, 'ventana', 'Busqueda')"></div></td>
          <td width="324"><span class="Tablas">
            <input name="empleadob1" type="text" class="Tablas" id="empleadob1" style="width:300px;background:#FFFF99" value="<?=$empleadob1 ?>" readonly=""/>
          </span></td>
        </tr>
        <tr>
          <td>Recibi&oacute;:</td>
          <td><span class="Tablas">
            <input name="empleado2" type="text" class="Tablas" id="empleado2" style="width:50px" onKeyDown="obtenerEmpleado(event,this.value,2); if(event.keyCode==13){borrarDescripciones(this.name)}" value="<?=$empleado2 ?>" onKeyPress="return Numeros(event); " onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''; borrarDescripciones(this.name)" />
          </span></td>
          <td><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarEmpleado.php?funcion=obtenerEmpleadoBusqueda&caja=2', 550, 450, 'ventana', 'Busqueda')"></div></td>
          <td><span class="Tablas">
            <input name="empleadob2" type="text" class="Tablas" id="empleadob2" style="width:300px;background:#FFFF99" value="<?=$empleadob2 ?>" readonly=""/>
          </span></td>
        </tr>
        <tr>
          <td colspan="4"></td>
        </tr>
        <tr>
          <td colspan="4"><table width="534" border="0" cellpadding="0" cellspacing="0" id="treporte">  
</table></td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" align="right">Devoluci&oacute; Efectivo:            <input name="defectivo" type="text" id="defectivo" style="width:100px" value="<?=$defectivo ?>"></td>
        </tr>
        <tr>
          <td colspan="4">
            </td>
        </tr>
        <tr>
          <td colspan="4"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
            <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>"></td>
        </tr>
      </table>
    </div></td>
  </tr>
</table>
</form>
</body>
</html>