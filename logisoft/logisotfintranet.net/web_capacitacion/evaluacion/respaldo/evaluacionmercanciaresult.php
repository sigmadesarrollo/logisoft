<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once('../Conectar.php');
	$link=Conectarse('webpmm');
$tipo=$_GET['tipo']; $cantidadempaque=$_GET['cantidad']; $miArray=$_GET['miArray'];$id=$_GET['id']; $usuario=$_GET['usuario']; $fechahora=$_GET['fechahora']; $destino=$_GET['destino']; $accion=$_GET['accion'];

	if($accion==1){
	$s="SELECT con.costo FROM catalogoservicio cs
INNER JOIN configuradorservicios con ON cs.id=con.servicio WHERE cs.id=$id";
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<bolsa>$f->costo</bolsa>			
			<encontro>$cant</encontro>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}
		echo $xml;
	}else if($accion==2){
		$s="SELECT cs.descripcion as servicio, con.condicion, con.costo, con.costoextra, con.limite, con.porcada FROM catalogoservicio cs
INNER JOIN configuradorservicios con ON cs.id=con.servicio WHERE cs.id=$id";
		
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<costo>$f->costo</costo>
			<costoextra>$f->costoextra</costoextra>
			<limite>$f->limite</limite>
			<porcada>$f->porcada</porcada>
			<encontro>$cant</encontro>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}
		echo $xml;	
	}else if($accion==3){
	$s="SELECT IFNULL(SUM(pesototal),0) As pesototal, IFNULL(SUM(volumen),0) As volumen FROM evaluacionmercanciadetalletmp WHERE usuario='$usuario' AND fecha='$fechahora'";			
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<peso>$f->pesototal</peso>
			<volumen>$f->volumen</volumen>			
			<encontro>$cant</encontro>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}
		echo $xml;
	}else if($accion==4){
		$s="SELECT cs.descripcion FROM catalogosucursal cs
			INNER JOIN catalogodestino cd ON cs.id=cd.sucursal
			WHERE cd.id='$destino'";		
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<SucDestino>$f->descripcion</SucDestino>						
			<encontro>$cant</encontro>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}
		echo $xml;
	}
	
	if($tipo=="emplaye"){		
		$sqlemplaye=@mysql_query("SELECT IFNULL(SUM(pesototal),0) As pesototal, IFNULL(SUM(volumen),0) As volumen FROM evaluacionmercanciadetalletmp WHERE usuario='$usuario' AND fecha='$fechahora'",$link);
		$remplaye=@mysql_fetch_array($sqlemplaye);
		$totalpeso=$remplaye[0];
		$totalvol=$remplaye[1];
	}else if($tipo=="grid"){
	$coma=",";
	$lista=split($coma,$miArray);		
			for ($i=0;$i<count($lista);$i++){	
				$var = trim($lista[$i]);
				if ($var!=""){
					$arre[$i]=strtoupper($var);							
				}
			}
			$s = "INSERT INTO evaluacionmercanciadetalletmp (id,cantidad, descripcion, contenido, peso, largo, ancho, alto, volumen, pesototal, pesounit, usuario, fecha) VALUES (null,'$arre[0]', UCASE('$arre[10]'), UCASE('$arre[2]'), '$arre[3]', '$arre[4]', '$arre[5]', '$arre[6]', '$arre[7]', '$arre[8]','$arre[9]', '$usuario','$fechahora')";
		$sqltmp=@mysql_query($s,$link) or die($s);
	;			
	
$sql=@mysql_query("SELECT * FROM contenidos WHERE descripcion='$arre[2]'",$link); 
		if(@mysql_num_rows($sql)==0){
$sqlins=@mysql_query("INSERT INTO contenidos(descripcion)VALUES('$arre[2]')",$link); 		}
	}else if($tipo=="borrar"){
		$sqldel=mysql_query("DELETE FROM evaluacionmercanciadetalletmp WHERE id='$id'",$link);		
	}else if($tipo=="destino"){
		$sql=@mysql_query("SELECT cs.descripcion FROM catalogosucursal cs
			INNER JOIN catalogodestino cd ON cs.id=cd.sucursal
			WHERE cd.id='$destino'",$link);
		$res=@mysql_fetch_array($sql);
		$SucDestino=htmlentities(strtoupper($res[0]));
	}else if($tipo=="modificar"){	
	$id=$_GET['id'];
	$coma=",";
	$lista=split($coma,$miArray);		
			for ($i=0;$i<count($lista);$i++){	
				$var = trim($lista[$i]);
				if ($var!=""){
					$arre[$i]=strtoupper($var);
				}
			}			
	$sqlupd=mysql_query("UPDATE evaluacionmercanciadetalletmp SET cantidad='$arre[0]', descripcion='$arre[10]', contenido='$arre[2]', peso='$arre[3]', largo='$arre[4]', ancho='$arre[5]', alto='$arre[6]', volumen='$arre[7]',pesototal='$arre[8]', pesounit='$arre[9]' WHERE id='$id'",$link);
	}
?>

<?
	if($tipo=="bolsa"){?>
<style type="text/css">
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style3 {	font-size: 9px;
	color: #464442;
}
.style5 {
	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
.style31 {font-size: 9px;
	color: #464442;
}
.style31 {	font-size: 9px;
	color: #464442;
}
.Txtamarillo {font:tahoma; font-size:9px; background-color:#FFFF99;text-transform:uppercase;
}
.Estilo1 {color: #FFFFFF; font-size: 8px; font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; }
.Estilo2 {	font-size: 8px;
	font-weight: bold;
}
-->
</style>

		
		  <input name="costobolsa" type="hidden" id="costobolsa" value="<?=$costobolsa ?>" />
		  <? }else if($tipo=="emplaye"){ ?>
		  <input name="costoemplaye" type="hidden" id="costoemplaye" value="<?=$costoemplaye ?>" />
		  <input name="costoemplayeextra" type="hidden" id="costoemplayeextra" value="<?=$costoemplayeextra ?>" />
		  <input name="totalpeso" type="" id="totalpeso" value="<?=$totalpeso ?>" />
		  <input name="totalvol" type="" id="totalvol" value="<?=$totalvol ?>" />
		  <? }else if($tipo=="grid" || $tipo=="borrar" || $tipo=="modificar"){?>
		  <table width="560" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="5" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
              <td width="27"  background="../img/borde1_2.jpg" class="style5" align="center">&nbsp;</td>
              <td width="29"  background="../img/borde1_2.jpg" class="style5" align="center">CANT</td>
              <td width="111" background="../img/borde1_2.jpg" class="style5" align="center">DESCRIPCION</td>
              <td width="119" background="../img/borde1_2.jpg" class="style5" align="center">CONTENIDO</td>
              <td width="57" background="../img/borde1_2.jpg" class="style5" align="center">PESO KG </td>
              <td width="46" background="../img/borde1_2.jpg" class="style5" align="center">LARGO</td>
              <td width="45" background="../img/borde1_2.jpg" class="style5" align="center">ANCHO</td>
              <td width="28" background="../img/borde1_2.jpg" class="style5" align="center">ALTO</td>
              <td width="57" align="center" background="../img/borde1_2.jpg" class="style5 Estilo2">P. VOLU </td>
              <td width="29" background="../img/borde1_2.jpg" class="style5"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
              <td width="7"  background="../img/borde1_3.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
            </tr>
            <tr>
              <td colspan="12" align="right"><div id="div" name="detalle" style=" height:150px; overflow:auto" align="left">
                  <? $line = 0; ?>
                  <table width="547" border="0" cellspacing="0" cellpadding="0">
                    <?
		//$sql=mysql_query("SELECT * FROM evaluacionmercanciadetalletmp WHERE usuario='$usuario' AND fecha='$fechahora'",$link);
		$sql=@mysql_query("SELECT e.id, e.cantidad, e.descripcion, cd.descripcion As catdes, e.contenido, e.peso, e.largo, e.ancho, e.alto, e.volumen, e.pesototal, e.pesounit FROM evaluacionmercanciadetalletmp e
INNER JOIN catalogodescripcion cd ON e.descripcion=cd.id WHERE e.usuario='$usuario' AND e.fecha='$fechahora'",$link);			
			if(mysql_num_rows($sql)>0){
			$linea=mysql_num_rows($sql);
			while($row=mysql_fetch_array($sql)){?>
                    <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>" ondblclick="ObtenerEvaluacion('<?=$row[id] ?>','<?=$usuario ?>');" >
                      <td height="16" width="17" ><input name="id2" type="hidden" value="<?=$row[id] ?>" /></td>
                      <td width="45" align="center" class="style31"  ><img src="img/delete.png" width="16" height="16" alt="Borrar" onclick="Borrar('<?=$row[id]?>','<?=$usuario ?>','<?=$fechahora ?>')" /><img src="img/update.gif" width="16" height="16" alt="Modificar" onclick="ObtenerEvaluacion('<?=$row[id] ?>','<?=$usuario ?>');" /></td>
                      <td width="32" align="center" class="style31"  ><input name="cantidad" type="text" class="style2" id="cantidad" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" value="<?=htmlentities($row[cantidad])?>" size="8" /></td>
                      <td width="95" align="center" class="style31"><input name="descripcion" type="text" class="style2" id="descripcion" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[catdes]) ?>" readonly="" size="20" /></td>
                      <td width="128" align="center" class="style31"><input name="contenido" type="text" class="style2" id="contenido" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[contenido]) ?>" readonly="" size="20" /></td>
                      <td width="119" class="style31" align="center"><input name="peso" type="text" class="style2" id="peso" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[pesototal]) ?>" readonly="" size="8" /></td>
                      <td width="43" class="style31" align="center"><input name="largo" type="text" readonly="" class="style2" id="largo" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[largo]) ?>" size="5" /></td>
                      <td width="29" class="style31" align="center"><input name="ancho" type="text" readonly="" class="style2" id="ancho" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[ancho]) ?>" size="5" />
                      </td>
                      <td width="22" align="center" class="style31" ><input name="alto" type="text" class="style2" id="alto" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[alto]) ?>" size="5" /></td>
                      <td width="40" align="center" class="style31"><input name="volumen" type="text" class="style2" id="volumen" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$row[volumen] ?>" size="8" /></td>
                    </tr>
                    <?
		$line ++ ; }	
			}else{ $msg="";echo"<input name='msg' type='hidden' value='".$msg."'>";
			while($line<=200){?>
                    <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >
                      <td height="16" width="17" ><input name="id2" type="hidden" id="id2" value="<?=$row[id] ?>" /></td>
                      <td width="45" align="center" class="style31"  >&nbsp;</td>
                      <td width="32" align="center" class="style31"  ><input name="cantidad" type="text" class="style2" id="cantidad" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" value="<?=htmlentities($row[cantidad])?>" size="8" /></td>
                      <td width="95" align="center" class="style31"><input name="descripcion" type="text" class="style2" id="descripcion" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[descripcion]) ?>" readonly="" size="20" /></td>
                      <td width="128" align="center" class="style31"><input name="contenido" type="text" class="style2" id="contenido" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[contenido]) ?>" readonly="" size="20" /></td>
                      <td width="119" class="style31" align="center"><input name="peso" type="text" class="style2" id="peso" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[pesototal]) ?>" readonly="" size="8" /></td>
                      <td width="43" class="style31" align="center"><input name="largo" type="text" readonly="" class="style2" id="largo" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[largo]) ?>" size="5" /></td>
                      <td width="29" class="style31" align="center"><input name="ancho" type="text" readonly="" class="style2" id="ancho" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[ancho]) ?>" size="5" />
                      </td>
                      <td width="22" align="center" class="style31" ><input name="alto" type="text" class="style2" id="alto" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[alto]) ?>" size="5" /></td>
                      <td width="40" align="center" class="style31"><input name="volumen" type="text" class="style2" id="volumen" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$row[volumen] ?>" size="8" /></td>
                    </tr>
                    <?
		$line ++ ; }	
			}
			
	?>
                  </table>
              </div></td>
            </tr>
          </table>		 
		    <? }else if($tipo=="destino"){ ?>		  
		    <input name="SucDestino" type="text" class="Tablas" id="SucDestino" style="background:#FFFF99" value="<?=$SucDestino ?>" size="20" readonly="" />
		    <? }else if($tipo=="imprimir"){?>
		  <table width="50" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td><img src="../img/Boton_Guardar.gif" alt="enviar" width="70" height="20" onclick="Validar();"></td>
            </tr>
        </table>
	   
	      <? }?>		
       
		