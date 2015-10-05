<?
	session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "select * from catalogosucursal where id = $_SESSION[IDSUCURSAL]";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$nsucursal = $f->descripcion;
	
	$s = "SELECT impdefault, impetiquetasguias, impetiquetaspaquetes, imptickets, impevaluaciones 
	FROM configuracion_impresoras WHERE sucursal = $_SESSION[IDSUCURSAL]";
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){		
		$encontro = "SI";
		$f = mysql_fetch_object($r);
		
		$default		= str_replace("\\","\\\\",$f->impdefault);
		$guias			= str_replace("\\","\\\\",$f->impetiquetasguias);
		$paquetes		= str_replace("\\","\\\\",$f->impetiquetaspaquetes);
		$tickets		= str_replace("\\","\\\\",$f->imptickets);
		$evaluaciones	= str_replace("\\","\\\\",$f->impevaluaciones);
	}else{
		$encontro = "NO";
	}
	
	$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion,':',cs.id) AS descripcion,
	cs.id
	FROM catalogosucursal cs ORDER BY cs.descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			if($f[id]==$_SESSION[IDSUCURSAL]){
				$are = split(":",$f[descripcion]);
				$nombreSucursal = $are[0];
			}
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
		}
		$desc = substr($desc, 0, -1);
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../sucursal/FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../sucursal/Tablas.css" rel="stylesheet" type="text/css">
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script language="javascript" src="../../javascript/ClaseTabla.js"></script>
<script language="javascript" src="../../javascript/ajax.js"></script>
<script src="../../javascript/moautocomplete.js"></script>
</head>
<OBJECT ID="Metodos" style="visibility:hidden"
CLASSID="CLSID:21B8DA59-7F02-40B9-A5E9-FC848C3DB134"
CODEBASE="../../activexs/Impresion.CAB#version=1,1,0,0">
</OBJECT>
<script>
	var tabla1 = new ClaseTabla();
	var datosimpre = Array();
	var u = document.all;
	var desc = new Array(<?php echo $desc; ?>);
	
	tabla1.setAttributes({
		nombre:"dgr_impresoras",
		campos:[
			{nombre:"Default", medida:4, alineacion:"left", tipo:"oculto", datos:"def"},
			{nombre:"Impresora", medida:300, alineacion:"left", datos:"impresora"}
		],
		filasInicial:15,
		alto:228,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	

	//FUNCIONES DE VALIDACION
	function solonumeros(evnt){
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
		}
	}

	//FUNCIONES DE LA PAGINA
	window.onload = function(){
		tabla1.create();
		
		setTimeout("ponerSiExiste()",1000);
	}
	
	function ponerSiExiste(){
		<? if($encontro=="SI"){ ?>
			if('<?=$guias?>'!='')
				u.dgr_impresora_guias.value='<?=$guias?>';
			if('<?=$paquetes?>'!='')
				u.dgr_impresora_paquetes.value='<?=$paquetes?>';
			if('<?=$tickets?>'!='')
				u.dgr_impresora_tickets.value='<?=$tickets?>';
			if('<?=$evaluaciones?>'!='')
				u.dgr_impresora_evaluaciones.value='<?=$evaluaciones?>';
			if('<?=$default?>'!='')
				u.dgr_impresora_default.value='<?=$default?>';
			
		<? } ?>
		ponerImpresoras();
	}
	
	
	function agregarImpresora(val){
		if(tabla1.getSelectedRow()!=null){
			switch(val){
				case 1:
					u.dgr_impresora_guias.value=tabla1.getSelectedRow().impresora;
				break;
				case 2:
					u.dgr_impresora_paquetes.value=tabla1.getSelectedRow().impresora;
				break;
				case 3:
					u.dgr_impresora_tickets.value=tabla1.getSelectedRow().impresora;
				break;
				case 4:
					u.dgr_impresora_evaluaciones.value=tabla1.getSelectedRow().impresora;
				break;
				case 5:
					u.dgr_impresora_default.value=tabla1.getSelectedRow().impresora;
				break;
			}
		}else{
			alerta3("Seleccione la impresora a agregar");
		}
		
	}
	
	function ponerImpresoras(){		
		datosimpre = Array();
		var obj = Object();
		
		tabla1.clear();
		
		var impredefault 	= Metodos.impresoraDefault();
		var impresoras 		= Metodos.mandarImpresoras();
		var arreimpre 		= impresoras.split(",");
		var indice = 0;
		for(var i=arreimpre.length-1; i>=0; i--){
			if(arreimpre[i]!=""){
				obj 			= new Object();
				obj.impresora 	= arreimpre[i];
				if(impredefault.toUpperCase()==arreimpre[i].toUpperCase())
					obj.def 	= "x";
				else
					obj.def 	= "";
				
				
				tabla1.add(obj);
			}
			if(impredefault.toUpperCase()==arreimpre[i].toUpperCase()){
				tabla1.setColorByIndex("#CC0000",indice);
			}
		}
	}
	
	function confirmarGuardarEmpleado(){
		confirmar("&iquest;Desea guardar la configuracion para este empleado?","¡Atencion!","guardarEmpleado()")
	}
	function guardarEmpleado(){
		var impetiquetasguias		= u.dgr_impresora_guias.value.replace(/\\/g,"\\\\");
		var impetiquetaspaquetes	= u.dgr_impresora_paquetes.value.replace(/\\/g,"\\\\");
		var imptickets				= u.dgr_impresora_tickets.value.replace(/\\/g,"\\\\");
		var impevaluaciones			= u.dgr_impresora_evaluaciones.value.replace(/\\/g,"\\\\");
		var impdefault				= u.dgr_impresora_default.value.replace(/\\/g,"\\\\");
		var sucursal				= u.sucursal_hidden.value;
		
		if(sucursal==""){
			alerta3("Seleccione una sucursal válida","¡ATENCION!");
			return false;
		}
		
		if(document.getElementById('btnguardarsucursal').style.display=='none'){
			var idusu = "&idusu="+document.getElementById('txtidusuario').value;
		}else{
			var idusu = '';
		}
		
		consultaTexto("resGuardarEmpleado","configuracionimpresiones_con.php?accion=1"+idusu+
				"&impdefault="+impdefault+"&impetiquetasguias="+impetiquetasguias+"&impetiquetaspaquetes="+impetiquetaspaquetes+
				"&imptickets="+imptickets+"&impevaluaciones="+impevaluaciones+"&sucursal="+sucursal+
				"&rand="+Math.random());
	}
	function resGuardarEmpleado(datos){
		if(datos.indexOf("guardado")>-1){
			info("La configuracion ha sido guardada","¡Atencion!");
		}else{
			alerta3("Error "+datos,"&iexcl;Atencion!");
		}
	}	
	
	
	function confirmarGuardarSucursal(){
			confirmar("&iquest;Desea guardar la configuracion para esta sucursal?","¡Atencion!","guardarEmpleado()")
	}
	
	
	
	function pedirEmpleado(id){
		if(id==""){
			consultaTexto("mostrarSucursales","configuracionimpresiones_con.php?accion=5"+
						  "&sucursal="+u.sucursal_hidden.value+"&rand="+Math.random());
		}else{
			consultaTexto("mostrarEmpleado","configuracionimpresiones_con.php?accion=4"+
						  "&sucursal="+u.sucursal_hidden.value+"&id="+id+"&rand="+Math.random());
		}
	}
	
	function mostrarSucursales(datos){
		var obj = eval(convertirValoresJson(datos));
		
			if(obj.imp1=='' && obj.imp2=='' && obj.imp3==''){
				alerta3("No se encontro configuracion para esta sucursal","&iexcl;Atencion!");
				u.dgr_impresora_guias.value = "";
				u.dgr_impresora_paquetes.value = "";
				u.dgr_impresora_tickets.value = "";
				u.dgr_impresora_evaluaciones.value = "";
				u.dgr_impresora_default.value = "";
			}else{
				u.dgr_impresora_guias.value = "";
				u.dgr_impresora_paquetes.value = "";
				u.dgr_impresora_tickets.value = "";
				u.dgr_impresora_evaluaciones.value = "";
				u.dgr_impresora_default.value = "";
				if(obj.imp1!=''){
					u.dgr_impresora_guias.value = obj.imp1;
				}
				if(obj.imp2!=''){
					u.dgr_impresora_paquetes.value = obj.imp2;
				}
				if(obj.imp3!=''){
					u.dgr_impresora_tickets.value = obj.imp3;
				}
				if(obj.imp4!=''){
					u.dgr_impresora_evaluaciones.value = obj.imp4;
				}
				if(obj.imp5!=''){
					u.dgr_impresora_default.value = obj.imp5;
				}
				
			}
			document.getElementById('btnguardarsucursal').style.display='';
			document.getElementById('btnguardarusuario').style.display='none';
			
	}
	
	function mostrarEmpleado(datos){
		var obj = eval(convertirValoresJson(datos));
		
		if(obj.id!=undefined){
			if(obj.conf==''){
				alerta3("No se encontro configuracion para este empleado ni su sucursal","&iexcl;Atencion!");
			}else if(obj.conf=='sucursal'){
				info("No se encontro configuracion para el usuario pero si para la sucursal \nEsta se puede aplicar al usuario","Atencion");
			}
			
			u.dgr_impresora_guias.value = "";
			u.dgr_impresora_paquetes.value = "";
			u.dgr_impresora_tickets.value = "";
			u.dgr_impresora_evaluaciones.value = "";
			u.dgr_impresora_default.value = "";
			
			u.dgr_impresora_guias.value = obj.imp1;
			u.dgr_impresora_paquetes.value = obj.imp2;
			u.dgr_impresora_tickets.value = obj.imp3;
			u.dgr_impresora_evaluaciones.value = obj.imp4;
			u.dgr_impresora_default.value = obj.imp5;
			
			document.getElementById('txtidusuario').value = obj.id;
			document.getElementById('txtusuario').value = obj.empleado;
			document.getElementById('btnguardarsucursal').style.display='none';
			document.getElementById('btnguardarusuario').style.display='';
		}else{
			alerta3("No se encontro el empleado buscado, o el empleado no pertenece a esta sucursal","&iexcl;Atencion!")
			document.getElementById('txtidusuario').value = "";
			document.getElementById('txtusuario').value = "";
			document.getElementById('btnguardarsucursal').style.display='';
			document.getElementById('btnguardarusuario').style.display='none';
		}
		
	}
</script>
<body>
<table width="623" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="465" class="FondoTabla">CONFIGURADOR DE IMPRESIONES</td>
  </tr>
  <tr>
    <td>
    <table width="618" border="0" cellpadding="0" cellspacing="0">
    <tr>
       	  <td colspan="2">
          	<table width="569" border="0" cellpadding="0" cellspacing="0">
<tr>
               	<td>
                
                <table border="0" cellpadding="0" cellspacing="0">
        	  <tr class="Tablas">
        	    <td>Sucursal:
        	      <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$_SESSION[IDSUCURSAL]?>" /></td>
        	    <td><input name="sucursal" type="text" id="sucursal" style="width:150px;" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.sucursal_hidden.value = this.codigo; if(u.sucursal_hidden.value=='undefined'){ this.codigo=''; u.sucursal_hidden.value='';}else{pedirEmpleado('');}}" onblur="document.all.sucursal_hidden.value = this.codigo; if(u.sucursal_hidden.value=='undefined'){ this.codigo=''; u.sucursal_hidden.value=''; }else{pedirEmpleado('');}" value="<?=$nombreSucursal?>" />
        	      <img src="../img/Buscar_24.gif" id="imagenBuscarSucursal" width="24" height="23" align="absbottom" style="cursor:pointer; display:none" onclick="mens.show('../buscadores_generales/buscarsucursal.php', 600, 450, 'ventana', 'Busqueda');" /></td>
        	    </tr>
      	  </table>
                
                </td>
                <td width="71">EMPLEADO</td>
                    <td width="45"><input type="text" name="txtidusuario" style="width:40px" onblur="if(this.value==''){document.getElementById('txtusuario').value=''}" 
                    onkeypress="if(event.keyCode==13){pedirEmpleado(this.value)}else{return solonumeros(event)}"/></td>
                    <td width="33"><div class="ebtn_buscar" onClick="abrirVentanaFija('../../buscadores_generales/buscarEmpleadoGen.php?funcion=pedirEmpleado', 570, 450, 'ventana', 'Busqueda')"></div></td>
                    <td width="208"><input type="text" name="txtusuario" readonly="readonly" style="background-color:#FFFF99; width:180px" /></td>
                </tr>
            </table>          </td>
