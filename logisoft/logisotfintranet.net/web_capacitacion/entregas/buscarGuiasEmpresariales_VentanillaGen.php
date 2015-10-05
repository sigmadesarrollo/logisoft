<? session_start();

	include('../Conectar.php');
	$link=Conectarse('webpmm');

	if($_GET[tipo]==1){
		//Traspasar mercancia.php // Almacenes
		$condicion=" gv.estado='ALMACEN DESTINO' and gv.ocurre=1 and gv.idsucursaldestino = $_SESSION[IDSUCURSAL]";
	}else if($_GET[tipo]==2){
		//Traspasar mercancia.php // Almacenes
		$condicion=" pg.pagado='N' AND gv.estado NOT IN ('ENTREGADA','CANCELADO','ENTREGADA CON FALTANTES','TRASPASO PENDIENTE')";
	}else if($_GET[tipo]==3){
		//Abono de cliente
		 $condicion=" pg.pagado='N' AND gv.estado<>'CANCELADO' AND 
		 pg.cliente='$_GET[cliente]' and not isnull(gv.factura) and pg.sucursalacobrar = $_SESSION[IDSUCURSAL]";
	}

	if($condicion==""){
		$condicion = " 1=1 ";
	}

	/*$get=@mysql_query('SELECT (SELECT COUNT(*)  FROM guiasventanilla '.$condicion.' )
+(SELECT COUNT(*) FROM guiasempresariales '.$condicion.' )');*/
	
	$s = "select count(id) from (
	(SELECT gv.id FROM guiasventanilla gv 
	INNER JOIN pagoguias pg ON gv.id=pg.guia 
	WHERE $condicion GROUP BY gv.id)
	UNION 
	(SELECT gv.id FROM guiasempresariales gv
	INNER JOIN pagoguias pg ON gv.id=pg.guia 
	WHERE $condicion GROUP BY gv.id)
	) as t1";

	$get=@mysql_query($s,$link) or die($s);	
	$total =@mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="select.js"></script>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<form name="buscar" >
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="27%" class="FondoTabla">Folio</td>
      <td width="20%" class="FondoTabla">Fecha</td>
      <td width="53%" class="FondoTabla">Cliente</td>
    </tr>
    <tr>
      <td height="300px" colspan="3" valign="top" class="Tablas"><table width="100%" border="0" align="center" class="Tablas">
          <?		  
		/*$get =@mysql_query('(SELECT gv.id,DATE_FORMAT(gv.fecha, "%d/%m/%Y") AS fecha FROM guiasventanilla gv inner join pagoguias pg on gv.id=pg.guia where pg.pagado="N" AND gv.estado<>"CANCELADA" GROUP'.$condicion.' )
UNION (SELECT id,DATE_FORMAT(fecha, "%d/%m/%Y") AS fecha FROM guiasempresariales '.$condicion.' ) limit '.$st.','.$pp,$link);*/
		
		$get =@mysql_query("SELECT id,fecha, destinatario FROM (
			(SELECT gv.id,DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,
			CONCAT_WS(' ',cc.nombre, cc.paterno, cc.materno) AS destinatario
			FROM guiasventanilla gv 
			INNER JOIN catalogocliente cc ON gv.iddestinatario = cc.id
			INNER JOIN pagoguias pg ON gv.id=pg.guia 
			WHERE $condicion GROUP BY gv.id)
			UNION 
			(SELECT gv.id,DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,
			CONCAT_WS(' ',cc.nombre, cc.paterno, cc.materno) AS destinatario 
			FROM guiasempresariales gv 
			INNER JOIN catalogocliente cc ON gv.iddestinatario = cc.id
			INNER JOIN pagoguias pg ON gv.id=pg.guia 
			WHERE $condicion GROUP BY gv.id)
			)tabla ORDER BY fecha limit ".$st.",".$pp,$link);
			while($row=@mysql_fetch_array($get)){
			?>
				<tr >
       <td width="128" class="Tablas" >


<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[id];?>');try{parent.VentanaModal.cerrar();}catch(e){parent.mens.cerrar();}" style="cursor: pointer; color:#0000FF"><?=$row[id];?></span></td>


            <td width="94" class="Tablas"><?=$row[fecha]; ?></td>


            <td width="260"><?=$row[destinatario]; ?></td>


          </tr>	


		<?	}


		


		?>

      </table></td>


    </tr>


    <tr>
      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarGuiasEmpresariales_VentanillaGen.php?funcion=$_GET[funcion]&cliente=$_GET[cliente]&tipo=$_GET[tipo]&st="); ?></font></td>


    </tr>


  </table> 


</form>


</body>


</html>


<? //} ?>