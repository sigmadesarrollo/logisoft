<?
	header('Content-type: text/xml');
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion] == 1){
		/*if($_GET[campo]=="nick")
			$_GET[campo] = "ccn.nick";
		else
			$_GET[campo] = "cc.".$_GET[campo];*/
		if($_GET[nick]!="")
			$andnick 		= " and nick like '%".$_GET[nick]."%' ";
		if($_GET[rfc]!="")
			$andrfc 		= " and rfc like '%".$_GET[rfc]."%' ";
		if($_GET[id]!="")
			$andid 			= " and id= $_GET[id] ";
		if($_GET[nombre]!="")
			$andnombre 		= " and concat(nombre,paterno,materno) like '%".str_replace(' ','%',$_GET[nombre])."%' ";
		if($_GET[ciudad]!="")
			$andciudad 		= " and sucursal like '$_GET[ciudad]%' ";
		
		$todosands = $andciudad.$andnombre.$andid.$andrfc.$andnick;
		
		
		
		$s = " SELECT * FROM losclientes
		where 1=1 $todosands ";
		
		//echo $s;
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			//echo "entro";
			$cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			while($f = mysql_fetch_object($r)){
			$xml .= "<nick>".cambio_texto(strtoupper($f->nick))."</nick>
				<rfc>".cambio_texto(strtoupper($f->rfc))."</rfc>
				<idcliente>$f->id</idcliente>
				<nombre>".cambio_texto(strtoupper($f->nombre.' '.$f->paterno.' '.$f->materno))."</nombre>
				<sucursal>".cambio_texto(strtoupper($f->sucursal))."</sucursal>
				<credito>".cambio_texto(strtoupper($f->credito))."</credito>
				<convenio>".cambio_texto(strtoupper($f->convenio))."</convenio>";
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