</tr>
    <tr>
       	  <td width="383">IMPRESORAS</td>
            <td width="235">ASIGNACIONES</td>
        </tr>
    <tr>
      <td height="178" valign="top">
      	<table id="dgr_impresoras" border="0" cellpadding="0" cellspacing="0"></table>      </td>
      <td valign="top">
      	<table width="234" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td align="center">Impresora de etiquetas de Guia</td>
            </tr>
        	<tr>
            	<td>
                	<input type="text" name="dgr_impresora_guias" style="width:100%" />
                </td>
            </tr>
            <tr>
            	<td>
                <table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td  id="btnagregarguia"><div class="ebtn_agregar" onclick="agregarImpresora(1)"></div></td>
                		<td  id="btnquitarguia">&nbsp;</td>
                	</tr>
                </table>                </td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
            </tr>
            <tr>
            	<td align="center">Impresora de etiquetas de Paquetes</td>
            </tr>
            <tr>
            	<td>
                	<input type="text" name="dgr_impresora_paquetes" style="width:100%" />
				</td>
            </tr>
        	<tr>
            	<td>
                <table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td  id="btnagregarpaquete"><div class="ebtn_agregar" onclick="agregarImpresora(2)"></div></td>
                		<td  id="btnquitarpaquete"></td>
                	</tr>
                </table>                </td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
            </tr>
            <tr>
            	<td align="center">Impresora Tickets</td>
            </tr>
            <tr>
              <td>
              <input type="text" name="dgr_impresora_tickets" style="width:100%" />
              </td>
            </tr>
            <tr>
              <td>
              <table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td id="btnagregartickets"><div class="ebtn_agregar" onclick="agregarImpresora(3)"></div></td>
                		<td  id="btnquitartickets"></td>
                	</tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="center">Impresora Evaluaciones</td>
            </tr>
            <tr>
              <td>
              		<input type="text" name="dgr_impresora_evaluaciones" style="width:100%" />
              </td>
            </tr>
            <tr>
              <td>
              	<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td  id="btnagregarevaluacion"><div class="ebtn_agregar" onclick="agregarImpresora(4)"></div></td>
                		<td  id="btnquitarevaluacion"></td>
                	</tr>
                </table>
                </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
            	<td align="center">Impresora Default</td>
            </tr>
        	<tr>
            	<td>
                <input type="text" name="dgr_impresora_default" style="width:100%" />
                </td>
            </tr>
            <tr>
            	<td>
                <table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td  id="btnagregardefault"><div class="ebtn_agregar" onclick="agregarImpresora(5)"></div></td>
                		<td  id="btnquitardefault"></td>
                	</tr>
                </table>                </td>
            </tr>
        </table>      </td>
    </tr>
    <tr>
      <td height="11">      </td>
      <td></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      	<table border="0">
        	<tr>
            	<td id="btnguardarsucursal"><div class="ebtn_guardarParaSucursal" onclick="confirmarGuardarSucursal()"></div></td>
                <td id="btnguardarusuario" ><div class="ebtn_guardarParaUsuario" onclick="confirmarGuardarEmpleado()"></div></td>
            </tr>
        </table>
      </td>
</tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    </table>
    </td>
  </tr>
</table>
</body>
</html>