<?

	include('../../Conectar.php');

	$link=Conectarse('webpmm');	

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

</head>



<body>

		<table width="465" border="0" cellspacing="0" cellpadding="0">

          <tr>

            <td width="465">

			<table width="459" border="0" cellspacing="0" cellpadding="0">

			<? 	$sql=mysql_query("SELECT mi.descripcion As Interfaces, 0 As Autorizar FROM modulos m INNER JOIN modulosinterfaces mi ON m.id=mi.idmodulo",$link);				

			while($res=mysql_fetch_array($sql)){?>

				<tr>

					<td><?=$res[0]; ?></td>

					<td><input name="autorizar" type="checkbox" value="1" <? if($autorizar==$row[1]){echo "checked";} ?> ></td>

				</tr>	

				  <? } ?>			  

            </table>

			</td>

          </tr>

        </table>

</body>

</html>

