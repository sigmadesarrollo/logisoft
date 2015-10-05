<? 

	require_once('class.ezpdf.php');

	$pdf =& new Cezpdf('a4');

	$pdf->selectFont('fonts/Helvetica.afm');

	$pdf->ezSetCmMargins(1,1,1.5,1.5);	

	

	$conexion = mysql_connect("localhost","pmm","guhAf2eh");

	mysql_select_db("pmm_dbpruebas", $conexion);

	/*$queEmp = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS nombre,email FROM catalogocliente limit 0,10";

	$resEmp = mysql_query($queEmp, $conexion) or die(mysql_error());

	$totEmp = mysql_num_rows($resEmp);*/

	

	$queEmp = "SELECT CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,

	referencia, DATE_FORMAT(fechareferencia,'%d/%m/%Y') AS fechareferencia, factura, importe, 

	asignado, DATE_FORMAT(fechaasignado,'%d/%m/%Y') AS fechaasignado, compromiso, 

	DATE_FORMAT(fecharevision,'%d/%m/%Y') AS fecharevision

	FROM actividadesusuariodetalle_tmp tmp

	INNER JOIN catalogocliente cc ON tmp.cliente = cc.id

	WHERE tmp.idusuario='".$_GET[empleado]."'";	

	$resEmp = mysql_query($queEmp, $conexion) or die(mysql_error());

	$totEmp = mysql_num_rows($resEmp);

	

	$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS nombre FROM catalogoempleado

	WHERE id=".$_GET[empleado]."";

	$r = mysql_query($s,$conexion) or die($s);

	$f = mysql_fetch_object($r);

	

	$ixx = 0;

	while($datatmp = mysql_fetch_assoc($resEmp)) { 

		$ixx = $ixx+1;

		$data[] = array_merge($datatmp);

	}	

	$titles = array(

                'nombre'=>'<b>Cliente</b>',                

				'referencia'=>'<b>Referencia</b>',

				'fecha'=>'<b>Fecha</b>',

				'vencimiento'=>'<b>Fecha Venc</b>',

				'importe'=>'<b>Importe</b>',

				'cobrar'=>'<b>Cobrar</b>',

				'observaciones'=>'<b>Observaciones</b>'

            );

	$options = array(

                'shadeCol'=>array(0.9,0.9,0.9),

                'xOrientation'=>'center',

                'width'=>500

            );



	$txttit = "<b>PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</b>\n";	

	$txttit .= "<b>ACTIVIDADES POR USUARIO</b>\n\n";

	$txttit .= "AL DIA ".date('d/m/Y')."\n";

	$txttit .= "<b>COBRADOR:  </b>";

	$txttit .= "".$f->nombre."\n";

	

	$pdf->ezImage('logo.jpg',0,50,"none","left");

	$pdf->ezText($txttit, 8);	

	//$pdf->ezTable($data,$titles);	

	$pdf->ezTable($data,$titles,'',$options);

	$pdf->ezText("\n\n\n", 10);

	$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10);

	$pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n\n", 10);

	$pdf->ezStream();

	

?>