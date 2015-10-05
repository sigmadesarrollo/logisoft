<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	
	if($_GET['accion']==1){// BUSCAR 
		$s = "SELECT id,foliotipo,prepagada,desdefolio,hastafolio,usuario,status,foliosactivados FROM solicitudguiasempresariales WHERE foliotipo='".$_GET['id']."' AND prepagada='".$_GET['prepagada']."'  ";
		$r = mysql_query($s,$link);// or die("error en linea ".__LINE__);
		$f = mysql_fetch_object($r);
		$cant = mysql_num_rows($r);	
		if(mysql_num_rows($r)>0){
				
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">";
			
			$xml.="<datosX>";
			$xml.="<venta>".cambio_texto($f->foliotipo)."</venta>";
			$xml.="<foliosactivados>".$f->foliosactivados."</foliosactivados>";
			$xml.="<r>".cambio_texto($f->prepagada)."</r>";
			$xml.="<vendedor>".cambio_texto($f->usuario)."</vendedor>";
			$xml.="<status>".cambio_texto($f->status)."</status>";
			$xml.="<encontro>".$cant."</encontro>";
			$xml.="</datosX>";
			
			$xml.="<datos>";
			$folioi=(int)substr($f->desdefolio,3,-1);
			$foliof=(int)substr($f->hastafolio,3,-1);
			$i=(int)substr($f->desdefolio,0,-10);
			$f=substr($f->desdefolio,-1);
			for($folioi;$folioi<=$foliof;$folioi++){
					$a=$i.str_pad($folioi,9,"0",STR_PAD_LEFT).$f;
					$r=mysql_query("SELECT id FROM guiasempresariales where id='".$i.str_pad($folioi,9,"0",STR_PAD_LEFT).$f."'",$link);
					if(mysql_num_rows($r)<1){						
						$xml.="<folio>".$i.str_pad($folioi,9,"0",STR_PAD_LEFT).$f."</folio>";
					}
			}
			$xml.="</datos>";
			$xml.="</xml>";				
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}
		echo $xml;
}
		if($_GET['accion']==2){//activar 
			$s = "update solicitudguiasempresariales set foliosactivados='$_GET[estado]' WHERE id='$_GET[id]'";
			mysql_query($s,$link) or die($s);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<cambio>$_GET[estado]</cambio>
			</datos>
			</xml>";
			echo $xml;
		}
?>
