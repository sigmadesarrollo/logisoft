<?
	include("Conectar.php");

	class NotaCreditoElectronica{
		var $dataEmpresa;
		var $dataCliente;
		
		public function setDatos($datEmp,$datCli){
			$this->dataEmpresa = $datEmp;
			$this->dataCliente = $datCli;
		}
		
		public function crearNotaCredito() {
			
			$con = new Conectar("webpmm");
			$l = $con->iniciar();
			
			//---------PROCESO DE DATOS DEL RECEPTOR-----------//
			$informacion["start"]="inicio";
			$serie = '';
			if (!empty($this->dataEmpresa['informacion']['serie'])) {
				$serie = 'serie="'.$this->dataEmpresa['informacion']['serie'].'"';
			}
			$informacion["nombre"]=$this->dataEmpresa['informacion']['name'];
			$informacion["rfc"]=strtoupper($this->dataEmpresa['informacion']["rfc"]);
			$informacion["calle"]=$this->dataEmpresa['informacion']["street"];
			$informacion["noExterior"]=$this->dataEmpresa['informacion']["outside_number"];
			$informacion["noInterior"]=$this->dataEmpresa['informacion']["inside_number"];
			$informacion["colonia"]=$this->dataEmpresa['informacion']["col"];
			$informacion["codigoPostal"]=$this->dataEmpresa['informacion']["cp"];
			$locCliente = '';
				if (!empty($this->dataEmpresa['Client']['locale'])) {
					$locCliente = 'localidad="'.$this->dataEmpresa['Client']['locale'].'"';
				}
				$outNumber = '';
				if (!empty($informacion["noExterior"])) {
					$outNumber = 'noExterior="'.$informacion["noExterior"].'"';
				}
				$inNumber = '';
				if (!empty($informacion["noInterior"])) {
					$inNumber = 'noInterior="'.$informacion["noInterior"].'"';
				}
				$informacion["folio"]=$this->dataEmpresa['informacion']["folio"];
				$informacion["numeroaprobacion"]=$this->dataEmpresa['informacion']["numeroaprobacion"];
				$informacion["anoaprobacion"]=$this->dataEmpresa['informacion']["anoaprobacion"];
			$informacion["municipio"]=$this->dataEmpresa['informacion']["municipio"];
			$informacion["estado"]=$this->dataEmpresa['informacion']["state"];
			$informacion["pais"]=$this->dataEmpresa['informacion']["country"];
			$result = $this->_getRemoteConceptos($this->dataEmpresa['producto']);
			$informacion["totalImpuestosTrasladados"] = $this->dataEmpresa['Impuestos']['iva'];
			//$informacion["importeIVAT"] = $result['importeIVAT'];
			$informacion["ivaRetenido"] = $this->dataEmpresa['Impuestos']['ivaRetenido'];
			$informacion["tasa"]= $this->dataEmpresa['Impuestos']['tasa'];
			if($informacion["tasa"]<1){
				$informacion["tasa"] *= 100;
				$informacion["tasa"] .= '';
			}
			
			$ivaretenidoImpuestos="";
			$ivaretenidoRetenciones="";
			
			if($informacion["ivaRetenido"]>0){
				$ivaretenidoImpuestos='totalImpuestosRetenidos="'.$informacion["ivaRetenido"].'"';
				$ivaretenidoRetenciones='<Retenciones>
								<Retencion importe="'.$informacion["ivaRetenido"].'" impuesto="IVA"></Retencion>
							</Retenciones>';
			}
			
			$conceptos = $result['conceptos'];
			$business["rfc"] = $this->dataCliente['Business']["rfc"];
			$business["name"] = $this->dataCliente['Business']["name"];
			$business["street"] = $this->dataCliente['Business']["street"];
			$business["outside_number"] = $this->dataCliente['Business']["outside_number"];
			$business["inside_number"] = $this->dataCliente['Business']["inside_number"];
			
			$boutNumber = '';
				if (!empty($business["outside_number"])) {
					$boutNumber = 'noExterior="'.$business["outside_number"].'"';
				}
				$binNumber = '';
				if (!empty($business["inside_number"])) {
					$binNumber = 'noInterior="'.$business["inside_number"].'"';
				}
			$business["col"] = $this->dataCliente['Business']["col"];
			$business["cp"] = $this->dataCliente['Business']["cp"];
			if (!empty($this->dataCliente['Business']['city_name'])) {
				$business["city"] = $this->dataCliente['Business']['city_name'];
			}
			$business["municipio"] = $this->dataCliente["Municipio"]['name'];
			$business["state"] = $this->dataCliente["State"]['name'];
			$business["country"] = $this->dataCliente["Country"]['name'];
			
			$s = "SELECT serieventa FROM catalogofoliosnotacredito 
			WHERE '".$informacion['folio']."' BETWEEN folioinicial AND foliofinal";
			
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			
			$serial_number = $f->serieventa;
			
			$s = "select * from certificates";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			//se quito de aqui por que el numero de certificado se refiere al numero de la compra de los folios
			//$serial_number = $f->serial_number;
			$keyCertificate = $f->key;
			//$this->cert = $this->getCertificado();
			$certificate = $this->getContCert($f->certificate);
			//$this->ctr_fact = $this->getCtr_fact();
			
			
			
			$this->fecha = date("Y-m-d\TH:i:s",strtotime("-1 hour"));
			$stotal = $this->dataEmpresa['Impuestos']['subtotal'];
			$ttotal = $this->dataEmpresa['Impuestos']['total'];
			if (empty($business['city'])) {
				$loc="";
			} else {
				$loc='localidad="'.$business['city'].'"';
			}
			$this->_cleanAttributesEntities($business);
			$this->_cleanAttributesEntities($informacion);
			$xml='<?xml version="1.0" encoding="UTF-8"?>
								<Comprobante xmlns="http://www.sat.gob.mx/cfd/2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/2  http://www.sat.gob.mx/sitio_internet/cfd/2/cfdv2.xsd" noCertificado="'.$serial_number.'" formaDePago="pago en una sola exhibicion" noAprobacion="'.$informacion['numeroaprobacion'].'" certificado="'.$certificate.'"   sello="#1#" anoAprobacion="'.$informacion['anoaprobacion'].'" fecha="'.$this->fecha.'" subTotal="'.$stotal.'" total="'.$ttotal.'" '.$serie.' folio="'.$informacion['folio'].'" tipoDeComprobante="egreso" version="2.0">
								 <Emisor rfc="'.$business['rfc'].'" nombre="'.$business['name'].'">
												<DomicilioFiscal calle="'.$business['street'].'"  '.$boutNumber.' '.$binNumber.' colonia="'.$business['col'].'" codigoPostal="'.$business['cp'].'" '.$loc.' municipio="'.$business['municipio'].'"  estado="'.$business['state'].'" pais="'.$business['country'].'" />
										</Emisor>
										<Receptor rfc="'.$informacion["rfc"].'" nombre="'.$informacion["nombre"].'">
												<Domicilio calle="'.$informacion["calle"].'" '.$outNumber.' '.$inNumber.' colonia="'.$informacion["colonia"].'" codigoPostal="'.$informacion["codigoPostal"].'" '.$locCliente.' municipio="'.$informacion["municipio"].'" estado="'.$informacion["estado"].'" pais="'.$informacion["pais"].'"/>
										</Receptor>'. $conceptos .'
										<Impuestos totalImpuestosTrasladados="' .$informacion["totalImpuestosTrasladados"]. '" '.$ivaretenidoImpuestos.'>
												'.$ivaretenidoRetenciones.'
												<Traslados>
														<Traslado importe="'.$informacion["totalImpuestosTrasladados"].'" tasa="'. $informacion["tasa"].'" impuesto="IVA"></Traslado>
												</Traslados>
										</Impuestos>
								</Comprobante>';
			$xml = $this->getXMLSing($xml,$keyCertificate);
			return $xml;
		}
		private function _cleanAttributesEntities(&$objects) {
			foreach ($objects as &$object) {
				$object = $this->_XMLClean($object);
			}
		}
		function _XMLClean($strin) {
			$strout = null;
			for ($i = 0; $i < strlen($strin); $i++) {
				switch ($strin[$i]) {
						case '<':
								$strout .= '&lt;';
								break;
						case '>':
								$strout .= '&gt;';
								break;
						case '&':
								$strout .= '&amp;';
								break;
						case '"':
								$strout .= '&quot;';
								break;
						default:
								$strout .= $strin[$i];
				}
	
			}
			return $strout;
		}
		private function _getRemoteConceptos($producto) {
			//TODO mandar el nombre por que falla, se tiene que poner o nombre  o descripcion
			$totalImpuestos = 0;
			$ttotal = 0;
			$stotal = 0;
			$imp = 0;
			$descripcion = '';
			$conceptos = '<Conceptos>';
			foreach ($producto as $concepto) {
				if (!empty($concepto['cantidad']) && !empty($concepto['preciounitario'])) {
					$conceptos .='<Concepto cantidad="'. $concepto['cantidad']. '" descripcion="' . $concepto['descripcion'] .'" valorUnitario="'. $concepto['preciounitario'] .'"  importe="'. $concepto['importe'] .'" />';
				}
			}
			$conceptos .= '</Conceptos>';
			$informacion["conceptos"] = $conceptos;
			return $informacion;
		}
		
		private function getXMLSing($xmlHon,$priv_key){
			//Carga Certificado
			$xml = new DomDocument();
			$xml->loadXML($xmlHon);
			//Carga prosedimiento de proceso de cadena original
			$xsl = new DomDocument;
			$xsl->load("ostring.xsl");
			$proc = new xsltprocessor();
			$proc->importStyleSheet($xsl);
			$original =$proc->transformToXML($xml);
			//firma la cadena original
			
			//$fp = $cert[0]['certificates']['key'];
			//$priv_key = $f['key'];
			//die($f['key']);
			//fclose($fp);
			
			$pkeyid = openssl_get_privatekey($priv_key);
			
			//$limit = strtotime('2010');
			//$current = strtotime('2011');
			//if ($current < $limit) {
			//	openssl_sign($original, $signature, $pkeyid,OPENSSL_ALGO_MD5);
			//} else {
				openssl_sign($original, $signature, $pkeyid,OPENSSL_ALGO_SHA1);
			//}
			
			//openssl_sign($original, $signature, $pkeyid,OPENSSL_ALGO_MD5);
			
			
			openssl_free_key($pkeyid);
			//coloca el sello en xml
			$esqueletonew=$xmlHon;
			$esqueletonew=str_replace("#1#",base64_encode($signature),$esqueletonew);
			$xmlReturn[1]=$esqueletonew;
			$xmlReturn[2]=$original;
			$xmlReturn[3]=base64_encode($signature);
			return $xmlReturn;
		}
		
		function getContCert($ncert) {
			//$fp = fopen("files/".$ncert, "r");
			//$cert = fread($fp, 8192);
			//TODO CHECK FOR CORRECT CERTIFICATE
			//$cert = $this->{$this->modelClass}->query("select * from certificates");
			return base64_encode($ncert);
		}
	}
	
	
	/*
	$miClase = new FacturacionElectronica();
	
	$cliente['informacion']['serie'] = "SER";
	$cliente['informacion']['name'] = "ENTREGAS PUNTUALES S DE RL DE CV";
	$cliente['informacion']['rfc'] = "EPU1004244X2";
	$cliente['informacion']['street'] = "AV. BENEMERITO DE LAS AMERICAS";
	//$cliente['informacion']["inside_number"] = "";
	$cliente['informacion']["outside_number"] = "301";
	$cliente['informacion']["col"] = "PALOS PRIENTOS";
	$cliente['informacion']["cp"] = "82165";
	# de la facturacion
	$cliente['informacion']["folio"] = "123";
	$cliente['informacion']["numeroaprobacion"] = "1";
	$cliente['informacion']["anoaprobacion"] = "2010";
	$cliente['informacion']["municipio"] = "MAZATLAN";
	$cliente['informacion']["state"] = "SINALOA";
	$cliente['informacion']["country"] = "MEXICO";
	
	#llenar los detallados 
	   #$cliente['producto']
	   $cliente['producto'][0]['preciounitario'] = 50;
	   $cliente['producto'][0]['descripcion'] = 50;
	   $cliente['producto'][0]['cantidad'] = 50;
	   $cliente['producto'][0]['importe'] = 50;
	   $cliente['producto'][1]['preciounitario'] = 50;
	   $cliente['producto'][1]['descripcion'] = 50;
	   $cliente['producto'][1]['cantidad'] = 50;
	   $cliente['producto'][1]['importe'] = 50;

	
	#---------------------
	$cliente['Impuestos']['totalImpuestosTrasladados'] = 0;
	$cliente['Impuestos']['tasa'] = "100";
	$cliente['Impuestos']['iva'] = "50";
	$cliente['Impuestos']['ivaRetenido'] = "50";
	$cliente['Impuestos']['subtotal'] = "500";
	$cliente['Impuestos']['total'] = "500";
	
	#empresa
	$empresa['Business']['name'] = "NAME";
	$empresa['Business']['rfc'] = "PAEE123456ASD";
	$empresa['Business']['street'] = "JUAREZ";
	$empresa['Business']["inside_number"] = "35";
	$empresa['Business']["outside_number"] = "35";
	$empresa['Business']["col"] = "JUAREZ";
	$empresa['Business']["cp"] = "82165";
	$empresa['Business']['city_name'] = "MAZATLAN";
	$empresa["Municipio"]['name'] = "MAZATLAN";
	$empresa["State"]['name'] = "SINALOA";
	$empresa["Country"]['name'] = "MEXICO";
	
	$miClase->setDatos($cliente,$empresa);
	
	print_r($miClase->crearFactura());*/
?>