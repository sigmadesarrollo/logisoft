<?
function Conectarse($base)
{
	if (!($link=mysql_connect("172.16.40.39","root","root")))
	{
		echo "Error conectando a la base de datos.";
		exit();
	}
	if (!mysql_select_db($base,$link))
	{
		echo "Error seleccionando la base de datos.";
		exit();
	}
	return $link;
}
function folio($tabla,$base)
{	
	$link2=Conectarse($base);
	$confolio="select ifnull(max(folio),0) + 1 as folio from $tabla";
	$resc=mysql_query($confolio,$link2);
	$rowco=mysql_fetch_array($resc);
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
	if($resto==0) {
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
?>

