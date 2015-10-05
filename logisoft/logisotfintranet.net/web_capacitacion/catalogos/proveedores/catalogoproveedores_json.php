<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');

	if($_GET['accion']==1){	
		$principal = "";	
		$s = "SELECT razon,nombre,rfc,web,calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado,
		pais,telefono,fax,tipoproveedor FROM catalogoproveedor WHERE id=".$_GET['proveedor'];
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r);
				$f->razon = cambio_texto($f->razon);
				$f->nombre = cambio_texto($f->nombre);
				$f->rfc = cambio_texto($f->rfc);
				$f->web = cambio_texto($f->web);
				$f->calle = cambio_texto($f->calle);
				$f->numero = cambio_texto($f->numero);
				$f->crucecalles = cambio_texto($f->crucecalles);
				$f->cp = cambio_texto($f->cp);				
				$f->colonia = cambio_texto($f->colonia);
				$f->poblacion = cambio_texto($f->poblacion);
				$f->municipio = cambio_texto($f->municipio);
				$f->estado = cambio_texto($f->estado);
				$f->pais = cambio_texto($f->pais);				
				$f->telefono = cambio_texto($f->telefono);
				$f->fax = cambio_texto($f->fax);
				
		$principal = str_replace('null','""',json_encode($f));
		
		$detalle = "";
		
		$s = "SELECT nombre, puesto, telefono, celular,email
		FROM catalogoproveedordetalle WHERE idproveedor='".$_GET['proveedor']."' ";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			while($d = mysql_fetch_object($r)){
				$d->nombre	= cambio_texto($d->nombre);
				$d->puesto	= cambio_texto($d->puesto);
				$d->telefono= cambio_texto($d->telefono);
				$d->celular	= cambio_texto($d->celular);
				$d->email	= cambio_texto($d->email);
				$registros[] = $d;				
			}
		$detalle = str_replace('null','""',json_encode($registros));
		
		echo "({principal:$principal,detalle:$detalle})";
		}else{
			echo "0";
		}
	}
	
	if($_GET['accion']==2){	
		$s = "select ifnull(max(id)+1,1) newid from catalogoproveedor";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		echo $f->newid;
	}

?>
