<? 
include("clases/CPermisos.php");
$cpermiso = new CPermiso();

function Conectarse($base){
	//if (!($link=@mysql_connect("10.6.186.45","webpmm","Sistemapmm09")))
	if (!($link=@mysql_connect('mysql.hostinger.mx',"u356875594_pmm","gqx64p9n"))){
		echo " en conectar Error conectando a la base de datos.";
		exit();
	}
	if($base=="webpmm")
		$base = "u356875594_pmm";
	if (!mysql_select_db($base,$link)){
		echo "en conectar Error seleccionando la base de datos.";
		exit();
	}
	if($_SESSION[IDSUCURSAL]!=""){
		$s = "SET @@session.time_zone = (SELECT zonahoraria FROM catalogosucursal WHERE id = '$_SESSION[IDSUCURSAL]');";
		mysql_query($s,$link);
	}
	mysql_set_charset('utf8',$link);
	mysql_query("SET NAMES 'utf8'");
	return $link;
}
function folio($tabla,$base){
	if($base=="webpmm")
		$base = "u356875594_pmm";
	$link2=Conectarse($base);
	$confolio=@mysql_query("select ifnull(max(id),0) + 1 as folio from $tabla",$link2);	
	$rowco=mysql_fetch_array($confolio);
	return $rowco;
}
function ObtenerFolio($tabla,$base){
	if($base=="webpmm")
		$base = "u356875594_pmm";
	$link2=Conectarse($base);
	$confolio=@mysql_query("select ifnull(max(folio),0) + 1 as folio from $tabla",$link2);	
	$rowco=mysql_fetch_array($confolio);
	return $rowco;
}
function cambiaf_a_normal($fecha){ //Convierte fecha de mysql a normal
    	ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha); 
	    $lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1]; 
	    return $lafecha; 
	} 
function cambiaf_a_mysql($fecha){//Convierte fecha de normal a mysql 
    	ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
	    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
    	return $lafecha; 
	} 
function paginacion($total,$pp,$st,$url) {
	if($total>$pp){
		$resto=$total%$pp;
	if($resto==0){
		$pages=$total/$pp;
	}else{
		$pages=(($total-$resto)/$pp)+1;
	}
	if($pages>10) {
		$current_page=($st/$pp)+1;
		if($st==0) {
		$first_page=0;
		$last_page=10;
	}elseif($current_page>=5 && $current_page<=($pages-5)) {
		$first_page=$current_page-5;
		$last_page=$current_page+5;
	}elseif($current_page<5) {
		$first_page=0;
		$last_page=$current_page+5+(5-$current_page);
		}else{
		$first_page=$current_page-5-(($current_page+5)-$pages);
		$last_page=$pages;
		}
	}else{
		$first_page=0;
		$last_page=$pages;
	}
	for($i=$first_page;$i< $last_page;$i++) {
	$pge=$i+1;
	$nextst=$i*$pp;
	if($st==$nextst) {
	$page_nav .= '<b>['.$pge.']'; 
	}else{
	$page_nav .= '<a href="'.$url.$nextst.'">'.$pge.'</a>'; 
	}
}
 
if($st==0) { $current_page = 1; } else { $current_page = ($st/$pp)+1; } 
	if($current_page< $pages){
		$page_last = '<b><a href="'.$url.($pages-1)*$pp.'">  Ultimo</a>';
		$page_next = '<a href="'.$url.$current_page*$pp.'">Siguiente</a>';
	} 
	if($st>0){
		$page_first = '<b><a href="'.$url.'0">Primero  </a></b>'; 
		$page_previous = '<a href="'.$url.''.($current_page-2)*$pp.'">Anterior</a>';
	}
}
 
return "$page_first $page_previous $page_nav $page_next $page_last";
}
function cambio_texto($texto){
	$texto = str_replace("&","&#38;",$texto);
	$texto = str_replace("<","",$texto);
	$texto = str_replace(">","",$texto);
	if(str_replace(" ","",$texto)=="")
		$texto = "";
	if($texto!=""){
		return utf8_encode($texto);
	}else{
		return "&#32;";
	}
}
function get_days_for_month($m,$y){
	if($m == 02){ 
		if(($y % 4 == 0) && (($y % 100 != 0) || ($y % 400 == 0))){
			return 29;
		}else{
			return 28;
		}
	}
		if ($m == 4 || $m == 6 || $m == 9 || $m == 11){
			return 30;
		}else{
			return 31;
		}
}
function calcularHabiles($dia,$mes,$year,$dias){	
	$inhabiles = array();
	$habiles = array();
	for($y=date('Y'); $y<=date('Y')+1; $y++){ 
		for($m=1; $m<=12; $m++){
			for($d=1; $d<=get_days_for_month($m,$y); $d++){
				$date = date('D', mktime(0,0,0,$m,$d,$y));
				if($date == 'Sun'){
					$inhabiles[] = date("d/m/Y", mktime(0,0,0,$m,$d,$y));
				}else{
					if(!in_array(date("d/m/Y", mktime(0,0,0,$m,$d,$y)),$inhabiles)){
						$habiles[] = date("d/m/Y", mktime(0,0,0,$m,$d,$y));
					}
				}
			}
		}
	}
	$date = $dia.'/'.$mes.'/'.$year;
	$contador = array_search($date,$habiles);
	return $habiles[$dias+$contador];
}

