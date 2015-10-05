<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l	= Conectarse("webpmm");
	
	if($_POST[guardar_hidden]==1){
		$s = "SELECT tarifaminimakg FROM configuradorgeneral";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$excedio = 0;
		$tarifaminima = $f->tarifaminimakg;
		
		for($i=1; $i<$_POST[cantidadzonas]; $i++){
			if($_POST["caja".$i]<$tarifaminima)
				$excedio = 1;
			$s = "update convenio_configurador_preciokg set valor = '".$_POST["caja".$i]."' 
			where zona = $i and tipo='CONSIGNACION' and isnull(idconvenio) and idusuario = $_SESSION[IDUSUARIO]";
			mysql_query($s,$l);
		}
		$_POST[tienedatoskgc]=1;
	}
	
	if($_GET[idconvenio]){
		
	}else{
		if($_GET[tienedatoskgc]!=1 && $_POST[tienedatoskgc]!= 1){
			$s = "delete from convenio_configurador_preciokg where idusuario = $_SESSION[IDUSUARIO] and tipo='CONSIGNACION' and isnull(idconvenio)";
			mysql_query($s,$l);
			
		}
		$s = "select * from convenio_configurador_preciokg where idusuario = $_SESSION[IDUSUARIO] and tipo='CONSIGNACION' and isnull(idconvenio) order by zona";
		$r = mysql_query($s,$l) or die($s);
		$sihaytabla = mysql_num_rows($r);
		if($sihaytabla<1){
			$s = "INSERT INTO convenio_configurador_preciokg
			SELECT NULL, cd.columna, zoi, zof, null, 'CONSIGNACION', '$_SESSION[NOMBREUSUARIO]', $_SESSION[IDUSUARIO], CURRENT_DATE
			FROM configuraciondetalles AS cd
			GROUP BY zoi";
			mysql_query($s,$l) or die($s."<br>".mysql_error($l));
		}
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
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
.Estilo4 {font-size: 12px}
.Estilo5 {
	font-size: 9px;
	font-family: tahoma;
	font-style: italic;
}
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script>
	var seleccionado = "";
	
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
	
	function seleccionar(id){
		if(id=="caja0"){
			document.all[seleccionado].className = "estilo_cajadesseleccion";
			seleccionado = "";
		}else{
			if(seleccionado!=""){
				document.all[seleccionado].className = "estilo_cajadesseleccion";
			}
			
			seleccionado = id;
			document.all[seleccionado].className = "estilo_cajaseleccion";
			document.all.zonas.value = id.replace("caja","");
			document.all.precio.value = document.all[seleccionado].value;
			document.all.precio.focus();
			document.all.precio.select();
		}
	}
	
	function agregarValor(){
		if(document.all.chktodos.checked){
			for(var i=1; i<=document.all.zonas.options.length-1; i++){
				document.all["caja"+i].value = document.all.precio.value;
			}
			document.all.precio.value = "";
			document.all.chktodos.checked = false;
		}else{
			if(document.all.zonas.value==0){
				alerta("Debe seleccionar una zona","Atencion!","zonas");
			}else{
				document.all[seleccionado].value = document.all.precio.value;
				document.all.precio.value = "";
			}
		}
		
		
		/*if(document.all.zonas.value==0){
			alerta("Debe seleccionar una zona","Atencion!","zonas");
		}else{
			if(document.all.chktodos.checked){
				for(var i=1; i<=document.all.zonas.options.length-1; i++){
					document.all["caja"+i].value = document.all.precio.value;
				}
				document.all.precio.value = "";
				document.all.chktodos.checked = false;
			}else{
				document.all[seleccionado].value = document.all.precio.value;
				document.all.precio.value = "";
			}
		}*/
	}
	
	function guardar(){
		var paso 	= 1;
		var cz		= parseInt(document.all.cantidadzonas.value);
		for(var i=1; i<cz; i++){
			if(document.all["caja"+i].value == ""){
				paso = 0;
			}
		}
		if(paso==0){
			alerta3("Complete los valores de todas las zonas","¡Atencion!");
		}else{
			document.all.guardar_hidden.value=1; 
			document.form1.submit();
		}
	}
</script>
<form name="form1" method="post" action="">
  <br>
<table width="543" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="539" class="FondoTabla Estilo4">Datos Generales  </td>
  </tr>
  <tr>
    <td><table width="535" border="0" align="center" cellpadding="0" cellspacing="0">
      
      <tr>
        <td colspan="2"><table width="535" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="25">Zona</td>
            <td width="211"><label>
              <select name="zonas" style="width:200px" onChange="seleccionar('caja'+this.value)">
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
              </select>
            </label></td>
            <td width="56">Precio KG </td>
            <td width="171"><label>
              <input name="precio" type="text" id="precio" style="width:100px" value="" onKeyPress="if(event.keyCode==13){agregarValor()}else{return tiposMoneda(event,this.value);}">
            </label>
            <input type="hidden" name="tienedatoskgc" value="<?=($_GET[tienedatoskgc]!="")?$_GET[tienedatoskgc]:$_POST[tienedatoskgc]?>">
            </td>
            <td width="72"><div class="ebtn_agregar" onClick="agregarValor()"></div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="535" colspan="2"><table border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><input type="checkbox" name="chktodos"></td>
            <td>Dar valor a todos</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2"><table width="532" border="0" align="left" cellpadding="0" cellspacing="0">
<tr>
            <td width="810" align="right"><div id="divdatos" name="detalle" style="width:535px; height:80px; overflow:auto" align="left">
           			<?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
              <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+55?>">
                <tr>
                  <td height="16" width="55"  class="formato_columnasg">&nbsp;</td>
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
                    <?=$f->zoi?>
                    -
                    <?=$f->zof?></td>
                  <?
							$zona++;
						}
					?>
                </tr>
                <tr>
                  <td  class="formato_columnasg" height="16" >Precio KG</td>
                  <?
				  		if($sihaytabla>0){
							$s = "select * from convenio_configurador_preciokg 
							where idusuario = $_SESSION[IDUSUARIO] and tipo='CONSIGNACION' and isnull(idconvenio)
							order by zona";	
						}else{
							$s = "SELECT * FROM configuraciondetalles GROUP BY zoi";
						}
						$r = mysql_query($s,$l) or die($s);
						$zona = 1;
						while($f = mysql_fetch_object($r)){
					?>
                  <td height="16" ><input type="text" readonly class="estilo_cajadesseleccion"
                  style="width:55; text-align:right" name="caja<?=$zona?>" value="<?=$f->valor?>"
                  onDblClick="seleccionar('caja<?=$zona?>')"></td>
                  <?	
				  		$zona++;
						}
					?>
                </tr>
              </table>
            </div></td>
          </tr>
        </table></td>
      </tr>
      

      <tr>
        <td colspan="2"><input type="hidden" name="cantidadzonas" value="<?=$zona?>"></td>
      </tr>
      <tr>
        <td colspan="2" align="center">
        	<table width="272">
       	    <tr>
           	    <td width="131" align="center"><div class="ebtn_guardar" onClick="guardar()"></div> <input type="hidden" name="guardar_hidden" value="<?=$_POST[guardar_hidden].$_GET[guardar_hidden]?>"></td>
                  <td width="129" align="center"><div class="ebtn_cerrarventana" onClick="parent.VentanaModal.cerrar();"></div></td>
                </tr>
            </table>
        
        </td>
      </tr>
    </table>
      <div align="center"></div></td>
  </tr>
</table>
<p>&nbsp;</p>
</form>
<script>
	<?
	if($_POST[guardar_hidden]==1){
	?> 
	parent.document.all.detallekgc.innerHTML = document.all.divdatos.innerHTML;
	parent.document.all.tienedatoskgc.value = 1;
	parent.document.all.tienedatoskgc_excedio.value = <?=$excedio?>;
	info("Los datos han sido guardados","¡Atencion!");
	<?
	}
	?>
</script>
</body>
</html>


