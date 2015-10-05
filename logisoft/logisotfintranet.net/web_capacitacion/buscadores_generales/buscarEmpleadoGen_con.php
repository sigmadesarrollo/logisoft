<?


	header('Content-type: text/xml');


	require_once("../Conectar.php");


	$l = Conectarse("webpmm");


	


	if($_GET[accion] == 1){		


		$_GET[campo] = "cc.".$_GET[campo];


		


		$todosands = "";


		


		if($_GET[numempleado]!="")


			$andnumempleado	= " and cc.numempleado= '$_GET[numempleado]' ";


		if($_GET[rfc]!="")


			$andrfc 		= " and cc.rfc = '$_GET[rfc]' ";		


		if($_GET[nombre]!="")


			$andnombre 		= " and cc.nombre like '$_GET[nombre]%' ";


		if($_GET[paterno]!="")


			$andpaterno 	= " and cc.apellidopaterno like '$_GET[paterno]%' ";


		if($_GET[materno]!="")


			$andmaterno 	= " and cc.apellidomaterno like '$_GET[materno]%' ";


		if($_GET[sucursal]!="")


			$andsucursal 		= " and cs.descripcion = '$_GET[sucursal]' ";


		


		$todosands = $andsucursal.$andmaterno.$andpaterno.$andnombre.$andnumempleado.$andrfc;


				


		$s = "select cc.id, cc.rfc, cc.numempleado, cc.nombre, cc.apellidopaterno, cc.apellidomaterno 


		from catalogoempleado as cc


		left join catalogosucursal cs on cc.sucursal = cs.id


		where cc.id>0  $todosands group by cc.id";


		


		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);





		if(mysql_num_rows($r)>0){


			$cant = mysql_num_rows($r);


			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 


			<datos>";


			while($f = mysql_fetch_object($r)){


			$xml .= "<id>".$f->id."</id>


				<rfc>".cambio_texto(strtoupper($f->rfc))."</rfc>


				<numempleado>".cambio_texto(strtoupper($f->numempleado))."</numempleado>


				<nombre>".cambio_texto(strtoupper($f->nombre))."</nombre>


				<paterno>".cambio_texto(strtoupper($f->apellidopaterno))."</paterno>


				<materno>".cambio_texto(strtoupper($f->apellidomaterno))."</materno>";


			}


			$xml .= "<encontro>$cant</encontro>


			</datos>


			</xml>";


		}else{


			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 


			<datos>


			<encontro>0</encontro>


			</datos>


			</xml>";


		}


	}


	echo $xml;


	


?>


