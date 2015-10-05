<?
	session_start();
	require_once('web_capacitacion/Conectar.php');
	$l=Conectarse('webpmm'); 
	

	$sql = "SELECT * FROM catalogoempleado WHERE user='$_GET[usuario]' and password='$_GET[password]'";
	$rec = mysql_query($sql,$l) or die($sql."<br>".mysql_error());

	//echo $sql;

	if (mysql_num_rows($rec)>0){
			$row=mysql_fetch_array($rec);
			$s = "select * from sesiones where idusuario = $row[id]";
			$r = mysql_query($s,$l) or die($s."<br>".mysql_error());
			
			/*if(mysql_num_rows($r)>0){
				echo "Ya inicio Sesion";
			}else{*/
				
				/*if($row[id]!=1){
					$s = "insert into sesiones set idusuario = ".$row['id'].", ip = '".$_SERVER['REMOTE_ADDR']."', fecha=CURRENT_TIMESTAMP";
					mysql_query($s,$l) or die($s."<br>".mysql_error());
				}*/
				$_SESSION[IDSUCURSAL]=$row['sucursal'];
				$_SESSION[NOMBREUSUARIO]=$row['user'];
				$_SESSION[IDUSUARIO]=$row['id']; 
				$_SESSION[DIRECCIONSISTEMA] = "https://www.pmmintranet.net/web_capacitacion/";
								
				echo "web_capacitacion/menuprincipal/index.php";
           // }
		}else{
			echo "Usuario y password incorrecto";
		}
?>