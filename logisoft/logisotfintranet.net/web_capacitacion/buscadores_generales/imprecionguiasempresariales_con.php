<?php

session_start();

require_once("../Conectar.php");

$link=Conectarse("webpmm");

if($_GET[accion]==1)
	{
		 $sqlt="SELECT catalogocliente.rfc, catalogocliente.id, catalogocliente.nombre,catalogocliente.paterno, catalogocliente.materno,
				catalogoclientenick.nick, direccion.calle, direccion.numero, direccion.colonia, direccion.cp, direccion.poblacion, direccion.estado,direccion.telefono
				FROM catalogocliente
				INNER JOIN catalogoclientenick ON catalogocliente.id=catalogoclientenick.cliente
				INNER JOIN direccion ON catalogocliente.id=direccion.codigo
				WHERE direccion.origen='cl' AND direccion.facturacion='SI' AND catalogocliente.id=".$_GET[idC];
		 $result=mysql_query($sqlt,$link);
		 
		 $registros = array();
		 
		 if(mysql_num_rows($result)>0)
		 {
			 while($f=mysql_fetch_object($result)){
				
			 $f->nombre=cambio_texto($f->nombre);
			 $f->paterno=cambio_texto($f->paterno);
			 $f->materno=cambio_texto($f->materno);
			 $f->nick=cambio_texto($f->nick);
			 $f->calle=cambio_texto($f->calle);
			 $f->colonia=cambio_texto($f->colonia);
			 $f->poblacion=cambio_texto($f->poblacion);
			 $f->estado=cambio_texto($f->estado);
			 
			 $registros[]=$f;
			 
			 }
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "No se encontro ningún Cliente"; 
		}
	
	}
	
//---------------------------------------------------------------------------------------------------------------------------
if($_GET[accion]==1)
	{
		 $sqlt="SELECT solicitudguiasempresariales.id, CONCAT(catalogocliente.nombre,' ',catalogocliente.paterno,' ', catalogocliente.materno) AS nombre_completo, solicitudguiasempresariales.desdefolio, solicitudguiasempresariales.hastafolio,catalogocliente.rfc, catalogocliente.id, catalogocliente.nombre,catalogocliente.paterno, 
catalogocliente.materno, direccion.calle, direccion.numero, direccion.colonia, direccion.cp, direccion.poblacion, direccion.estado,direccion.telefono
FROM solicitudguiasempresariales
INNER JOIN catalogocliente ON  solicitudguiasempresariales.idcliente = catalogocliente.id
INNER JOIN direccion ON catalogocliente.id=direccion.codigo
WHERE solicitudguiasempresariales.id =".$_GET[idF];
		 $result=mysql_query($sqlt,$link);
		 
		 $registros = array();
		 
		 if(mysql_num_rows($result)>0)
		 {
			 while($f=mysql_fetch_object($result)){
				
			 $f->nombre=cambio_texto($f->nombre);
			 $f->paterno=cambio_texto($f->paterno);
			 $f->materno=cambio_texto($f->materno);
			 $f->nick=cambio_texto($f->nick);
			 $f->calle=cambio_texto($f->calle);
			 $f->colonia=cambio_texto($f->colonia);
			 $f->poblacion=cambio_texto($f->poblacion);
			 $f->estado=cambio_texto($f->estado);
			 
			 $registros[]=$f;
			 
			 }
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "No se encontro ningún Cliente"; 
		}
	
	}

?>