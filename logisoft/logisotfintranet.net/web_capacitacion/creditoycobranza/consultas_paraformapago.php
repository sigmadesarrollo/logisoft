<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Untitled Document</title>

</head>



<body>

<?

	$s = "INSERT INTO formapago SET guia='$newfolio',procedencia='G',tipo='V',total='$_GET[total]',efectivo='$_GET[efectivo]',

		tarjeta='$_GET[tarjeta]',transferencia='$_GET[trasferencia]',cheque='$_GET[cheque]',ncheque='$_GET[ncheque]',banco='$_GET[banco]',notacredito='$_GET[nc]',

		nnotacredito='$_GET[nc_folio]',sucursal='$_SESSION[IDSUCURSAL]',usuario='$_SESSION[IDUSUARIO]',fecha=current_date";

		@mysql_query(str_replace("''","null",$s),$l) or die($s);

?>





                          <input type="hidden" name="nc_folio">

                          <input type="hidden" name="nc">

                          <script>

						  	

		var nc 						= u.nc.value.replace("$ ","").replace(/,/g,"");

		var nc_folio				= u.nc_folio.value.replace("$ ","").replace(/,/g,"");

						  </script>

</body>

</html>

