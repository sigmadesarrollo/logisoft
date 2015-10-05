<style type="text/css">
	.botonX, .botonX:active{ background:url(style/bg-button.jpg) 0px 0px repeat-x; padding:0px 15px; border:none; height:22px; font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#fff; cursor:pointer;-moz-border-radius:2px; -webkit-border-radius:2px;}
</style>
<div id="<?=$nombreBuscador?>_fondo" style="width:100%; height:100%; top:0px; left:0px; position:fixed; background:#000; z-index:998; display:none; filter:alpha(opacity=50);">
</div>
<div id="<?=$nombreBuscador?>" style="position:absolute; display:none; background-image:url(../img/fondo_nuevoBuscar.jpg); left: 195px; top: 294px; width: 546px; height: 278px; z-index:1000;">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<td height="5px"></td>
    </tr>	
</table>
<table width="517"  align="center" cellpadding="0" cellspacing="0" border="0" >
    <tr> 
      <td width="517" align="center"></td>
    </tr>
    <tr> 
        <td colspan="7" class="FondoTabla" style="font-weight:bold; color:#FFF; background:url(../img/fondo_nuevoBuscar.jpg) top;">
    <table width="518" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="483">Direcciones<input type="hidden" name="<?=$nombreBuscador?>_idcliente" /></td>
                <td width="36">
                	<a href="#" class="FondoTabla" onclick="<?=$funcionOcultar?>()" style="color:#FFF">Cerrar</a>
                </td>
            </tr>
        	<tr>
        	  <td height="216" colspan="2" style="background-color:#FFF">
              		<table width="518" border="0" cellpadding="0" cellspacing="0">
                    	<tr>
                        	<td style="background-color:#000; font-size:10px; padding-left:15px; padding-top:4px;">
                            <table width="500">
                            	<tr>
                                	<td>Colonia </td>
                                    <td><input type="text" style="width:125px;" id="<?=$nombreBuscador?>_ColoniaB" onkeypress="if(event.keyCode==13){<?=$nombreBuscador?>_buscarDireccion();}" /> </td>
                            		<td>Poblacion </td>
		                            <td><input type="text" style="width:125px;" id="<?=$nombreBuscador?>_PoblacionB" onkeypress="if(event.keyCode==13){<?=$nombreBuscador?>_buscarDireccion();}" /></td>
                                    <td rowspan="2" align="center" valign="middle"><button class="botonX" onclick="<?=$nombreBuscador?>_buscarDireccion()">Buscar</button></td>
                                </tr>
                                <tr>
		              				<td>Calle </td>
                                    <td><input type="text" style="width:125px;" id="<?=$nombreBuscador?>_CalleB" onkeypress="if(event.keyCode==13){<?=$nombreBuscador?>_buscarDireccion();}" /> </td>
	                            	<td>CP </td>
                                    <td><input type="text" style="width:125px;" id="<?=$nombreBuscador?>_CPB" onkeypress="if(event.keyCode==13){<?=$nombreBuscador?>_buscarDireccion();}" /></td>
	                            </tr>
                            </table>
                          </td>
                        </tr>
                    	<tr>
                        	<td style="background-color:#000" height="116">
		              		<table cellpadding="0" cellspacing="0" border="0" id="<?=$nombreBuscador?>_tablaDireccion"></table>
                            </td>
                        </tr>
                    	<tr>
                    	  <td  style="background-color:#000; border:1px #000 solid" id="<?=$nombreBuscador?>celdaPaginado">&nbsp;</td>
                  	  </tr>
                      <tr>
                    	  <td  style="background-color:#000; border:1px #000 solid; text-align:center" align="center">
                          <button class="botonX" onclick="<?=$funcionMostrar?>_direcciones()">Agregar Nueva Direccion</button>
                          </td>
                  	  </tr>
                    </table>
              </td>
       	  </tr>
        </table>
      </td>
    </tr>
    </table>
</div>

<div id="<?=$nombreBuscador?>_agregaDireccion" style="position:absolute; display:none; background-image:url(../img/fondo_nuevoBuscar.jpg); left: 130px; top: 343px; width: 691px; height: 207px; z-index:1001;">
	<table width="666" align="center" cellpadding="0" cellspacing="0">
              <tr>
              	<td height="2px"></td>
              </tr>
    </table>
    <table width="666" align="center" cellpadding="0" cellspacing="0">
              <tr>
              	<td>
                <table width="663">
                	<tr>
                    	<td width="611" class="FondoTabla" style="color:#FFF">Direccion</td>
                    	<td width="40"><a href="#" onclick="<?=$funcionOcultar?>_direcciones()"  class="FondoTabla" style="color:#FFF">Cerrar</a></td>
                    </tr>
                </table>
                </td>
              </tr>
    </table>
	<table width="666" align="center" cellpadding="0" cellspacing="0" style="background-color:#FFF">
              <tr>
                <td>&nbsp;</td>
                <td width="211">&nbsp;</td>
                <td width="104">&nbsp;</td>
                <td width="231">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td width="27">&nbsp;</td>
              </tr>
              <tr>
                <td width="91" class="Tablas">Calle:</td>
                <td colspan="4" class="Tablas"><input name="<?=$nombreBuscador?>_calle" type="text" id="<?=$nombreBuscador?>_calle" class="Tablas" size="38" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_calle').value,'<?=$nombreBuscador?>_calle');" value="<?=$calle; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress=""/>
                  Numero:          <input name="<?=$nombreBuscador?>_numero" type="text" id="<?=$nombreBuscador?>_numero" class="Tablas" size="38" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_numero').value,'<?=$nombreBuscador?>_numero');" value="<?=$calle; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress=""/>        </td>
              </tr>
              <tr>
                <td class="Tablas">Cruce Calles:</td>
                <td colspan="4"><input name="<?=$nombreBuscador?>_entrecalles" class="Tablas" type="text" id="<?=$nombreBuscador?>_entrecalles" size="61" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_entrecalles').value,'<?=$nombreBuscador?>_entrecalles');" value="<?= $entrecalles; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress=""/></td>
              </tr>
              <tr>
                <td class="Tablas">C.P.:</td>
                <td class="Tablas"><input name="<?=$nombreBuscador?>_cp" type="text" id="<?=$nombreBuscador?>_cp" class="Tablas" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_cp').value,'<?=$nombreBuscador?>_cp'); " onKeyPress="return solonumeros(event)" onKeyDown="<?=$nombreBuscador?>_CodigoPostal(event,this.value);"  value="<?= $cp; ?>" size="10" maxlength="5" style="font:tahoma;font-size:9px; text-transform:uppercase" />
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="Tablas">Colonia:</td>
                <td id="<?=$nombreBuscador?>_celcolonia"><input name="<?=$nombreBuscador?>_colonia" type="text" value="<?=$colonia ?>" class="Tablas" id="<?=$nombreBuscador?>_colonia" style=" width:183px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event,'../../buscadores_generales/ajax-list-colonias.php'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=''){setTimeout('obtenerColoniaValida()',1000);}" /></td>
                <td><img src="../img/Buscar_24.gif" style="cursor:pointer" onclick="<?=$funcionOcultar?>_mostrar()"></td>
                </tr>
              <tr>
                <td class="Tablas">Poblaci&oacute;n:</td>
                <td class="Tablas"><input name="<?=$nombreBuscador?>_poblacion" type="text" class="Tablas" id="<?=$nombreBuscador?>_poblacion" style="width:120px; background:#F1F7FA;  text-transform:uppercase" readonly=""  value="<?= $poblacion; ?>" /></td>
                <td class="Tablas">Mun./Del.:&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="Tablas"><input name="<?=$nombreBuscador?>_municipio" type="text" class="Tablas" id="<?=$nombreBuscador?>_municipio" size="20"  style="width:120px;background:#F1F7FA; text-transform:uppercase" readonly="" value="<?=$municipio; ?>" /></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="Tablas">Estado:</td>
                <td class="Tablas"><input name="<?=$nombreBuscador?>_estado" type="text" class="Tablas" id="<?=$nombreBuscador?>_estado" size="20" value="<?=$estado; ?>" style="width:120px;background:#F1F7FA; text-transform:uppercase" readonly="" /></td>
                <td class="Tablas">Pa&iacute;s:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="Tablas"><input name="<?=$nombreBuscador?>_pais" type="text" id="<?=$nombreBuscador?>_pais" class="Tablas" size="20" value="<?=$pais; ?>" style="width:120px; text-transform:uppercase; background-color:#F1F7FA" readonly=""/></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="Tablas">Tel&eacute;fono:</td>
                <td><input name="<?=$nombreBuscador?>_telefono" type="text" class="Tablas" id="<?=$nombreBuscador?>_telefono" size="20" onBlur="<?=$nombreBuscador?>_trim(document.getElementById('<?=$nombreBuscador?>_telefono').value,'<?=$nombreBuscador?>_telefono');" value="<?= $telefono; ?>" style="width:120px;" onKeyPress="" /></td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp; </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="Tablas">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="5" class="Tablas" style="text-align:center">
                	<button class="botonX" onclick="<?=$nombreBuscador?>_guardarDirecciones()">Guardar</button>
                </td>
              </tr>
              <tr>
                <td colspan="5" height="5px" class="Tablas">
                </td>
              </tr>
            </table>
</div>
<div id="<?=$nombreBuscador?>_buscarColonia" style="position:absolute; display:none; background-image:url(../img/fondo_nuevoBuscar.jpg); left: 318px; top: 327px; width: 492px; height: 295px; z-index:1002;">
	<table width="472" height="270" class="FondoTabla" align="center">
    	<tr style="color:#FFF">
        	<td height="16">
            	<table width="465" border="0" cellpadding="0" cellspacing="0">
                	<tr>
		            	<td width="362">Buscar Colonias</td>
                        <td width="53"><a href="#" class="FondoTabla" onclick="<?=$funcionOcultar?>_colonias()" style="color:#FFF">Cerrar</a></td>
                    </tr>
                </table>
          </td>
        </tr>
        <tr style="background-color:#FFF">
        	<td height="230">
            	<table width="466">
                	<tr style="color:#333">
                    	<td width="48" class="Tablas">Colonia</td>
                        <td width="106"><input type="text" name="<?=$nombreBuscador?>_buscaColonia" 
                        id="<?=$nombreBuscador?>_buscaColonia" style="width:100px" /></td>
                        <td width="43" class="Tablas">Ciudad</td>
                        <td width="98"><input type="text" name="<?=$nombreBuscador?>_buscaCiudad" 
                        id="<?=$nombreBuscador?>_buscaCiudad" style="width:100px" /></td>
                        <td width="99">
                        <img src="<?=$raiz?>img/Boton_Generar.gif" style="cursor:pointer" onClick="<?=$nombreBuscador?>_buscarColonias()" align="absbottom">
                        </td>
                    </tr>
                	<tr>
                	  <td height="218" colspan="5" style="background-color:#000">
                      	<table border="0" cellpadding="0" cellspacing="0" id="<?=$nombreBuscador?>_coloniasEncontradas">
				        </table>
                      </td>
               	  </tr>
                	<tr>
                	  <td colspan="5">
                      	<div id="<?=$nombreBuscador?>_paginado_colonias" align="center" style="display:none; width:363px; height:15px;">
                            <table width="162" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                            <td>
                          <img src="<?=$raiz?>img/first.gif" name="d_primero" width="16" height="16" style="cursor:pointer"  onclick="<?=$nombreBuscador?>_pagprimero()" /> 
                          <img src="<?=$raiz?>img/previous.gif" name="d_atrasdes" width="16" height="16" style="cursor:pointer" onclick="<?=$nombreBuscador?>_paganterior()" /> 
                          </td>
                          <td>
                          <div style="position:relative; width:50px; height:15px; text-align:center; color:#333" id="<?=$nombreBuscador?>_pagina1"></div>
                          </td>
                          <td>
                          <img src="<?=$raiz?>img/next.gif" name="d_sigdes" width="16" height="16" style="cursor:pointer" onclick="<?=$nombreBuscador?>_pagsiguiente()" /> 
                          <img src="<?=$raiz?>img/last.gif" name="d_ultimo" width="16" height="16" style="cursor:pointer" onclick="<?=$nombreBuscador?>_pagultimo()" />
                          </td>
                          </tr>
                          </table>
                        </div>
                      </td>
              	  </tr>
                </table>
            </td>
        </tr>
  </table>
</div>
<script>
	var <?=$nombreBuscador?>tablaDir = new ClaseTabla();
	var <?=$nombreBuscador?>dataSet1 = new DataSet();
	
	<?=$nombreBuscador?>tablaDir.setAttributes({
		nombre:"<?=$nombreBuscador?>_tablaDireccion",
		campos:[
			{nombre:"CALLE", medida:120, alineacion:"left", datos:"calle"},
			{nombre:"NUMERO", medida:40, alineacion:"left", datos:"numero"},
			{nombre:"COLONIA", medida:120, alineacion:"left", datos:"colonia"},
			{nombre:"CP", medida:50, alineacion:"left", datos:"codigopostal"},
			{nombre:"POBLACION", medida:120, alineacion:"left", datos:"poblacion"},
			{nombre:"TEL", medida:40, alineacion:"left", datos:"telefono"},
			{nombre:"IdDir", medida:4, tipo:"oculto", alineacion:"left", datos:"iddireccion"}
		],
		filasInicial:10,
		alto:150,
		seleccion:true,
		eventoDblClickFila:"<?=$nombreBuscador?>_darDireccion(<?=$nombreBuscador?>tablaDir.getSelectedRow())",
		ordenable:false,
		nombrevar:"<?=$nombreBuscador?>tablaDir"
	});
	
	function <?=$nombreBuscador?>_darDireccion(objeto){
		<?=$funcion?>(objeto);
	}
	
	<?=$nombreBuscador?>tablaDir.create();
	<?=$nombreBuscador?>dataSet1.crear({
			'paginasDe':15,
			'objetoTabla':<?=$nombreBuscador?>tablaDir,
			'objetoPaginador':document.getElementById('<?=$nombreBuscador?>celdaPaginado'),
			'nombreVariable':'<?=$nombreBuscador?>dataSet1',
			'ubicacion':'../',
			'funcionOrdenar':function(a,b){
				return ((a.calle.toLowerCase()>b.calle.toLowerCase())?1:((a.calle.toLowerCase()<b.calle.toLowerCase())?-1:0))
			}
		});
	
	function <?=$nombreBuscador?>_buscarDireccion(){
		var col = document.getElementById('<?=$nombreBuscador?>_ColoniaB').value;
		var pob = document.getElementById('<?=$nombreBuscador?>_PoblacionB').value;
		var cal = document.getElementById('<?=$nombreBuscador?>_CalleB').value;
		var cp = document.getElementById('<?=$nombreBuscador?>_CPB').value;
		
		if(col=="" && pob=="" && cal=="" && cp==""){
			<?=$nombreBuscador?>dataSet1.filtrar(null);
		}else{
			<?=$nombreBuscador?>dataSet1.filtrar(function(objeto){
				if(
				(objeto.colonia.toUpperCase().indexOf(col.toUpperCase()) > -1 || col=="") && 
				(objeto.poblacion.toUpperCase().indexOf(pob.toUpperCase()) > -1 || pob=="") &&
				(objeto.calle.toUpperCase().indexOf(cal.toUpperCase()) > -1 || cal=="") &&
				(objeto.codigopostal==cp || cp=="")
				)
					return true;
				else
					return false;
			});
		}
	}
	
	function <?=$nombreBuscador?>_trim(cadena,caja){
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
	
	function <?=$nombreBuscador?>_CodigoPostal(e,cp){
		tecla=(document.all) ? e.keyCode : e.which;
		if((tecla==13 || tecla==9) && cp!=""){
			
			consulta("<?=$nombreBuscador?>_mostrarPostal","<?=$raiz?>buscadores_generales/consultasColonias.php?accion=1&cp="+cp+"&sid="+Math.random());
		}
	}
	
	function <?=$nombreBuscador?>_mostrarPostal(datos){
		var Input = '<input name="<?=$nombreBuscador?>_colonia" type="text" class="Tablas" id="<?=$nombreBuscador?>_colonia" style=" width:183px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,\'getCountriesByLetters\',event,\'../../buscadores_generales/ajax-list-colonias.php\'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=\'\'){setTimeout(\'obtenerColoniaValida()\',1000);}" />';

		var combo1 = "<select name='<?=$nombreBuscador?>_colonia' id='<?=$nombreBuscador?>_colonia' class='Tablas' style='width:183px;font:tahoma;font-size:9px'>";

		
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		document.getElementById('<?=$nombreBuscador?>_colonia').value=""; 
		document.getElementById('<?=$nombreBuscador?>_poblacion').value="";
		document.getElementById('<?=$nombreBuscador?>_municipio').value="";
		document.getElementById('<?=$nombreBuscador?>_estado').value="";
		document.getElementById('<?=$nombreBuscador?>_pais').value="";	
		
		if(con>0){
			if(datos.getElementsByTagName('total').item(0).firstChild.data>1){
				document.getElementById('<?=$nombreBuscador?>_celcolonia').innerHTML = combo1;
				var combo = document.all.<?=$nombreBuscador?>_colonia;
				combo.options.length = null;
				uOpcion = document.createElement("OPTION");
				uOpcion.value=0;
				uOpcion.text="..:: Selecciona ::..";
				combo.add(uOpcion);
			var total =datos.getElementsByTagName('total').item(0).firstChild.data;
				for(i=0;i<total;i++){	
					uOpcion = document.createElement("OPTION");
					uOpcion.value=datos.getElementsByTagName('colonia').item(i).firstChild.data;
					uOpcion.text=datos.getElementsByTagName('colonia').item(i).firstChild.data;
					combo.add(uOpcion);
				}
				document.getElementById('<?=$nombreBuscador?>_cp').value=datos.getElementsByTagName('cp').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_colonia').value=datos.getElementsByTagName('colonia').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_poblacion').value=datos.getElementsByTagName('poblacion').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_municipio').value=datos.getElementsByTagName('municipio').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_estado').value=datos.getElementsByTagName('estado').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_pais').value=datos.getElementsByTagName('pais').item(0).firstChild.data;
				setTimeout("document.getElementById('<?=$nombreBuscador?>_telefono').focus()",500);
			}else{		
				document.getElementById('<?=$nombreBuscador?>_celcolonia').innerHTML = Input;
				document.getElementById('<?=$nombreBuscador?>_cp').value=datos.getElementsByTagName('cp').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_colonia').value=datos.getElementsByTagName('colonia').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_poblacion').value=datos.getElementsByTagName('poblacion').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_municipio').value=datos.getElementsByTagName('municipio').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_estado').value=datos.getElementsByTagName('estado').item(0).firstChild.data;
				document.getElementById('<?=$nombreBuscador?>_pais').value=datos.getElementsByTagName('pais').item(0).firstChild.data;
				setTimeout("document.getElementById('<?=$nombreBuscador?>_telefono').focus()",500);
			}
		}else{			
			alerta2("El Código Postal no existe",'¡Atención!','<?=$nombreBuscador?>_cp');
			document.getElementById('<?=$nombreBuscador?>_celcolonia').innerHTML = Input;
			document.getElementById('<?=$nombreBuscador?>_cp').focus();
		}
	}
	
	var <?=$nombreBuscador?>_tabla1 = new ClaseTabla();
	
	<?=$nombreBuscador?>_tabla1.setAttributes({
		nombre:"<?=$nombreBuscador?>_coloniasEncontradas",
		campos:[
			{nombre:"N_Colonia", medida:200, alineacion:"left", datos:"colonia"},
			{nombre:"N_Poblacion", medida:200, alineacion:"left", datos:"poblacion"},
			{nombre:"N_Cp", medida:4, alineacion:"left", tipo:"oculto", datos:"codigopostal"},
			{nombre:"N_Municipio", medida:4, alineacion:"left", tipo:"oculto", datos:"municipio"},
			{nombre:"N_Estado", medida:4, alineacion:"right", tipo:"oculto", datos:"estado"},
			{nombre:"N_Pais", medida:4, alineacion:"right", tipo:"oculto", datos:"pais"},
			{nombre:"idPais", medida:4, alineacion:"right", tipo:"oculto", datos:"idcol"},
			{nombre:"idPais", medida:4, alineacion:"right", tipo:"oculto", datos:"idpob"},
			{nombre:"idPais", medida:4, alineacion:"right", tipo:"oculto", datos:"idmun"},
			{nombre:"idPais", medida:4, alineacion:"right", tipo:"oculto", datos:"idest"},
			{nombre:"idPais", medida:4, alineacion:"right", tipo:"oculto", datos:"idpais"}
		],
		filasInicial:14,
		alto:200,
		seleccion:true,
		eventoDblClickFila:"<?=$nombreBuscador?>_ponerColonia()",
		ordenable:false,
		nombrevar:"<?=$nombreBuscador?>_tabla1"
	});
	var <?=$nombreBuscador?>_buscPag = "";
	<?=$nombreBuscador?>_tabla1.create();
	var <?=$nombreBuscador?>_buscPagIndice = 0;
	
	function <?=$funcionOcultar?>_colonias(){
		document.getElementById('<?=$nombreBuscador?>_buscarColonia').style.display='none';
	}
	
	function <?=$funcionOcultar?>_mostrar(){
		document.getElementById('<?=$nombreBuscador?>_buscarColonia').style.display='';
	}
	
	function <?=$nombreBuscador?>_ponerColonia(){
		var Input = '<input name="<?=$nombreBuscador?>_colonia" type="text" class="Tablas" id="<?=$nombreBuscador?>_colonia" style=" width:183px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,\'getCountriesByLetters\',event,\'../../buscadores_generales/ajax-list-colonias.php\'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=\'\'){setTimeout(\'obtenerColoniaValida()\',1000);}" />';
		document.getElementById('<?=$nombreBuscador?>_celcolonia').innerHTML = Input;
		
		var fila = <?=$nombreBuscador?>_tabla1.getSelectedRow();
		document.getElementById('<?=$nombreBuscador?>_cp').value=fila.codigopostal; 
		document.getElementById('<?=$nombreBuscador?>_colonia').value=fila.colonia; 
		document.getElementById('<?=$nombreBuscador?>_poblacion').value=fila.poblacion;
		document.getElementById('<?=$nombreBuscador?>_municipio').value=fila.municipio;
		document.getElementById('<?=$nombreBuscador?>_estado').value=fila.estado;
		document.getElementById('<?=$nombreBuscador?>_pais').value=fila.pais;	
		<?=$funcionOcultar?>_colonias();
	}
	
	function <?=$nombreBuscador?>_buscarColonias(){
		var colonia = document.getElementById('<?=$nombreBuscador?>_buscaColonia').value;
		var ciudad = document.getElementById('<?=$nombreBuscador?>_buscaCiudad').value;
		consultaTexto("<?=$nombreBuscador?>_respuestaColonia","<?=$raiz?>buscadores_generales/buscarColoniasCiudad.php?colonia="+colonia+"&ciudad="+ciudad);
	}
	
	function <?=$nombreBuscador?>_respuestaColonia(datos){
		<?=$nombreBuscador?>_buscPagIndice = 0;
		document.getElementById('<?=$nombreBuscador?>_paginado_colonias').style.display='none';
		try{
			<?=$nombreBuscador?>_buscPag = eval(datos);
			<?=$nombreBuscador?>_tabla1.setJsonData(<?=$nombreBuscador?>_buscPag[0].datos);
			if(<?=$nombreBuscador?>_buscPag.length>1)
				document.getElementById('<?=$nombreBuscador?>_paginado_colonias').style.display='';
		}catch(e){
			alert(datos);
		}
		<?=$nombreBuscador?>_ponerPagina()
	}
	
	function <?=$nombreBuscador?>_pagprimero(){
		<?=$nombreBuscador?>_buscPagIndice = 0;
		<?=$nombreBuscador?>_tabla1.setJsonData(<?=$nombreBuscador?>_buscPag[0].datos);
		<?=$nombreBuscador?>_ponerPagina();
	}
	
	function <?=$nombreBuscador?>_pagultimo(){
		<?=$nombreBuscador?>_buscPagIndice = <?=$nombreBuscador?>_buscPag.length-1;
		<?=$nombreBuscador?>_tabla1.setJsonData(<?=$nombreBuscador?>_buscPag[<?=$nombreBuscador?>_buscPag.length-1].datos);
		<?=$nombreBuscador?>_ponerPagina();
	}
	
	function <?=$nombreBuscador?>_pagsiguiente(){
		if(<?=$nombreBuscador?>_buscPagIndice+1 < <?=$nombreBuscador?>_buscPag.length){
			<?=$nombreBuscador?>_buscPagIndice++;
			<?=$nombreBuscador?>_tabla1.setJsonData(<?=$nombreBuscador?>_buscPag[<?=$nombreBuscador?>_buscPagIndice].datos);
		}else
			alerta3("Has llegado al final","¡ATENCION!");
		<?=$nombreBuscador?>_ponerPagina()
	}
	
	function <?=$nombreBuscador?>_paganterior(){
		if(<?=$nombreBuscador?>_buscPagIndice-1<0)
			alerta3("Has llegado al inicio","¡ATENCION!");
		else{
			<?=$nombreBuscador?>_buscPagIndice--;
			<?=$nombreBuscador?>_tabla1.setJsonData(<?=$nombreBuscador?>_buscPag[<?=$nombreBuscador?>_buscPag.length-1].datos);
		}
		<?=$nombreBuscador?>_ponerPagina()
	}
	
	function <?=$nombreBuscador?>_ponerPagina(){
		document.getElementById('<?=$nombreBuscador?>_pagina1').innerHTML=(<?=$nombreBuscador?>_buscPagIndice+1)+"-"+(<?=$nombreBuscador?>_buscPag.length);
	}
	
	function <?=$funcionMostrar?>(datos,cliente){
		try{
			<?=$nombreBuscador?>_tabla1.clear();
			document.getElementById('<?=$nombreBuscador?>_ColoniaB').value = "";
			document.getElementById('<?=$nombreBuscador?>_PoblacionB').value = "";
			document.getElementById('<?=$nombreBuscador?>_CalleB').value = "";
			document.getElementById('<?=$nombreBuscador?>_CPB').value = "";
			
			document.getElementById('<?=$nombreBuscador?>_calle').value = "";
			document.getElementById('<?=$nombreBuscador?>_numero').value = "";
			document.getElementById('<?=$nombreBuscador?>_entrecalles').value = "";
			document.getElementById('<?=$nombreBuscador?>_cp').value = "";
			document.getElementById('<?=$nombreBuscador?>_colonia').value = "";
			document.getElementById('<?=$nombreBuscador?>_poblacion').value = "";
			document.getElementById('<?=$nombreBuscador?>_municipio').value = "";
			document.getElementById('<?=$nombreBuscador?>_estado').value = "";
			document.getElementById('<?=$nombreBuscador?>_pais').value = "";
			document.getElementById('<?=$nombreBuscador?>_telefono').value = "";
			document.getElementById('<?=$nombreBuscador?>_buscaColonia').value = "";
			document.getElementById('<?=$nombreBuscador?>_buscaCiudad').value = "";
			
			<?=$nombreBuscador?>dataSet1.filtrar(null);
		}catch(e){
			e = null;
		}
		document.getElementById('<?=$nombreBuscador?>_idcliente').value="";
		if(datos!=null)
			<?=$nombreBuscador?>dataSet1.setJsonData(datos);
		if(cliente!=null)
			document.getElementById('<?=$nombreBuscador?>_idcliente').value=cliente;
		document.getElementById('<?=$nombreBuscador?>_fondo').style.display='';
		document.getElementById('<?=$nombreBuscador?>').style.display='';
	}
	
	function <?=$funcionOcultar?>(){
		document.getElementById('<?=$nombreBuscador?>_fondo').style.display='none';
		document.getElementById('<?=$nombreBuscador?>').style.display='none';
	}
	
	function <?=$funcionMostrar?>_direcciones(){
		
		document.getElementById('<?=$nombreBuscador?>_agregaDireccion').style.display='';
	}
	function <?=$funcionOcultar?>_direcciones(){
		document.getElementById('<?=$nombreBuscador?>_agregaDireccion').style.display='none';
	}
	
	function <?=$nombreBuscador?>_guardarDirecciones(){
		if(document.getElementById('<?=$nombreBuscador?>_calle').value==""){
			alerta('Debe capturar la Calle.', '¡Atención!','<?=$nombreBuscador?>_calle');
			return false;
		}
		if(document.getElementById('<?=$nombreBuscador?>_entrecalles').value==""){
			alerta('Debe capturar las Calles.', '¡Atención!','<?=$nombreBuscador?>_entrecalles');
			return false;
		}
		if(document.getElementById('<?=$nombreBuscador?>_cp').value==""){
			alerta('Debe capturar el Codigo Postal.', '¡Atención!','<?=$nombreBuscador?>_cp');
			return false;
		}
		if(document.getElementById('<?=$nombreBuscador?>_colonia').value==""){
			alerta('Debe capturar Colonia.', '¡Atención!','<?=$nombreBuscador?>_colonia');
			return false;
		}
		
		consultaTexto("regresarClienteG","<?=$raiz?>buscadores_generales/guardarDireccionCliente.php?"+
			"idcliente="+document.getElementById('<?=$nombreBuscador?>_idcliente').value+
			"&calle="+document.getElementById('<?=$nombreBuscador?>_calle').value+
			"&numero="+document.getElementById('<?=$nombreBuscador?>_numero').value+
			"&crucecalles="+document.getElementById('<?=$nombreBuscador?>_entrecalles').value+
			"&cp="+document.getElementById('<?=$nombreBuscador?>_cp').value+
			"&colonia="+document.getElementById('<?=$nombreBuscador?>_colonia').value+
			"&poblacion="+document.getElementById('<?=$nombreBuscador?>_poblacion').value+
			"&municipio="+document.getElementById('<?=$nombreBuscador?>_municipio').value+
			"&estado="+document.getElementById('<?=$nombreBuscador?>_estado').value+
			"&pais="+document.getElementById('<?=$nombreBuscador?>_pais').value+
			"&telefono="+document.getElementById('<?=$nombreBuscador?>_telefono').value);

	}
	
	function regresarClienteG(objetoS){
		if(objetoS.indexOf('ok')>-1){
			
			var obj = new Object();
			obj.calle=document.getElementById('<?=$nombreBuscador?>_calle').value;
			obj.numero=document.getElementById('<?=$nombreBuscador?>_numero').value;
			obj.colonia=document.getElementById('<?=$nombreBuscador?>_colonia').value;
			obj.codigopostal=document.getElementById('<?=$nombreBuscador?>_cp').value;
			obj.poblacion=document.getElementById('<?=$nombreBuscador?>_poblacion').value;
			obj.telefono=document.getElementById('<?=$nombreBuscador?>_telefono').value;
			obj.iddireccion=objetoS.split(",")[1];
			<?=$nombreBuscador?>dataSet1.agregarRegistro(obj);
			<?=$funcionOcultar?>_direcciones();
		}else{
			alerta3("ERROR AL GUARDAR");
		}
	}
	
	function <?=$nombreBuscador?>_limpiar(){
		 document.getElementById('<?=$nombreBuscador?>_rdmoral').value="";
		 document.getElementById('<?=$nombreBuscador?>_nombre').value="";
		 document.getElementById('<?=$nombreBuscador?>_paterno').value="";
		 document.getElementById('<?=$nombreBuscador?>_materno').value="";
		 document.getElementById('<?=$nombreBuscador?>_rfc').value="";
		 document.getElementById('<?=$nombreBuscador?>_celular').value="";
		 document.getElementById('<?=$nombreBuscador?>_email').value="";
		 document.getElementById('<?=$nombreBuscador?>_web').value="";
		 document.getElementById('<?=$nombreBuscador?>_calle').value="";
		 document.getElementById('<?=$nombreBuscador?>_numero').value="";
		 document.getElementById('<?=$nombreBuscador?>_entrecalles').value="";
		 document.getElementById('<?=$nombreBuscador?>_cp').value="";
		 document.getElementById('<?=$nombreBuscador?>_colonia').value="";
		 document.getElementById('<?=$nombreBuscador?>_poblacion').value="";
		 document.getElementById('<?=$nombreBuscador?>_municipio').value="";
		 document.getElementById('<?=$nombreBuscador?>_estado').value="";
		 document.getElementById('<?=$nombreBuscador?>_pais').value="";
		 document.getElementById('<?=$nombreBuscador?>_telefono').value="";
	}
</script>

