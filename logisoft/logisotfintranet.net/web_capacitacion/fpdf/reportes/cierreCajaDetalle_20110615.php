<?	//die("DISCULPE LAS MOLESTIAS EN ESTE MOMENTO EL REPORTE SE ENCUENTRA FUERA DE SERVICIO");
	require_once("../../Conectar.php");
	require_once("../fpdf.php");

	class pdf extends FPDF{
		var $widths;
		var $aligns;
		var $total;
		
		function Header(){
			require_once("../../Conectar.php");
			$l = Conectarse("webpmm");
			$s = "SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal]; $r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
			$this->SetFont('Arial','B',15);		
			//Movernos a la derecha		
			$this->Cell(80);		
			//Titulo		
			$this->Cell(30,10,'ENTREGAS PUNTUALES S DE RL DE CV',0,0,'C');		
			//Salto de linea
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->Cell(70,10,'REPORTE: CORTE DIARIO                                  FECHA IMPRESO:'.date('d/m/Y').'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'SUCURSAL: '.$f->descripcion.'',0,0,'L');
			$this->Ln(5);
			if(!empty($_GET[fecha])){
				$this->Cell(70,10,'FECHA: DEL DIA '.strtoupper(obtenerFechaActual($_GET[fecha],"webpmm")).'',0,0,'L');
			}else{
				$this->Cell(70,10,'FECHA: DEL DIA '.$_GET[fechainicio].' AL '.((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]).'',0,0,'L');
			}
			$this->Ln(13);
		
		}
		
		function Titulos($titulos,$medidas){
			$this->SetFont('Arial','B',7);
			for($i=0;$i<count($titulos);$i++){	
				$this->Cell($medidas[$i],7,$titulos[$i],1,0,'C');						
			}
			$this->Ln();
		}
		
		function Footer(){
			//Posición: a 1,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		}
		
		function addLeyenda2($ref,$posicion,$posicion2){
			$this->SetFont( "Arial", "B", 7);
			$length = $this->GetStringWidth($ref);
			$r1  = $posicion2;
			$r2  = $r1 + $length;
			$y1  = $posicion;
			$y2  = $y1+5;
			$this->SetXY( $r1 , $y1 );
			$this->Cell($length,4, $ref);
		}
		
		function SetWidths($w){
			//Set the array of column widths
			$this->widths=$w;
		}
		
		function SetAligns($a){
			//Set the array of column alignments
			$this->aligns=$a;
		}
		
		function Row($data){
			//Calculate the height of the row
			$nb=0;
			for($i=0;$i<count($data);$i++)
				$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
			$h=5*$nb;
			//Issue a page break first if needed
			$this->CheckPageBreak($h);
			//Draw the cells of the row
			for($i=0;$i<count($data);$i++){
				$w=$this->widths[$i];
				$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
				//Save the current position
				$x=$this->GetX();
				$y=$this->GetY();
				//Draw the border
				//$this->Rect($x,$y,$w,$h);
				//Print the text
				$data[5] = "";
				$data[6] = "";
				$dato = "-".$data[$i]."-";				
				if(!is_numeric($data[$i])){
					$this->MultiCell($w,5,utf8_encode($data[$i]),0,$a);
				}else{
					if(preg_match($dato,".")){
						$this->MultiCell($w,5, "$".number_format($data[$i],2,".",","),0,'R');
					}else{
						$this->MultiCell($w,5,$data[$i],0,'R');
					}
				}
				//Put the position to the right of the cell
				$this->SetXY($x+$w,$y);
			}
			//Go to the next line
			$this->Ln($h);
		}
		
		function CheckPageBreak($h){
			//If the height h would cause an overflow, add a new page immediately
			if($this->GetY()+$h>$this->PageBreakTrigger)
				$this->AddPage($this->CurOrientation);
		}
		
		function NbLines($w,$txt){
			//Computes the number of lines a MultiCell of width w will take
			$cw=&$this->CurrentFont['cw'];
			if($w==0)
				$w=$this->w-$this->rMargin-$this->x;
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			$s=str_replace("\r",'',$txt);
			$nb=strlen($s);
			if($nb>0 and $s[$nb-1]=="\n")
				$nb--;
			$sep=-1;
			$i=0;
			$j=0;
			$l=0;
			$nl=1;
			while($i<$nb)
			{
				$c=$s[$i];
				if($c=="\n")
				{
					$i++;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
					continue;
				}
				if($c==' ')
					$sep=$i;
				$l+=$cw[$c];
				if($l>$wmax)
				{
					if($sep==-1)
					{
						if($i==$j)
							$i++;
					}
					else
						$i=$sep+1;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
				}
				else
					$i++;
			}
			return $nl;
		}
	}
	
	$pdf = new pdf();
	$pdf->AliasNbPages();		
	
	$s = "SELECT gv.id as guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') as fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, gv.total AS importe, gv.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) as empleado, if(gv.ocurre=1,'Ocurre','EAD') ocurre
	FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
	INNER JOIN catalogosucursal csx ON '$_GET[sucursal]' = csx.id
	LEFT JOIN catalogoempleado ce ON gv.idusuario = ce.id";
	
	//1) GUIAS CONTADO
	$criterioguiascontado = " INNER JOIN catalogocliente cc ON gv.idremitente = cc.id 
	LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
	LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA' 
	WHERE gv.id like '%A' AND gv.tipoflete = 0 AND gv.condicionpago = 0 AND 
	IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = ".$_GET[sucursal]." ,IF(gv.idsucursalorigen=".$_GET[sucursal]." AND SUBSTRING(gv.id,1,3)=csx.idsucursal,gv.idsucursalorigen = ".$_GET[sucursal].",hc2.sucursal = ".$_GET[sucursal]."))
	AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND (gv.estado<>'CANCELADO' OR (gv.estado='CANCELADO' AND NOT ISNULL(hc.id))) ORDER BY gv.idusuario";
	obtenerDatos($s.$criterioguiascontado,$pdf,"GUIAS CONTADO","SI");
	
	//2) GUIAS CREDITO
	$criterioguiascredito = " INNER JOIN catalogocliente cc ON gv.idremitente = cc.id 
	LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
	LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
	WHERE gv.id like '%A' AND  gv.tipoflete = 0 AND gv.condicionpago = 1 AND 
	IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = ".$_GET[sucursal]." ,IF(gv.idsucursalorigen=".$_GET[sucursal]." AND SUBSTRING(gv.id,1,3)=csx.idsucursal,gv.idsucursalorigen = ".$_GET[sucursal].",hc2.sucursal = ".$_GET[sucursal]."))
	AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND (gv.estado<>'CANCELADO' OR (gv.estado='CANCELADO' AND NOT ISNULL(hc.id))) ORDER BY gv.idusuario";
	obtenerDatos($s.$criterioguiascredito,$pdf,"GUIAS CREDITO","SI");
	
	//3) GUIAS POR COBRAR CONTADO
	$criterioguiascobrarcontado = " INNER JOIN catalogocliente cc ON gv.iddestinatario = cc.id 
	LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
	LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA' 
	WHERE gv.id like '%A' AND  gv.tipoflete = 1 AND gv.condicionpago = 0 AND 
	IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = ".$_GET[sucursal]." ,IF(gv.idsucursalorigen=".$_GET[sucursal]." AND SUBSTRING(gv.id,1,3)=csx.idsucursal,gv.idsucursalorigen = ".$_GET[sucursal].",hc2.sucursal = ".$_GET[sucursal].")) 
	AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND (gv.estado<>'CANCELADO' OR (gv.estado='CANCELADO' AND NOT ISNULL(hc.id))) ORDER BY gv.idusuario";
	obtenerDatos($s.$criterioguiascobrarcontado,$pdf,"GUIAS POR COBRAR CONTADO","SI");
	
	//4) GUIAS POR COBRAR CREDITO
	$criterioguiascobrarcredito = " INNER JOIN catalogocliente cc ON gv.iddestinatario = cc.id 
	LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
	LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA' 
	WHERE gv.id like '%A' AND  gv.tipoflete = 1 AND gv.condicionpago = 1 AND 
	IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = ".$_GET[sucursal]." ,IF(gv.idsucursalorigen=".$_GET[sucursal]." AND SUBSTRING(gv.id,1,3)=csx.idsucursal,gv.idsucursalorigen = ".$_GET[sucursal].",hc2.sucursal = ".$_GET[sucursal].")) 
	AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND (gv.estado<>'CANCELADO' OR (gv.estado='CANCELADO' AND NOT ISNULL(hc.id))) ORDER BY gv.idusuario";
	obtenerDatos($s.$criterioguiascobrarcredito,$pdf,"GUIAS POR COBRAR CREDITO","SI");	
	
	//5) FACTURACION DE VENTAS GUIAS PREPAGADAS CONTADO
	$criterioprepagadascontado = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, sum(fd.total) AS importe, f.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalle fd ON f.folio=fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE fd.tipoguia='PREPAGADA' AND
	date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND f.credito='NO' AND f.idsucursal = ".$_GET[sucursal]." AND f.facturaestado!='CANCELADO' 
	GROUP BY f.folio ORDER BY f.idusuario";	
	obtenerDatos($criterioprepagadascontado,$pdf,"INGRESO FACTURACION DE GUIAS PREPAGADAS CONTADO","SI");
	
	//6) FACTURACION DE VENTAS GUIAS PREPAGADAS CREDITO
	$criterioprepagadascredito = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, sum(fd.total) AS importe, f.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalle fd ON f.folio=fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE fd.tipoguia='PREPAGADA' AND 
	date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND f.credito='SI' AND f.idsucursal = ".$_GET[sucursal]." AND f.facturaestado!='CANCELADO'
	GROUP BY f.folio ORDER BY f.idusuario";	
	obtenerDatos($criterioprepagadascredito,$pdf,"INGRESO FACTURACION DE GUIAS PREPAGADAS CREDITO","SI");
	
	//7) FACTURACION DE GUIAS CONSIGNACION CONTADO
	$criterioconsignacioncontado = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, IFNULL(SUM(fd.total),0) AS importe,
	f.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalle fd ON f.folio = fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.tipoguia='empresarial' AND f.credito = 'NO' AND f.tipofactura='NORMAL'
	AND fd.tipoguia='CONSIGNACION' AND f.idsucursal = ".$_GET[sucursal]." AND f.facturaestado!='CANCELADO'
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	GROUP BY f.folio ORDER BY f.idusuario";
	obtenerDatos($criterioconsignacioncontado,$pdf,"INGRESO FACTURACION DE GUIAS CONSIGNACION CONTADO","SI");	
	
	//8) FACTURACION DE GUIAS CONSIGNACION CREDITO
	$criterioconsignacioncredito = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, IFNULL(SUM(fd.total),0) AS importe,
	f.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalle fd ON f.folio = fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.tipoguia='empresarial' AND f.credito = 'SI' AND f.tipofactura='NORMAL' AND fd.tipoguia='CONSIGNACION' 
	AND f.idsucursal = ".$_GET[sucursal]." AND f.facturaestado!='CANCELADO'
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	GROUP BY f.folio ORDER BY f.idusuario";
	obtenerDatos($criterioconsignacioncredito,$pdf,"INGRESO FACTURACION DE GUIAS CONSIGNACION CREDITO","SI");
	
	//09)FACTURACION DE SOBREPESO CONTADO
	$criteriofacturacionsobrepesocontado = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, sum(fd.total) AS importe, f.idusuario, 
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalleguias fd ON f.folio = fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.credito = 'NO' AND f.tipofactura='NORMAL' AND f.idsucursal = ".$_GET[sucursal]." 
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND fd.concepto LIKE '%EXCEDENTE%' AND f.facturaestado!='CANCELADO'
	GROUP BY f.folio ORDER BY f.idusuario";
	obtenerDatos($criteriofacturacionsobrepesocontado,$pdf,"INGRESO FACTURACION DE SOBREPESO CONTADO","SI");
	
	//10)FACTURACION DE SOBREPESO CREDITO
	$criteriofacturacionsobrepesocredito = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, sum(fd.total) AS importe, f.idusuario, 
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalleguias fd ON f.folio = fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.credito = 'SI' AND f.tipofactura='NORMAL' AND f.idsucursal = ".$_GET[sucursal]."
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND fd.concepto LIKE '%EXCEDENTE%' AND f.facturaestado!='CANCELADO'
	GROUP BY f.folio ORDER BY f.idusuario";
	obtenerDatos($criteriofacturacionsobrepesocredito,$pdf,"FACTURACION DE SOBREPESO CREDITO","SI");
	
	//11)FACTURACION DE VALOR DECLARADO CONTADO
	$criteriofacturacionvalordeclaradocontado = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, sum(fd.total) AS importe, f.idusuario, 
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalleguias fd ON f.folio = fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.credito = 'NO' AND f.tipofactura='NORMAL' AND f.idsucursal = ".$_GET[sucursal]." 
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND fd.concepto LIKE '%VALOR DECLARADO%' AND f.facturaestado!='CANCELADO'
	GROUP BY f.folio ORDER BY f.idusuario";
	obtenerDatos($criteriofacturacionvalordeclaradocontado,$pdf,"FACTURACION DE VALOR DECLARADO CONTADO","SI");
	
	//12)FACTURACION DE VALOR DECLARADO CREDITO
	$criteriofacturacionvalordeclaradocredito = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, sum(fd.total) AS importe, f.idusuario, 
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM facturacion f
	INNER JOIN facturadetalleguias fd ON f.folio = fd.factura
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.credito = 'SI' AND f.tipofactura='NORMAL' AND f.idsucursal = ".$_GET[sucursal]." 
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND fd.concepto LIKE '%VALOR DECLARADO%' AND f.facturaestado!='CANCELADO'
	GROUP BY f.folio ORDER BY f.idusuario";
	obtenerDatos($criteriofacturacionvalordeclaradocredito,$pdf,"FACTURACION DE VALOR DECLARADO CREDITO","SI");
	
	//13)FACTURACION DE OTROS CONCEPTOS CONTADO
	$criteriofacturacionotrosconceptoscontado = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, f.otrosmontofacturar AS importe, f.idusuario, 
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado FROM facturacion f
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.idsucursal = ".$_GET[sucursal]." AND f.tipofactura='NORMAL' AND f.credito = 'NO' AND f.otrosmontofacturar > 0
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND f.facturaestado!='CANCELADO'
	ORDER BY f.idusuario";
	obtenerDatos($criteriofacturacionotrosconceptoscontado,$pdf,"FACTURACION DE OTROS CONCEPTOS CONTADO","SI");
	
	//13)FACTURACION DE OTROS CONCEPTOS CREDITO
	$criteriofacturacionotrosconceptoscredito = "SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, f.otrosmontofacturar AS importe, f.idusuario, 
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado FROM facturacion f
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id
	WHERE f.idsucursal = ".$_GET[sucursal]." AND f.tipofactura='NORMAL' AND f.credito = 'SI' AND f.otrosmontofacturar > 0
	AND date(f.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND f.facturaestado!='CANCELADO'
	ORDER BY f.idusuario";
	obtenerDatos($criteriofacturacionotrosconceptoscredito,$pdf,"FACTURACION DE OTROS CONCEPTOS CREDITO","SI");
	
	//6) CORREO INTERNO
	$criterioguiascorreointerno = "SELECT c.guia, DATE_FORMAT(fechacorreo,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ',de.nombre,de.apellidopaterno,de.apellidomaterno) AS destinatario,
	cs.prefijo AS destino, 0 AS importe, c.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM correointerno c
	LEFT JOIN catalogoempleado de ON c.destintario = de.id
	INNER JOIN catalogosucursal cs ON c.sucdestino = cs.id
	LEFT JOIN catalogoempleado ce ON c.idusuario = ce.id
	WHERE c.sucorigen = ".$_GET[sucursal]."
	AND c.fechacorreo BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND c.estado='GUARDADO' ORDER BY c.idusuario";	
	obtenerDatos($criterioguiascorreointerno,$pdf,"CORREO INTERNO","SI");	
	
	//12) CORREO INTERNO FORANEO RECIBIDO
	$criterioguiasforaneacorreointerno = "SELECT c.guia, DATE_FORMAT(fechacorreo,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ',de.nombre,de.apellidopaterno,de.apellidomaterno) AS destinatario,
	cs.prefijo AS destino, 0 AS importe, c.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM correointerno c
	LEFT JOIN catalogoempleado de ON c.destintario = de.id
	INNER JOIN catalogosucursal cs ON c.sucdestino = cs.id
	LEFT JOIN catalogoempleado ce ON c.idusuario = ce.id
	WHERE c.sucdestino = ".$_GET[sucursal]." 
	AND c.fechacorreo BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND c.estado='GUARDADO' order by ce.id";
	obtenerDatos($criterioguiasforaneacorreointerno,$pdf,"CORREO INTERNO FORANEO RECIBIDO","");
	
	//13) INGRESOS POR COBRANZA DE GUIAS A CREDITO
	$criterioingresocobranzacredito = "SELECT * FROM(
	SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(a.fecharegistro,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente, cs.prefijo AS destino,
	IFNULL(f.total + f.sobmontoafacturar + f.otrosmontofacturar,0) AS importe,
	a.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM abonodecliente a
	INNER JOIN abonodecliente_facturas af ON a.id = af.folioabono and a.idsucursal = af.sucursal
	INNER JOIN facturacion f ON af.factura = f.folio
	INNER JOIN catalogocliente cc ON a.idcliente = cc.id
	INNER JOIN catalogosucursal cs ON a.idsucursal = cs.id
	LEFT JOIN catalogoempleado ce ON a.idusuario = ce.id
	WHERE a.fecharegistro BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND a.idsucursal = ".$_GET[sucursal]." AND f.facturaestado = 'GUARDADO' AND f.tipoguia != 'ventanilla'
	UNION
	SELECT fd.folio AS guia, DATE_FORMAT(a.fecharegistro,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente, cs.prefijo AS destino,
	IFNULL(fd.total,0) AS importe,a.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) empleado
	FROM abonodecliente a
	INNER JOIN abonodecliente_facturas af ON a.id = af.folioabono AND a.idsucursal = af.sucursal
	INNER JOIN facturacion f ON af.factura = f.folio
	INNER JOIN facturadetalle fd ON f.folio = fd.factura
	INNER JOIN catalogocliente cc ON a.idcliente = cc.id
	INNER JOIN catalogosucursal cs ON a.idsucursal = cs.id
	LEFT JOIN catalogoempleado ce ON a.idusuario = ce.id
	WHERE a.fecharegistro BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND a.idsucursal = ".$_GET[sucursal]." AND f.facturaestado = 'GUARDADO' AND f.tipoguia = 'ventanilla'
	GROUP BY fd.folio
	UNION
	SELECT CONCAT('Fact-',f.folio) AS guia, DATE_FORMAT(a.fechaliquidacion,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente, cs.prefijo AS destino,
	IFNULL(f.total + f.sobmontoafacturar + f.otrosmontofacturar,0) AS importe,
	a.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM liquidacioncobranza a
	INNER JOIN liquidacioncobranza_facturas af ON a.id = af.folioliquidacion
	INNER JOIN facturacion f ON af.factura = f.folio
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	INNER JOIN catalogosucursal cs ON a.sucursal = cs.id
	LEFT JOIN catalogoempleado ce ON a.idusuario = ce.id
	WHERE a.fechaliquidacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND a.sucursal = ".$_GET[sucursal]." AND f.facturaestado = 'GUARDADO' AND f.tipoguia != 'ventanilla'
	UNION
	SELECT fd.folio AS guia, DATE_FORMAT(a.fechaliquidacion,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente, cs.prefijo AS destino,
	IFNULL(fd.total,0) AS importe,a.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) empleado
	FROM liquidacioncobranza a
	INNER JOIN liquidacioncobranza_facturas af ON a.id = af.folioliquidacion
	INNER JOIN facturacion f ON af.factura = f.folio
	INNER JOIN facturadetalle fd ON f.folio = fd.factura
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	INNER JOIN catalogosucursal cs ON a.sucursal = cs.id
	LEFT JOIN catalogoempleado ce ON a.idusuario = ce.id
	WHERE a.fechaliquidacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND a.sucursal = ".$_GET[sucursal]." AND f.facturaestado = 'GUARDADO' AND f.tipoguia = 'ventanilla'
	GROUP BY fd.folio) t ORDER BY t.idusuario";	
	obtenerDatos($criterioingresocobranzacredito,$pdf,"INGRESOS POR COBRANZA DE GUIAS A CREDITO","");
	
	//14) GUIAS FORANEAS COBRAR-CONTADO ENTREGADAS
	$criterioingresoguiasforaneacobrarcontadoentregadas = "SELECT * FROM (
		SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
		cs.prefijo AS destino, gv.total AS importe, gv.idusuario,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado, 
		CONCAT('ENTREGA ',IF(gv.ocurre=1,'Ocurre','EAD'),IF(gv.ocurre=1,'',CONCAT(' - UNIDAD: ',IFNULL(sg.unidad,'')))) ocurre,
		sg.fecha fechaseguimiento
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id	
		LEFT JOIN catalogoempleado ce ON gv.idusuario = ce.id
		INNER JOIN catalogocliente cc ON gv.iddestinatario = cc.id
		LEFT JOIN seguimiento_guias sg ON gv.id = sg.guia AND sg.fecha = gv.fechaentrega AND sg.estado LIKE '%REPARTO EAD%'
		WHERE gv.tipoflete = 1 AND gv.condicionpago = 0 AND (gv.estado = 'ENTREGADA' OR gv.estado = 'POR ENTREGAR') AND gv.idsucursaldestino = ".$_GET[sucursal]." 
		AND gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
			AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
		ORDER BY gv.ocurre,ce.id, sg.fecha DESC
	) t1 GROUP BY guia ORDER BY ocurre, empleado";
	obtenerDatos($criterioingresoguiasforaneacobrarcontadoentregadas,$pdf,"INGRESOS DE GUIAS POR COBRAR CONTADO ENTREGADAS","","SI");
	
	//15) GUIAS FORANEAS COBRAR-CREDITO ENTREGADAS
	$criterioingresoguiasforaneacobrarcreditoentregadas = "SELECT * FROM (
		SELECT gv.id as guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') as fecha,
		CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
		cs.prefijo AS destino, gv.total AS importe, gv.idusuario,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) as empleado, 
		CONCAT('ENTREGA ',IF(gv.ocurre=1,'Ocurre','EAD'),IF(gv.ocurre=1,'',CONCAT(' - UNIDAD: ',IFNULL(sg.unidad,'')))) ocurre
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id	
		LEFT JOIN catalogoempleado ce ON gv.idusuario = ce.id
		INNER JOIN catalogocliente cc ON gv.iddestinatario = cc.id 
		LEFT JOIN seguimiento_guias sg ON gv.id = sg.guia AND sg.fecha = gv.fechaentrega AND sg.estado LIKE '%REPARTO EAD%'
		WHERE gv.tipoflete = 1 AND gv.condicionpago = 1 AND (gv.estado = 'ENTREGADA' OR gv.estado = 'POR ENTREGAR') AND gv.idsucursaldestino = ".$_GET[sucursal]." 
		AND gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
			AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
		order by gv.ocurre,ce.id, sg.fecha DESC
	) t1 GROUP BY guia ORDER BY ocurre, empleado";
	obtenerDatos($criterioingresoguiasforaneacobrarcreditoentregadas,$pdf,"GUIAS FORANEAS COBRAR-CREDITO ENTREGADAS","","SI");
	
	//16) GUIAS FORANEAS PAGADAS-CONTADO ENTREGADAS
	$criterioguiasforaneapagadacontadoentregadas = "SELECT * FROM(
	SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, gv.total AS importe, gv.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado,
	CONCAT('ENTREGA ',IF(gv.ocurre=1,'Ocurre','EAD'),IF(gv.ocurre=1,'',CONCAT(' - UNIDAD: ',IFNULL(sg.unidad,'')))) ocurre
	FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
	INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
	LEFT JOIN catalogoempleado ce ON gv.idusuario = ce.id
	LEFT JOIN seguimiento_guias sg ON gv.id = sg.guia AND sg.fecha = gv.fechaentrega AND sg.estado LIKE '%REPARTO EAD%'
	WHERE gv.tipoflete = 0 AND gv.condicionpago = 0 AND (gv.estado = 'ENTREGADA' OR gv.estado = 'POR ENTREGAR') 
	AND gv.idsucursaldestino = ".$_GET[sucursal]."
	AND gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	UNION
	SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, ge.total AS importe, ge.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado,
	CONCAT('ENTREGA ',IF(ge.ocurre=1,'Ocurre','EAD'),IF(ge.ocurre=1,'',CONCAT(' - UNIDAD: ',IFNULL(sg.unidad,'')))) ocurre
	FROM guiasempresariales ge
	INNER JOIN catalogosucursal cs ON ge.idsucursaldestino = cs.id
	INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
	LEFT JOIN catalogoempleado ce ON ge.idusuario = ce.id
	LEFT JOIN seguimiento_guias sg ON ge.id = sg.guia AND sg.fecha = ge.fechaentrega AND sg.estado LIKE '%REPARTO EAD%'
	WHERE ge.tipoflete='PAGADA' AND ge.tipopago='CONTADO' AND (ge.estado = 'ENTREGADA' OR ge.estado = 'POR ENTREGAR') 
	AND ge.idsucursaldestino = ".$_GET[sucursal]."
	AND ge.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."')t
	group by guia
	ORDER BY ocurre desc,idusuario";	
	obtenerDatos($criterioguiasforaneapagadacontadoentregadas,$pdf,"GUIAS FORANEAS PAGADAS-CONTADO ENTREGADAS","","SI");
	
	//17) GUIAS FORANEAS PAGADAS-CREDITO ENTREGADAS
	$criterioguiasforaneapagadacreditoentregadas = "SELECT * FROM(
	SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, gv.total AS importe, gv.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado,
	CONCAT('ENTREGA ',IF(gv.ocurre=1,'Ocurre','EAD'),IF(gv.ocurre=1,'',CONCAT(' - UNIDAD: ',IFNULL(sg.unidad,'')))) ocurre
	FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
	INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
	LEFT JOIN catalogoempleado ce ON gv.idusuario = ce.id
	LEFT JOIN seguimiento_guias sg ON gv.id = sg.guia AND sg.fecha = gv.fechaentrega AND sg.estado LIKE '%REPARTO EAD%'
	WHERE gv.tipoflete = 0 AND gv.condicionpago = 1 AND (gv.estado = 'ENTREGADA' OR gv.estado = 'POR ENTREGAR') 
	AND gv.idsucursaldestino = ".$_GET[sucursal]."
	AND gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	UNION
	SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, ge.total AS importe, ge.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado,
	CONCAT('ENTREGA ',IF(ge.ocurre=1,'Ocurre','EAD'),IF(ge.ocurre=1,'',CONCAT(' - UNIDAD: ',IFNULL(sg.unidad,'')))) ocurre
	FROM guiasempresariales ge
	INNER JOIN catalogosucursal cs ON ge.idsucursaldestino = cs.id
	INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
	LEFT JOIN catalogoempleado ce ON ge.idusuario = ce.id
	LEFT JOIN seguimiento_guias sg ON ge.id = sg.guia AND sg.fecha = ge.fechaentrega AND sg.estado LIKE '%REPARTO EAD%'
	WHERE ge.tipoflete='PAGADA' AND ge.tipopago='CREDITO' AND (ge.estado = 'ENTREGADA' OR ge.estado = 'POR ENTREGAR') 
	AND ge.idsucursaldestino = ".$_GET[sucursal]."
	AND ge.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."')t
	group by guia
	ORDER BY  ocurre desc,idusuario";
	obtenerDatos($criterioguiasforaneapagadacreditoentregadas,$pdf,"GUIAS FORANEAS PAGADAS-CREDITO ENTREGADAS","","SI");
	
	//18) RELACION DE GUIAS CANCELADAS
	$relguiascance = "SELECT gv.id AS guia, DATE_FORMAT(IF(ISNULL(hc.id),gv.fecha,hc.fecha),'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, gv.total AS importe, gv.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado, IF(gv.ocurre=1,'Ocurre','EAD') ocurre
	FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id	
	INNER JOIN catalogocliente cc ON gv.idremitente = cc.id 
	LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
	LEFT JOIN catalogoempleado ce ON IF(ISNULL(hc.id),gv.idusuario,hc.usuario) = ce.id
	WHERE gv.estado = 'CANCELADO' AND IF(ISNULL(hc.id),gv.idsucursalorigen = '".$_GET[sucursal]."', hc.sucursal='".$_GET[sucursal]."')
	AND IF(ISNULL(hc.id),gv.fecha,hc.fecha) 
		BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	GROUP BY gv.id
	ORDER BY gv.idusuario";
	
	/*$criterioguiasforaneapagadacreditoentregadas = " INNER JOIN catalogocliente cc ON gv.idremitente = cc.id 
	WHERE gv.estado = 'CANCELADO' AND gv.idsucursalorigen = ".$_GET[sucursal]." 
	AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	ORDER BY gv.idusuario";*/
	obtenerDatos($relguiascance,$pdf,"RELACION DE GUIAS CANCELADAS","SI");
	
	//FACTURAS CANCELADAS
	$facturascanceladas = "SELECT f.folio, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente, cs.prefijo AS destino,
	IFNULL(f.total + f.sobmontoafacturar + f.otrosmontofacturar,0) AS importe, 
	f.idusuario, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado FROM facturacion f
	INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
	INNER JOIN catalogocliente cc ON f.cliente = cc.id
	LEFT JOIN catalogoempleado ce ON f.idusuario = ce.id 
	WHERE f.fechacancelacion 
	BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	AND f.facturaestado='CANCELADO' AND f.idsucursal = ".$_GET[sucursal]."  AND f.tipoguia <> 'ventanilla'";
	obtenerDatos($facturascanceladas,$pdf,"FACTURAS CANCELADAS DEL DIA","SI");
	
	//RELACION DE GUIAS CON AUTORIZACION PARA CANCELAR
	$criterioguiasforaneapagadacreditoentregadas = " INNER JOIN catalogocliente cc ON gv.idremitente = cc.id 
	WHERE gv.estado = 'AUTORIZACION PARA CANCELAR' AND gv.idsucursalorigen = ".$_GET[sucursal]." 
	AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."'
	ORDER BY gv.idusuario";
	obtenerDatos($s.$criterioguiasforaneapagadacreditoentregadas,$pdf,"RELACION DE GUIAS CON AUTORIZACION PARA CANCELAR","SI");
	
	function ponerEncabezados($pdf,$tituloreporte,$medidas,$titulos){
		$pdf->addLeyenda2($tituloreporte,38,10);
		$pdf->AddPage();		
		$pdf->SetFont('Arial','B',7);
		$pdf->SetWidths($medidas);
		$pdf->Titulos($titulos,$medidas);
		$pdf->SetFont('Arial','',5);
	}
	function ponerOcurre($pdf,$titulo){	
		$pdf->SetFont('Arial','B',7);
		$arr = array('0'=> '','1'=>'','2'=>$titulo,'3'=>"",'4'=>'','6'=>'','7'=>'','8'=>'');
		$pdf->Row($arr);		
		$pdf->SetFont('Arial','',5);
	}
	
	function obtenerDatos($criterio,$pdf,$tituloreporte,$llevausuario,$entregadas=null){		
		$l = Conectarse("webpmm");
		$r = mysql_query($criterio,$l) or die($criterio." ".$tituloreporte." ".__LINE__);
		$tguias = 0; $tvalor = 0;
		$data = array();
		$total = mysql_num_rows($r);
		if($total>0){
			$lasguias = "";
			while($f = mysql_fetch_array($r)){
				$f[0] = cambio_texto($f[0]);
				$f[2] = cambio_texto($f[2]);
				$f[3] = cambio_texto($f[3]);
				$f[6] = cambio_texto($f[6]);
				
				if(strpos($lasguias." ",$f[0]." ")=== false){
					$lasguias .= $f[0]." ,";
					$tguias++;
					$tvalor = $tvalor + $f[4];
					$data[] = array('0'=>$f[0],'1'=>$f[1],'2'=>$f[2],'3'=>$f[3],'4'=>$f[4],'5'=>$f[5],'6'=>$f[6],'7'=>$f[7]);
				}
			}
			
			$titulos = array('GUIA','FECHA','CLIENTE','DESTINO','IMPORTE');
			$medidas = array(25,20,100,15,30);
			$pdf->AddPage();			
			$pdf->SetFont('Arial','B',7);
			
			$pdf->SetWidths($medidas);
			
			$pdf->Titulos($titulos,$medidas);
			
			$pdf->SetFont('Arial','',5);
			$usuario = "";
			$v_total = 0;
			$v_guias = 0;
			$v_empleado = "";
			$v_ocurre = "";
			#contador debe ser 45 guias
			$conreg = 0;
			for($i=0;$i<count($data);$i++){
				$conreg++;
				
				if($entregadas=="SI" && ($v_ocurre=="" || $v_ocurre!=$data[$i][7])){
					$conreg++;
					if($conreg > 42){
						$conreg=0;
						ponerEncabezados($pdf,$tituloreporte,$medidas,$titulos);
					}
					$v_ocurre=$data[$i][7];
					ponerOcurre($pdf,$v_ocurre);
				}
				
				if($conreg > 42){
					$conreg=0;
					$conreg++;
					ponerEncabezados($pdf,$tituloreporte,$medidas,$titulos);
				}
				
				if(!empty($llevausuario)){
					if($usuario!= $data[$i][5] && $usuario!=""){
						$conreg++;
						$conreg++;
						$conreg++;
						if($conreg > 42){
							$conreg=0;
							ponerEncabezados($pdf,$tituloreporte,$medidas,$titulos);						}
						$arr = null;
						$arr[] = array('0'=> '------------------------------------',
									   '1'=>'--------------------------',
									   '2'=>'---------------------------------------------------------------------------------------------------------------------------------------------------------------------',
									   '3'=>'------------------','4'=>'---------------------------------------------');
						$arr[] = array('0'=> 'TOTAL DEL USUARIO ','1'=>'','2'=>$v_empleado,'3'=>$v_guias,'4'=>$v_total);
						$arr[] = array('0'=> ' ','1'=>'','2'=>'','3'=>'','4'=>'');
						$v_total = 0;
						$v_guias = 0;
						$v_empleado = "";
						$pdf->Row($arr[0]);
						$pdf->SetFont('Arial','B',6);
						$pdf->Row($arr[1]);
						$pdf->SetFont('Arial','',5);
						$pdf->Row($arr[2]);
					}
				}
				$data[$i][7]="";
				$pdf->Row($data[$i]);
				$usuario = $data[$i][5];
				$v_total += $data[$i][4];
				$v_empleado = $data[$i][6];
				$v_guias++;
				
			}
			if(!empty($llevausuario)){
				$arr = null;
				$conreg++;
				$conreg++;
				$conreg++;
				if($conreg > 42){
					$conreg=0;
					ponerEncabezados($pdf,$tituloreporte,$medidas,$titulos);				}
				$arr[] = array('0'=> '------------------------------------',
									   '1'=>'--------------------------',
									   '2'=>'---------------------------------------------------------------------------------------------------------------------------------------------------------------------',
									   '3'=>'------------------','4'=>'---------------------------------------------');
				$arr[] = array('0'=> 'TOTAL DEL USUARIO ','1'=>'','2'=>$v_empleado,'3'=>$v_guias,'4'=>$v_total);
				$arr[] = array('0'=> ' ','1'=>'','2'=>'','3'=>'','4'=>'');
				$pdf->Row($arr[0]);
				$pdf->SetFont('Arial','B',6);
				$pdf->Row($arr[1]);
				$pdf->SetFont('Arial','',5);
				$pdf->Row($arr[2]);
			}			 
						
			$pdf->SetFont('Arial','B',15);
			$pdf->addLeyenda2($tituloreporte,38,10);
			
			$pdf->SetFont('Arial','',7);
			$pdf->addLeyenda2("TOTAL:",260,30);
			$pdf->addLeyenda2($tguias,260,120);
			$pdf->addLeyenda2("$".number_format($tvalor,2,'.',','),260,150);
		}
	}
	//die(0);
	$pdf->Output();	
?>