function obtenerFechaActual($fecha,$base){	
	if($fecha!=""){
		if($base=="webpmm")
			$base = "u356875594_pmm";
		$l = Conectarse($base);
		$s = "SELECT CONCAT_WS('/',DAYOFWEEK('".cambiaf_a_mysql($fecha)."'), MONTH('".cambiaf_a_mysql($fecha)."')) AS fecha";		
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		$row = split("/",$f->fecha);		
	}else{
		$row = split("/",date('w/n/Y'));
	}
	
	if($fecha==""){	
		switch ($row[0]){
			case 0: $dia = "Domingo "; break; 
			case 1: $dia = "Lunes "; break; 
			case 2:	$dia = "Martes "; break; 
			case 3:	$dia = "Miercoles "; break; 
			case 4:	$dia = "Jueves "; break; 
			case 5:	$dia = "Viernes "; break; 
			case 6: $dia = "Sabado "; break;
		}
	}else{
		switch ($row[0]){
			case 1: $dia = "Domingo "; break; 
			case 2: $dia = "Lunes "; break; 
			case 3:	$dia = "Martes "; break; 
			case 4:	$dia = "Miercoles "; break; 
			case 5:	$dia = "Jueves "; break; 
			case 6:	$dia = "Viernes "; break; 
			case 7: $dia = "Sabado "; break;
		}
	}
	
	switch ($row[1]){		
		case 1: $mes = "Enero"; break; 
		case 2:	$mes = "Febrero"; break; 
		case 3: $mes = "Marzo"; break; 
		case 4: $mes = "Abril"; break; 
		case 5: $mes = "Mayo"; break; 
		case 6: $mes = "Junio"; break; 
		case 7: $mes = "Julio"; break; 
		case 8: $mes = "Agosto"; break; 
		case 9: $mes = "Septiembre"; break; 
		case 10: $mes = "Octubre"; break;
		case 11: $mes = "Noviembre"; break; 
		case 12: $mes = "Diciembre"; break;  
	}
	
	if($fecha==""){
		return $dia.date('d')." de ".$mes." del ".date('Y');
	}else{
		$t = split("/",$fecha);
		return $dia.$t[0]." de ".$mes." del ".$t[2];
	}
}
/*$sql = "SELECT COLUMN_NAME
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA  LIKE '$nombre_base_datos'
    AND TABLE_NAME = '$nombre_tabla' ";
*/

/*
var f1 = u.inicio.value.split("/");
		var f2 = u.fin.value.split("/");
		
		if(f1[0].substr(0,1)=="0"){
			f1[0] = f1[0].substr(1,1);
		}
		if(f1[1].substr(0,1)=="0"){
			f1[1] = f1[1].substr(1,1);
		}
		
		if(f2[0].substr(0,1)=="0"){
			f2[0] = f2[0].substr(1,1);
		}
		if(f2[1].substr(0,1)=="0"){
			f2[1] = f2[1].substr(1,1);
		}
		
		f1 = new Date(f1[2],f1[1],f1[0]);
		f2 = new Date(f2[2],f2[1],f2[0]);
		
		if(f1 > f2){
			mens.show("A","La Fecha inicio no debe ser mayor a la Fecha fin","�Atenci�n!","inicio");
			return false;
		}

*/
?>