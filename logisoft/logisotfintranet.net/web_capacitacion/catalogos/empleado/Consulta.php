<? 	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}	

	if($_GET['accion']==1){
		header('Content-type: text/xml');
		require_once('../../Conectar.php');	
		$link=Conectarse('webpmm');
		$s="SELECT * FROM catalogopuesto WHERE id='".$_GET['puesto']."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
	$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);
		if($f->sbase==""){$f->sbase=0;}
		if($f->comisiones==""){$f->comisiones=0;}		
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>			
			<descripcion>$f->descripcion</descripcion>
			<departamento>$f->departamento</departamento>
			<sbase>$f->sbase</sbase>
			<comisiones>$f->comisiones</comisiones>
			<sminimo>$f->sminimo</sminimo>					
			<encontro>$cant</encontro>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	
	}else if($_GET['accion']==2){
		require_once('../../Conectar.php');	
		$link=Conectarse('webpmm');
	$user = mysql_query("SELECT * FROM catalogoempleado where user = '".@$_REQUEST['Usuario']."'",$link);
		if (mysql_num_rows($user)==0){
		//echo @$_REQUEST['Usuario'].' - Nombre de usuario libre';
		echo 'Nombre de usuario libre';
		}else{
		//echo @$_REQUEST['Usuario'].' - Nombre de usuario ocupado';
		echo 'Nombre de usuario ocupado';
		}
	
	}

?>
