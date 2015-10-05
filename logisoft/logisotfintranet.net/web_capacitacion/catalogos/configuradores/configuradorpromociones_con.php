<?
	 session_start();
	require_once('../../Conectar.php');
	$l=Conectarse('webpmm');
	
	if($_GET[accion]==1){
				$sql="SELECT id,despues,porcentaje,porcA,porcB FROM configuradorpromociones";
				$r=mysql_query($sql,$l)or die($sql); 
				$registros= array();
		
				if (mysql_num_rows($r)>0){
						while ($f=mysql_fetch_object($r))
						{
							$registros[]=$f;	
						}
						echo str_replace('null','""',json_encode($registros));
				}else{
					echo str_replace('null','""',json_encode(0));
				}
	}
?>
