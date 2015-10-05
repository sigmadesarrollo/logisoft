<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	include('../../Conectar.php');	
	$link=Conectarse('webpmm');
	$accion=$_POST['accion']; $codigo=$_POST['codigo']; $descripcion=$_POST['descripcion'];$usuario=$_SESSION[NOMBREUSUARIO]; $recorrido=$_POST['recorridohrs'].":".$_POST['recorridomin']; $km=$_POST['km']; $tipounidad=$_POST['tipounidad'];$fechahora=$_GET['fechahora'];$hidensucursal=$_POST['hidensucursal']; $tipounidad_des=$_POST['tipounidad_des'];$transbordo=$_POST[transbordo]; $antusuario=$_POST['antusuario'];
	$idusuario=$_SESSION[IDUSUARIO];


$fechahora = $_POST['fechahora'];
	$fecha = date("d/m/Y h:i");
	$f     = cambiaf_a_mysql($fecha);
	$hora  = date("H:i:s");	
	if($fechahora == ""){
	$fechahora = $f.' '.$hora;
}

if($accion == ""){	
	$row = folio('catalogoruta','webpmm');
	$codigo = $row[0];
}else if($accion == "grabar"){
		$sql=mysql_query("SELECT * FROM catalogorutadetalletmp WHERE idusuario=$_SESSION[IDUSUARIO] ",$link) or die("Error en la Linea ".__LINE__) or die(" Error en la linea ".__LINE__.mysql_error($link));
	if(mysql_num_rows($sql)>0){
		$sqlins  = mysql_query("INSERT INTO catalogoruta (id,descripcion,recorrido,km,idtipounidad,tipounidad,usuario,fecha) 
VALUES (NULL,UCASE('$descripcion'), '$recorrido','$km','$tipounidad','$tipounidad_des','$usuario', '$fechahora')",$link) or die("Error en la Linea- ".__LINE__.mysql_error($link));	
		$codigo  = mysql_insert_id();
		$sqlgrid = mysql_query("INSERT INTO catalogorutadetalle SELECT 0 as id,'$codigo' As ruta,tipo, diasalidas, sucursal, horasllegada, tiempodescarga,tiempocarga, horasalida, trayectosucursal,transbordo,sucursalestransbordo,idusuario, usuario, fecha FROM catalogorutadetalletmp WHERE idusuario=$_SESSION[IDUSUARIO]",$link) or die("Error en la Linea ".__LINE__);
		$sqldetalle_eliminar = mysql_query("DELETE FROM catalogorutadetalletmp WHERE idusuario=$_SESSION[IDUSUARIO]",$link) or die("Error en la Linea --".__LINE__);
		/**********/
		if($hidensucursal!=""){
			if($hidensucursal=="TODAS"){
				$sqlins=mysql_query("INSERT INTO catalogorutadetallesucursal (id,idruta,idsucursal,sucursal,usuario,fecha) VALUES(NULL,'$codigo','0',UCASE('$hidensucursal'),'$usuario',current_timestamp())",$link) or die("Error en la linea ".__LINE__);
			}else{
				$hidensucursal=substr($hidensucursal,0,strlen($hidensucursal)-1);
				$coma = ",";
				$lista=split($coma,$hidensucursal);	
				if (count($lista)>0){
					for ($i=0;$i<count($lista);$i++){
						$var = trim($lista[$i]);
						$var = split(":",$var);			
						if ($var!=""){
						$sqlins=mysql_query("INSERT INTO catalogorutadetallesucursal (id,idruta,idsucursal,sucursal,usuario,fecha) VALUES(NULL,'$codigo','$var[0]',UCASE('$var[1]'),'$usuario',current_timestamp())",$link) or die("INSERT INTO catalogorutadetallesucursal (id,idruta,idsucursal,sucursal,usuario,fecha) VALUES(NULL,'$codigo','$var[0]',UCASE('$var[1]'),'$usuario',current_timestamp())"."Error en la linea ".__LINE__);	
						}
					}
				}
			}
		}
		/**********/
		$mensaje = 'Los datos han sido guardados correctamente';
		//$accion  = "limpiar";
	}else{
		$msg = 'Capture una Ruta';
		$accion  = "";
	}
}else if($accion=="modificar"){
		$f= date("Y/m/d h:i:s");
		$sql=mysql_query("SELECT * FROM catalogorutadetalletmp WHERE idusuario=$_SESSION[IDUSUARIO] ",$link) or die("Error en la Linea ".__LINE__);
		if(mysql_fetch_array($sql)>0){
		$sqlupd=mysql_query("UPDATE catalogoruta SET descripcion='$descripcion',recorrido='$recorrido', km='$km',idtipounidad='$tipounidad', tipounidad='$tipounidad_des',usuario='$usuario', fecha='$f' WHERE id='$codigo'",$link) or die("Error en la Linea ".__LINE__);
		$sqldetalle_eliminar = mysql_query("DELETE FROM catalogorutadetalle WHERE ruta='$codigo'",$link) or die("Error en la Linea ".__LINE__);
		$sqldetalle = mysql_query("INSERT INTO catalogorutadetalle SELECT 0 as id,'$codigo' As ruta,tipo, diasalidas, sucursal, horasllegada, tiempodescarga,tiempocarga, horasalida, trayectosucursal,transbordo,sucursalestransbordo,'$idusuario' as idusuario, '$usuario','$f' FROM catalogorutadetalletmp WHERE idusuario=$_SESSION[IDUSUARIO] ",$link) or die("Error en la Linea ".__LINE__);
		$sqldetalle_eliminar_tmp = mysql_query("DELETE FROM catalogorutadetalletmp WHERE idusuario=$_SESSION[IDUSUARIO]",$link) or die("Error en la Linea ".__LINE__);
	/**********/
		$sql_del_suc=mysql_query("DELETE FROM catalogorutadetallesucursal WHERE idruta ='$codigo'",$link)or die("Error en la liena ".__LINE__);
		if($hidensucursal!=""){
			if($hidensucursal=="TODAS"){
					$sqlins=mysql_query("INSERT INTO catalogorutadetallesucursal (id,idruta,idsucursal,sucursal,usuario,fecha) VALUES(NULL,'$codigo','0',UCASE('$hidensucursal'),'$usuario',current_timestamp())",$link) or die("Error en la linea ".__LINE__);
				}else{
					$hidensucursal=substr($hidensucursal,0,strlen($hidensucursal)-1);
					$lista=split(",",$hidensucursal);	
					if (count($lista)>0){
						for ($i=0;$i<count($lista);$i++){
							$var = trim($lista[$i]);
							$var = split(":",$var);			
							if ($var!=""){
							$sqlins=mysql_query("INSERT INTO catalogorutadetallesucursal (id,idruta,idsucursal,sucursal,usuario,fecha) VALUES(NULL,'$codigo','$var[0]',UCASE('$var[1]'),'$usuario',current_timestamp())",$link) or die("Error en la linea ".__LINE__);					
							}
						}
					}
				}
		}
		/**********/
		$mensaje = 'Los cambios han sido guardados correctamente';	
		//$accion = "limpiar";
	}else{
		$msg     = 'Capture una Ruta';
		//$accion  = "";
	}
}



if($accion == "limpiar" || $accion == "" ){
	$sqldetalle_eliminar_tmp = mysql_query("DELETE FROM catalogorutadetalletmp WHERE idusuario=$_SESSION[IDUSUARIO]",$link);
	$descripcion = "";
	$codigo      = "";
	$km			 = "";
	$recorridohrs = "";
	$recorridomin = "";
	$tipounidad  = "";
	$sucursal  	 = "";
	$sucursalb   = "";
	$carga		 = "";
	$descarga	 = "";
	$row		 = folio('catalogoruta','webpmm');
	$codigo 	 = $row[0];
	$fechahora   = "";
	$accion 	 = "";
	$hidensucursal="";
	$tipounidad_des="";
	
	$fecha = date("d/m/Y h:i");
	$f     = cambiaf_a_mysql($fecha);
	$hora  = date("H:i:s");	
	if($fechahora == ""){
	$fechahora = $f.' '.$hora;
	}
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Cat&aacute;logo Ruta</title>


<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<link href="FondoTabla.css" rel="stylesheet" type="text/css">
<link href="puntovta.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />

<script src="select.js"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/funciones_tablas.js"></script>



<script language="JavaScript" type="text/javascript">
	var tablainicio="";
	var u = document.all;
	var nav4 = window.Event ? true : false;

	function Numeros(evt){
		// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
		var key = nav4 ? evt.which : evt.keyCode; 
		return (key <= 13 || (key >= 48 && key <= 57));
	}

	function tiposMoneda(evnt,valor){
		caja = valor;
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
				return false;
			}else{
				if(charCode==46){
					if(caja.indexOf(".")>-1){
						return false;
					}
				}
			}
			return true;
		}
	}

	window.onload = function(){
		consulta("eliminarTemporal","catalogosrutas_Result.php?accion=9");
		u.descripcion.focus();
		obtenerDetalles();
	}
	function obtenerDetalles(){
			var datosTabla = <? if($codigo!=""){echo $codigo;}else{echo "0";} ?>;
			Ruta(datosTabla);
	}
	function eliminarTemporal(datos){}

