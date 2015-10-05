<?	require('../fpdf.php');
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	function meses($mes){
		$lmeses = array("ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC");
		return $lmeses[$mes];
	}
		
	class PDF extends FPDF{
		
		
		
		function crearColumnas($header1,$header2, $w1, $w2){
			$this->Line(11,57,200,57);
			$this->Line(11,67,200,67);
			$this->SetFont('Courier','B',8);
			for($i=0;$i<count($header1);$i++){	
				$this->Cell($w1[$i],5,$header1[$i],0,0,'C');						
			}
			$this->Ln();
			for($i=0;$i<count($header2);$i++){	
				$this->Cell($w2[$i],5,$header2[$i],0,0,'C');						
			}
			$this->Ln();
		}
		
		function ImprovedTable($header1,$header2,$data){
			//print_r($data);
			//die('die');
			//Anchuras de las columnas						
					
					
					//Cabeceras
					$w1 = array(21,27,23,83,38);
					$w2 = array(29,42,42,18,17,23,21);
					$this->crearColumnas($header1,$header2,$w1,$w2);
					//Datos			
					$cont=0;
					
					$cont++;
					//echo "zzz<br>";
					
					$this->SetFont('Courier','',7);
					$this->Cell($w1[0],6, utf8_decode($data[0][Fecha]),0);				
					$this->Cell($w1[1],6, utf8_decode($data[0][Tipo]),0);				
					$this->Cell($w1[2],6, utf8_decode($data[0][Numero]),0);
					$this->Cell($w1[3],6, utf8_decode($data[0][Concepto]),0);
					$this->Cell($w1[4],6, '',0);
					$this->Ln();
					
					$conId = 0;
					
					$tCargos = 0;
					$tAbonos = 0;
					
					foreach($data as $row){
						$contId++;
						$cont++;
						//echo "zzz<br>";
						$this->SetFont('Courier','',7);
						$this->Cell($w2[0],5, $contId,0);				
						$this->Cell($w2[1],5, utf8_decode($row[Cuenta]),0);				
						$this->Cell($w2[2],5, utf8_decode($row[ConceptoU]),0);
						$this->Cell($w2[3],5, '',0);
						$this->Cell($w2[4],5, '',0);
						if($row[Cargo]==0){
							$elcargo = '';
						}else{
							$elcargo = "$".number_format($row[Cargo],2);
						}
						$this->Cell($w2[5],5,$elcargo,0,0,'R');
						if($row[Abono]==0){
							$elabono = '';
						}else{
							$elabono = "$".number_format($row[Abono],2);
						}
						$this->Cell($w2[6],5,$elabono,0,0,'R');
						$this->Ln();
						
						$tCargos += $row[Cargo];
						$tAbonos += $row[Abono];
						
						$this->SetFont('Courier','',7);
						$this->Cell($w1[0],5, '',0);				
						$this->Cell($w1[1],5, '',0);				
						$this->Cell($w1[2],5, '',0);
						$this->Cell($w1[3],5, utf8_decode($row[ConceptoM]),0);
						$this->Cell($w1[4],5, '',0);
						$this->Ln();
						
						if($cont>18){
							$this->addPage();
							$this->crearColumnas($header1,$header2,$w1,$w2);
							$cont=0;
						}
					}
					
					$this->underline = true;
					$this->Cell($w2[0],5, '',0);				
					$this->Cell($w2[1],5, '',0);				
					$this->Cell($w2[2],5, '',0);
					$this->Cell($w2[3],5, '',0);
					$this->Cell($w2[4],5, 'TOTALES',0);
					$this->Cell($w2[5],5,"$".number_format($tCargos,2),0,0,'R');
					$this->Cell($w2[6],5,"$".number_format($tAbonos,2),0,0,'R');
					$this->Ln();
					$this->Ln();
					$this->underline = false;
					$this->Cell(192,5, 'La información aqui presentada es real, generada de la operación del día de hoy '.date('d/m/Y').', realizada en MAZATLAN. ',0);
					$this->Ln();
					$this->Cell(192,5, 'Por lo que de existir diferencias a cargos en los ingresos reportados acepto cubrir la cantidad mencionada en la',0);
					$this->Ln();
					$this->Cell(192,5, 'cuenta contable Diferencias. ',0);
					$this->Ln();
					$this->Cell(192,5, '  ',0);
					$this->Ln();
					$this->Cell(192,5, 'Responsable:____________________________ ',0);
					$this->Ln();
					$this->Cell(192,5, 'Reviso:_________________________________ ',0);
					$this->Ln();


					//Linea de cierre
					//die("xx");
		}
		function Header(){
			require_once('../../Conectar.php');
			$l = Conectarse('webpmm');
			
			$s = "SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";
			$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);			
			
			//Logo
			$this->Image('../logo.jpg',10,8,33);
			$this->Ln(20);
			//Arial bold 15		
			$this->SetFont('Courier','B',15);
		
			//Movernos a la derecha		
			$this->Cell(80);
		
			//Titulo		
			$this->Cell(70,10,'PAQUETERIA Y MENSAJERIA EN MOVIMIENTO',0,0,'C');
		
			//Salto de linea		
			/*$this->Ln(10);
			
			$this->SetFont('Courier','B',8);
			*/
			
			$this->Ln(10);
			$this->SetFont('Courier','B',10);
			$this->Cell(70,10,'SOPORTE DE POLIZA                                       FECHA IMPRESO:'.date('d/m/Y').'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'SUCURSAL: '.utf8_decode($f->descripcion).'              FECHA DEL DIA: '.$_GET[fechaembarque].'',0,0,'L');			
			$this->Ln(12);
		}
		
		function Footer(){
			//Posición: a 1,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Courier','I',8);
			//Número de página
			$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		}
	}
	
	
	
			$fecinicio = cambiaf_a_mysql($_GET[fechainicio]);
		$fecfin	= cambiaf_a_mysql($_GET[fechafin]);
		$sucursal	= $_GET[sucursal];
		$detallado = array();
		
		$NumeroPoliza = "01" .$_GET[sucursal]. "0" . substr($_GET[fechainicio],0,2);
		
		$s = "SELECT * FROM catalogosucursal WHERE id = $sucursal";
		$rsuc = mysql_query($s,$l) or die($s);
		$fsuc = mysql_fetch_object($rsuc);
		
		$nsucursal = $fsuc->descripcion;
		
		$vlConcepto = "LIQ. DEL " . $_GET[fechainicio] . " AL " . $_GET[fechafin] . " de " . $fsuc->prefijo;
		//Format(daFechaIni.Day, "00")
		
		$s = "SELECT ID, Modulo, asientocontable FROM asientocontableasignacion WHERE  AsientoContable <> 0";
		$r = mysql_query($s,$l) or die($s);
		
		while($f = mysql_fetch_object($r)){
			$s = "select * from asientocontabledetalle 
			where AsientoContable = $f->asientocontable";
			
			$vlCargo = 0;
			$vlAbono = 0;
			
			$rx = mysql_query($s,$l) or die($s);
			while($detalle = mysql_fetch_object($rx)){
				
				//--echo $detalle->Concepto."<br>";
				//--print_r($detalle);
				//--echo "<br>";
				
				//echo "(".$f->Modulo.") - Modulo<br>";
				switch ($f->Modulo){
                    case "Guias Ventanilla":
                        $s = "SELECT * FROM guiasventanilla WHERE IDSucursalOrigen='$sucursal' And Fecha>='$fecinicio' And Fecha<='$fecfin' And id LIKE '%A'";
						break;
                    case "Guias Empresariales":
                        $s = "SELECT * FROM guiasempresariales GE INNER JOIN facturacion F ON GE.factura=F.folio 
						WHERE IDSucursalOrigen='$sucursal' And F.Fecha>='$fecinicio' And F.Fecha<='$fecfin' And GE.id LIKE '%A'";
						break;
					case "Guias Canceladas":
						$s = "SELECT * FROM guiasventanilla WHERE IDSucursalOrigen='$sucursal' And Fecha>='$fecinicio' 
						And Fecha<='$fecfin' And Estado='CANCELADO' And id LIKE '%A'";
						break;
					case "Facturas":
						$s = "SELECT * FROM facturacion WHERE IDSucursal='$sucursal' And Fecha>='$fecinicio' And Fecha<='$fecfin' And FacturaEstado='GUARDADO'";
						break;
					case "Facturas Canceladas":
						$s = "SELECT * FROM facturacion WHERE IDSucursal='$sucursal' And FechaCancelacion>='$fecinicio' 
						And FechaCancelacion<='$fecfin' And FacturaEstado='CANCELADO'";
						break;
				}
				$ri = mysql_query($s,$l) or die($s);
				$encontro = mysql_num_rows($ri);
				
				//echo $encontro."() - $s <br>";
				
				$vlTabla = "";
				$vlCondicion = "";
				$vlCampos = "";
				$vlImporte = "";
				$vlCodID = "";
				$vlCtaCont = $detalle->Cuenta;
				
				if($encontro>0){
					//echo $detalle->Referencia." - referencia ->xx<br>";
					switch ($detalle->Referencia){
						case "Sucursal":
						case "Rango de Fecha, Sucursal":
							if($detalle->importeBase=="Total por Sucursal a Cobrar"){
								$vlCampos = "PG.SucursalACobrar As Sucursal, ";
							}else{
								switch ($f->Modulo){
									case "Guias Ventanilla":
										$vlCampos = "GV.IDSucursalOrigen As Sucursal, ";
										break;
									case "Guias Empresariales":
										$vlCampos = "GE.IDSucursalOrigen As Sucursal, ";
										break;
									case "Guias Canceladas":
										$vlCampos = "GV.IDSucursalOrigen As Sucursal, ";
										break;
									case "Facturas":
									case "Facturas Canceladas":
										$vlCampos = "F.IDSucursal As Sucursal, ";
										break;
								}
							}
							break;
						case "Coordinador de Sucursal":
							$vlCampos = "CE.ID AS CoordinadorSuc, ";
							break;
						case "Folio, Sucursal":
							if($detalle->importeBase=="Total por Sucursal a Cobrar"){
								switch ($f->Modulo){
									case "Guias Ventanilla":
										$vlCampos = "GV.ID, PG.SucursalACobrar As Sucursal, ";
										break;
									case "Guias Empresariales":
										$vlCampos = "GE.ID, PG.SucursalACobrar As Sucursal, ";
										break;
									case "Guias Canceladas":
										$vlCampos = "GV.ID, PG.SucursalACobrar As Sucursal, ";
										break;
									case "Facturas":
									case "Facturas Canceladas":
										$vlCampos = "F.Folio, F.IDSucursal As Sucursal, ";
										break;
								}
							}else{
								switch ($f->Modulo){
									case "Guias Ventanilla":
										$vlCampos = "GV.ID, GV.IDSucursalOrigen As Sucursal, ";
										break;
									case "Guias Empresariales":
										$vlCampos = "GE.ID, GE.IDSucursalOrigen As Sucursal, ";
										break;
									case "Guias Canceladas":
										$vlCampos = "GV.ID, GV.IDSucursalOrigen As Sucursal, ";
										break;
									case "Facturas":
									case "Facturas Canceladas":
										$vlCampos = "F.Folio, F.IDSucursal As Sucursal, ";
										break;
								}
							}
							break;
					}
				
					#trabajar con las cuentas
					$detalle->Cuenta = str_replace("+","xSEPAx",$detalle->Cuenta);
					$cuenta = split("xSEPAx",$detalle->Cuenta);
					for($i=0; $i<count($cuenta); $i++){
						//echo "$cuenta[$i] CUENTA <br>";
						if(strpos($cuenta[$i],'"')===false){
							if($cuenta[$i]=="SUC01" || $cuenta[$i]=="SUC02" || $cuenta[$i]=="SUC03"  || $cuenta[$i]=="SUC04"  || $cuenta[$i]=="SUC05"){
								if(strpos($vlCampos,'Sucursal')===false){
									
									//echo  $detalle->ImporteBase." -> IMPORTE BASE<br>";
									
									if($detalle->ImporteBase == "Total por Sucursal a Cobrar"){
										$vlCampos .= "PG.SucursalACobrar As Sucursal, ";
									}else{
										
										//echo $f->Modulo." -> MODULO IMPORTE BASE";
										
										 switch ($f->Modulo){
											case "Guias Ventanilla":
												$vlCampos .= "GV.IDSucursalOrigen As Sucursal, ";
												break;
											case "Guias Empresariales":
												$vlCampos .= "GE.IDSucursalOrigen As Sucursal, ";
												break;
											case "Guias Canceladas":
												$vlCampos .= "GV.IDSucursalOrigen As Sucursal, ";
												break;
											case "Facturas":
											case "Facturas Canceladas":
												$vlCampos .= "F.IDSucursal As Sucursal, ";
												break;
										 }
									}
								}
							}elseif($cuenta[$i]=="GER01" || $cuenta[$i]=="GER02" || $cuenta[$i]=="GER03"  || $cuenta[$i]=="GER04"  || $cuenta[$i]=="GER05"){
								if(strpos($vlCampos,'CE.ID AS CoordinadorSuc')===false){
									$vlCampos .= "CE.ID AS CoordinadorSuc, ";
								}
							}
						}
					}
					
					$vlCtaCont = str_replace("+ +", "+",$vlCtaCont);
					$vlCtaCont = str_replace("+ ", "",$vlCtaCont);
					
					switch ($detalle->ImporteBase){
						case "Flete":
							switch ($f->Modulo){
								case "Guias Ventanilla":
								case "Guias Canceladas":
									$vlImporte = "(GV.TFlete*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Guias Empresariales":
									$vlImporte = "(GE.TFlete*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Facturas":
											case "Facturas Canceladas":
									$vlImporte = "(F.Flete*" .$detalle->Porcentaje. ") As Importe ";
									break;
							}
							break;
						case "Descuento":
							switch ($f->Modulo){
								case "Guias Ventanilla":
								case "Guias Canceladas":
									$vlImporte = "(GV.TTotalDescuento*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Guias Empresariales":
									$vlImporte = "(GE.TTotalDescuento*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Facturas":
											case "Facturas Canceladas":
									$vlImporte = "(F.TotalDescuento*" .$detalle->Porcentaje. ") As Importe ";
									break;
							}
							break;
						case "Entrega A Domicilio":
							switch ($f->Modulo){
								case "Guias Ventanilla":
								case "Guias Canceladas":
									$vlImporte = "(GV.TCostoEAD*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Guias Empresariales":
									$vlImporte = "(GE.TCostoEAD*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Facturas":
											case "Facturas Canceladas":
									$vlImporte = "(F.EAD*" .$detalle->Porcentaje. ") As Importe ";
									break;
							}
							break;
						case "Recolección":
							switch ($f->Modulo){
								case "Guias Ventanilla":
								case "Guias Canceladas":
									$vlImporte = "(GV.TRecoleccion*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Guias Empresariales":
									$vlImporte = "(GE.TRecoleccion*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Facturas":
											case "Facturas Canceladas":
									$vlImporte = "(F.Recoleccion*" .$detalle->Porcentaje. ") As Importe ";
									break;
							}
							break;
						case "Seguro":
							switch ($f->Modulo){
								case "Guias Ventanilla":
								case "Guias Canceladas":
									$vlImporte = "(GV.TSeguro*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Guias Empresariales":
									$vlImporte = "(GE.TSeguro*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Facturas":
											case "Facturas Canceladas":
									$vlImporte = "((F.Seguro+F.SobSeguro)*" .$detalle->Porcentaje. ") As Importe ";
									break;
							}
							break;
						case "Otros":
							switch ($f->Modulo){
								case "Guias Ventanilla":
								case "Guias Canceladas":
									$vlImporte = "(GV.TOtros*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Guias Empresariales":
									$vlImporte = "(GE.TOtros*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Facturas":
											case "Facturas Canceladas":
									$vlImporte = "(F.OtrosMontoFacturar*" .$detalle->Porcentaje. ") As Importe ";
									break;
							}
							break;
						case "Excedente":
							switch ($f->Modulo){
								case "Guias Ventanilla":
								case "Guias Canceladas":
									$vlImporte = "(GV.TExcedente*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Guias Empresariales":
									$vlImporte = "(GE.TExcedente*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Facturas":
											case "Facturas Canceladas":
									$vlImporte = "(FG.TExcedente*" .$detalle->Porcentaje. ") As Importe ";
									break;
							}
							break;
						case "Combustible":
							switch ($f->Modulo){
								case "Guias Ventanilla":
								case "Guias Canceladas":
									$vlImporte = "(GV.TCombustible*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Guias Empresariales":
									$vlImporte = "(GE.TCombustible*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Facturas":
											case "Facturas Canceladas":
									$vlImporte = "(F.Combustible*" .$detalle->Porcentaje. ") As Importe ";
									break;
							}
							break;
						case "Subtotal":
							switch ($f->Modulo){
								case "Guias Ventanilla":
								case "Guias Canceladas":
									$vlImporte = "(GV.SubTotal*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Guias Empresariales":
									$vlImporte = "(GE.SubTotal*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Facturas":
											case "Facturas Canceladas":
									$vlImporte = "((F.SubTotal+F.SobSubtotal+F.OtrosSubtotal)*" .$detalle->Porcentaje. ") As Importe ";
									break;
							}
							break;
						case "IVA":
							switch ($f->Modulo){
								case "Guias Ventanilla":
								case "Guias Canceladas":
									$vlImporte = "(GV.TIVA*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Guias Empresariales":
									$vlImporte = "(GE.TIVA*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Facturas":
											case "Facturas Canceladas":
									$vlImporte = "((F.IVA+F.SobIVA+F.OtrosIVA)*" .$detalle->Porcentaje. ") As Importe ";
									break;
							}
							break;
						case "IVA Retenido":
							switch ($f->Modulo){
								case "Guias Ventanilla":
								case "Guias Canceladas":
									$vlImporte = "(GV.IVARetenido*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Guias Empresariales":
									$vlImporte = "(GE.IVARetenido*" .$detalle->Porcentaje. ") As Importe ";
									break;
								case "Facturas":
								case "Facturas Canceladas":
									$vlImporte = "((F.IVARetenido+F.SobIVARetenido+F.OtrosIVARetenido)*" .$detalle->Porcentaje. ") As Importe ";
									break;
							}
							break;
						case "Total":
						case "Total por Sucursal a Cobrar":
							$vlImporte = "";
							
							
							//print_r($f);
							$s = "SELECT afp.*
							FROM asientocontabledetalleformaspago afp
							INNER JOIN asientocontabledetalle ad ON afp.AsientoContable = '$f->asientocontable'
								AND afp.AsientoContableDetalle = '$detalle->ID'
								group by afp.ID;";
							//echo "$s<br>";
							$rfp = mysql_query($s,$l) or die($s);
							$encon_formapago = mysql_num_rows($rfp);
							//echo "<br>";
							//echo "$vlImporte<br>";
							$vlImporte = "";
							if($encon_formapago > 0){
								switch ($f->Modulo){
									case "Facturas":
											case "Facturas Canceladas":
										$vlImporte = "((F.Total+F.SobMontoAFacturar+F.OtrosMontoFacturar)*" .$detalle->Porcentaje. ") As Importe ";
										break;
									default:
										
										while($ffp=mysql_fetch_object($rfp)){
											 switch ($ffp->FormadePago){
												case "Efectivo":
													$vlImporte .= "IFNull(FP.Efectivo,0)+";
													break;
												case "Tarjeta de Crédito":
													$vlImporte .= "IFNull(FP.Tarjeta,0)+";
													break;
												case "Transferencia":
												case "Transferencia (Anticipo a Clientes)":
													$vlImporte .= "IFNull(FP.Transferencia,0)+";
													break;
												case "Cheque":
													$vlImporte .= "IFNull(FP.Cheque,0)+";
													break;
												case "Nota de Crédito":
													$vlImporte .= "IFNull(FP.NotaCredito,0)+";
													break;
											}
										}
										$vlImporte = "((" . substr($vlImporte,0,strlen($vlImporte)-1). ")*" .$detalle->Porcentaje. ") As Importe ";
										break;
								}
							}else{
								switch ($f->Modulo){
									case "Guias Ventanilla":
								case "Guias Canceladas":
										$vlImporte = "(GV.Total*" .$detalle->Porcentaje. ") As Importe ";
										break;
									case "Guias Empresariales":
										$vlImporte = "(GE.Total*" .$detalle->Porcentaje. ") As Importe ";
										break;
									case "Facturas":
											case "Facturas Canceladas":
										$vlImporte = "((F.Total+F.SobMontoAFacturar+F.OtrosMontoFacturar)*" .$detalle->Porcentaje. ") As Importe ";
										break;
								}
							}
							break;
						case "Valor Declarado":
							switch ($f->Modulo){
								case "Facturas":
											case "Facturas Canceladas":
									$vlImporte = "(FG.TSeguro*" .$detalle->Porcentaje. ") As Importe ";
									break;
							}
							break;
						case "Prepagadas":
							switch ($f->Modulo){
								case "Facturas":
											case "Facturas Canceladas":
									$vlImporte = "(FD.Flete*" .$detalle->Porcentaje. ") As Importe ";
									break;
							}
							break;
						case "Consignación":
							switch ($f->Modulo){
								case "Facturas":
											case "Facturas Canceladas":
									$vlImporte = "(FD.SubTotal*" .$detalle->Porcentaje. ") As Importe ";
									break;
							}
							break;
					}
				
				
					#parteee treeeess
					switch ($f->Modulo){
							case "Guias Ventanilla":
								$vlCodID = "GV.ID,";
								if($detalle->ImporteBase == "Total por Sucursal a Cobrar"){
									$vlTabla = "guiasventanilla GV INNER JOIN pagoguias PG ON GV.ID=PG.Guia 
									LEFT JOIN formapago FP ON GV.ID=FP.Guia And FP.procedencia='G' 
									INNER JOIN catalogoempleado CE ON PG.SucursalACobrar=CE.Sucursal AND CE.Puesto=34 ";
								}else{
									$vlTabla = "guiasventanilla GV LEFT JOIN formapago FP ON GV.ID=FP.Guia And FP.procedencia='G' 
									INNER JOIN catalogoempleado CE ON GV.IDSucursalOrigen=CE.Sucursal AND CE.Puesto=34 ";
								}
								$vlCondicion = "WHERE GV.IDSucursalOrigen='$sucursal' And GV.Fecha>='$fecinicio' And GV.Fecha<='$fecfin' And GV.id LIKE '%A' ";
								break;
							case "Guias Empresariales":
								$vlCodID = "GE.ID,";
								if($detalle->ImporteBase == "Total por Sucursal a Cobrar"){
									$vlTabla = "guiasempresariales GE INNER JOIN pagoguias PG ON GE.ID=PG.Guia 
									LEFT JOIN formapago FP ON GE.ID=FP.Guia And FP.procedencia='G' 
									INNER JOIN catalogoempleado CE ON PG.SucursalACobrar=CE.Sucursal AND CE.Puesto=34 ";
								}else{
									$vlTabla = "guiasempresariales GE 
									LEFT JOIN formapago FP ON GE.ID=FP.Guia And FP.procedencia='G' And FP.procedencia='G' 
									INNER JOIN catalogoempleado CE ON GE.IDSucursalOrigen=CE.Sucursal AND CE.Puesto=34 ";
								}
								$vlCondicion = "WHERE GE.IDSucursalOrigen='$sucursal' And GE.Fecha>='$fecinicio' And GE.Fecha<='$fecfin' And GE.id LIKE '%A' ";
								break;
							case "Guias Canceladas":
								$vlCodID = "GV.ID,";
								if($detalle->ImporteBase == "Total por Sucursal a Cobrar"){
									$vlTabla = "guiasventanilla GV INNER JOIN pagoguias PG ON GV.ID=PG.Guia
									LEFT JOIN formapago FP ON GV.ID=FP.Guia And FP.procedencia='G' 
									INNER JOIN catalogoempleado CE ON PG.SucursalACobrar=CE.Sucursal AND CE.Puesto=34 ";
								}else{
									$vlTabla = "guiasventanilla GV 
									LEFT JOIN formapago FP ON GV.ID=FP.Guia And FP.procedencia='G' 
									INNER JOIN catalogoempleado CE ON GV.IDSucursalOrigen=CE.Sucursal AND CE.Puesto=34 ";
								}
								$vlCondicion = "WHERE GV.IDSucursalOrigen='$sucursal' And GV.Estado='CANCELADO' 
								And GV.Fecha>='$fecinicio' And GV.Fecha<='$fecfin' And GV.Estado='CANCELADO' And GV.id LIKE '%A' ";
								break;
							case "Facturas":
								$vlCodID = "F.Folio,";
								if($detalle->ImporteBase == "Prepagadas"){
									$vlTabla = "facturacion F INNER JOIN facturadetalle FD ON F.Folio=FD.Factura 
									INNER JOIN catalogoempleado CE ON F.IDSucursal=CE.Sucursal AND CE.Puesto=34 ";
									$vlCondicion = "WHERE F.IDSucursal='$sucursal' And F.FacturaEstado='GUARDADO' And 
									F.Fecha>='$fecinicio' And F.Fecha<='$fecfin' And FD.TipoGuia='PREPAGADA' ";
								}elseif($detalle->ImporteBase == "Consignación"){
									$vlTabla = "facturacion F INNER JOIN facturadetalle FD ON F.Folio=FD.Factura 
									INNER JOIN catalogoempleado CE ON F.IDSucursal=CE.Sucursal AND CE.Puesto=34 ";
									$vlCondicion = "WHERE F.IDSucursal='$sucursal' And F.FacturaEstado='GUARDADO' And F.Fecha>='$fecinicio' 
									And F.Fecha<='$fecfin' And FD.TipoGuia='CONSIGNACION' ";
								}elseif($detalle->ImporteBase == "Valor Declarado" Or $detalle->ImporteBase == "Excedente"){
									$vlTabla = "facturacion F INNER JOIN facturadetalleguias FG ON F.Folio=FG.Factura 
									INNER JOIN catalogoempleado CE ON F.IDSucursal=CE.Sucursal AND CE.Puesto=34 ";
									$vlCondicion = "WHERE F.IDSucursal='$sucursal' And F.FacturaEstado='GUARDADO' And F.Fecha>='$fecinicio' And 
									F.Fecha<='$fecfin' And (FG.TipoGuia='CONSIGNACION' Or FG.TipoGuia='PREPAGADA') ";
								}else{
									$vlTabla = "facturacion F LEFT JOIN facturadetalle FD ON F.Folio=FD.Factura 
									LEFT JOIN facturadetalleguias FG ON F.Folio=FG.Factura 
									INNER JOIN catalogoempleado CE ON F.IDSucursal=CE.Sucursal AND CE.Puesto=34 ";
									$vlCondicion = "WHERE F.IDSucursal='$sucursal' And F.FacturaEstado='GUARDADO' And F.Fecha>='$fecinicio' 
									And F.Fecha<='$fecfin' And (FD.TipoGuia='CONSIGNACION' Or FD.TipoGuia='PREPAGADA' 
									OR FG.TipoGuia='CONSIGNACION' Or FG.TipoGuia='PREPAGADA') ";
								}
								break;
							case "Facturas Canceladas":
								$vlCodID = "F.Folio,";
								if($detalle->ImporteBase == "Prepagadas"){
									$vlTabla = "facturacion F INNER JOIN facturadetalle FD ON F.Folio=FD.Factura 
									INNER JOIN catalogoempleado CE ON F.IDSucursal=CE.Sucursal AND CE.Puesto=34 ";
									$vlCondicion = "WHERE F.IDSucursal='$sucursal' And F.FacturaEstado='CANCELADO' 
									And F.Fecha>='$fecinicio' And F.Fecha<='$fecfin' And FD.TipoGuia='PREPAGADA' ";
								}elseif($detalle->ImporteBase == "Consignación"){
									$vlTabla = "facturacion F INNER JOIN facturadetalle FD ON F.Folio=FD.Factura 
									INNER JOIN catalogoempleado CE ON F.IDSucursal=CE.Sucursal AND CE.Puesto=34 ";
									$vlCondicion = "WHERE F.IDSucursal='$sucursal' And F.FacturaEstado='CANCELADO' 
									And F.Fecha>='$fecinicio' And F.Fecha<='$fecfin' And FD.TipoGuia='CONSIGNACION' ";
								}elseif($detalle->ImporteBase == "Valor Declarado" || $detalle->ImporteBase == "Excedente"){
									$vlTabla = "facturacion F INNER JOIN facturadetalleguias FG ON F.Folio=FG.Factura 
									INNER JOIN catalogoempleado CE ON F.IDSucursal=CE.Sucursal AND CE.Puesto=34 ";
									$vlCondicion = "WHERE F.IDSucursal='$sucursal' And F.FacturaEstado='CANCELADO' 
									And F.Fecha>='$fecinicio' And F.Fecha<='$fecfin' And (FG.TipoGuia='CONSIGNACION' Or FG.TipoGuia='PREPAGADA') ";
								}else{
									$vlTabla = "facturacion F INNER JOIN facturadetalle FD ON F.Folio=FD.Factura 
									INNER JOIN catalogoempleado CE ON F.IDSucursal=CE.Sucursal AND CE.Puesto=34 ";
									$vlCondicion = "WHERE F.IDSucursal='$sucursal' And F.FacturaEstado='CANCELADO' 
									And F.Fecha>='$fecinicio' And F.Fecha<='$fecfin' And (FD.TipoGuia='CONSIGNACION' Or FD.TipoGuia='PREPAGADA') ";
								}
								break;
						}
				
				
					 $vlComandMySQL = "";
					 $vlGrup = "";
					 //echo "($detalle->Concentrar) --> Concentrar";
					 if($detalle->Concentrar==1){
						if( $vlCampos <> ""){
							if( $vlImporte == ""){
								$vlCampos = substr($vlCampos,0,strlen($vlCampos)-2). " ";
							}
							$vlGrup = str_replace(" AS CoordinadorSuc", "",str_replace(" As Sucursal","", $vlCampos));
							if( substr($vlGrup,strlen($vlGrup)-2,2) == ", "){ 
								$vlGrup = substr($vlGrup,0,strlen($vlGrup)-2);
							}
							
							if(strpos($vlCampos,"FP.")===false){
								//$vlTabla = str_replace("LEFT JOIN formapago FP ON GV.ID=FP.Guia And FP.procedencia='G'","",$vlTabla);
							}
							$vlComandMySQL = "SELECT " . $vlCampos . (($vlImporte <> "")? "Sum": "") . $vlImporte . "FROM " . $vlTabla . $vlCondicion 
							. "GROUP BY " . $vlGrup;
						}else{
							$vlComandMySQL = "SELECT Sum" . $vlImporte . "FROM " . $vlTabla . $vlCondicion;
						}
					}else{
						if( $vlImporte = ""){
							$vlCampos = substr($vlCampos,0,strlen($vlCampos)-2)." ";
						}
					}
					
					
					#hasta aki se genera la lista --
					$s = $vlComandMySQL;
					//echo "$s<br>";
					//echo "<br>xx";
					$rlista = mysql_query($s,$l) or die($s);
					while($flista = mysql_fetch_object($rlista)){
						//--echo print_r($flista);
						//--echo "<br>xx<br>";
						/*if($detalle->Concepto=='IVA N.C.'){
							echo "...... $s .....<br><br>";
						}
						if($detalle->Concepto=='NOTA DE CREDITO'){
							echo "...... $s .....<br><br>";
						}
						if($detalle->Concepto=='ANTICIPO DE CLIENTES'){
							echo "...... $s .....<br><br>";
						}
						if($detalle->Concepto=='ABONO NOTA DE CREDITO'){
							echo "...... $s .....<br><br>";
						}*/
						
						$vlCtaCont = str_replace("+","xSEPAx",$vlCtaCont);
	
						$cuenta = split("xSEPAx",$vlCtaCont);
						$vlCuenta = "";
						for($i=0; $i<count($cuenta); $i++){
							if(strpos($cuenta[$i],'"')!==false){
								$vlCuenta .= str_replace('"',"",$cuenta[$i]);
							}else{
								switch($cuenta[$i]){
									case "SUC01":
									case "SUC02":
									case "SUC03":
									case "SUC04": 
									case "SUC05":
										$s = "select * from catalogosucursal where id = '$flista->Sucursal'";
										$rsuc = mysql_query($s,$l) or die($s);
										$fsuc = mysql_fetch_object($rsuc);
										
										switch($cuenta[$i]){
											case "SUC01":
												$vlCuenta .= $fsuc->suc01;
												break;
											case "SUC02":
												$vlCuenta .= $fsuc->suc02;
												break;
											case "SUC03":
												$vlCuenta .= $fsuc->suc03;
												break;
											case "SUC04":
												$vlCuenta .= $fsuc->suc04;
												break;
											case "SUC05":
												$vlCuenta .= $fsuc->suc05;
												break;
										}
										break;
									case "GER01":
									case "GER02":
									case "GER03":
									case "GER04":
									case "GER05":
										$s = "select * from catalogoempleado where id = '$flista->CoordinadorSuc'";
										$remp = mysql_query($s,$l) or die($s);
										$femp = mysql_fetch_object($remp);
										switch($cuenta[$i]){
											case "GER01":
												$vlCuenta .= $femp->ger01;
												break;
											case "GER02":
												$vlCuenta .= $femp->ger02;
												break;
											case "GER03":
												$vlCuenta .= $femp->ger03;
												break;
											case "GER04":
												$vlCuenta .= $femp->ger04;
												break;
											case "GER05":
												$vlCuenta .= $femp->ger05;
												break;
										}
									case "GEN01":
									case "GEN02":
									case "GEN03":
									case "GEN04": 
									case "GEN05": 
									case "GEN06": 
									case "GEN07":
									case "GEN08":
										$s = "SELECT * FROM segmentoscont WHERE Concepto = '$cuenta[$i]'";
										$rseg = mysql_query($s,$l) or die($s);
										$fseg = mysql_fetch_object($rseg);
										
										$vlCuenta .= $fseg->Segmento;
										break;
								}
							}
						}
						
						//die($vlCuenta);
						//echo $detalle->ImporteBase."<br><br>";
						if($detalle->ImporteBase == "Diferencia cargo-abono"){
							if($vlCargo > $vlAbono){
								$vlImp = ($vlCargo - $vlAbono) * $detalle->Porcentaje;
							}else{
								$vlImp = ($vlAbono - $vlCargo) * $detalle->Porcentaje;
							}
						}else{
							$vlImp = 0;
							
							//echo "----";
							//print_r($flista);
							//echo "<br><br>";
							
							if($flista->Importe != ""){
								$vlImp = $flista->Importe * $detalle->Porcentaje;
							}
						}
						
						//echo "importe --> $vlImp<br><br>";
						
						if($detalle->TipoMovimiento == "Cargos" || $detalle->TipoMovimiento == "Cargos en Rojo"){
							$vlCargo = $vlCargo + $vlImp;
						}else{
							$vlAbono = $vlAbono + $vlImp;
						}
	
						if($vlImp > 0 && $detalle->SuprimirCero == 1){
							$vlConceptoM = "";
							//--echo $detalle->Referencia."<br>";
							switch ($detalle->Referencia){
								case "Rango de Fecha, Sucursal":
									$s = "SELECT * FROM catalogosucursal WHERE id = $flista->Sucursal";
									//echo "a- $s <br>";
									$rsuc = mysql_query($s,$l) or die($s);
									$fsuc = mysql_fetch_object($rsuc);
	
									$vlConceptoM = "LIQ. DEL ".cambiaf_a_normal($fecinicio)." AL ".cambiaf_a_normal($fecfin)." $fsuc->prefijo";
									break;
								case "Sucursal":
									$s = "SELECT * FROM catalogosucursal WHERE id = $flista->Sucursal";
									//echo "b- $s <br>";
									$rsuc = mysql_query($s,$l) or die($s);
									$fsuc = mysql_fetch_object($rsuc);
									
									$vlConceptoM = $fsuc->descripcion;
									break;
								case "Coordinador de Sucursal":
									$s = "SELECT * FROM catalogoempleado WHERE id = $flista->CoordinadorSuc";
									//echo "c- $s <br>";
									$remp = mysql_query($s,$l) or die($s);
									$femp = mysql_fetch_object($remp);
								
									$vlConceptoM = $femp->nombre;
									break;
								case "Folio, Sucursal":
									$s = "SELECT * FROM catalogosucursal WHERE id = $flista->Sucursal";
									//echo "d- $s <br>";
									$rsuc = mysql_query($s,$l) or die($s);
									$fsuc = mysql_fetch_object($rsuc);
									
									$vlConceptoM = $flista->ID . " " . $fsuc->prefijo;
									break;
							}
							
							
							
							//echo $vlConceptoM."<br>";
							//print_r($detalle);
							//echo "<br>$vlConcepto;<br>$vlConceptoM;<br><br>";
							$arreglo[Fecha]		=	$fecinicio;
							$arreglo[Tipo]		=	"Ingresos";
							$arreglo[Numero]	=	$NumeroPoliza;
							$arreglo[Concepto]	=	$vlConcepto;
							$arreglo[ConceptoU]	=	$detalle->Concepto;
							$arreglo[ConceptoM]	=	$vlConceptoM;
							$arreglo[Orden]		=	$detalle->OrdenImpresion;
							$arreglo[Cuenta]	=	$vlCuenta;
							
							if($detalle->TipoMovimiento == "Cargos" || $detalle->TipoMovimiento == "Cargos en Rojo"){
								$arreglo[TipoMov] = "0";
								if($detalle->TipoMovimiento == "Cargos en Rojo"){
									$arreglo[Cargo] = round($vlImp * -1, 2);
									$vlTotCargo += round($vlImp * -1, 2);
								}else{
									$arreglo[Cargo] = round($vlImp, 2);
									$vlTotCargo += round($vlImp, 2);
								}
								//echo $arreglo[Cargo]."<br><br>";
								$arreglo[Abono] = 0;
							}else{
								$arreglo[TipoMov] = "1";
								$arreglo[Cargo] = 0;
								if($detalle->TipoMovimiento == "Abonos en Rojo"){
									$arreglo[Abono] = round($vlImp * -1, 2);
									$vlTotAbono += round($vlImp * -1, 2);
	
								}else{
									$arreglo[Abono] = round($vlImp, 2);
									$vlTotAbono += round($vlImp, 2);
								}
							}
							$arreglo[AgregarDif] = $detalle->AsignarDiferencia;
							$detallado[] = $arreglo;
						}
					}
				}
			}
		}
		
		if($vlTotCargo <> $vlTotAbono){
			for($i=0; $i<count($detallado); $i++){
				$drPoliza = $detallado[$i];
            
				if($drPoliza[AgregarDif] <> 0){
					if($drPoliza[TipoMov] = "0"){
						if($vlTotCargo > $vlTotAbono){
							$drPoliza[Cargo] -= ($vlTotCargo - $vlTotAbono);
						}else{
							$drPoliza[Cargo] += ($vlTotAbono - $vlTotCargo);
						}
					}else{
						if($vlTotCargo > $vlTotAbono){
							$drPoliza[Cargo] += ($vlTotCargo - $vlTotAbono);
						}else{
							$drPoliza[Cargo] -= ($vlTotAbono - $vlTotCargo);
						}
					}
					break;
				}
			}
        }
        //dsDataSet.Tables("Poliza").DefaultView.Sort = "Orden"
		
		function ordenar($a,$b){
			return $a[Orden]-$b[Orden];
		}
		
		usort($detallado,"ordenar");
		
		for($i=0; $i<count($detallado); $i++){
			$drPoliza = $detallado[$i];
			$vlCadena = "M ";
			$vlCuenta = $drPoliza[Cuenta];
			
			if(strlen($vlCuenta) > 20){
				$vlCadena .= substr($vlCuenta,0,20);
			}else{
				$vlCadena .=  str_pad($vlCuenta,20,' ',STR_PAD_RIGHT);
			}
			
			$vlCadena .= str_pad('',22,' ',STR_PAD_RIGHT);
            $vlCadena .= $drPoliza[TipoMov]." ";
			
			if($drPoliza[TipoMov] == "0"){
				$vlImp = $drPoliza[Cargo];
			}else{
				$vlImp = $drPoliza[Abono];
			}
			
			$vlCadena .= $vlImp."     "."0"."             0.0";
			$vlConceptoM = $drPoliza[ConceptoM];
			
			if(strlen($vlConceptoM) > 30){
				$vlCadena .= substr($vlConceptoM,0, 30);
			}else{
				$vlCadena .= $vlConceptoM;
			}
		}
		
		//$detallado;
	
	$pdf = new PDF('P','mm','letter');
	$pdf->AliasNbPages();
	//Ttulos de las columnas
	$header1 = array('FECHA','TIPO','NUMERO','CONCEPTO','CLASE DIARIO');
	$header2 = array('NO REF','CUENTA','','NOMBRE','DIARIO','CARGO','ABONOS');
	
	//Carga de datos
	$pdf->SetFont('Courier','B',10);	
	
	$pdf->addPage();
	
	$pdf->ImprovedTable($header1,$header2,$detallado);
	
	$pdf->Output();
?>