<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$_GET[idsucorigen] = $_SESSION[IDSUCURSAL];
	
	if($_GET[accion] == 1){		
		$s = "INSERT INTO evaluacionmercancia SET
		folio = obtenerFolio('evaluacionmercancia',$_SESSION[IDSUCURSAL]),
		fechaevaluacion = current_date,
		estado = 'GUARDADO',
		guiaempresarial = '', 
		recoleccion = 0,
		destino = '".$_GET[destino]."', 
		sucursaldestino = (SELECT cs.descripcion
		FROM catalogodestino cd 
		INNER JOIN catalogosucursal cs ON cd.sucursal = cs.id
		WHERE cd.id = '".$_GET[destino]."'), 
		bolsaempaque = 0,
		cantidadbolsa = 0,
		totalbolsaempaque = 0, 
		emplaye = 0,
		totalemplaye = 0, 
		sucursal = ".$_SESSION[IDSUCURSAL].", 
		usuario = '".$_SESSION[NOMBREUSUARIO]."', 
		fecha = current_timestamp()";			
		$result = mysql_query($s,$l) or die($s);			
		$folio = mysql_insert_id();	
		$s = "SELECT folio FROM evaluacionmercancia WHERE id = ".$folio."";
		$result = mysql_query($s,$l) or die($s); 
		$fx = mysql_fetch_object($result);
		
		$s = "INSERT INTO evaluacionmercanciadetalle SET
			evaluacion = ".$fx->folio.", cantidad = '".$_GET[cuantos]."', descripcion = '$_GET[iddescripcion]',
			contenido = UCASE('".trim($_GET["contenido"])."'), peso = '".$_GET["peso"]."',
			largo = '".$_GET["largo"]."', ancho = '".$_GET["ancho"]."',
			alto = '".$_GET["alto"]."', volumen = '".$_GET["volumen"]."',
			pesototal = '".$_GET["pesototal"]."', pesounit = '".$_GET["pesounit"]."',
			idusuario = ".$_SESSION[IDUSUARIO].", usuario = '".$_SESSION[NOMBREUSUARIO]."', fecha = CURRENT_TIMESTAMP(),
			sucursal = ".$_SESSION[IDSUCURSAL].", espesototal = '0'";
		mysql_query($s,$l);
		
		
		echo "({folio:$fx->folio})";
	}
	
	//solicitar clientes
	if($_GET[accion] == 2){
		$s = "SELECT diascredito FROM solicitudcredito WHERE cliente = $_GET[idcliente]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		if($f->diascredito>0){
			$s = "SELECT IFNULL(SUM(total),0) AS totalvencido FROM pagoguias WHERE pagado = 'N' and pagoguias.credito = 'SI' AND cliente = $_GET[idcliente] AND
			ISNULL(fechacancelacion) AND fechacreo < ADDDATE(CURRENT_DATE, INTERVAL -".$f->diascredito." DAY)";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$vencido = $f->totalvencido;
		}else{
			$vencido = 0;
		}

		$s = "select cc.id, concat_ws(' ', cc.nombre, cc.paterno, cc.materno) as ncliente, cc.rfc, cc.celular,
		cc.personamoral
		from catalogocliente as cc where id = $_GET[idcliente]";
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$cant0 = mysql_num_rows($r);
			$f = mysql_fetch_object($r);
			
			$cliente = "{
				'encontro':'$cant0',
				'idcliente':'$f->id',
				'vencido':'$vencido',
				'ncliente':'".cambio_texto(strtoupper($f->ncliente))."',
				'personamoral':'".cambio_texto(strtoupper($f->personamoral))."',
				'rfc':'".cambio_texto(strtoupper($f->rfc))."',
				'celular':'".cambio_texto($f->celular)."'
			}
			";
			
			$iddir 	= "";
			$and	= " and d.id not in";
			$s = "CREATE TEMPORARY TABLE `direccion_tmp` (  
			 `idx` int(11) NOT NULL auto_increment, 
             `id` double NOT NULL,                  
             `calle` varchar(150) NOT NULL default '',                 
             `numero` varchar(10) NOT NULL default '',                
             `cp` int(5) NOT NULL default '0',                         
             `colonia` varchar(150) NOT NULL default '',               
             `poblacion` varchar(50) NOT NULL default '',                             
             `telefono` varchar(20) NOT NULL default '',                 
             PRIMARY KEY  (`idx`)                                       
           ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
			mysql_query($s,$l) or die($s);
			
			if($_GET[poblacion]!=""){
				$s  = "select id from direccion where origen='cl' and codigo = $_GET[idcliente] and facturacion = 'NO' and poblacion = '$_GET[poblacion]'";
				//echo "<br>$s<br>";
				$rx = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rx)>0){
					$fx 	= mysql_fetch_object($rx);
					$iddir	= "$fx->id";
					
					$s = "insert into direccion_tmp
					select null, d.id, d.calle, d.numero, d.cp, d.colonia, d.poblacion, d.telefono
					from direccion as d
					where origen='cl' and codigo = $_GET[idcliente] and poblacion = '$_GET[poblacion]' and id = $fx->id";
					//echo "<br>$s<br>";
					mysql_query($s,$l) or die($s);
				}
			}
			
			if($_GET[poblacion]!=""){
				$s  = "select id from direccion where origen='cl' and codigo = $_GET[idcliente] and facturacion = 'SI' and poblacion = '$_GET[poblacion]'";
				//echo "<br>$s<br>";
				$rx = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rx)>0){
					$fx 	= mysql_fetch_object($rx);
					$iddir	.= ($iddir=="")?"$fx->id":",$fx->id";
					
					$s = "insert into direccion_tmp
					select null, d.id, d.calle, d.numero, d.cp, d.colonia, d.poblacion, d.telefono
					from direccion as d
					where origen='cl' and codigo = $_GET[idcliente] and facturacion = 'SI' and poblacion = '$_GET[poblacion]' and id = $fx->id";
					//echo "<br>$s<br>";
					mysql_query($s,$l) or die($s);
				}
			}
			$and .= "($iddir)";	
			if($iddir!=""){
				$s = "insert into direccion_tmp
				select null, d.id, d.calle, d.numero, d.cp, d.colonia, d.poblacion, d.telefono
				from direccion as d
				where origen='cl' and codigo = $_GET[idcliente] $and";
				//echo "<br>$s<br>";
				mysql_query($s,$l) or die($s);
			}
			
			
			$s = "select d.id iddireccion, d.calle, d.numero, d.cp codigopostal, d.colonia, d.poblacion, ifnull(d.telefono,0) telefono
			".(($iddir=="")?", crucecalles, municipio ":"")."
			from ".(($iddir=="")?"direccion":"direccion_tmp")." as d
			".(($iddir=="")?" where origen = 'cl' and codigo = $_GET[idcliente] ":" order by idx")."";
			//echo "<br>$s<br>";
			$rx = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rx)>0){
				$cant = mysql_num_rows($rx);
				while($fx = mysql_fetch_object($rx)){
					$fx->iddireccion = cambio_texto(strtoupper($fx->iddireccion));
					$fx->calle = cambio_texto(strtoupper($fx->calle));
					$fx->numero = cambio_texto(strtoupper($fx->numero));
					$fx->codigopostal = cambio_texto(strtoupper($fx->codigopostal));
					$fx->colonia = cambio_texto(strtoupper($fx->colonia));
					$fx->poblacion = cambio_texto(strtoupper($fx->poblacion));
					$fx->telefono = cambio_texto(strtoupper($fx->telefono));
					
					$datos[] = $fx;
				}	
				$direcciones = json_encode($datos);
			}else{
				$direcciones = "[]";
			}
			echo "({
				'cliente':".str_replace('&#32;','',$cliente).",
				'direcciones':".str_replace('&#32;','',$direcciones)."
			})";
		}else{
			echo "({
				'cliente':0
			})";
		}
	}
	
	
	
	?>