function validar(){
var recorrido = document.all.recorridohrs.value+":"+document.all.recorridomin.value;
	if(document.all.descripcion.value == ""){
			alerta('Debe capturar Descripción', '¡Atención!','descripcion');
			return false;			
	}else if(recorrido == "00:00"){
			alerta('Debe capturar Tiempo Recorrido', '¡Atención!','recorridohrs');			
			return false;
	}else if(document.all.km.value == ""){
			alerta('Debe capturar KM', '¡Atención!','km');			
			return false;
	}else if(document.all.tipounidad.value == ""){
			alerta('Debe capturar Tipo Unidad', '¡Atención!','tipounidad');			
			return false;
	}else if(document.all.origen.value != 1){
			alerta('Debe capturar la ruta origen', '¡Atención!','sucursal');			
			return false;
	}else if(document.all.destino.value != 1){
			alerta('Debe capturar la ruta destino', '¡Atención!','sucursal');			
			return false;
	}
	
		if(document.all.accion.value == "" ){
			document.all.accion.value="grabar";
			document.form1.submit();
		}else if(document.all.accion.value == "modificar"){
			document.all.accion.value="modificar";
			document.form1.submit();
		}
}

function limpiar(){
	var u = document.all;
	u.descripcion.value = "";
	u.recorridohrs.value= "00";
	u.recorridomin.value= "00";
	u.km.value		    = "";
	u.tipounidad.value  = "";
	u.sucursal.value    = "";
	u.sucursalb.value   = "";
	u.rtipo[0].checked  = true;
	u.cargahrs.value	= "00";
	u.cargamin.value	= "00";
	u.descargahrs.value	= "00";
	u.descargamin.value	= "00";
	u.hllegada.value 	= "00";
	u.mllegada.value	= "00";
	u.hsalida.value 	= "00";
	u.msalida.value		= "00";
	
	
	u.hiddenrtipo.value = "";
	u.idfila.value		= "";
	u.idhidden.value	= "";
	document.all.accion.value = "limpiar";
	document.form1.submit();
}

	function obtenerTipoUnidadEnter(id){	
		consultaTexto("mostrarTipoUnidad","catalogoRutas_con.php?accion=2&unidad="+id+"&m="+Math.random());
	}
	function mostrarTipoUnidad(datos){
		if(datos!="0"){
			var obj = eval(convertirValoresJson(datos));
			u.tipounidad_des.value = obj[0].descripcion;
			u.sucursal.select();
		}else{
			alerta("El codigo de Tipo unidad no existe","¡Atención!","tipounidad");
		}
	}
	function obtenerTipoUnidad(id,des){		
		document.getElementById('tipounidad').value = id;
		document.getElementById('tipounidad_des').value	= des;
	}

	function obtenerSucursalEnter(id){		
		consultaTexto("mostrarSucursal","catalogoRutas_con.php?accion=3&sucursal="+id+"&m="+Math.random());
	}
	function mostrarSucursal(datos){
		if(datos!="0"){
			var obj = eval(convertirValoresJson(datos));
			u.sucursalb.value = obj[0].prefijo;
			u.rtipo[0].focus();
		}else{
			alerta("El codigo de la Sucursal no existe","¡Atención!","tipounidad");
		}
	}
function obtenerSucursal(id,descripcion){	
	u.sucursal.value  = id;
	u.sucursalb.value = descripcion;
}

//***************BUSQUEDA POR RUTAS****************//
function Ruta(id){
	//OBTIENE LAS RUTAS 
	consulta("mostrarRuta","consultas.php?accion=1&codigo="+id+"&sid="+Math.random());
}

function mostrarRuta(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var u = document.all;
		u.hidensucursal.value ="";
		if(tablainicio == ""){
			tablainicio=document.all.detalle.innerHTML;
		}
		document.all.detalle.innerHTML=tablainicio;
		reiniciar_indice(valt1);
		LimpiarCampos();      
		if(con>0){
			u.codigo.value     = datos.getElementsByTagName('id').item(0).firstChild.data;
			u.descripcion.value= datos.getElementsByTagName('descripcion').item(0).firstChild.data;
			var recorrido	   = datos.getElementsByTagName('recorrido').item(0).firstChild.data;
			var r= recorrido.split(":");
			u.recorridohrs.value=r[0];
			u.recorridomin.value=r[1];
			u.km.value		   = datos.getElementsByTagName('km').item(0).firstChild.data;
			u.tipounidad.value = datos.getElementsByTagName('tipounidad').item(0).firstChild.data;
			u.tipounidad_des.value = datos.getElementsByTagName('tipounidad_des').item(0).firstChild.data;
			u.antusuario.value = datos.getElementsByTagName('antusuario').item(0).firstChild.data;
		/**********/	
			if(datos.getElementsByTagName('todas').item(0).firstChild.data=="TODAS"){
				u.hidensucursal.value ="TODAS";
				u.todas.checked = true;	
				u.sucursalesead1.disabled=true;
				u.sucursalesead1_sel.disabled = true;
			}else{
				u.todas.checked = false;	
				u.sucursalesead1.disabled=false;
				u.sucursalesead1_sel.disabled = false;
			}
			var cansuc = datos.getElementsByTagName('cansuc').item(0).firstChild.data;	
				u.sucursalesead1_sel.options.length = 0;
				var opcion;
				for(var i=0; i<cansuc; i++){
					opcion = new Option(datos.getElementsByTagName('suc').item(i).firstChild.data,datos.getElementsByTagName('idsuc').item(i).firstChild.data);
					u.sucursalesead1_sel.options[u.sucursalesead1_sel.options.length] = opcion;
					if(datos.getElementsByTagName('todas').item(0).firstChild.data=="NO"){
						u.sucursalesead1.disabled		= false;
						u.sucursalesead1_sel.disabled 	= false;
						u.hidensucursal.value +=datos.getElementsByTagName('idsuc').item(i).firstChild.data+":"+datos.getElementsByTagName('suc').item(i).firstChild.data+",";
					}
				}
		/**********/		
			u.hiddenrtipo.value= datos.getElementsByTagName('hiddenrtipo').item(0).firstChild.data;
			u.accion.value     = datos.getElementsByTagName('accion').item(0).firstChild.data;
			u.fechahora.value  = datos.getElementsByTagName('fechahora').item(0).firstChild.data;
			u.origen.value     = 1;
			u.destino.value    = 1;
	
			var grid = datos.getElementsByTagName('grid').item(0).firstChild.data;
				if(grid!=0){
				var L 	 = grid.split(",");
			u.num.value	 =L.length;
					for(var i=0;i<L.length;i++){
						var G = L[i].split("/");
						//INSERTA EN LA GRID
						if(G[4]=="00:00"){G[4]="";}if(G[7]=="00:00"){G[7]="";}
						if(G[10]==1){G[10]="<img src='imagenes/tick.png' >"}else{G[10]=""}
						insertar_en_tabla(valt1,"<img src='imagenes/cross.png' onclick='EliminarFila(\"xxIDFILAxx\","+G[0]+","+G[1]+",4)'><img src='imagenes/tick.png' onclick='ModificarFila(\"xxIDFILAxx\","+G[0]+",2)'>" +"└"+G[2]+"└"+G[3]+"└"+G[4]+"└"+G[5]+"└"+G[6]+"└"+G[7]+"└"+G[8]+"└"+G[10]); 
			}
			
		}
		//-------------
		G = L[0].split("/");
		semana = G[2].split("-");
			for(var f=1;f<=7;f++){
				for(var i=0;i<semana.length;i++){
					if(document.getElementById('checkbox'+f).value==semana[i] ){
						document.getElementById('checkbox'+f).checked=true;
						break;
					}else{
						document.getElementById('checkbox'+f).checked=false;
					}
				}
			}
		//-----------
	}else{
			/*alerta("La Ruta No Existe",'¡Atención!','descripcion');
			u.descripcion.focus();*/
	}
}

