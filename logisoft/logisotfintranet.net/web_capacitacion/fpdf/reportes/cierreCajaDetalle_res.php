<?	require_once("../../Conectar.php");
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
			$this->Cell(30,10,'PAQUETERIA Y MENSAJERIA EN MOVIMIENTO',0,0,'C');		
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
				$this->Cell(70,10,'FECHA: DEL DIA '.$_GET[fechainicio].' AL '.$_GET[fechafin].'',0,0,'L');
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
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) as empleado
	FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
	INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
	INNER JOIN catalogoempleado ce ON gv.idusuario = ce.id";
	
	$se = "UNION
	SELECT ge.id as guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') as fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, ge.total AS importe, ge.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) as empleado
	FROM guiasempresariales ge
	INNER JOIN catalogosucursal cs ON ge.idsucursaldestino = cs.id
	INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
	INNER JOIN catalogoempleado ce ON ge.idusuario = ce.id";
	
	//1) GUIAS CONTADO
	$criterioguiascontado = " WHERE gv.tipoflete = 0 AND gv.condicionpago = 0 AND gv.idsucursalorigen = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO' ".$se." WHERE ge.tipoflete = 'PAGADA' AND ge.tipopago = 'CONTADO' 
	AND ge.idsucursalorigen = ".$_GET[sucursal]."
	AND ".((!empty($_GET[fechainicio]))? "ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "ge.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND ge.tipoguia='CONSIGNACION' AND (factura IS NULL OR factura=0) ORDER BY idusuario";
	obtenerDatos($s.$criterioguiascontado,$pdf,"GUIAS CONTADO","SI");
	
	//2) GUIAS CREDITO
	$criterioguiascredito = " WHERE gv.tipoflete = 0 AND gv.condicionpago = 1 AND gv.idsucursalorigen = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO' ".$se." WHERE ge.tipoflete = 'PAGADA' AND ge.tipopago = 'CREDITO' 
	AND ge.idsucursalorigen = ".$_GET[sucursal]."
	AND ".((!empty($_GET[fechainicio]))? "ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "ge.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND ge.tipoguia='CONSIGNACION' AND (factura IS NULL OR factura=0) ORDER BY idusuario";
	obtenerDatos($s.$criterioguiascredito,$pdf,"GUIAS CREDITO","SI");
	
	//3) GUIAS POR COBRAR CONTADO
	$criterioguiascobrarcontado = " WHERE gv.tipoflete = 1 AND gv.condicionpago = 0 AND gv.idsucursalorigen = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO' ORDER BY gv.idusuario";
	obtenerDatos($s.$criterioguiascobrarcontado,$pdf,"GUIAS POR COBRAR CONTADO","SI");
	
	//4) GUIAS POR COBRAR CREDITO
	$criterioguiascobrarcredito = " WHERE gv.tipoflete = 1 AND gv.condicionpago = 1 AND gv.idsucursalorigen = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO' ORDER BY gv.idusuario";
	obtenerDatos($s.$criterioguiascobrarcredito,$pdf,"GUIAS POR COBRAR CREDITO","SI");
	
	//5) GUIAS PREPAGADAS
	$criterioprepagadas = "SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, 0 AS importe, ge.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM guiasempresariales ge
	INNER JOIN catalogosucursal cs ON ge.idsucursaldestino = cs.id
	INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
	INNER JOIN catalogoempleado ce ON ge.idusuario = ce.id
	WHERE ge.tipoguia = 'PREPAGADA' AND ge.idsucursalorigen = ".$_GET[sucursal]."
	AND ".((!empty($_GET[fechainicio]))? "ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "ge.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." ORDER BY ge.idusuario";
	obtenerDatos($criterioprepagadas,$pdf,"GUIAS PREPAGADAS","SI");
	
	//6) CORREO INTERNO
	$criterioguiascorreointerno = "SELECT c.guia, DATE_FORMAT(fechacorreo,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ',de.nombre,de.apellidopaterno,de.apellidomaterno) AS destinatario,
	cs.prefijo AS destino, 0 AS importe, c.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM correointerno c
	INNER JOIN catalogoempleado de ON c.destintario = de.id
	INNER JOIN catalogosucursal cs ON c.sucdestino = cs.id
	INNER JOIN catalogoempleado ce ON c.idusuario = ce.id
	WHERE c.sucorigen = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "c.fechacorreo BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "c.fechacorreo = '".cambiaf_a_mysql($_GET[fecha])."'")." AND c.estado='GUARDADO' ORDER BY c.idusuario";
	/*$criterioguiascorreointerno = " WHERE SUBSTR(gv.id,1,LENGTH(gv.id)-10)='888' AND gv.idsucursalorigen = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO' ORDER BY gv.idusuario";*/
	obtenerDatos($criterioguiascorreointerno,$pdf,"CORREO INTERNO","SI");
	
	//7) GUIAS FORANEAS DE CONTADO RECIBIDAS
	$criterioguiasforaneacontadorecibidas = " WHERE gv.tipoflete = 0 AND gv.condicionpago = 0 AND gv.idsucursaldestino = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO'";
	obtenerDatos($s.$criterioguiasforaneacontadorecibidas,$pdf,"GUIAS FORANEAS DE CONTADO RECIBIDAS","");
	
	//8) GUIAS FORANEAS DE CREDITO RECIBIDAS
	$criterioguiasforaneacreditorecibidas = " WHERE gv.tipoflete = 0 AND gv.condicionpago = 1 AND gv.idsucursaldestino = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO'";
	obtenerDatos($s.$criterioguiasforaneacreditorecibidas,$pdf,"GUIAS FORANEAS DE CREDITO RECIBIDAS","");
	
	//9) GUIAS FORANEAS POR COBRAR CONTADO RECIBIDAS
	$criterioguiasforaneacobrarcontadorecibidas = " WHERE gv.tipoflete = 1 AND gv.condicionpago = 0 AND gv.idsucursaldestino = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO' ";	
	obtenerDatos($s.$criterioguiasforaneacobrarcontadorecibidas,$pdf,"GUIAS FORANEAS POR COBRAR CONTADO RECIBIDAS","");
	
	//10) GUIAS FORANEAS POR COBRAR CREDITO RECIBIDAS
	$criterioguiasforaneacobrarcreditorecibidas = " WHERE gv.tipoflete = 1 AND gv.condicionpago = 1 AND gv.idsucursaldestino = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO'";
	obtenerDatos($s.$criterioguiasforaneacobrarcreditorecibidas,$pdf,"GUIAS FORANEAS POR COBRAR CREDITO RECIBIDAS","");
	
	//11) GUIAS FORANEAS PREPAGADAS RECIBIDAS
	$criterioprepagadasforaneas = "SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, 0 AS importe, ge.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM guiasempresariales ge
	INNER JOIN catalogosucursal cs ON ge.idsucursaldestino = cs.id
	INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
	INNER JOIN catalogoempleado ce ON ge.idusuario = ce.id
	WHERE ge.tipoguia = 'PREPAGADA' AND ge.idsucursaldestino = ".$_GET[sucursal]."
	AND ".((!empty($_GET[fechainicio]))? "ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "ge.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")."";
	obtenerDatos($criterioprepagadasforaneas,$pdf,"GUIAS FORANEAS PREPAGADAS RECIBIDAS","");
	
	//12) CORREO INTERNO FORANEO RECIBIDO
	$criterioguiasforaneacorreointerno = "SELECT c.guia, DATE_FORMAT(fechacorreo,'%d/%m/%Y') AS fecha,
	CONCAT_WS(' ',de.nombre,de.apellidopaterno,de.apellidomaterno) AS destinatario,
	cs.prefijo AS destino, 0 AS importe, c.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado
	FROM correointerno c
	INNER JOIN catalogoempleado de ON c.destintario = de.id
	INNER JOIN catalogosucursal cs ON c.sucdestino = cs.id
	INNER JOIN catalogoempleado ce ON c.idusuario = ce.id
	WHERE c.sucdestino = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "c.fechacorreo BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "c.fechacorreo = '".cambiaf_a_mysql($_GET[fecha])."'")." AND c.estado='GUARDADO'";
	
	
	/*$criterioguiasforaneacorreointerno = " WHERE SUBSTR(gv.id,1,LENGTH(gv.id)-10)='888' AND gv.idsucursaldestino = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO'";*/
	obtenerDatos($criterioguiasforaneacorreointerno,$pdf,"CORREO INTERNO FORANEO RECIBIDO","");
	
	//13) INGRESOS POR COBRANZA DE GUIAS A CREDITO
	$criterioingresocobranzacredito = " INNER JOIN formapago fp ON gv.id = fp.guia
	WHERE gv.condicionpago = 1 AND fp.procedencia = 'C' AND fp.sucursal = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "fp.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "fp.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO'";
	obtenerDatos($s.$criterioingresocobranzacredito,$pdf,"INGRESOS POR COBRANZA DE GUIAS A CREDITO","");
	
	//14) INGRESOS POR GUIAS FORANEAS COBRAR-CONTADO ENTREGADAS
	$criterioingresoguiasforaneacobrarcontadoentregadas = " WHERE gv.tipoflete = 1 AND gv.condicionpago = 0 AND gv.estado = 'ENTREGADA' AND gv.idsucursaldestino = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO'";
	obtenerDatos($s.$criterioingresoguiasforaneacobrarcontadoentregadas,$pdf,"INGRESOS POR GUIAS FORANEAS COBRAR-CONTADO ENTREGADAS","");
	
	//15) INGRESOS POR GUIAS FORANEAS COBRAR-CREDITO ENTREGADAS
	$criterioingresoguiasforaneacobrarcreditoentregadas = " WHERE gv.tipoflete = 1 AND gv.condicionpago = 1 AND gv.estado = 'ENTREGADA' AND gv.idsucursaldestino = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO'";
	obtenerDatos($s.$criterioingresoguiasforaneacobrarcreditoentregadas,$pdf,"INGRESOS POR GUIAS FORANEAS COBRAR-CREDITO ENTREGADAS","");
	
	//16) GUIAS FORANEAS PAGADAS-CONTADO ENTREGADAS
	$criterioguiasforaneapagadacontadoentregadas = " WHERE gv.tipoflete = 0 AND gv.condicionpago = 0 AND gv.estado = 'ENTREGADA' AND gv.idsucursaldestino = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO'";
	obtenerDatos($s.$criterioguiasforaneapagadacontadoentregadas,$pdf,"GUIAS FORANEAS PAGADAS-CONTADO ENTREGADAS","");
	
	//17) GUIAS FORANEAS PAGADAS-CREDITO ENTREGADAS
	$criterioguiasforaneapagadacreditoentregadas = " WHERE gv.tipoflete = 0 AND gv.condicionpago = 1 AND gv.estado = 'ENTREGADA' AND gv.idsucursaldestino = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO'";
	obtenerDatos($s.$criterioguiasforaneapagadacreditoentregadas,$pdf,"GUIAS FORANEAS PAGADAS-CREDITO ENTREGADAS","");
	
	//18) RELACION DE GUIAS CANCELADAS
	$criterioguiasforaneapagadacreditoentregadas = " WHERE gv.estado = 'CANCELADO' AND gv.idsucursalorigen = ".$_GET[sucursal]." 
	AND ".((!empty($_GET[fechainicio]))? "gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" 
	: "gv.fecha = '".cambiaf_a_mysql($_GET[fecha])."'")." AND gv.estado<>'CANCELADO' ORDER BY gv.idusuario";
	obtenerDatos($s.$criterioguiasforaneapagadacreditoentregadas,$pdf,"RELACION DE GUIAS CANCELADAS","SI");
	
	function obtenerDatos($criterio,$pdf,$tituloreporte,$llevausuario){	
	
		if($tituloreporte=="RELACION DE GUIAS CANCELADAS"){
			die($criterio);
		}
		$l = Conectarse("webpmm");
		$r = mysql_query($criterio,$l) or die($criterio);		
		$tguias = 0; $tvalor = 0;
		$data = array();
		$total = mysql_num_rows($r);
		if($total>0){
			while($f = mysql_fetch_array($r)){
				$f[0] = cambio_texto($f[0]);
				$f[2] = cambio_texto($f[2]);
				$f[3] = cambio_texto($f[3]);
				$f[6] = cambio_texto($f[6]);
				$tguias++;
				$tvalor = $tvalor + $f[4];			
				$data[] = array('0'=>$f[0],'1'=>$f[1],'2'=>$f[2],'3'=>$f[3],'4'=>$f[4],'5'=>$f[5],'6'=>$f[6]);
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
			
			for($i=0;$i<count($data);$i++){
				if(!empty($llevausuario)){
					if($usuario!= $data[$i][5] && $usuario!=""){
						$arr = null;
						$arr[] = array('0'=> 'TOTAL DEL USUARIO ','1'=>'','2'=>$v_empleado,'3'=>$v_guias,'4'=>$v_total);
						$v_total = 0;
						$v_guias = 0;
						$v_empleado = "";
						$pdf->Row($arr[0]);					
					}
				}
				$pdf->Row($data[$i]);
				$usuario = $data[$i][5];
				$v_total += $data[$i][4];
				$v_empleado = $data[$i][6];
				$v_guias++;
				
			}
			if(!empty($llevausuario)){
				$arr = null;
				$arr[] = array('0'=> 'TOTAL DEL USUARIO ','1'=>'','2'=>$v_empleado,'3'=>$v_guias,'4'=>$v_total);
				$pdf->Row($arr[0]);
			}			 
						
			$pdf->SetFont('Arial','B',15);
			$pdf->addLeyenda2($tituloreporte,38,10);
			
			$pdf->SetFont('Arial','',7);
			$pdf->addLeyenda2("TOTAL:",260,30);
			$pdf->addLeyenda2($tguias,260,120);
			$pdf->addLeyenda2("$".number_format($tvalor,2,'.',','),260,150);
		}
	}
	
	$pdf->Output();	
?>