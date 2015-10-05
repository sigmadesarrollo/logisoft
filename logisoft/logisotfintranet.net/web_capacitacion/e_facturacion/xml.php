<?
	$xml = '  <?xml version="1.0" encoding="UTF-8" ?> 
- <!--  edited with XMLSpy v2008 (http://www.altova.com) by SAT (.) 
  --> 
- <xs:schema xmlns="http://www.sat.gob.mx/cfd/2" xmlns:xs="http://www.w3.org/2001/XMLSchema" targetNamespace="http://www.sat.gob.mx/cfd/2" elementFormDefault="qualified" attributeFormDefault="unqualified">
- <xs:element name="Comprobante">
- <xs:annotation>
  <xs:documentation>Est�ndar para la expresi�n de comprobantes fiscales digitales.</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:sequence>
- <xs:element name="Emisor">
- <xs:annotation>
  <xs:documentation>Nodo requerido para expresar la informaci�n del contribuyente emisor del comprobante.</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:sequence>
- <xs:element name="DomicilioFiscal" type="t_UbicacionFiscal">
- <xs:annotation>
  <xs:documentation>Nodo requerido para precisar la informaci�n de ubicaci�n del domicilio fiscal del contribuyente emisor</xs:documentation> 
  </xs:annotation>
  </xs:element>
- <xs:element name="ExpedidoEn" type="t_Ubicacion" minOccurs="0">
- <xs:annotation>
  <xs:documentation>Nodo opcional para precisar la informaci�n de ubicaci�n del domicilio en donde es emitido el comprobante fiscal en caso de que sea distinto del domicilio fiscal del contribuyente emisor.</xs:documentation> 
  </xs:annotation>
  </xs:element>
  </xs:sequence>
- <xs:attribute name="rfc" type="t_RFC" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para la Clave del Registro Federal de Contribuyentes correspondiente al contribuyente emisor del comprobante sin guiones o espacios.</xs:documentation> 
  </xs:annotation>
  </xs:attribute>
- <xs:attribute name="nombre" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para el nombre o raz�n social del contribuyente emisor del comprobante.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
  </xs:complexType>
  </xs:element>
- <xs:element name="Receptor">
- <xs:annotation>
  <xs:documentation>Nodo requerido para precisar la informaci�n del contribuyente receptor del comprobante.</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:sequence>
- <xs:element name="Domicilio" type="t_Ubicacion">
- <xs:annotation>
  <xs:documentation>Nodo para la definici�n de la ubicaci�n donde se da el domicilio del receptor del comprobante fiscal.</xs:documentation> 
  </xs:annotation>
  </xs:element>
  </xs:sequence>
- <xs:attribute name="rfc" type="t_RFC" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para precisar la Clave del Registro Federal de Contribuyentes correspondiente al contribuyente receptor del comprobante.</xs:documentation> 
  </xs:annotation>
  </xs:attribute>
- <xs:attribute name="nombre" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para precisar el nombre o raz�n social del contribuyente receptor.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
  </xs:complexType>
  </xs:element>
- <xs:element name="Conceptos">
- <xs:annotation>
  <xs:documentation>Nodo requerido para enlistar los conceptos cubiertos por el comprobante.</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:sequence>
- <xs:element name="Concepto" maxOccurs="unbounded">
- <xs:annotation>
  <xs:documentation>Nodo para introducir la informaci�n detallada de un bien o servicio amparado en el comprobante.</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:choice minOccurs="0">
- <xs:element name="InformacionAduanera" type="t_InformacionAduanera" minOccurs="0" maxOccurs="unbounded">
- <xs:annotation>
  <xs:documentation>Nodo opcional para introducir la informaci�n aduanera aplicable cuando se trate de ventas de primera mano de mercanc�as importadas.</xs:documentation> 
  </xs:annotation>
  </xs:element>
- <xs:element name="CuentaPredial" minOccurs="0">
- <xs:annotation>
  <xs:documentation>Nodo opcional para asentar el n�mero de cuenta predial con el que fue registrado el inmueble, en el sistema catastral de la entidad federativa de que trate.</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:attribute name="numero" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para precisar el n�mero de la cuenta predial del inmueble cubierto por el presente concepto en caso de recibos de arrendamiento.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:whiteSpace value="collapse" /> 
  <xs:minLength value="1" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
  </xs:complexType>
  </xs:element>
- <xs:element name="ComplementoConcepto" minOccurs="0">
- <xs:annotation>
  <xs:documentation>Nodo opcional donde se incluir�n los nodos complementarios de extensi�n al concepto, definidos por el SAT, de acuerdo a disposiciones particulares a un sector o actividad especifica.</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:sequence>
  <xs:any minOccurs="0" maxOccurs="unbounded" /> 
  </xs:sequence>
  </xs:complexType>
  </xs:element>
- <xs:element name="Parte" minOccurs="0" maxOccurs="unbounded">
- <xs:annotation>
  <xs:documentation>Nodo opcional para expresar las partes o componentes que integran la totalidad del concepto expresado en el comprobante fiscal digital</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:sequence>
- <xs:element name="InformacionAduanera" type="t_InformacionAduanera" minOccurs="0" maxOccurs="unbounded">
- <xs:annotation>
  <xs:documentation>Nodo opcional para introducir la informaci�n aduanera aplicable cuando se trate de partes o componentes importados vendidos de primera mano.</xs:documentation> 
  </xs:annotation>
  </xs:element>
  </xs:sequence>
- <xs:attribute name="cantidad" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para precisar la cantidad de bienes o servicios del tipo particular definido por la presente parte.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:decimal">
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="unidad" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para precisar la unidad de medida aplicable para la cantidad expresada en la parte.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:whiteSpace value="collapse" /> 
  <xs:minLength value="1" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="noIdentificacion" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para expresar el n�mero de serie del bien o identificador del servicio amparado por la presente parte.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="descripcion" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para precisar la descripci�n del bien o servicio cubierto por la presente parte.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="valorUnitario" type="t_Importe" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para precisar el valor o precio unitario del bien o servicio cubierto por la presente parte.</xs:documentation> 
  </xs:annotation>
  </xs:attribute>
- <xs:attribute name="importe" type="t_Importe" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para precisar el importe total de los bienes o servicios de la presente parte. Debe ser equivalente al resultado de multiplicar la cantidad por el valor unitario expresado en la parte.</xs:documentation> 
  </xs:annotation>
  </xs:attribute>
  </xs:complexType>
  </xs:element>
  </xs:choice>
- <xs:attribute name="cantidad" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para precisar la cantidad de bienes o servicios del tipo particular definido por el presente concepto.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:decimal">
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="unidad" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para precisar la unidad de medida aplicable para la cantidad expresada en el concepto.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:whiteSpace value="collapse" /> 
  <xs:minLength value="1" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="noIdentificacion" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para expresar el n�mero de serie del bien o identificador del servicio amparado por el presente concepto.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="descripcion" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para precisar la descripci�n del bien o servicio cubierto por el presente concepto.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="valorUnitario" type="t_Importe" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para precisar el valor o precio unitario del bien o servicio cubierto por el presente concepto.</xs:documentation> 
  </xs:annotation>
  </xs:attribute>
- <xs:attribute name="importe" type="t_Importe" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para precisar el importe total de los bienes o servicios del presente concepto. Debe ser equivalente al resultado de multiplicar la cantidad por el valor unitario expresado en el concepto.</xs:documentation> 
  </xs:annotation>
  </xs:attribute>
  </xs:complexType>
  </xs:element>
  </xs:sequence>
  </xs:complexType>
  </xs:element>
- <xs:element name="Impuestos">
- <xs:annotation>
  <xs:documentation>Nodo requerido para capturar los impuestos aplicables.</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:sequence>
- <xs:element name="Retenciones" minOccurs="0">
- <xs:annotation>
  <xs:documentation>Nodo opcional para capturar los impuestos retenidos aplicables</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:sequence>
- <xs:element name="Retencion" maxOccurs="unbounded">
- <xs:annotation>
  <xs:documentation>Nodo para la informaci�n detallada de una retenci�n de impuesto espec�fico</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:attribute name="impuesto" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para se�alar el tipo de impuesto retenido</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:whiteSpace value="collapse" /> 
- <xs:enumeration value="ISR">
- <xs:annotation>
  <xs:documentation>Impuesto sobre la renta</xs:documentation> 
  </xs:annotation>
  </xs:enumeration>
- <xs:enumeration value="IVA">
- <xs:annotation>
  <xs:documentation>Impuesto al Valor Agregado</xs:documentation> 
  </xs:annotation>
  </xs:enumeration>
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="importe" type="t_Importe" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para se�alar el importe o monto del impuesto retenido</xs:documentation> 
  </xs:annotation>
  </xs:attribute>
  </xs:complexType>
  </xs:element>
  </xs:sequence>
  </xs:complexType>
  </xs:element>
- <xs:element name="Traslados" minOccurs="0">
- <xs:annotation>
  <xs:documentation>Nodo opcional para asentar o referir los impuestos trasladados aplicables</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:sequence>
- <xs:element name="Traslado" maxOccurs="unbounded">
- <xs:annotation>
  <xs:documentation>Nodo para la informaci�n detallada de un traslado de impuesto espec�fico</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:attribute name="impuesto" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para se�alar el tipo de impuesto trasladado</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:whiteSpace value="collapse" /> 
- <xs:enumeration value="IVA">
- <xs:annotation>
  <xs:documentation>Impuesto al Valor Agregado</xs:documentation> 
  </xs:annotation>
  </xs:enumeration>
- <xs:enumeration value="IEPS">
- <xs:annotation>
  <xs:documentation>Impuesto especial sobre productos y servicios</xs:documentation> 
  </xs:annotation>
  </xs:enumeration>
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="tasa" type="t_Importe" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para se�alar la tasa del impuesto que se traslada por cada concepto amparado en el comprobante</xs:documentation> 
  </xs:annotation>
  </xs:attribute>
- <xs:attribute name="importe" type="t_Importe" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para se�alar el importe del impuesto trasladado</xs:documentation> 
  </xs:annotation>
  </xs:attribute>
  </xs:complexType>
  </xs:element>
  </xs:sequence>
  </xs:complexType>
  </xs:element>
  </xs:sequence>
- <xs:attribute name="totalImpuestosRetenidos" type="t_Importe" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para expresar el total de los impuestos retenidos que se desprenden de los conceptos expresados en el comprobante fiscal digital.</xs:documentation> 
  </xs:annotation>
  </xs:attribute>
- <xs:attribute name="totalImpuestosTrasladados" type="t_Importe" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para expresar el total de los impuestos trasladados que se desprenden de los conceptos expresados en el comprobante fiscal digital.</xs:documentation> 
  </xs:annotation>
  </xs:attribute>
  </xs:complexType>
  </xs:element>
- <xs:element name="Complemento" minOccurs="0">
- <xs:annotation>
  <xs:documentation>Nodo opcional donde se incluir�n los nodos complementarios determinados por el SAT, de acuerdo a las disposiciones particulares a un sector o actividad especifica.</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:sequence>
  <xs:any minOccurs="0" maxOccurs="unbounded" /> 
  </xs:sequence>
  </xs:complexType>
  </xs:element>
- <xs:element name="Addenda" minOccurs="0">
- <xs:annotation>
  <xs:documentation>Nodo opcional para recibir las extensiones al presente formato que sean de utilidad al contribuyente. Para las reglas de uso del mismo, referirse al formato de origen.</xs:documentation> 
  </xs:annotation>
- <xs:complexType>
- <xs:sequence>
  <xs:any minOccurs="0" maxOccurs="unbounded" /> 
  </xs:sequence>
  </xs:complexType>
  </xs:element>
  </xs:sequence>
- <xs:attribute name="version" use="required" fixed="2.0">
- <xs:annotation>
  <xs:documentation>Atributo requerido con valor prefijado a 2.0 que indica la versi�n del est�ndar bajo el que se encuentra expresado el comprobante.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="serie" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para precisar la serie a la que corresponde el comprobante. Este atributo acepta una cadena de caracteres alfab�ticos de 1 a 10 caracteres sin incluir caracteres acentuados.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:maxLength value="10" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="folio" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido que acepta un valor num�rico entero superior a 0 que expresa el folio del comprobante.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:maxLength value="20" /> 
  <xs:whiteSpace value="collapse" /> 
  <xs:pattern value="[0-9]+" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="fecha" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para la expresi�n de la fecha y hora de expedici�n del comprobante fiscal. Se expresa en la forma aaaa-mm-ddThh:mm:ss, de acuerdo con la especificaci�n ISO 8601.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:dateTime">
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="sello" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para contener el sello digital del comprobante fiscal, al que hacen referencia las reglas de resoluci�n miscel�nea aplicable. El sello deber� ser expresado c�mo una cadena de texto en formato Base 64.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="noAprobacion" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para precisar el n�mero de aprobaci�n emitido por el SAT, para el rango de folios al que pertenece el folio particular que ampara el comprobante fiscal digital.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:integer">
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="anoAprobacion" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para precisar el a�o en que se solicito el folio que se est�n utilizando para emitir el comprobante fiscal digital.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:integer">
  <xs:whiteSpace value="collapse" /> 
  <xs:totalDigits value="4" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="formaDePago" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para precisar la forma de pago que aplica para este comprobante fiscal digital. Se utiliza para expresar Pago en una sola exhibici�n o n�mero de parcialidad pagada contra el total de parcialidades, Parcialidad 1 de X.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="noCertificado" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para expresar el n�mero de serie del certificado de sello digital que ampara al comprobante, de acuerdo al acuse correspondiente a 20 posiciones otorgado por el sistema del SAT.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:length value="20" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="certificado" use="optional">
- <xs:annotation>

  <xs:documentation>Atributo opcional que sirve para expresar el certificado de sello digital que ampara al comprobante como texto, en formato base 64.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="condicionesDePago" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para expresar las condiciones comerciales aplicables para el pago del comprobante fiscal digital.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:whiteSpace value="collapse" /> 
  <xs:minLength value="1" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="subTotal" type="t_Importe" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para representar la suma de los importes antes de descuentos e impuestos.</xs:documentation> 
  </xs:annotation>
  </xs:attribute>
- <xs:attribute name="descuento" type="t_Importe" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para representar el importe total de los descuentos aplicables antes de impuestos.</xs:documentation> 
  </xs:annotation>
  </xs:attribute>
- <xs:attribute name="motivoDescuento" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para expresar el motivo del descuento aplicable.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="total" type="t_Importe" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para representar la suma del subtotal, menos los descuentos aplicables, m�s los impuestos trasladados, menos los impuestos retenidos.</xs:documentation> 
  </xs:annotation>
  </xs:attribute>
- <xs:attribute name="metodoDePago" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional de texto libre para expresar el m�todo de pago de los bienes o servicios amparados por el comprobante. Se entiende como m�todo de pago leyendas tales como: cheque, tarjeta de cr�dito o debito, dep�sito en cuenta, etc.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="tipoDeComprobante" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para expresar el efecto del comprobante fiscal para el contribuyente emisor.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:enumeration value="ingreso" /> 
  <xs:enumeration value="egreso" /> 
  <xs:enumeration value="traslado" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
  </xs:complexType>
  </xs:element>
- <xs:complexType name="t_Ubicacion">
- <xs:annotation>
  <xs:documentation>Tipo definido para expresar domicilios o direcciones</xs:documentation> 
  </xs:annotation>
- <xs:attribute name="calle" use="optional">
- <xs:annotation>
  <xs:documentation>Este atributo opcional sirve para precisar la avenida, calle, camino o carretera donde se da la ubicaci�n.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="noExterior" use="optional">
- <xs:annotation>
  <xs:documentation>Este atributo opcional sirve para expresar el n�mero particular en donde se da la ubicaci�n sobre una calle dada.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="noInterior" use="optional">
- <xs:annotation>
  <xs:documentation>Este atributo opcional sirve para expresar informaci�n adicional para especificar la ubicaci�n cuando calle y n�mero exterior (noExterior) no resulten suficientes para determinar la ubicaci�n de forma precisa.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="colonia" use="optional">
- <xs:annotation>
  <xs:documentation>Este atributo opcional sirve para precisar la colonia en donde se da la ubicaci�n cuando se desea ser m�s espec�fico en casos de ubicaciones urbanas.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="localidad" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional que sirve para precisar la ciudad o poblaci�n donde se da la ubicaci�n.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="referencia" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para expresar una referencia de ubicaci�n adicional.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="municipio" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional que sirve para precisar el municipio o delegaci�n (en el caso del Distrito Federal) en donde se da la ubicaci�n.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="estado" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional que sirve para precisar el estado o entidad federativa donde se da la ubicaci�n.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="pais" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido que sirve para precisar el pa�s donde se da la ubicaci�n.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="codigoPostal" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional que sirve para asentar el c�digo postal en donde se da la ubicaci�n.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
  </xs:complexType>
- <xs:complexType name="t_UbicacionFiscal">
- <xs:annotation>
  <xs:documentation>Tipo definido para expresar domicilios o direcciones</xs:documentation> 
  </xs:annotation>
- <xs:attribute name="calle" use="required">
- <xs:annotation>
  <xs:documentation>Este atributo requerido sirve para precisar la avenida, calle, camino o carretera donde se da la ubicaci�n.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="noExterior" use="optional">
- <xs:annotation>
  <xs:documentation>Este atributo opcional sirve para expresar el n�mero particular en donde se da la ubicaci�n sobre una calle dada.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="noInterior" use="optional">
- <xs:annotation>
  <xs:documentation>Este atributo opcional sirve para expresar informaci�n adicional para especificar la ubicaci�n cuando calle y n�mero exterior (noExterior) no resulten suficientes para determinar la ubicaci�n de forma precisa.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="colonia" use="optional">
- <xs:annotation>
  <xs:documentation>Este atributo opcional sirve para precisar la colonia en donde se da la ubicaci�n cuando se desea ser m�s espec�fico en casos de ubicaciones urbanas.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="localidad" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional que sirve para precisar la ciudad o poblaci�n donde se da la ubicaci�n.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="referencia" use="optional">
- <xs:annotation>
  <xs:documentation>Atributo opcional para expresar una referencia de ubicaci�n adicional.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:whiteSpace value="collapse" /> 
  <xs:minLength value="1" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="municipio" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido que sirve para precisar el municipio o delegaci�n (en el caso del Distrito Federal) en donde se da la ubicaci�n.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="estado" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido que sirve para precisar el estado o entidad federativa donde se da la ubicaci�n.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="pais" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido que sirve para precisar el pa�s donde se da la ubicaci�n.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="codigoPostal" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido que sirve para asentar el c�digo postal en donde se da la ubicaci�n.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:whiteSpace value="collapse" /> 
  <xs:length value="5" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
  </xs:complexType>
- <xs:simpleType name="t_RFC">
- <xs:annotation>
  <xs:documentation>Tipo definido para expresar claves del Registro Federal de Contribuyentes</xs:documentation> 
  </xs:annotation>
- <xs:restriction base="xs:string">
  <xs:minLength value="12" /> 
  <xs:maxLength value="13" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
- <xs:simpleType name="t_Importe">
- <xs:annotation>
  <xs:documentation>Tipo definido para expresar importes num�ricos con fracci�n a seis decimales</xs:documentation> 
  </xs:annotation>
- <xs:restriction base="xs:decimal">
  <xs:fractionDigits value="6" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
- <xs:complexType name="t_InformacionAduanera">
- <xs:annotation>
  <xs:documentation>Tipo definido para expresar informaci�n aduanera</xs:documentation> 
  </xs:annotation>
- <xs:attribute name="numero" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para expresar el n�mero del documento aduanero que ampara la importaci�n del bien.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="fecha" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para expresar la fecha de expedici�n del documento aduanero que ampara la importaci�n del bien. Se expresa en el formato aaaa-mm-dd</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:date">
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
- <xs:attribute name="aduana" use="required">
- <xs:annotation>
  <xs:documentation>Atributo requerido para precisar la aduana por la que se efectu� la importaci�n del bien.</xs:documentation> 
  </xs:annotation>
- <xs:simpleType>
- <xs:restriction base="xs:string">
  <xs:minLength value="1" /> 
  <xs:whiteSpace value="collapse" /> 
  </xs:restriction>
  </xs:simpleType>
  </xs:attribute>
  </xs:complexType>
  </xs:schema>';
?>