//******/***************/

function trim(cadena,caja)
{
	for(i=0;i<cadena.length;)
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(i+1, cadena.length);
		else
			break;
	}

	for(i=cadena.length-1; i>=0; i=cadena.length-1)
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(0,i);
		else
			break;
	}
	
	document.getElementById(caja).value=cadena;
}

function tabular(e,obj){
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
            else frm.elements[i+1].focus();
            return false;
} 



function inhabilita(){
	var u = document.all;
	if(document.form1.rtipo[0].checked == true){
		u.hllegada.disabled = true;
		u.mllegada.disabled = true;
		u.hsalida.disabled  = false;
		u.msalida.disabled	= false;
		
		u.cargahrs.disabled = false;
		u.cargamin.disabled = false;
		u.ttsshrs.disabled	= false;
		u.ttssmin.disabled	= false;
		u.descargahrs.disabled=true;
		u.descargamin.disabled=true;

		u.hllegada.value = "00";
		u.mllegada.value = "00";
		u.cargahrs.value = "00";
		u.cargamin.value = "00";
		u.ttsshrs.value	 = "00";
		u.ttssmin.value	 = "00";	
			
		document.getElementById('checkbox1').disabled = false; 
		document.getElementById('checkbox2').disabled = false; 
		document.getElementById('checkbox3').disabled = false; 
		document.getElementById('checkbox4').disabled = false; 
		document.getElementById('checkbox5').disabled = false; 
		document.getElementById('checkbox6').disabled = false; 
		document.getElementById('checkbox7').disabled = false; 

		document.all.transbordo.checked=false;
		document.all.transbordo.value=0;
	}
	if(document.form1.rtipo[1].checked == true){
		u.hllegada.disabled = false;
		u.mllegada.disabled = false;
		u.hsalida.disabled  = false;
		u.msalida.disabled	= false;
		
		u.cargahrs.disabled = false;
		u.cargamin.disabled = false;
		u.ttsshrs.disabled	= false;
		u.ttssmin.disabled	= false;
		u.descargahrs.disabled=false;
		u.descargamin.disabled=false;
		
		document.getElementById('checkbox1').disabled = true; 
		document.getElementById('checkbox2').disabled = true; 
		document.getElementById('checkbox3').disabled = true; 
		document.getElementById('checkbox4').disabled = true; 
		document.getElementById('checkbox5').disabled = true; 
		document.getElementById('checkbox6').disabled = true; 
		document.getElementById('checkbox7').disabled = true; 

		document.all.transbordo.checked=false;
		document.all.transbordo.value=0;	
	}
	if(document.form1.rtipo[2].checked == true){
		u.hllegada.disabled = false;
		u.mllegada.disabled = false;
		u.hsalida.disabled  = true;
		u.msalida.disabled	= true;
		
		u.cargahrs.disabled = true;
		u.cargamin.disabled = true;
		u.ttsshrs.disabled	= true;
		u.ttssmin.disabled	= true;
		u.descargahrs.disabled=false;
		u.descargamin.disabled=false;	
		
		u.hsalida.value  = "00";
		u.msalida.value	 = "00";
		u.cargahrs.value = "00";
		u.cargamin.value = "00";
		u.ttsshrs.value	 = "00";
		u.ttssmin.value	 = "00";	
			
		document.getElementById('checkbox1').disabled = true; 
		document.getElementById('checkbox2').disabled = true; 
		document.getElementById('checkbox3').disabled = true; 
		document.getElementById('checkbox4').disabled = true; 
		document.getElementById('checkbox5').disabled = true; 
		document.getElementById('checkbox6').disabled = true; 
		document.getElementById('checkbox7').disabled = true; 
		

	}
	
}

function LimpiarCampos(){
	var u = document.all;
	u.sucursal.value 	= "";
	u.sucursalb.value	= "";
	u.cargahrs.value 	="00";
	u.cargamin.value 	="00";
	u.descargahrs.value	="00";
	u.descargamin.value	="00";
	u.hllegada.value 	= "00";
	u.mllegada.value 	= "00";
	u.hsalida.value  	= "00";
	u.msalida.value	 	= "00";
	u.ttsshrs.value	 	= "00";
	u.ttssmin.value	 	= "00";	
	u.hiddenrtipo.value ="";
	u.idfila.value   	= ""; 
	u.idhidden.value	= "";
	u.sucursalesead12.value="";
	u.todas2.value		="";
	u.hidensucursal2.value="";
	u.sucursalesead1_sel2.options.length = 0;
	u.transbordo.checked=false;
	u.sucursalestransbordo.style.visibility="hidden";
}

function limpiarSucursalCampo(){
		var u = document.all;
		u.sucursalesead12.value="";
		u.todas2.checked=false;
		u.hidensucursal2.value="";
		u.sucursalesead1_sel2.options.length = 0;
}
	

