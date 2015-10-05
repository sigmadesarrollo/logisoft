<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$_GET[idsucorigen] = $_SESSION[IDSUCURSAL];
	
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
				'cliente':$cliente,
				'direcciones':$direcciones
			})";
		}else{
			echo "({
				'cliente':0
			})";
		}
	}
	
	?>
