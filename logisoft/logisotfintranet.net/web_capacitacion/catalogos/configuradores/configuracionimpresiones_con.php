<?
	session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		if($_GET[idusu]!=""){
			$s = "select * from configuracion_impresoras where usuario = $_GET[idusu]";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)<1){
				$s = "insert into configuracion_impresoras set usuario = '$_GET[idusu]',  
				impdefault='$_GET[impdefault]', impetiquetasguias='$_GET[impetiquetasguias]', 
				impetiquetaspaquetes='$_GET[impetiquetaspaquetes]',
				imptickets='$_GET[imptickets]', impevaluaciones='$_GET[impevaluaciones]'
				";
			}else{		
				$s = "UPDATE configuracion_impresoras SET   
				impdefault='$_GET[impdefault]', impetiquetasguias='$_GET[impetiquetasguias]', 
				impetiquetaspaquetes='$_GET[impetiquetaspaquetes]',
				imptickets='$_GET[imptickets]', impevaluaciones='$_GET[impevaluaciones]'
				WHERE usuario = '$_GET[idusu]'";
			}
		}else{
			$s = "select * from configuracion_impresoras where sucursal = $_GET[sucursal]";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)<1){
				$s = "insert into configuracion_impresoras set sucursal='$_GET[sucursal]', 
				impdefault='$_GET[impdefault]', impetiquetasguias='$_GET[impetiquetasguias]', 
				impetiquetaspaquetes='$_GET[impetiquetaspaquetes]',
				imptickets='$_GET[imptickets]', impevaluaciones='$_GET[impevaluaciones]'";
			}else{		
				$s = "UPDATE configuracion_impresoras SET  
				impdefault='$_GET[impdefault]', impetiquetasguias='$_GET[impetiquetasguias]', 
				impetiquetaspaquetes='$_GET[impetiquetaspaquetes]',
				imptickets='$_GET[imptickets]', impevaluaciones='$_GET[impevaluaciones]'
				WHERE sucursal='$_GET[sucursal]'";
			}
		}
		mysql_query($s,$l) or die($s);
		
		echo "guardado";
	}
	
	if($_GET[accion]==2){
		$s = "select * from configuracion_impresoras WHERE usuario='$_GET[usuario]'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)<1){
			$s = "select * from configuracion_impresoras WHERE sucursal='$_GET[sucursal]'";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>1){
				$f->impdefault 				= cambio_texto($f->impdefault);
				$f->impetiquetasguias		= cambio_texto($f->impetiquetasguias);
				$f->impetiquetaspaquetes	= cambio_texto($f->impetiquetaspaquetes);
				$f = mysql_fetch_object($r);
				echo "(".str_replace("null",'""',json_encode($f)).")";
			}else{
				echo "({})";
			}
		}else{
			$f = mysql_fetch_object($r);
			echo "(".str_replace("null",'""',json_encode($f)).")";
		}
	}
	
	if($_GET[accion]==3){
		if($_GET[usuario]!=""){
			$s = "select * from configuracion_impresoras where usuario = '$_GET[usuario]'";
			$r = mysql_query($s,$l) or die($s);
		}else{
			$s = "select * from configuracion_impresoras where sucursal = '$_GET[sucursal]'";
			$r = mysql_query($s,$l) or die($s);
		}
		if(mysql_num_rows($r)>0){
			echo "({'encontrados':'1', 'id':'$f->id'})";
		}else{
			echo "({'encontrados':'0', 'id':'0'})";
		}
	}
	
	if($_GET[accion]==4){
		$s = "SELECT id,CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) as empl FROM catalogoempleado WHERE id = $_GET[id] AND sucursal=$_GET[sucursal]";
		$r = mysql_query($s,$l) or die($s);
		$res = "";
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$res = "({'id':'$f->id','empleado':'$f->empl'";
		}else{
			$res = "({'noencontrado':''";
		}
		$s = "SELECT impdefault, impetiquetasguias, impetiquetaspaquetes, imptickets, impevaluaciones 
		FROM configuracion_impresoras WHERE usuario = $_GET[id]";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$f->impdefault = str_replace("\\","\\\\",$f->impdefault);
			$f->impetiquetasguias = str_replace("\\","\\\\",$f->impetiquetasguias);
			$f->impetiquetaspaquetes = str_replace("\\","\\\\",$f->impetiquetaspaquetes);
			$f->imptickets = str_replace("\\","\\\\",$f->imptickets);
			$f->impevaluaciones = str_replace("\\","\\\\",$f->impevaluaciones);
			$res .= ",'imp5':'$f->impdefault','imp1':'$f->impetiquetasguias','imp2':'$f->impetiquetaspaquetes',
			'imp3':'$f->imptickets','imp4':'$f->impevaluaciones', 'conf':'usuario'";
		}else{
			$s = "SELECT impdefault, impetiquetasguias, impetiquetaspaquetes, imptickets, impevaluaciones 
			FROM configuracion_impresoras WHERE sucursal = $_GET[sucursal]";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				$f->impdefault = str_replace("\\","\\\\",$f->impdefault);
				$f->impetiquetasguias = str_replace("\\","\\\\",$f->impetiquetasguias);
				$f->impetiquetaspaquetes = str_replace("\\","\\\\",$f->impetiquetaspaquetes);
				$f->imptickets = str_replace("\\","\\\\",$f->imptickets);
				$f->impevaluaciones = str_replace("\\","\\\\",$f->impevaluaciones);
				$res .= ",'imp5':'$f->impdefault','imp1':'$f->impetiquetasguias','imp2':'$f->impetiquetaspaquetes',
				'imp3':'$f->imptickets','imp4':'$f->impevaluaciones', 'conf':'sucursal'";
			}else{
				$res .= ",'imp1':'','imp2':'','imp3':'', 'conf':''";
			}
		}
		$res .= "})";
		
		echo cambio_texto($res);
	}
	
	if($_GET[accion]==5){
			$s = "SELECT impdefault, impetiquetasguias, impetiquetaspaquetes, imptickets, impevaluaciones 
			FROM configuracion_impresoras WHERE sucursal = $_GET[sucursal]";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				$f->impdefault = str_replace("\\","\\\\",$f->impdefault);
				$f->impetiquetasguias = str_replace("\\","\\\\",$f->impetiquetasguias);
				$f->impetiquetaspaquetes = str_replace("\\","\\\\",$f->impetiquetaspaquetes);
				$f->imptickets = str_replace("\\","\\\\",$f->imptickets);
				$f->impevaluaciones = str_replace("\\","\\\\",$f->impevaluaciones);
				$res .= "({'imp5':'$f->impdefault','imp1':'$f->impetiquetasguias','imp2':'$f->impetiquetaspaquetes',
				'imp3':'$f->imptickets','imp4':'$f->impevaluaciones', 'conf':'sucursal'";
			}else{
				$res .= "({'imp1':'','imp2':'','imp3':'', 'conf':''";
			}
		$res .= "})";
		
		echo cambio_texto($res);
	}
?>














