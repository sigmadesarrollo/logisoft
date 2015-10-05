<? function Conectarse($base){
	//if (!($link=@mysql_connect("10.6.186.45","webpmm","Sistemapmm09")))
	if (!($link=mysql_connect('localhost',"pmm","guhAf2eh"))){
		echo " en conectar Error conectando a la base de datos.";
		exit();
	}
	if($base=="webpmm")
		$base = "pmm_dbpruebas";
	if (!mysql_select_db($base,$link)){
		echo "en conectar Error seleccionando la base de datos.";
		exit();
	}
	return $link;
}
function folio($tabla,$base){
	if($base=="webpmm")
		$base = "pmm_dbpruebas";
	$link2=Conectarse($base);
	$confolio=@mysql_query("select ifnull(max(id),0) + 1 as folio from $tabla",$link2);	
	$rowco=mysql_fetch_array($confolio);
	return $rowco;
}
function ObtenerFolio($tabla,$base){
	if($base=="webpmm")
		$base = "pmm_dbpruebas";
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
	if($texto == " ")
		$texto = "";
	if($texto!=""){
		$n_texto=ereg_replace("&","&#38;",$texto);
		$n_texto=ereg_replace("á","&#224;",$n_texto);
		$n_texto=ereg_replace("é","&#233;",$n_texto);
		$n_texto=ereg_replace("í","&#237;",$n_texto);
		$n_texto=ereg_replace("ó","&#243;",$n_texto);
		$n_texto=ereg_replace("ú","&#250;",$n_texto);
		
		$n_texto=ereg_replace("Á","&#193;",$n_texto);
		$n_texto=ereg_replace("É","&#201;",$n_texto);
		$n_texto=ereg_replace("Í","&#205;",$n_texto);
		$n_texto=ereg_replace("Ó","&#211;",$n_texto);
		$n_texto=ereg_replace("Ú","&#218;",$n_texto);
		
		$n_texto=ereg_replace("ñ", "&#241;", $n_texto);
		$n_texto=ereg_replace("Ñ", "&#209;", $n_texto);
		$n_texto=ereg_replace("¿", "&#191;", $n_texto);
		return $n_texto;
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
/*$sql = "SELECT COLUMN_NAME
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA  LIKE '$nombre_base_datos'
    AND TABLE_NAME = '$nombre_tabla' ";
*/
?>