//*********************************************//
function agregar(){
	var u = document.all;
	var semana = "";
	var valorSeleccionado; 
	var horallegada = u.hllegada.value +":"+ u.mllegada.value;
	var horasalida  = u.hsalida.value  +":"+ u.msalida.value;
	var carga		= u.cargahrs.value +":"+ u.cargamin.value;
	var descarga 	= u.descargahrs.value +":"+ u.descargamin.value;
	var ttss 		= u.ttsshrs.value +":"+ u.ttssmin.value;
	//CHECANDO LOS RADIOBUTTON
	var elementos = document.getElementsByName("rtipo");
	for(var i=0; i<elementos.length; i++) {
		if(elementos[i].checked) {
			valorSeleccionado = elementos[i].value;
		}
	}
	
	//VALIDANDO LOS CAMPOS GRID
	if(u.rtipo[0].checked == true && u.origen.value != "" && u.idhidden.value == ""){
		alerta('Ya existe un origen', '¡Atención!','sucursal'); 
		return false;
	}else if(u.rtipo[0].checked == true && u.origen.value == 1 && u.hiddenrtipo.value!= 1){
		alerta('Ya existe un origen', '¡Atención!','sucursal'); 
		return false;
	}else if(u.rtipo[0].checked != true && u.origen.value == 1 && u.idfila.value == "tfx_0" && u.hiddenrtipo.value== 1){
		alerta('No se puede modificar el tipo de ruta', '¡Atención!','sucursal'); 
		return false;
	}else if(u.rtipo[2].checked == true && u.destino.value != "" && u.idhidden.value == ""){
		alerta('Ya existe un destino', '¡Atención!','sucursal'); 
		return false;
	}else if(u.rtipo[1].checked != true && u.origen.value == 1 && u.destino.value == 1 && u.hiddenrtipo.value==2 && u.idfila.value!="" ){
		alerta('No puede modificar el tipo de ruta', '¡Atención!','sucursal'); 
		return false;
	}else if(u.rtipo[1].checked == true && u.destino.value != "" && u.idhidden.value == ""){
		alerta('Después de capturar un destino, no puede insertar intermedio', '¡Atención!','sucursal'); 
		return false;
	}else if(u.rtipo[1].checked == true && u.origen.value == ""  && u.idhidden.value == ""){
		alerta('Primero capture un origen', '¡Atención!','sucursal'); 
		return false;
	}else if(u.idhidden.value==2 && u.rtipo[0].checked==true && u.origen.value == 1){
		alerta('Ya existe un origen', '¡Atención!','sucursal'); 
		return false;
	}else if(u.rtipo[2].checked == true && u.origen.value == ""  && u.idhidden.value == ""){
		alerta('Primero capture un origen', '¡Atención!','sucursal'); 
		return false;
	}else if(u.tipounidad.value == ""){
		alerta('Capture Tipo Unidad', '¡Atención!','tipounidad'); 
		return false;
	}else if(u.sucursal.value == ""){
		alerta('Capture Sucursal', '¡Atención!','sucursal'); 
		return false;
	}else if( u.rtipo[0].checked == true && ( document.getElementById('checkbox1').checked==false && document.getElementById('checkbox2').checked == false && document.getElementById('checkbox3').checked == false && document.getElementById('checkbox4').checked==false && document.getElementById('checkbox5').checked == false && document.getElementById('checkbox6').checked == false   && document.getElementById('checkbox7').checked == false) ){
		alerta('Capture Días de Salida', '¡Atención!','checkbox1'); 
		return false;
	}else if(u.rtipo[0].checked == true && carga == "00:00"){
		alerta('Capture Tiempo Carga', '¡Atención!','cargahrs'); 
		return false;
	}else if(u.rtipo[1].checked == true && carga == "00:00"){
		alerta('Capture Tiempo Carga', '¡Atención!','cargahrs'); 
		return false;
	}else if(u.rtipo[1].checked == true && descarga =="00:00"){
		alerta('Capture Tiempo Descarga', '¡Atención!','descargahrs'); 
		return false;
	}else if(u.rtipo[2].checked == true && descarga =="00:00"){
		alerta('Capture Tiempo Descarga', '¡Atención!','descargahrs'); 
		return false;
	}else if(u.rtipo[0].checked == true && ttss == "00:00"){
		alerta('Capture Tiempo Trayecto Siguiente Sucursal', '¡Atención!','ttsshrs');    
		return false;
	}else if(u.rtipo[1].checked == true && ttss == "00:00"){
		alerta('Capture Tiempo Trayecto Siguiente Sucursal', '¡Atención!','ttsshrs');    
		return false;
	}else if(u.rtipo[0].checked == true && horasalida  == "00:00" ){
		alerta('Capture Hora Salida', '¡Atención!','hsalida'); 
		return false;
	}else if(u.rtipo[0].checked == true && horasalida == "00:00"){
		alerta('Capture Hora Salida', '¡Atención!','hsalida'); 
		return false;
	}else if(u.rtipo[1].checked == true && horallegada == "00:00"){
		alerta('Capture Hora Llegada', '¡Atención!','hllegada'); 
		return false;
	}else if(u.rtipo[1].checked == true && horasalida  == "00:00"){
		alerta('Capture Hora Salida', '¡Atención!','hsalida'); 
		return false;
	}else if(u.rtipo[2].checked == true && horallegada == "00:00"){
		alerta('Capture Hora Llegada', '¡Atención!','hllegada'); 
		return false;
	}else if(u.transbordo.checked==true && u.sucursalesead1_sel2.options.length == 0){
		alerta('Capture Sucursal Transbordo', '¡Atención!','transbordo'); 
		return false;
	}else{
	
		if(u.rtipo[0].checked==true){
		//CONCATENANDO LOS DIAS DE LA SEMANA
			for(var i=1;i<=7;i++){
				if (document.getElementById('checkbox'+i).checked == true) {
					semana += document.getElementById('checkbox'+i).value +"-";
				}
			}
		semana=semana.substr(0,semana.length-1);
	 	}else{semana="";}
		
		if(u.idhidden.value == ""){
			
			//u.fecha.value = fechahora(u.fecha.value);
			// PARA INSERTAR  EL ROW.
			  	   //INSERTAR UNA CONSULTA NUEVA EN LA TABLA DETALLE TEMPORAL
				   consulta("agregarfilas","catalogosrutas_Result.php?rtipo="+valorSeleccionado+"&semana="+semana+"&sucursal="+u.sucursal.value+"&sucursalb="+u.sucursalb.value+"&llegada="+horallegada+"&descarga="+descarga+"&carga="+carga+"&salida="+horasalida+"&ttss="+ttss+"&transbordo="+u.transbordo.value+"&hidensucursal2="+u.hidensucursal2.value+"&tipo="+1+"&fecha="+u.fechahora.value+"&sid="+Math.random());
				
				  	 if(u.rtipo[0].checked == true &&  u.origen.value == ""){
			  			u.origen.value=1;
				 	 }else if(u.rtipo[2].checked == true &&  u.destino.value == ""){
						u.destino.value=1;
					 }
			
		}else {
		
				//PARA EL MODIFICAR  LA ROW.
					 //PARA MODIFICAR LA GRID EN TEMPORALES "CATALOGORUTADETALLETEMP"
					consulta("agregarfilas","catalogosrutas_Result.php?rtipo="+valorSeleccionado+"&semana="+semana+"&sucursal="+u.sucursal.value+"&sucursalb="+u.sucursalb.value+"&llegada="+horallegada+"&descarga="+descarga+"&carga="+carga+"&salida="+horasalida+"&ttss="+ttss+"&transbordo="+u.transbordo.value+"&hidensucursal2="+u.hidensucursal2.value+"&id="+u.idhidden.value+"&tipo="+3+"&fecha="+u.fechahora.value+"&sid="+Math.random());
					if(u.rtipo[0].checked == true &&  u.origen.value == ""){
			  			u.origen.value=1;
				 	 }else if(u.rtipo[2].checked == true &&  u.destino.value == ""){
						u.destino.value=1;
					 }else if(u.rtipo[1].checked == true && u.destino.value!="" && u.hiddenrtipo.value == 3){
						 u.destino.value="";
					 }else if(u.rtipo[2].checked == true && u.destino.value == "" && u.hiddenrtipo.value == 2){
						 u.destino.value=1;
					 }
			}
 	  }
}

