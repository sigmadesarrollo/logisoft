<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	
		require_once('../../Conectar.php');	
		$link=Conectarse('webpmm');	  
		$modulo=$_GET['modulo'];
		$usuario=$_SESSION[NOMBREUSUARIO]; $accion=$_POST['accion']; $unidad=$_POST['unidad']; $descripcion=$_POST['descripcion']; $numeroeconomico=$_POST['numeroeconomico']; $cvolumen=$_POST['cvolumen']; $ckilos=$_POST['ckilos']; $tarjetacirculacion=$_POST['tarjetacirculacion'];$tiporuta=$_POST['tiporuta'];$sucursal=$_POST['sucursal']; $celular=$_POST['celular'];$placas=$_POST[placas];
	$servicio=$_POST[servicio];$des_servicio=$_POST[des_servicio];
	if($accion=="grabar"){
		$s = "INSERT INTO 
		catalogounidad(id, tipounidad, numeroeconomico, cvolumen, ckilos, tarjetacirculacion,tiporuta,sucursal,celular,placas,usuario, fecha,fueradeservicio,desservicio)
		VALUES(null,'$unidad',UCASE('$numeroeconomico'), '$cvolumen','$ckilos',UCASE('$tarjetacirculacion'),UCASE('$tiporuta'),UCASE('$sucursal'),'$celular',UCASE('$placas'),'$usuario', current_date(),'$servicio','$des_servicio')";
			$sqlins=mysql_query($s,$link) or die($s);		
			$accion="modificar";
			$mensaje = 'Los datos han sido guardados correctamente';
		
	}else if($accion=="modificar"){		
			$sqlupd=mysql_query("UPDATE catalogounidad SET tipounidad='$unidad',cvolumen='$cvolumen', ckilos='$ckilos', tarjetacirculacion=UCASE('$tarjetacirculacion'),tiporuta='$tiporuta',sucursal=UCASE('$sucursal'), celular='$celular',placas=UCASE('$placas'), usuario='$usuario', fecha=current_date(),fueradeservicio='$servicio',desservicio='$des_servicio' where numeroeconomico='$numeroeconomico'",$link)  or die("ERROR EN LA LINEA".__LINE__);
			$mensaje = 'Los cambios han sido guardados correctamente';	
	}else if($accion=="limpiar"){
			$accion=""; $unidad=""; $descripcion=""; $numeroeconomico=""; $cvolumen=""; $ckilos=""; $tarjetacirculacion=""; $celular=""; $placas="";$servicio="";$des_servicio="";
	}
?>
<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<title>Cat&aacute;logo Unidades</title>
<link href="FondoTabla.css" rel="stylesheet" type="text/css">
<script src="../../javascript/shortcut.js"></script>
<script language="JavaScript" type="text/javascript">
var nav4 = window.Event ? true : false;
function Numeros(evt){ 
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
	var key = nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 48 && key <= 57));
}
function validar(){
 	if(document.getElementById('numeroeconomico').value==""){
			alerta('Debe capturar Numero Economico','¡Atención!','numeroeconomico');
	}else if(document.getElementById('unidad').value==""){
			alerta('Debe capturar Tipo Unidad','¡Atención!','unidad');
	}else if(document.getElementById('cvolumen').value==""){
			alerta('Debe capturar Capacidad Volumen','¡Atención!','cvolumen');			
	}else if(document.getElementById('cvolumen').value<0){	
	alerta('Debe capturar una cantidad mayor o igual a Cero','¡Atención!','cvolumen');			
	}else if(document.getElementById('ckilos').value==""){
			alerta('Debe capturar Capacidad Kilos','¡Atención!','ckilos');
	}else if(document.getElementById('ckilos').value<0){	
			alerta('Debe capturar una cantidad mayor o igual a Cero','¡Atención!','ckilos');			
	}else if(document.getElementById('tiporuta').value==""){
			alerta('Debe capturar Tipo Ruta','¡Atención!','tiporuta');
	}else if(document.getElementById('sucursal').value==""){
			alerta('Debe capturar Sucursal','¡Atención!','sucursal');
	}else{
			if(document.getElementById('accion').value==""){
				document.getElementById('accion').value = "grabar";
				document.form1.submit();
			}else if(document.getElementById('accion').value=="modificar"){
				document.form1.submit();
			}
	}
}

function limpiar(){
	document.getElementById('unidad').value="";
	document.getElementById('descripcion').value="";
	document.getElementById('numeroeconomico').value="";
	document.getElementById('cvolumen').value="";
	document.getElementById('ckilos').value="";
	document.getElementById('tarjetacirculacion').value="";
	document.getElementById('tiporuta').value="";
	document.getElementById('sucursal').value="";
	document.getElementById('celular').value="";
	document.getElementById('placas').value="";
	document.all.des_servicio.value=0;
	document.all.des_servicio.disabled = true;
	document.all.servicio.checked = false;
	document.getElementById('accion').value = "limpiar";
	document.form1.submit();
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
            /*ACA ESTA EL CAMBIO*/
            if (frm.elements[i+1].disabled ==true )    
                tabular(e,frm.elements[i+1]);
            else if (frm.elements[i+1].readOnly ==true )    
                tabular(e,frm.elements[i+1]);
            else frm.elements[i+1].focus();
            return false;
}  

