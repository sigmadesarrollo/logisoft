<?  session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l	= Conectarse("webpmm");
	
	if($_POST[guardar_hidden]==1){
		$s = "delete from convenio_configurador_caja where idusuario = $_SESSION[IDUSUARIO] and tipo = 'CONVENIO' and isnull(idconvenio)";
		mysql_query($s,$l);
		
		$excedio = 0;
		
		for($j=0; $j<$_POST[cantidadfilas]; $j++){
			$cantidad = $_POST[pesolimite][$j];
			$cantidad = $cantidad*2;
			for($i=1; $i<$_POST[cantidadzonas]; $i++){
				if($_POST["zona".$i][$j]<$cantidad)
					$excedio = 1;
				$s = "insert into convenio_configurador_caja 
				set zona=$i, kmi=".$_POST["kmi".$i].", kmf=".$_POST["kmf".$i].",
				precio = ".(($_POST["zona".$i][$j]!="")?$_POST["zona".$i][$j]:"null").", 
				descripcion='".$_POST["descripcion"][$j]."', tipo='CONVENIO', 
				usuario = '".$_SESSION[NOMBREUSUARIO]."', idusuario='".$_SESSION[IDUSUARIO]."', fecha=current_date,
				pesolimite='".$_POST[pesolimite][$j]."', preciokgexcedente='".$_POST[precioexcedente][$j]."'";
				
				mysql_query($s,$l);
			}
		}
		$_POST[tienedatosprecio]=1;
	}
	
	if($_GET[idconvenio]){
		
	}else{
		if($_GET[tienedatosprecio]!=1 && $_POST[tienedatosprecio]!= 1){
			$s = "delete from convenio_configurador_caja where idusuario = $_SESSION[IDUSUARIO] and tipo = 'CONVENIO' and isnull(idconvenio)";
			mysql_query($s,$l);
			
		}
	}

	$s = mysql_query("SELECT CONCAT_WS(':',descripcion,id) AS descripcion FROM catalogodescripcion",$l);
	if(mysql_num_rows($s)>0){
		while($f=mysql_fetch_array($s)){
			$desc= "'".utf8_encode($f[0])."'".','.utf8_encode($desc);
		}	
		$desc=substr($desc, 0, -1);
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/moautocomplete.js"></script>
<script type="text/javascript" src="../javascript/ClaseTabla.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<script src="../javascript/ajax.js"></script>
<link href="../javascript/ajaxlist/ajaxlist_estilos.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
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
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css">
</head>
<script>
	var u		= document.all;
	var nseleccionado = "";
	var iseleccionado = "";
	
	window.onload = function(){
		u.descripcions.focus();
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
	
	function verificarsiExiste(valor){
		if(!isNaN(valor))
			valor = document.all.descripcions.value;
		var tbody 	= document.all.tablaconveniopreciocaja;
		var cant	= tbody.rows.length-1;
		if(cant == 1){
			if(document.all.descripcion.value==valor){
				return 0;
			}
		}else{
			for(var i=0; i<cant; i++){
				if(document.all.descripcion[i].value==valor){
					return i;
				}
			}
		}
		return undefined;
	}
	
	function obtenerIndice(objeto,nombre){
		var tbody 	= document.all.tablaconveniopreciocaja;
		var noObj	= tbody.rows.length-1;
		
		if(noObj==1){
			return "0";
		}else{
			for(var i=0; i<noObj; i++){
				if(u[nombre][i]==objeto){
					return i;
				}
			}
		}
	}
	function popUp(URL){
		if(URL!=""){
			if(document.getElementById('abierto').value==""){
			document.getElementById('abierto').value="abierto";
			day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=530,height=380,left = 470,top = 200');");				
			}else{
				alerta2('Ya Se Encuentra Abierta la busqueda','¡Atención!','descripcion');
			}
	
		}
	}
	
	function agregar(){
		var indice = verificarsiExiste(u.descripcion_txt.value);
		var tbody 	= document.all.tablaconveniopreciocaja;
		
		if(u.descripcion_txt.value==undefined || u.descripcion_txt.value==""){
			alerta3("Seleccione una descripcion", "¡Atencion!");
		}else{
			if(indice!=undefined){
				if(indice==0 && tbody.rows.length==2){					
					u.descripcion_txt.value = u.descripcions.value;
					u.descripcions.value = u.descripcion_txt.value;
					if(document.all.chktodos.checked){
						for(var i=1; i<=document.all.zona.options.length-1; i++){
							u["zona"+i].value = u.preciocaja_txt.value;
						}
					}else{
						u["zona"+u.zona.value].value = u.preciocaja_txt.value;
					}
					u.pesolimite.value = u.pesolimite_txt.value;
					u.precioexcedente.value = u.precioexcedente_txt.value;
				}else{
					u.descripcion_txt.value = u.descripcion[indice].value;
					u.descripcion[indice].value = u.descripcion_txt.value;
					if(document.all.chktodos.checked){
						for(var i=1; i<=document.all.zona.options.length-1; i++){
							u["zona"+i][indice].value = u.preciocaja_txt.value;
						}
					}else{
						u["zona"+u.zona.value][indice].value = u.preciocaja_txt.value;
					}
					u.pesolimite[indice].value = u.pesolimite_txt.value;
					u.precioexcedente[indice].value = u.precioexcedente_txt.value;
				}
			}else{
				var tbody 	= document.all.tablaconveniopreciocaja;
				var tr 		= tbody.insertRow(tbody.rows.length);
				
				var td 		= tr.insertCell(tr.cells.length);
				td.innerHTML = "<img src='../img/delete.png' id='imagenBorrar' onClick='borrar(obtenerIndice(this,\"imagenBorrar\"))' style='cursor:pointer'>";
				
				var td 		= tr.insertCell(tr.cells.length);
				td.innerHTML = "<input type='text' readonly class='estilo_cajadesseleccion' " +
						  "style='width:80; text-align:right' name='descripcion[]' id='descripcion' value='"+u.descripcions.value+"'" + 
						  "onDblClick='seleccionar(obtenerIndice(this,\"descripcion\"),\"zona1\")'>";
				var zonas 	= document.all.cantidadzonas.value;
				//descripcion
				for(var i=1; i<zonas; i++){
					var td 		= tr.insertCell(tr.cells.length);
					td.innerHTML = "<input type='text' readonly class='estilo_cajadesseleccion' " +
						  "style='width:55; text-align:right' name='zona"+i+"[]' id='zona"+i+"' value='"+((i==u.zona.value || u.chktodos.checked)?u.preciocaja_txt.value:"")+"'" + 
						  "onDblClick='seleccionar(obtenerIndice(this,\"zona"+i+"\"),\"zona"+i+"\")'>";
				}
				var td 		= tr.insertCell(tr.cells.length);
				td.innerHTML = "<input type='text' readonly class='estilo_cajadesseleccion' " +
						  "style='width:80; text-align:right' name='pesolimite[]' id='pesolimite' value='"+u.pesolimite_txt.value+"'" + 
						  "onDblClick='seleccionar(obtenerIndice(this,\"pesolimite\"),\"zona1\")'>";
				var td 		= tr.insertCell(tr.cells.length);
				td.innerHTML = "<input type='text' readonly class='estilo_cajadesseleccion' " +
						  "style='width:80; text-align:right' name='precioexcedente[]' id='precioexcedente' value='"+u.precioexcedente_txt.value+"'" + 
						  "onDblClick='seleccionar(obtenerIndice(this,\"precioexcedente\"),\"zona1\")'>";
			}
		}
		document.all.chktodos.checked=false;
	}	
	function seleccionar(indice, nombre){
		var tbody 	= document.all.tablaconveniopreciocaja;
		var noObj	= tbody.rows.length-1;
		
		if(indice=="" && indice != "0"){
			if(nseleccionado!=""){
				if(noObj==1)
					document.all[nseleccionado].className = "estilo_cajadesseleccion";
				else
					document.all[nseleccionado][iseleccionado].className = "estilo_cajadesseleccion";
				seleccionado = "";
			}
		}else{
			if(nseleccionado!=""){
				if(noObj==1)
					document.all[nseleccionado].className = "estilo_cajadesseleccion";
				else
					document.all[nseleccionado][iseleccionado].className = "estilo_cajadesseleccion";
			}
			
			nseleccionado = nombre;
			iseleccionado = indice;

			if(noObj==1){
				document.all[nseleccionado].className = "estilo_cajaseleccion";
				document.all.descripcion_txt.value = document.all.descripcion.value;
				u.descripcions.value = document.all.descripcion.value;
				u.descripcion_txt.value = document.all.descripcion.value;
				document.all.zona.value = nseleccionado.replace("zona","");
				document.all.preciocaja_txt.value = document.all[nseleccionado].value;
				document.all.pesolimite_txt.value = document.all.pesolimite.value;
				document.all.precioexcedente_txt.value = document.all.precioexcedente.value;
			}else{
				document.all[nseleccionado][iseleccionado].className = "estilo_cajaseleccion";
				u.descripcions.value = document.all.descripcion[iseleccionado].value;
				u.descripcion_txt.value = document.all.descripcion[iseleccionado].value;
				document.all.zona.value = nseleccionado.replace("zona","");
				document.all.preciocaja_txt.value = document.all[nseleccionado][iseleccionado].value;
				document.all.pesolimite_txt.value = document.all.pesolimite[iseleccionado].value;
				document.all.precioexcedente_txt.value = document.all.precioexcedente[iseleccionado].value;
			}
			
			document.all.preciocaja_txt.focus();
			document.all.preciocaja_txt.select();
		}
	}
	function borrar(indice){
		confirmar("¿Desea borrar la descripcion?","¡Atencion!","u.tablaconveniopreciocaja.deleteRow("+(indice+1)+");","")		
	}
	function obtener(id,descripcion){
		u.descripcions.value = descripcion;
		u.descripcion_txt.value = descripcion;
		u.zona.focus();
	}
	
	function guardar(){
		var paso 	= 1;
		var cz		= parseInt(document.all.cantidadzonas.value);
		var cf		= parseInt(u.tablaconveniopreciocaja.rows.length-1);
		if(cf == 1){
			for(var i=1; i<cz; i++){
				if(document.all["zona"+i].value == ""){
					paso = 0;
				}
				if(document.all.descripcion.value == "")
					paso = 0;
				if(document.all.pesolimite.value == "")
					paso = 0;
				if(document.all.precioexcedente.value == "")
					paso = 0;
			}
		}else{
			for(var j=0; j<cf; j++){
				for(var i=1; i<cz; i++){
					if(document.all["zona"+i][j].value == ""){
						paso = 0;
					}
					if(document.all.descripcion[j].value == "")
						paso = 0;
					if(document.all.pesolimite[j].value == "")
						paso = 0;
					if(document.all.precioexcedente[j].value == "")
						paso = 0;
				}
			}
		}
		if(paso==0){
			alerta3("Complete los valores de todas las zonas","¡Atencion!");
		}else{
			u.guardar_hidden.value=1; 
			u.cantidadfilas.value=u.tablaconveniopreciocaja.rows.length-1; 
			document.form1.submit()
		}
	}
	
	function obtenerDescripcionValida(){
		consultaTexto("descripcionValida","../evaluacion/evaluacionMercancia_con.php?accion=12&descripcion="+u.descripcions.value);	
	}
	function descripcionValida(datos){
		if(datos.indexOf("no")>-1){
			if(u.descripcions.value!=""){
				u.descripcion_txt.value="";
				u.descripcions.value="";
				alerta("La Descripción no es valida","¡Atención!","descripcions");
				return false;
			}
		}else{
			var row = datos.split(",");
			u.descripcion_txt.value = row[1];
		}
	}
	
	function validaDescripcion(e,obj){
		tecla=(document.all) ? e.keyCode : e.which;
		if((tecla==8 || tecla==46)&& document.getElementById(obj).value==""){
			document.getElementById('descripcion_txt').value=""; 
		}	
	}
	
	var desc 	= new Array(<?php echo $desc; ?>);
	
</script>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="549" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="618" class="FondoTabla Estilo4">Datos Generales  </td>
  </tr>
  <tr>
    <td><table width="547" border="0" align="center" cellpadding="0" cellspacing="0">
      
      <tr>
        <td colspan="6"><table width="545" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="62">Descripci&oacute;n</td>
            <td width="176"><span class="Tablas">
              <input name="descripcions" type="text" class="Tablas" id="descripcion2" style="text-transform:uppercase" autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.descripcion_txt.value=this.codigo; document.all.zona.focus(); }" onKeyDown="if(event.keyCode==9){document.all.descripcion_txt.value=this.codigo;}" onKeyUp="return validaDescripcion(event,this.name)" value="<?=$descripcion ?>" size="30" maxlength="50" onBlur="if(this.value!=''){setTimeout('obtenerDescripcionValida()',700);}" />
            </span>
              <!--onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event,'../evaluacion/ajax-list-descripcion.php')"-->
            <input name="descripcion_txt" type="hidden" />
            </td>
            <td width="37"><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="javascript:popUp('../evaluacion/buscar.php?tipo=descripcion')" style="cursor:pointer"></td>
            <td width="119"><input name="abierto" type="hidden" readonly=""></td>
            <td width="13">&nbsp;</td>
            <td width="53"><input type="hidden" name="tienedatosprecio" 
            value="<?=($_GET[tienedatosprecio]!="")?$_GET[tienedatosprecio]:$_POST[tienedatosprecio]?>"></td>
            <td width="8">&nbsp;</td>
            <td width="77">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="86">Zona</td>
        <td width="136">
		<select name="zona" style="width:100px" class="Tablas" onkeypress="if(event.keyCode==13){u.preciocaja_txt.focus();}">
			<option value="0">.::Selecciona::.</option>
                <?
					$s = "SELECT columna FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$zona = 0;
						while($f = mysql_fetch_object($r)){
				?>
					<option value="<?=$f->columna?>">Zona <?=$f->columna?></option>
				<?	
						}
				?>
        </select></td>
        <td width="124">Precio Caja/Paquete</td>
        <td width="123"><input name="preciocaja_txt" class="Tablas" type="text" style="width:100px" value="" onKeyPress="if(event.keyCode==13){document.all.pesolimite_txt.focus();}else{return tiposMoneda(event,this.value);}"></td>
        <td width="43">&nbsp;</td>
        <td width="105">&nbsp;</td>
      </tr>
      <tr>
        <td width="86">Peso Limite</td>
        <td width="136"><input name="pesolimite_txt" class="Tablas" type="text" style="width:100px" value="" onKeyPress="if(event.keyCode==13){u.precioexcedente_txt.focus();}else{return solonumeros(event);}"></td>
        <td width="124">Precio KG Exedente </td>
        <td width="123"><input name="precioexcedente_txt" class="Tablas" type="text" style="width:100px" value="" onKeyPress="if(event.keyCode==13){agregar();}else{return tiposMoneda(event,this.value);}"></td>
        <td width="43">&nbsp;</td>
        <td width="105"><div class="ebtn_agregar" onClick="agregar()"></div></td>
      </tr>
      <tr>
        <td colspan="6"><table border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><input type="checkbox" name="chktodos"></td>
            <td>Dar valor a todos</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="6"><div id="detalle" style="width:547px; height:80px; overflow:auto" align="left">
           			<?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
              <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+265?>" id="tablaconveniopreciocaja">
                <tr>
                  <td height="16" width="25"  class="formato_columnasg">
                  	
                    </td>
                  <td height="16" width="80"  class="formato_columnasg">Descripcion</td>
                  <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$zona = 1;
						while($f = mysql_fetch_object($r)){
					?>
                  <td height="16" class="formato_columnasg" width="55px" align="center" >Zona
                    <?=$zona?>
                    <br>
                    <?=$f->zoi?> - <?=$f->zof?> <input type="hidden" name="kmi<?=$zona?>" value="<?=$f->zoi?>" >
                    <input type="hidden" name="kmf<?=$zona?>" value="<?=$f->zof?>" ></td>
                  <?
							$zona++;
						}
					?>
				  <td height="16" width="80"  class="formato_columnasg" align="right">Peso Limite</td>
				  <td height="16" width="80"  class="formato_columnasg" align="right">Precio Excedente</td>
                </tr>
                <?
					$s = "SELECT * FROM convenio_configurador_caja WHERE idusuario = $_SESSION[IDUSUARIO] and tipo='CONVENIO' and isnull(idconvenio) GROUP BY descripcion";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_array($r)){
				?>
                <tr>
                	<td><img src='../img/delete.png' id='imagenBorrar' onClick='borrar(obtenerIndice(this,"imagenBorrar"))' style='cursor:pointer; visibility:visible'></td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='descripcion[]' id='descripcion' value='<?=$f[descripcion]?>'  
					  onDblClick='seleccionar(obtenerIndice(this,"descripcion"),"zona1")'></td>
                    <? 
					$s = "SELECT * FROM convenio_configurador_caja WHERE idusuario = $_SESSION[IDUSUARIO] and tipo='CONVENIO' and isnull(idconvenio) and descripcion = '$f[descripcion]' order by zona";
					$rx = mysql_query($s,$l) or die($s);
					while($fx = mysql_fetch_object($rx)){
					?>
                    <td>
                      <input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:55; text-align:right' name='zona<?=$fx->zona?>[]' id='zona<?=$fx->zona?>' value='<?=$fx->precio?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"zona<?=$fx->zona?>"),"zona<?=$fx->zona?>")'>
                    </td>
                    <? }?>
                    <td><input type='text' readonly class='estilo_cajadesseleccion'
					  style='width:80; text-align:right' name='pesolimite[]' id='pesolimite' value='<?=$f["pesolimite"]?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"pesolimite"),"zona1")'></td>
                    <td><input type='text' readonly class='estilo_cajadesseleccion' 
					  style='width:80; text-align:right' name='precioexcedente[]' id='precioexcedente' value='<?=$f["preciokgexcedente"]?>' 
					  onDblClick='seleccionar(obtenerIndice(this,"precioexcedente"),"zona1")'></td>
                </tr>
                <?
					}
				?>
              </table>
		
		</div></td>
      </tr>
      

      <tr>
        <td colspan="6"><input type="hidden" name="cantidadzonas" value="<?=$zona?>"></td>
      </tr>
      <tr>
        <td colspan="6" align="center"><table width="272">
          <tr>
            <td width="131" align="center"><div class="ebtn_guardar" onClick="guardar();"></div>
                <input type="hidden" name="guardar_hidden" value="<?=$_POST[guardar_hidden].$_GET[guardar_hidden]?>">
                <input type="hidden" name="cantidadfilas" value="0">
                </td>
            <td width="129" align="center"><div class="ebtn_cerrarventana" onClick="parent.VentanaModal.cerrar();"></div></td>
          </tr>
        </table></td>
      </tr> 
    </table>
      <div align="center"></div></td>
  </tr>
</table>
<p>&nbsp;</p>
</form>
</body>
<script>
	<?
	if($_POST[guardar_hidden]==1){
	?> 
	parent.document.all.div_descripcion.innerHTML = document.all.detalle.innerHTML.replace(/visible/g,"hidden");
	parent.document.all.tienedatosprecio.value = 1;
	parent.document.all.tienedatosprecio_excedio.value = <?=$excedio?>;
	info("Los datos han sido guardados","¡Atencion!");
	<?
	}
	?>
</script>
</html>