//************************************//
function agregarfilas(datos){
		var u  = document.all;
		var semana = datos.getElementsByTagName('semana').item(0).firstChild.data;
		if(semana == 0){semana = "";}
		var id              = datos.getElementsByTagName('id').item(0).firstChild.data;
		u.hiddenrtipo.value = datos.getElementsByTagName('rtipo').item(0).firstChild.data;
		u.sucursal.value	= datos.getElementsByTagName('sucursal').item(0).firstChild.data;
		u.sucursalb.value	= datos.getElementsByTagName('sucursalb').item(0).firstChild.data;
		var horallegada		= datos.getElementsByTagName('llegada').item(0).firstChild.data;
		if(horallegada == "00:00"){horallegada = "";}
		var descarga 	= datos.getElementsByTagName('descarga').item(0).firstChild.data;
		if(descarga == "00:00"){descarga = "";}
		var carga   	= datos.getElementsByTagName('carga').item(0).firstChild.data;
		if(carga == "00:00"){carga = "";}
		var horasalida  	= datos.getElementsByTagName('salida').item(0).firstChild.data;
		if(horasalida == "00:00"){horasalida = "";}
		var ttss   		= datos.getElementsByTagName('ttss').item(0).firstChild.data;
		
		
	  	if(u.transbordo.value==1){transbordo="<img src='imagenes/tick.png' >"}else{transbordo="";}
		if(u.idhidden.value == "") {
			//INSERTA EN LA TABLA
				insertar_en_tabla(valt1,"<img src='imagenes/cross.png' onclick='EliminarFila(\"xxIDFILAxx\","+id+","+u.hiddenrtipo.value+",4)'><img src='imagenes/tick.png' onclick='ModificarFila(\"xxIDFILAxx\","+id+",2)'>" +"└"+semana+"└"+u.sucursalb.value+"└"+horallegada+"└"+descarga+"└"+carga+"└"+horasalida+"└"+ttss+"└"+transbordo);
				
				LimpiarCampos();
				var num=parseInt(u.num.value);
				u.num.value=++num;
					
		}else{
			//MODIFICA EN LA TABLA
			modificar_fila(valt1, u.idfila.value,"<img src='imagenes/cross.png' onclick='EliminarFila(\""+u.idfila.value+"\","+id+","+u.hiddenrtipo.value+",4)'><img src='imagenes/tick.png' onclick='ModificarFila(\""+u.idfila.value+"\","+id+",2)'>" +"└"+semana+"└"+u.sucursalb.value+"└"+horallegada+"└"+descarga+"└"+carga+"└"+horasalida+"└"+ttss+"└"+transbordo);
			LimpiarCampos();
		}

}


//*************MUESTRA LOS DATOS DE LA GRID EN LAS CAJAS DE TEXTO*********************//
function ModificarFila(idfila,id,tipo){
	consulta("mostrarModificarFila","catalogosrutas_Result.php?tipo="+tipo+"&idfila="+idfila+"&id="+id+"&sid="+Math.random());
}

function mostrarModificarFila(datos){
		var u               = document.all;
		var semana          = datos.getElementsByTagName('semana').item(0).firstChild.data;
		u.idhidden.value    = datos.getElementsByTagName('id').item(0).firstChild.data;
		u.idfila.value      = datos.getElementsByTagName('idfila').item(0).firstChild.data;
		u.hiddenrtipo.value = datos.getElementsByTagName('rtipo').item(0).firstChild.data;
		if(datos.getElementsByTagName('rtipo').item(0).firstChild.data == 1){
			document.form1.rtipo[0].checked = true;
			semana = semana.split("-");
			for(var f=1;f<=7;f++){
				for(var i=0;i<semana.length;i++){
					if(document.getElementById('checkbox'+f).value==semana[i] ){
						document.getElementById('checkbox'+f).checked=true;
						break;
					}else{
						document.getElementById('checkbox'+f).checked=false;
					}
				}
			}
			inhabilita();
		}else if(datos.getElementsByTagName('rtipo').item(0).firstChild.data == 2){
			document.form1.rtipo[1].checked = true;
			inhabilita();
		}else if(datos.getElementsByTagName('rtipo').item(0).firstChild.data == 3){
			document.form1.rtipo[2].checked = true;
			inhabilita();
		}
		
		u.sucursal.value = datos.getElementsByTagName('sucursal').item(0).firstChild.data;
		u.sucursalb.value= datos.getElementsByTagName('sucursalb').item(0).firstChild.data;
		var horallegada  = datos.getElementsByTagName('llegada').item(0).firstChild.data;
		llegada = horallegada.split(":");
		u.hllegada.value 	= llegada[0];
		u.mllegada.value 	= llegada[1];

		var descarga = datos.getElementsByTagName('descarga').item(0).firstChild.data;
		des_carga = descarga.split(":");
		u.descargahrs.value	= des_carga[0];
		u.descargamin.value	= des_carga[1];		
		var carga  = datos.getElementsByTagName('carga').item(0).firstChild.data;
		carga_s = carga.split(":");
		u.cargahrs.value = carga_s[0];
		u.cargamin.value = carga_s[1];	
		var horasalida   = datos.getElementsByTagName('salida').item(0).firstChild.data;
		salida = horasalida.split(":");
		u.hsalida.value 	= salida[0];
		u.msalida.value 	= salida[1];
		var ttss     = datos.getElementsByTagName('ttss').item(0).firstChild.data;
		ttss_s = ttss.split(":");
		u.ttsshrs.value 	= ttss_s[0];
		u.ttssmin.value 	= ttss_s[1];
		var transbordo     = datos.getElementsByTagName('transbordo').item(0).firstChild.data;
		if(transbordo==1){
			u.transbordo.value=1;
			u.transbordo.checked=true;
			u.sucursalestransbordo.style.visibility="visible";	
			u.hidensucursal2.value   = datos.getElementsByTagName('hidensucursal2').item(0).firstChild.data;
		
		/**********/	
			if(datos.getElementsByTagName('hidensucursal2').item(0).firstChild.data=="TODAS"){
				u.hidensucursal2.value ="TODAS";
				u.todas2.checked = true;	
				u.sucursalesead12.disabled=true;
				u.sucursalesead1_sel2.disabled = true;
				agregarTodasSucursales2();
			}else{
				u.todas2.checked = false;	
				u.sucursalesead12.disabled=false;
				u.sucursalesead1_sel2.disabled = false;
			
			var suc = datos.getElementsByTagName('hidensucursal2').item(0).firstChild.data;	
			var cansuc = suc.split(",");
			
			u.sucursalesead1_sel2.options.length = 0;
			var opcion;
				for(var i=0; i<cansuc.length; i++){
					var sucursales=cansuc[i].split(":");
					opcion = new Option(sucursales[1],sucursales[0]);
					u.sucursalesead1_sel2.options[u.sucursalesead1_sel2.options.length] = opcion;
					if(datos.getElementsByTagName('hidensucursal2').item(0).firstChild.data!="TODAS"){
						u.sucursalesead12.disabled		= false;
						u.sucursalesead1_sel2.disabled 	= false;
						//u.hidensucursal.value +=sucursales[0]+":"+sucursales[1]+",";
					}
				}
			}
			
		}else{
			u.transbordo.checked=false;
			u.transbordo.value=0;
			u.sucursalestransbordo.style.visibility="hidden";	
		}
		/**********/		
}
//***************************************//


/*********ELIMINAR FILA********************/
function EliminarFila(idfila,id,radiotipo,tipo){
	var u =document.all;
	var num =u.num.value-1;
	if(radiotipo==1 && u.destino.value != ""){
		alerta('No puedes eliminar el origen, es necesario eliminar primero transbordo y el destino', '¡Atención!','sucursal'); 
		return false;
	}else if(idfila=="tfx_0" && u.num.value != 1){
		alerta('No puedes eliminar el origen, es necesario eliminar primero intermedio y el destino', '¡Atención!','sucursal');
		return false;
	}else if(radiotipo == 2 && u.destino.value != "" ){
		alerta('No puedes eliminar intermedio, es necesario eliminar primero el destino', '¡Atención!','sucursal'); 
		return false;
	}else{
		if(radiotipo==1 && u.destino.value == ""){u.origen.value="";}
		if(radiotipo==3) {u.destino.value=""; u.num.value=u.num.value-1;
		}else if(radiotipo==2 && u.destino.value == "") {u.num.value=u.num.value-1;}
		consulta("mostrarEliminarFila","catalogosrutas_Result.php?tipo="+tipo+"&idfila="+idfila+"&id="+id+"&sid="+Math.random());
	}
}