function obtener(id,tipo){
	if(tipo=="unidad"){
		consulta("mostrarNumeroEconomico","catalogounidadresult.php?tipo=unidad&economico="+id+"&sid="+Math.random());		
	}else{		
		consulta("MostrarUnidad","catalogounidadresult.php?unidad="+id+"&tipo="+tipo+"&sid="+Math.random());		
	}		
}
function MostrarUnidad(datos){
		var u= document.all;
		var con   = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){
		u.tarjetacirculacion.focus();
		u.unidad.value = datos.getElementsByTagName('unidad').item(0).firstChild.data;
		u.descripcion.value=datos.getElementsByTagName('descripcion').item(0).firstChild.data;
		}
}

function mostrarNumeroEconomico(datos){
		var u= document.all;
		var con   = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){
		u.numeroeconomico.value = datos.getElementsByTagName('numeroeconomico').item(0).firstChild.data;
		u.unidad.value = datos.getElementsByTagName('unidad').item(0).firstChild.data;
		u.descripcion.value=datos.getElementsByTagName('descripcion').item(0).firstChild.data;
		u.tarjetacirculacion.value=datos.getElementsByTagName('tarjetacirculacion').item(0).firstChild.data;
		u.cvolumen.value=datos.getElementsByTagName('cvolumen').item(0).firstChild.data;
		u.ckilos.value=datos.getElementsByTagName('ckilos').item(0).firstChild.data;
		u.tiporuta.value=datos.getElementsByTagName('tiporuta').item(0).firstChild.data;
		u.sucursal.value=datos.getElementsByTagName('sucursal').item(0).firstChild.data;
		u.celular.value=datos.getElementsByTagName('celular').item(0).firstChild.data;		
		u.placas.value=datos.getElementsByTagName('placas').item(0).firstChild.data;
		var servicio=datos.getElementsByTagName('servicio').item(0).firstChild.data;
		if(servicio==1){
			var des_servicio =datos.getElementsByTagName('des_servicio').item(0).firstChild.data;
			u.servicio.checked=true;
			u.servicio.value=1;
			u.des_servicio.disabled=false;
			u.des_servicio.value=des_servicio;
		}else{
			u.servicio.checked=false;
			u.servicio.value=0;
			u.des_servicio.disabled=true;
			u.des_servicio.value=0;
		}
		
		u.accion.value = datos.getElementsByTagName('accion').item(0).firstChild.data;
	}
}
function foco(nombrecaja){
	if(nombrecaja=="numeroeconomico"){
		document.getElementById('oculto').value="1";
	}else if(nombrecaja=="unidad"){
		document.getElementById('oculto').value="2";
	}
}
shortcut.add("Ctrl+b",function() {
	if(document.form1.oculto.value=="1"){
abrirVentanaFija('buscar.php?tipo=unidad', 550, 450, 'ventana', 'Busqueda')
	}else if(document.form1.oculto.value=="2"){
abrirVentanaFija('buscar.php?tipo=tipounidad1', 550, 450, 'ventana', 'Busqueda')	
	}
});
</script>
<script src="../../javascript/ajax.js"></script>
<script src="select.js"></script>
<script src="../../javascript/ajax.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="FondoTabla.css" rel="stylesheet" type="text/css">
<link href="puntovta.css" rel="stylesheet" type="text/css">
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
.txtbox {	font-size:9px;
	text-transform: uppercase;
}
-->
</style>
</head>
<body onLoad="document.form1.numeroeconomico.focus()" >
<form name="form1" method="post" action="" >

  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><br>
      <table width="370" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
        <tr>
          <td width="563" class="FondoTabla">CAT&Aacute;LOGO UNIDADES</td>
        </tr>
        <tr>
          <td><table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="82" class="Tablas"><strong>No. 
                  Economico:</strong></td>
                <td width="268"><input name="numeroeconomico" type="text" class="Tablas" id="numeroeconomico"  style="font:tahoma; font-size:9px; text-transform:uppercase" onKeyDown="if(event.keyCode==13){document.all.unidad.focus(); document.all.oculto.value=2;}else{return tabular(event,this)}" value="<?=$numeroeconomico ?>" size="38" onBlur="obtener(this.value,'unidad');document.getElementById('oculto').value=''" onFocus="foco(this.name)"  >
                  <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscar.php?tipo=unidad', 550, 480, 'ventana', 'Busqueda')" /></td>
              </tr>
              <tr>
                <td colspan="2"><div id="txtHint">
                  <table width="349" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="2" class="Tablas"><div id="txtUnidad">
                        <table width="348" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="82" class="Tablas">T. Unidad: </td>
                            <td width="266"><input name="unidad" type="text" class="Tablas" id="unidad" style=" font:tahoma; font-size:9px; background:#FFFF99" value="<?=$unidad ?>" size="10" readonly onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''"  />
                              &nbsp;&nbsp; <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscar.php?tipo=tipounidad1', 550, 490, 'ventana', 'Busqueda')" />&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="Tablas">&nbsp;</td>
                            <td><input name="descripcion" type="text" class="Tablas" id="descripcion" style=" font:tahoma; font-size:9px; background:#FFFF99" value="<?=$descripcion ?>" size="38" readonly /></td>
                          </tr>
                        </table>
                    </div></td>
                  </tr>
                  <tr>
                    <td width="82" class="Tablas">T. Circulaci&oacute;n:</td>
                    <td width="267"><input name="tarjetacirculacion" type="text" class="Tablas" id="tarjetacirculacion" style="font:tahoma; font-size:9px; text-transform:uppercase" onKeyPress="return tabular(event,this)" value="<?=$tarjetacirculacion ?>" size="38"></td>
                  </tr>
                  <tr>
                    <td width="82" class="Tablas">Cap. Volumen: </td>
                    <td><span class="Tablas">
                      <input name="cvolumen" class="Tablas" type="text" id="cvolumen" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" style="font:tahoma; font-size:9px" value="<?=$cvolumen ?>" size="10">
                      &nbsp;&nbsp;&nbsp;Cap. Kilos:
                      <input name="ckilos" type="text" id="ckilos" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)"  style="font:tahoma; font-size:9px" value="<?=$ckilos ?>" size="10" class="Tablas">
                    </span></td>
                  </tr>
                  <tr>
                    <td width="82" class="Tablas">Tipo Ruta:</td>
                    <td><select name="tiporuta" class="Tablas" id="tiporuta" style="width:210px; text-transform:uppercase" onkeydown="return tabular(event,this)">
                      <option>SELECCIONAR</option>
                      <option value="LOCAL" <? if($tiporuta=="LOCAL"){echo "selected";}?>>LOCAL</option>
                      <option value="FORANEA" <? if($tiporuta=="FORANEA"){echo "selected";}?>>FORANEA</option>
                    </select></td>
                  </tr>
                  <tr>
                    <td width="82" class="Tablas">Sucursal:</td>
                    <td><span class="Tablas">
                      <select name="sucursal" id="sucursal" class="Tablas" style="width:210px; text-transform:uppercase" onkeydown="return tabular(event,this)" >
                        <option>SELECCIONAR</option>
                        <?
					  $sqlt="SELECT id,UCASE(descripcion) FROM catalogosucursal ORDER BY descripcion ASC ";
					  $result=mysql_query($sqlt,$link);
					  while($row=mysql_fetch_array($result)){ 			  
						?>
                        <option value="<?=$row[0] ?>"  <? if($sucursal==$row[0]){echo "selected";} ?> >
                        <?=$row[1]; ?>
                        </option>
                        <?	}   ?>
                      </select>
                    </span></td>
                  </tr>
                  <tr>
                    <td width="82" class="Tablas">Celular:</td>
                    <td><span class="Tablas">
                      <input name="celular" class="Tablas" type="text" id="celular" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" style="font:tahoma; font-size:9px" value="<?=$celular ?>" size="20">
                    </span></td>
                  </tr>
                  <tr>
                    <td width="82" class="Tablas">Placas:</td>
                    <td><span class="Tablas">
                      <input name="placas" class="Tablas" type="text" id="placas"  onKeyDown="return tabular(event,this)" style=" text-transform:uppercase" value="<?=$placas ?>" size="20">
                    </span></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="Tablas"><input type="checkbox" name="servicio" value="0" onClick="if(document.all.servicio.checked==true){document.all.servicio.value=1; document.all.des_servicio.disabled=false;  }else{document.all.servicio.value=0; document.all.des_servicio.disabled=true;document.all.des_servicio.value=0; }">
                      Fuera de 
                      Servicio
					    <?
					$s="SELECT * FROM fueradeservicio";
					$r= mysql_query($s,$link) or die($s);					
					?>					
					    <select name="des_servicio" class="Tablas"  style="text-transform:uppercase; width:180px" disabled="disabled">
					      <option value="0"></option>
					      <? while($f=mysql_fetch_object($r)){ ?>
					      <option value="<?=$f->id?>" ><?=$f->descripcion?></option>
					      <? } ?>          
					        </select>                      </td>
                    </tr>
                </table>
                </div></td>
              </tr>
              <tr>
                <td colspan="2"><span class="Tablas">
                <input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
                <input name="oculto" type="hidden" id="oculto" value="<?=$accion ?>">
                </span> <table width="157" border="0" align="right">
                  <tr>
                    <td width="72"><img style="cursor:pointer" onClick="validar()" src="../../img/Boton_Guardar.gif" width="70" height="20"></td>
                    <td width="108"><img style="cursor:pointer" onClick="confirmar('Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')" src="../../img/Boton_Nuevo.gif" width="70" height="20"></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
            </table></td>
        </tr>
      </table>
      </p></td>
  </tr>
</table>
</form>
</body>
</html>
<?
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
	}
//	}
?>