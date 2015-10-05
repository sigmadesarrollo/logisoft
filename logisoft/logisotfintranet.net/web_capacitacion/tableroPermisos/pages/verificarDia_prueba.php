<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT DAYOFWEEK(ADDDATE(CURRENT_DATE, INTERVAL -1 DAY)) dia";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	if($f->dia==1)
		$diasmenos = 2;
	else
		$diasmenos = 1;
	
	$s = "select i.id, date_format(i.fechainiciodia,'%d/%m/%Y') as fechainiciodia from iniciodia i 
	where not EXISTS(select * from cierredia c where i.id = c.iniciodia and i.sucursal = c.sucursal) AND i.sucursal = ".$_SESSION[IDSUCURSAL]." 
	order by i.fechainiciodia";
	$r = mysql_query($s,$l) or die($s);
	$dias = "";
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_object($r)){
			$dias .= $f->fechainiciodia.",";
		}
		
		$dias = substr($dias,0,strlen($dias)-1);
	}
	
	$s = "select * from iniciodia where sucursal = $_SESSION[IDSUCURSAL] and date(fechainiciodia) = current_date";
	$r = mysql_query($s,$l) or die($s);
	
	$s = "select * from iniciocaja where sucursal = $_SESSION[IDSUCURSAL] and date(fechainiciocaja) = current_date and usuariocaja = $_SESSION[IDUSUARIO]";
	$rx = mysql_query($s,$l) or die($s);
	
	$s = "select * from iniciocajaocurre where sucursal = $_SESSION[IDSUCURSAL] and date(fechainiciocaja) = current_date and usuariocaja = $_SESSION[IDUSUARIO]";
	$ro = mysql_query($s,$l) or die($s);
	
	$s = "select * from iniciocajaabonocliente where sucursal = $_SESSION[IDSUCURSAL] and date(fechainiciocaja) = current_date and usuariocaja = $_SESSION[IDUSUARIO]";
	$ra = mysql_query($s,$l) or die($s);
	
	$s = "SELECT IFNULL(MAX(id),0) as id FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciodia = CURDATE()";
	$ri = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($ri);
	
	$s = "SELECT iniciodia FROM cierredia WHERE iniciodia='".$f->id."'";
	$rc= mysql_query($s,$l) or die($s);
	
	$s = "SELECT * FROM cierrecaja WHERE tipocierre='definitivo' AND fechacierre=CURDATE() AND usuariocaja=$_SESSION[IDUSUARIO] and sucursal = $_SESSION[IDSUCURSAL] ";
	$t = mysql_query($s,$l) or die($s);
	
	$s = "SELECT * FROM cierreprincipal WHERE fechacierre = CURDATE() AND sucursal = $_SESSION[IDSUCURSAL] AND estado = 'CERRADA'";
	$p = mysql_query($s,$l) or die($s);
	
	$cerrarondia = 0;
	$cerraronprincipal = 0;
	$s = "SELECT id FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]."";
	$y = mysql_query($s,$l) or die($s);
	
	if(mysql_num_rows($y)>0){
		$s = "SELECT id FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciodia = ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY)";
		$aa = mysql_query($s,$l) or die($s);
		
		if(mysql_num_rows($aa)>0){
			$s = "SELECT IFNULL(MAX(id),0) as id FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciodia = ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY)";
			$rt = mysql_query($s,$l) or die($s); $ft = mysql_fetch_object($rt);
			
			$s = "SELECT iniciodia FROM cierredia WHERE fechacierredia=ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY) AND sucursal = $_SESSION[IDSUCURSAL] AND iniciodia = $ft->id";
			$ci= mysql_query($s,$l) or die($s);
			$cerrarondia = ((mysql_num_rows($ci)==0)?1:0);
			
			$s = "SELECT * FROM cierreprincipal WHERE fechacierre = ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY) AND sucursal = $_SESSION[IDSUCURSAL] AND estado = 'CERRADA'";
			$cp= mysql_query($s,$l) or die($s);
			$cerraronprincipal = ((mysql_num_rows($cp)==0)?1:0);
		}
	}
	
	$usuario = "";
	$s = "select usuariocaja from iniciocaja where sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciocaja = ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY)";
	echo $s."<br><br>";
	$u = mysql_query($s,$l) or die($s);
	while($us = mysql_fetch_object($u)){				
		$s = "select tipocierre from cierrecaja
		where sucursal = ".$_SESSION[IDSUCURSAL]." AND fechacierre = ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY) AND tipocierre = 'definitivo'
		and usuariocaja = ".$us->usuariocaja."";		
		echo $s."<br><br>";;
		$rr = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($rr)==0){
			$usuario .= $us->usuariocaja.",";			
		}
	}
	echo $usuario."<br><br>";;
	if(!empty($usuario)){
		$empleados = "";
		$usuario = substr($usuario,0,strlen($usuario)-1);
		$row = split(",",$usuario);
		for($i=0;$i<count($row);$i++){
			$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS empleado 
			FROM catalogoempleado WHERE id = ".$row[$i]."";
			$rus = mysql_query($s,$l) or die($s);
			$em= mysql_fetch_object($rus);
			$empleados .= $em->empleado.",";
		}
		
		$usuario = ((!empty($empleados)) ? utf8_encode(substr($empleados,0,strlen($empleados)-1)) : $empleados);
	}
	
	if($_SESSION[IDSUCURSAL]!=1){
		echo "({'iniciodia':".((mysql_num_rows($r)>0)?"'1'":"'0'").",
				'iniciocaja':".((mysql_num_rows($rx)>0)?"'1'":"'0'").",
				'iniciocajaocurre':".((mysql_num_rows($ro)>0)?"'1'":"'0'").",
				'iniciocajaabonocliente':".((mysql_num_rows($ra)>0)?"'1'":"'0'").",
				'cierredia':".((mysql_num_rows($rc)>0)?"'1'":"'0'").",
				'cierrecajadefinitivo':".((mysql_num_rows($t)>0)?"'1'":"'0'").",
				'cierrecajaprincipal':".((mysql_num_rows($p)>0)?"'1'":"'0'").",
				'cerraronprincipal':".$cerraronprincipal.",
				'cerrarondia':".$cerrarondia.",
				'usuarios':'$usuario',
				'dias':'$dias',
				'sucursal':".$_SESSION[IDSUCURSAL]."})";

	}else{
		echo "({'iniciodia':1, 'iniciocaja':1, 
		'iniciocajaocurre':1, 'iniciocajaabonocliente':1, 
		'cierredia':1, 'cierrecajadefinitivo':1, 
		'cierrecajaprincipal':1,
		'sucursal':".$_SESSION[IDSUCURSAL]."})";
	}
	
?>