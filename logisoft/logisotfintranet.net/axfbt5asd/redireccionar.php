<?
	session_start();
	require_once("ConectarSolo.php");
	$l = Conectarse("webpmm");	
	
	$s = "select ccu.idcliente, concat_ws(' ',cc.nombre, cc.paterno, cc.materno) as cliente, 
	if(isnull(cc.sucursal) or cc.sucursal=0,2,cc.sucursal) as sucursal
	from catalogocliente_usuarios ccu
	inner join catalogocliente cc on ccu.idcliente = cc.id
	where ccu.usuario = '$_GET[usuario]' and ccu.password='$_GET[password]'";
	$r = mysql_query($s,$l) or die("Error mysql".$s);
	if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r);
		$_SESSION['IDCLIENTE'] = $f->idcliente;
		$_SESSION['NOMBRECLIENTE'] = $f->cliente;
		$_SESSION['IDSUCURSAL'] = $f->sucursal;		
		header ("location: http://www.pmmentuempresa.com/web/menuprincipal.php?pagina=recoleccion/rec_solo.php");
	}else{
		echo "USTED NO TIENE PERMISOS PARA VER ESTA PAGINA";
	}
?>