function mostrarEliminarFila(datos){
	var u       = document.all;
	LimpiarCampos();
	var fila  = datos.getElementsByTagName('idfila').item(0).firstChild.data;
	borrar_fila_tabla(valt1, fila);
}
//*******************************//


/**************************/
	//funciones para los combos
	function insertarServicio(combo, valor, va, nombre, tipo){
		var u = document.all;
		if(combo.value!=""){
			for(var i=0; i<va.options.length; i++){
				if(va.options[i].value==valor){
					alerta3(nombre+" seleccionado ya fue agregado","¡Atencion!");
					combo.value="";
					return false;
				}
			}
			var opcion = new Option(combo.options[combo.selectedIndex].text,combo.value);
			va.options[va.options.length] = opcion;
			u.hidensucursal.value +=combo.options[combo.selectedIndex].value+":"+combo.options[combo.selectedIndex].text+",";
			combo.value="";
		}
	}
/***********************/
	function borrarServicio(va,tipo){
		var u = document.all;
		if(va.options.selectedIndex>-1){			
		var frase = u.hidensucursal.value.replace(u.sucursalesead1_sel.value+":"+u.sucursalesead1_sel.options[u.sucursalesead1_sel.selectedIndex].text,"");
			u.hidensucursal.value = frase.replace(",,",",");
			if(u.hidensucursal.value.substring(0,1)==","){
				u.hidensucursal.value = u.hidensucursal.value.substring(1,u.hidensucursal.value.legth);
			}
			va.options[va.options.selectedIndex] = null;
			va.value = "";
		}
	}
	


function agregarTodasSucursales(){
	var u = document.all;
	if(u.todas.checked==true){
		u.hidensucursal.value = "TODAS";
		u.sucursalesead1_sel.options.length = 0;
		for(var i=1; i<u.sucursalesead1.options.length; i++){
			var opcion = new Option(u.sucursalesead1.options[i].text,u.sucursalesead1.value);
			u.sucursalesead1_sel.options[u.sucursalesead1_sel.options.length] = opcion;		
		}
		u.sucursalesead1.disabled=true;
		u.sucursalesead1_sel.disabled = true;
	}else{
		u.sucursalesead1_sel.options.length = 0;
		u.hidensucursal.value = "";
		u.sucursalesead1.disabled=false;
		u.sucursalesead1_sel.disabled = false;
	}
}	
/***********************/

	function respServicio(res){
		if(res.indexOf("bien")<0){
			alerta3(res,"Error");
		}
	}
/**************************/

	//funciones para los combos
	function insertarServicio2(combo, valor, va, nombre, tipo){
		var u = document.all;
		if(combo.value!=""){
			for(var i=0; i<va.options.length; i++){
				if(va.options[i].value==valor){
					alerta3(nombre+" seleccionado ya fue agregado","¡Atencion!");
					combo.value="";
					return false;
				}
			}
			var opcion = new Option(combo.options[combo.selectedIndex].text,combo.value);
			va.options[va.options.length] = opcion;
			u.hidensucursal2.value +=combo.options[combo.selectedIndex].value+":"+combo.options[combo.selectedIndex].text+",";
			combo.value="";
		}
	}
	
	
	function borrarServicio2(va,tipo){
		var u = document.all;
		if(va.options.selectedIndex>-1){			
		var frase = u.hidensucursal2.value.replace(u.sucursalesead1_sel2.value+":"+u.sucursalesead1_sel2.options[u.sucursalesead1_sel2.selectedIndex].text,"");
			u.hidensucursal2.value = frase.replace(",,",",");
			if(u.hidensucursal2.value.substring(0,1)==","){
				u.hidensucursal2.value = u.hidensucursal2.value.substring(1,u.hidensucursal2.value.legth);
			}
			va.options[va.options.selectedIndex] = null;
			va.value = "";
		}
	}

	function agregarTodasSucursales2(){
		var u = document.all;
		if(u.todas2.checked==true){
			u.hidensucursal2.value = "TODAS,";
			u.sucursalesead1_sel2.options.length = 0;
			for(var i=1; i<u.sucursalesead12.options.length; i++){
				var opcion = new Option(u.sucursalesead12.options[i].text,u.sucursalesead12.value);
				u.sucursalesead1_sel2.options[u.sucursalesead1_sel2.options.length] = opcion;		
			}
			u.sucursalesead12.disabled=true;
			u.sucursalesead1_sel2.disabled = true;
		}else{
			u.sucursalesead1_sel2.options.length = 0;
			u.hidensucursal2.value = "";
			u.sucursalesead12.disabled=false;
			u.sucursalesead1_sel2.disabled = false;
		}
	}	
/***********/
</script>


