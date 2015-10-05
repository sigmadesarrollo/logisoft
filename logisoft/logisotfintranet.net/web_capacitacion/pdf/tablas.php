<? 
	require_once('class.ezpdf.php');
	$pdf =& new Cezpdf('a4');
	$pdf->selectFont('fonts/Helvetica.afm');
	$pdf->ezSetCmMargins(1,1,1.5,1.5);	
	
	$conexion = mysql_connect("localhost","root","");
	mysql_select_db("webpmm", $conexion);
	$queEmp = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS nombre,email FROM catalogocliente limit 0,10";
	$resEmp = mysql_query($queEmp, $conexion) or die(mysql_error());
	$totEmp = mysql_num_rows($resEmp);
	
	$ixx = 0;
	while($datatmp = mysql_fetch_assoc($resEmp)) { 
		$ixx = $ixx+1;
		$data[] = array_merge($datatmp);
	}	
	$titles = array(
                'cantidad'=>'<b>Cantidad</b>',                
				'concepto'=>'<b>Conceptos</b>',
				'importe'=>'<b>Importe</b>'				
            );
	$options = array(
                'shadeCol'=>array(0.9,0.9,0.9),
                'xOrientation'=>'center',
                'width'=>500
            );

	$txttit = "<b>PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</b>\n";	
	$txttit .= "<b>RELACION DE COBRANZA</b>\n\n";
	$txttit .= "DEL DIA JUEVES 20 DE NOVIEMBRE DEL 2009\n";
	$txttit .= "<b>COBRADOR:  </b>";
	$txttit .= "JUAN PEREZ\n";
	$txttit .= "<b>UNIDAD:  </b>";
	$txttit .= "K-300\n";
	
	$pdf->ezImage('logo.jpg',0,50,"none","left");
	$pdf->ezText($txttit, 8);

	$pdf->ezTable($data,$titles,'',$options);
	$pdf->ezText("\n\n\n", 10);
	$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10);
	$pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n\n", 10);
	$pdf->ezStream();
	
?>
<?php /*
include ('class.ezpdf.php');
$pdf =& new Cezpdf();
$pdf->selectFont('./fonts/Helvetica.afm');
$data = array(array('num'=>1,'name'=>'gandalf','type'=>'wizard'),array('num'=>2,'name'=>'bilbo','type'=>'hobbit','url'=>'http://www.ros.co.nz/pdf/'),array('num'=>3,'name'=>'frodo','type'=>'hobbit'),array('num'=>4,'name'=>'saruman','type'=>'bad
	dude','url'=>'http://sourceforge.net/projects/pdf-php'),array('num'=>5,'name'=>'sauron','type'=>'really bad dude'));
$pdf->ezTable($data);
$pdf->ezStream();*/
?>