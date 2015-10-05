<? session_start();
	header('Content-type: text/xml');
	require_once('../../Conectar.php');	
	$link=Conectarse('webpmm');
	$usuario=$_SESSION[NOMBREUSUARIO];
	$accion=$_GET['accion'];
	$codigo=$_GET['codigo'];

if($accion==1){
	//MUESTRA LAS RUTAS
	$s="SELECT * from catalogoruta WHERE id='$codigo'";
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">";
			$xml.="<datos>";
			$xml.="<id>$f->id</id>";
			$xml.="<descripcion>$f->descripcion</descripcion>";
			$xml.="<recorrido>$f->recorrido</recorrido>";
			$xml.="<km>$f->km</km>";
			$xml.="<tipounidad>$f->idtipounidad</tipounidad>";
			$xml.="<tipounidad_des>$f->tipounidad</tipounidad_des>";
			$xml.="<antusuario>".cambio_texto($f->usuario)."</antusuario>";
			$sql_detalletmpdel=mysql_query("DELETE FROM catalogorutadetalletmp WHERE idusuario='".$_SESSION[IDUSUARIO]."'",$link) or die(mysql_error($link));
			
			$sql_detalletmp=mysql_query("INSERT INTO catalogorutadetalletmp SELECT 0 as id,tipo, diasalidas, sucursal, horasllegada, tiempodescarga,tiempocarga, horasalida, trayectosucursal,transbordo,sucursalestransbordo,".$_SESSION[IDUSUARIO].", usuario, fecha FROM catalogorutadetalle WHERE ruta='$codigo'",$link);
			
			$con=mysql_query("SELECT RU.id,RU.tipo,RU.diasalidas,SU.prefijo AS sucursal,RU.horasllegada,RU.tiempodescarga,
RU.tiempocarga,RU.horasalida,RU.trayectosucursal,RU.usuario,RU.fecha,RU.transbordo  FROM catalogorutadetalletmp AS RU
INNER JOIN  catalogosucursal AS SU ON SU.id=RU.sucursal
where RU.idusuario='".$_SESSION[IDUSUARIO]."' ORDER BY tipo ASC",$link);

			if(mysql_num_rows($con)>0){
			$consulta="";
			$xml.="<grid>";
			while($row=mysql_fetch_array($con)){
				$consulta.= $row['0'] ."/".$row['1']."/".$row['2']."/".$row['3']."/".substr($row['4'], 0, 5)."/".substr($row['5'], 0, 5)."/".substr($row['6'],0,5)."/".substr($row['7'],0,5)."/".substr($row['8'],0,5)."/".$row['9']."/".$row['11'].",";
			}
			$consulta=substr($consulta,0,-1);
			$xml.=$consulta;
			$xml.="</grid>";
			$xml.="<fechahora>".$f->fecha."</fechahora>";
			}else{
			$xml.="<fechahora>$f->fecha</fechahora>";
			$xml.="<grid>0</grid>";
			}
			$xml.="<hiddenrtipo>--</hiddenrtipo>";
			$xml.="<encontro>$cant</encontro>";
			$xml.="<accion>modificar</accion>";
			$xml.="</datos>";
			
		
			$suc_detalle="SELECT *  FROM catalogorutadetallesucursal WHERE 	idruta = '$codigo'";
			$s = mysql_query($suc_detalle,$link);
			$cant_su = mysql_num_rows($s);
				$f = mysql_fetch_object($s);
				if($f->sucursal=="TODAS"){
						$xml.="<datos>";
						$suc = mysql_query("SELECT id,descripcion FROM catalogosucursal WHERE id > 1",$link);
						$cant = mysql_num_rows($suc);		
						$xml.="<cansuc>".$cant."</cansuc>";
						$xml.="<todas>TODAS</todas>";
						while($row=mysql_fetch_object($suc)){
							$xml.="<idsuc>".cambio_texto($row->id)."</idsuc>";
							$xml.="<suc>".cambio_texto($row->descripcion)."</suc>";
						}
						$xml.="</datos>";
				}else if($cant_su>1){
						$xml.="<datos>";
						$xml.="<todas>NO</todas>";
						$xml.="<cansuc>".$cant_su."</cansuc>";
						$fi = mysql_query($suc_detalle,$link);
						while($rowcu=mysql_fetch_array($fi)){
							$xml.="<idsuc>".cambio_texto($rowcu['idsucursal'])."</idsuc>";	
							$xml.="<suc>".cambio_texto($rowcu['sucursal'])."</suc>";					
						}
						$xml.="</datos>";
				}else{
						$fi = mysql_query($suc_detalle,$link);
						$r= mysql_fetch_array($fi);
						$xml.="<datos>";
						$xml.="<todas>NO</todas>";
						$xml.="<cansuc>".$cant_su."</cansuc>";
						$xml.="<idsuc>".cambio_texto($r['idsucursal'])."</idsuc>";					
						$xml.="<suc>".cambio_texto($r['sucursal'])."</suc>";				
						$xml.="</datos>";
						
				}
			$xml.="</xml>";	
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;	
		
}

if($_GET[accion]==2){
	$suc_detalle="SELECT *  FROM catalogorutadetallesucursal WHERE 	idruta = '$codigo'";
	$s = mysql_query($suc_detalle,$link);
	$cant_su = mysql_num_rows($s);
		$f = mysql_fetch_object($s);
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">";
		if($f->sucursal=="TODAS"){
				$xml.="<datos>";
				$suc = mysql_query("SELECT id,descripcion FROM catalogosucursal WHERE id > 1",$link);		
				$xml.="<cansuc>TODAS</cansuc>";
				//$xml.="<todas>todas</todas>";
				while($row=mysql_fetch_object($suc)){
					$xml.="<idsuc>".cambio_texto($row->id)."</idsuc>";
					$xml.="<suc>".cambio_texto($row->descripcion)."</suc>";
				}
				$xml.="</datos>";
		}else if($cant_su>1){
				$xml.="<datos>";
				$xml.="<cansuc>".$cant_su."</cansuc>";
				$fi = mysql_query($suc_detalle,$link);
				while($rowcu=mysql_fetch_array($fi)){
					$xml.="<idsuc>".cambio_texto($rowcu['idsucursal'])."</idsuc>";	
					$xml.="<suc>".cambio_texto($rowcu['sucursal'])."</suc>";					
				}
				$xml.="</datos>";
		}else{
				$fi = mysql_query($suc_detalle,$link);
				$r= mysql_fetch_array($fi);
				$xml.="<datos>";
				$xml.="<cansuc>".$cant_su."</cansuc>";
				$xml.="<idsuc>".cambio_texto($r['idsucursal'])."</idsuc>";					
				$xml.="<suc>".cambio_texto($r['sucursal'])."</suc>";				
				$xml.="</datos>";
				
		}
		
			
			$xml.="</xml>";	
			echo $xml;	
}

?>