<style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style3 {	font-size: 9px;
	color: #464442;
}
.style5 {color: #FFFFFF ; font-size:9px}
-->
.Balance {background-color: #FFFFFF; border: 0px none;font-size: 9px;}
.Balance2 {background-color: #DEECFA; border: 0px none;font-size: 9px;}

<!--
.Estilo1 {
	font-size: 9px;
	font-weight: bold;
	color: #FFFFFF;
}
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
-->
</style>
</head>

<body>
<form name="form1" method="post">
  <p>&nbsp;</p>
  <table width="620" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td class="FondoTabla">Datos Generales</td>
  </tr>
  <tr>
    <td>
        <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="3" ><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
            <input name="fechahora" type="hidden" id="fechahora" value="<?=$fechahora ?>">
            <input name="antusuario" type="hidden" id="antusuario" value="<?=$antusuario ?>"></td>
          </tr>
          <tr>
            <td colspan="2" class="Tablas">C&oacute;digo:
              <input name="codigo" type="text" id="codigo" value="<?=$codigo ?>" style=" width:80PX;background:#FFFF99;" readonly=""  onKeyDown="return tabular(event,this)" class="Tablas" />
            <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('catalogosrutas_Buscar.php?tipo=3', 600, 500, 'ventana', 'Busqueda')"></td>
<td width="367" valign="bottom" class="Tablas">&nbsp;</td>
          </tr>
          <tr>
            <td class="Tablas">Descripcion:</td>
            <td colspan="2" class="Tablas"><input class="Tablas" name="descripcion" type="text" id="descripcion"  onBlur="trim(document.getElementById('descripcion').value,'descripcion');" value="<?=$descripcion ?>" style="text-transform:uppercase;width:500px" onKeyDown="return tabular(event,this)" /></td>
</tr>
          <tr>
            <td width="79" class="Tablas">T. Recorrido:           </td>
            <td width="164" class="Tablas"><select name="recorridohrs" size="1" class="Tablas" id="recorridohrs">
              <? for($h=0;$h<=200;$h++){ ?>
              <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
              <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
              </option>
              <? }?>
            </select>
Hrs
<select name="recorridomin" size="1" class="Tablas" id="select4">
  <? for($m=0;$m<60;$m++){ ?>
  <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
  <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
  </option>
  <? }?>
</select>
Min </td>
            <td valign="top" class="Tablas">KM:
            <input name="km" type="text" id="km"  class="Tablas" style="width:150px" onBlur="trim(document.getElementById('km').value,'km');" onKeyPress="return tiposMoneda(event,this.value);" onKeyDown="return tabular(event,this)" value="<?=$km ?>" size="10" maxlength="10" /></td>
          </tr>
          
          <tr>
            <td colspan="3" class="FondoTabla">D&iacute;as de Salida </td>
          </tr>
          <tr>
            <td colspan="3" class="Tablas"><label>
              <input type="checkbox" name="checkbox1" value="L" />
            L</label>
              <label>
              <input type="checkbox" name="checkbox2" value="M" />
            M</label>
              <label>
              <input type="checkbox" name="checkbox3" value="MI" />
            MI</label>
              <label>
              <input type="checkbox" name="checkbox4" value="J" />
            J</label>
              <label>
              <input type="checkbox" name="checkbox5" value="V" />
            V</label>
              <label>
              <input type="checkbox" name="checkbox6" value="S" />
            S
            <input type="checkbox" name="checkbox7" value="D" />
            D              </label></td>
          </tr>
          <tr>
            <td colspan="3" class="Tablas"><label></label></td>
          </tr>
          <tr>
            <td class="Tablas">Tipo Unidad</td>
            <td class="Tablas"><input class="Tablas" name="tipounidad" type="text" id="tipounidad" style="width:80px" value="<?=$tipounidad ?>" onKeyPress="if(event.keyCode==13){ obtenerTipoUnidadEnter(this.value);}" />
            <img src="../../img/Buscar_24.gif" alt="R" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('catalogosrutas_Buscar.php?tipo=1', 600, 500, 'ventana', 'Busqueda')" /></td>
            <td class="Tablas"><input name="tipounidad_des"  class="Tablas" type="text" id="tipounidad_des" style="background-color:#FFFF99; text-transform:uppercase;width:350px" value="<?=$tipounidad_des ?>" readonly="" /></td>
          </tr>
          <tr>
            <td class="Tablas"><label>Sucursal</label></td>
            <td class="Tablas"><input name="sucursal"  class="Tablas" type="text" id="sucursal" style="width:80px" value="<?=$sucursal ?>" onKeyPress="if(event.keyCode==13){ obtenerSucursalEnter(this.value);}"/>
              <img src="../../img/Buscar_24.gif" alt="R" width="24" height="23" align="absbottom" style="cursor:pointer" title="Buscar Sucursal" onClick="abrirVentanaFija('catalogosrutas_Buscar.php?tipo=2', 600, 500, 'ventana', 'Busqueda')" /></td>
            <td class="Tablas"><input name="sucursalb"  class="Tablas" type="text" id="sucursalb" style="background-color:#FFFF99; text-transform:uppercase;;width:350px" value="<?=$sucursalb ?>" readonly="" /></td>
          </tr>
          
          <tr>
            <td colspan="3" class="Tablas"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="71%"><label>
                  <input name="rtipo" type="radio" onClick="inhabilita();" value='1' checked >
Origen
<input type="radio"  name="rtipo"   value='2' onClick="inhabilita();"  >
Intermedio
<input type="radio" name="rtipo"  value='3' onClick="inhabilita();"  >
Destino</label>
                  <input name="transbordo" type="checkbox" id="transbordo"   value="0" onClick="if(document.all.transbordo.checked==true){document.all.transbordo.value=1;document.all.sucursalestransbordo.style.visibility='visible';limpiarSucursalCampo();}else{document.all.transbordo.value=0;document.all.sucursalestransbordo.style.visibility='hidden';limpiarSucursalCampo()}">
                  <label id="transbordolabel" >Transbordo</label></td>
                <td width="29%" rowspan="4"><table id="sucursalestransbordo" width="177" border="0" align="right" cellpadding="0" cellspacing="0" style="visibility:hidden">
                  <tr>
                    <td width="7" height="16"   class="formato_columnas_izq"></td>
                    <td width="169"class="formato_columnas" align="center"><div align="center">SUCURSALES </div></td>
                    <td width="1"class="formato_columnas_der"></td>
                  </tr>
                  <tr>
                    <td colspan="12"><table width="177" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td colspan="12" class="Tablas"><input name="todas2" type="checkbox" id="todas2" onClick="agregarTodasSucursales2();">
                            Todos
                            <select name="sucursalesead12"  class="Tablas" id="sucursalesead12" style="width:100px" onChange="insertarServicio2(this, this.value, document.all.sucursalesead1_sel2, 'La Sucursal', 'SUCONVENIO')")>
                <option value=""></option>
                <? 
					$s = "select * from catalogosucursal where id > 1";
					$r = mysql_query($s,$link) or die($s);
					while($f = mysql_fetch_object($r)){
				?>
                <option value="<?=$f->id?>">
                <?=cambio_texto($f->descripcion)?>
                </option>
                <?
					}
				?>
              </select>
              <input name="hidensucursal2" type="hidden" id="hidensucursal2"></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td colspan="12">
                      <div align="center">
                        <select name="sucursalesead1_sel2" size="4" id="sucursalesead1_sel2" style="width:150px" onDblClick="borrarServicio2(this, 'SUCONVENIO')">
                        </select>
                        </div></td></tr>
                </table></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="49%"><label>Tiempo Carga
                        
                    </label>
                      <label>
                      <select name="cargahrs" size="1" class="Tablas" id="cargahrs">
                        <? for($h=0;$h<=24;$h++){ ?>
                        <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                        <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                        </option>
                        <? }?>
                      </select>
                      Hrs
                      <select name="cargamin" size="1" class="Tablas" id="select2">
                        <? for($m=0;$m<60;$m++){ ?>
                        <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
                        <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                        </option>
                        <? }?>
                      </select>
                      Min
                      </label></td>
                    <td width="51%">Tiempo Descarga
                      <select name="descargahrs" size="1" class="Tablas" id="descargahrs" disabled="disabled">
                        <? for($h=0;$h<=24;$h++){ ?>
                        <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                        <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                        </option>
                        <? }?>
                      </select>
                      Hrs
                      <select name="descargamin" size="1" class="Tablas" id="descargamin" disabled="disabled">
                        <? for($m=0;$m<60;$m++){ ?>
                        <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
                        <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                        </option>
                        <? }?>
                      </select>
                      Min</td>
                  </tr>
                </table></td>
                </tr>
              <tr>
                <td colspan="1">Tiempo Trayecto Siguiente Sucursal                  
                  <select name="ttsshrs" size="1" class="Tablas" id="ttsshrs">
                    <? for($h=0;$h<=24;$h++){ ?>
                    <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                    <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                    </option>
                    <? }?>
                    </select>
                  Hrs
                  <select name="ttssmin" size="1" class="Tablas" id="select3">
                    <? for($m=0;$m<60;$m++){ ?>
                    <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
                    <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                    </option>
                    <? }?>
                  </select>
                  Min</td>
                </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="48%"><label>Hora Llegada</label>
                      <label>
                      <select name="hllegada" size="1" class="Tablas" id="hllegada" disabled>
                        <? 	for($h=0;$h<=24;$h++){ ?>
                        <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                          <?= str_pad($h,2,"0",STR_PAD_LEFT);?>
                          </option>
                        <? }?>
                      </select>
                      <select name="mllegada" size="1" class="Tablas" id="mllegada" disabled>
                        <? for($m=0;$m<60;$m++){ ?>
                        <option value="<?= str_pad($m,2,"0",STR_PAD_LEFT);?>">
                          <?= str_pad($m,2,"0",STR_PAD_LEFT);?>
                          </option>
                        <? }?>
                      </select>
                      </label></td>
                    <td width="52%">Hora Salida
                      <select name="hsalida" size="1" class="Tablas" id="hsalida">
                        <? for($h=0;$h<=24;$h++){ ?>
                        <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                        <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                        </option>
                        <? }?>
                      </select>
                      <select name="msalida" size="1" class="Tablas" id="msalida">
                        <? for($m=0;$m<60;$m++){ ?>
                        <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
                        <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                        </option>
                        <? }?>
                      </select></td>
                  </tr>
                </table></td>
                </tr>
            </table></td>
          </tr>
          
          <tr>
            <td colspan="3"><div align="right"><span class="Tablas">
              <input name="num" type="hidden" id="num" value="0" size="5">
              <input name="origen" type="hidden" id="origen" size="5">
              <input name="destino" type="hidden" id="destino" size="5">
              <input name="hiddenrtipo" type="hidden" id="hiddenrtipo" size="5" >
              <input type="hidden" name="idfila" id="idfila" size="5" >
              <input type="hidden" name="idhidden" id="idhidden">
            <img src="../../img/Boton_Agregari.gif" alt="g" title="Guardar" width="70" height="20" onClick="agregar();" style="cursor:pointer"/></span></div></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
          </tr>
          
          <tr>
            <td colspan="3"><table width="600" border="0" align="center" cellpadding="0" cellspacing="0" >
              <tr>
                <td width="5" height="16"   background="../../img/borde1_1.jpg"><img src="../../img/space.gif" alt="d"></td>
                <td width="17" background="../../img/borde1_2.jpg" class="style5" align="center">&nbsp;</td>
                <td width="74" background="../../img/borde1_2.jpg" class="style5" align="center">D&iacute;as Salida </td>
                <td width="56" background="../../img/borde1_2.jpg" class="style5" align="center">Sucursal</td>
                <td width="85" background="../../img/borde1_2.jpg" class="style5" align="center">Hr Llegada </td>
                <td width="85" background="../../img/borde1_2.jpg" class="style5" align="center">T. Descarga </td>
                <td width="66" background="../../img/borde1_2.jpg" class="style5" align="center">T. Carga </td>
                <td width="96" background="../../img/borde1_2.jpg" class="style5" align="center">H. salida</td>
                <td width="75" background="../../img/borde1_2.jpg" class="style5"><img src="../../img/space.gif" alt="d" width="1" height="1" />T. T 
                  S.</td>
                <td width="36" background="../../img/borde1_2.jpg" class="style5"><div align="center">T</div></td>
                <td width="5"  background="../../img/borde1_3.jpg"><img src="../../img/space.gif" alt="d"></td>
              </tr>
              <tr>
                <td colspan="10" align="right"><div id="detalle" name="detalle" style=" height:80px; overflow:auto" align="left">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="tablaxx" alagregar="" alborrar="">
                      <tr  class="Balance">
                        <td width="33"></td>
                        <td width="70"></td>
                        <td width="65"></td>
                        <td width="93"></td>
                        <td width="86"></td>
                        <td width="64"></td>
                        <td width="74"></td>
                        <td width="70"></td>
						<td width="40"></td>
                      </tr>
                      <tr id="tfx_0" class="Balance" >
                        <td width="33">&nbsp;</td>
                        <td width="70">&nbsp;</td>
                        <td width="65">&nbsp;</td>
                        <td width="93">&nbsp;</td>
                        <td width="86">&nbsp;</td>
                        <td width="64">&nbsp;</td>
                        <td width="74">&nbsp;</td>
                        <td width="70">&nbsp;</td>
						<td width="40">&nbsp;</td>
                      </tr>
                      <tr id="tfx_1" class="Balance2" >
                        <td width="33">&nbsp;</td>
                        <td width="70">&nbsp;</td>
                        <td width="65">&nbsp;</td>
                        <td width="93">&nbsp;</td>
                        <td width="86">&nbsp;</td>
                        <td width="64">&nbsp;</td>
                        <td width="74">&nbsp;</td>
                        <td width="70">&nbsp;</td>
						<td width="40">&nbsp;</td>
                      </tr>
					 <tr id="tfx_2" class="Balance" >
                        <td width="33">&nbsp;</td>
                        <td width="70">&nbsp;</td>
                        <td width="65">&nbsp;</td>
                        <td width="93">&nbsp;</td>
                        <td width="86">&nbsp;</td>
                        <td width="64">&nbsp;</td>
                        <td width="74">&nbsp;</td>
                        <td width="70">&nbsp;</td>
						<td width="40">&nbsp;</td>
                      </tr>
					  <tr id="tfx_3" class="Balance2" >
                        <td width="33">&nbsp;</td>
                        <td width="70">&nbsp;</td>
                        <td width="65">&nbsp;</td>
                        <td width="93">&nbsp;</td>
                        <td width="86">&nbsp;</td>
                        <td width="64">&nbsp;</td>
                        <td width="74">&nbsp;</td>
                        <td width="70">&nbsp;</td>
						<td width="40">&nbsp;</td>
                      </tr>
					  <tr id="tfx_4" class="Balance" >
                        <td width="33">&nbsp;</td>
                        <td width="70">&nbsp;</td>
                        <td width="65">&nbsp;</td>
                        <td width="93">&nbsp;</td>
                        <td width="86">&nbsp;</td>
                        <td width="64">&nbsp;</td>
                        <td width="74">&nbsp;</td>
                        <td width="70">&nbsp;</td>
						<td width="40">&nbsp;</td>
                      </tr>
					  <tr id="tfx_5" class="Balance2" >
                        <td width="33">&nbsp;</td>
                        <td width="70">&nbsp;</td>
                        <td width="65">&nbsp;</td>
                        <td width="93">&nbsp;</td>
                        <td width="86">&nbsp;</td>
                        <td width="64">&nbsp;</td>
                        <td width="74">&nbsp;</td>
                        <td width="70">&nbsp;</td>
						<td width="40">&nbsp;</td>
                      </tr>
                    </table>
                </div></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><table width="266" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td width="9" height="16"   class="formato_columnas_izq"></td>
                <td width="250"class="formato_columnas" align="center"><div align="center">SUCURSALES </div></td>
                <td width="9"class="formato_columnas_der"></td>
              </tr>
              <tr>
                <td colspan="12"><table width="266" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td colspan="12" class="Tablas"><input name="todas" type="checkbox" id="todas" onClick="agregarTodasSucursales();">
                        Todos
                        <select name="sucursalesead1"  class="Tablas" style="width:200px" onChange="insertarServicio(this, this.value, document.all.sucursalesead1_sel, 'La Sucursal', 'SUCONVENIO')")>
                            <option value=""></option>
                <? 
					$s = "select * from catalogosucursal where id > 1";
					$r = mysql_query($s,$link) or die($s);
					while($f = mysql_fetch_object($r)){
				?>
                            <option value="<?=$f->id?>">
                              <?=$f->descripcion?>
                            </option>
                <?
					}
				?>
                        </select>
                        <input name="hidensucursal" type="hidden" id="hidensucursal"></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="12"><select name="sucursalesead1_sel" size="4" style="width:265px" onDblClick="borrarServicio(this, 'SUCONVENIO')">
                </select></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;
              <table width="154" border="0" align="right">
                <tr>
                  <td width="70"><span class="Tablas"><img src="../../img/Boton_Guardar.gif" alt="g" width="70" height="20" align="right" style="cursor:pointer; text-align: right;" title="Guardar" onClick="validar();"/></span></td>
                  <td width="114"><span class="Tablas"><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" align="right" style="cursor:pointer" title="Guardar" onClick="confirmar('Perdera la información capturada ¿Desea continuar?', '', 'limpiar();', '')"/></span></td>
                </tr>
              </table>            </td>
          </tr>
      </table>
      
        <center>
          <a href="../../menu/webministator.php">
          <label>
          
          </label>
          <img src="../../img/inicio_30.gif" alt="HOME" width="29" height="33" border="0"></a>
        </center></td>
  </tr>
</table>
</form>
</body>
<?
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
}
if ($msg!=""){
	echo "<script language='javascript' type='text/javascript'>alerta3('".$msg."');</script>";
}
?>

<script>
 	var valt1 = agregar_una_tabla("tablaxx", "tfx_", 5, "Balance└Balance2","");
	parent.frames[1].document.getElementById('titulo').innerHTML = 'CATÁLOGO RUTAS';
</script>



</html>
