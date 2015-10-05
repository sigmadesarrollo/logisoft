<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	
	$losands = ($_GET[ands]=="1")?" and e.guiaempresarial<>0 ":" and e.guiaempresarial=0 ";
	
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	$and = "";
	
	if($_GET[sucorigen]!=""){
		$and = " and e.sucursal = ".$_SESSION[IDSUCURSAL];
	}
	
	$tipo=$_GET['tipo'];
	switch ($tipo) {
		case "evaluacion":
			$get=@mysql_query('select count(*) from evaluacionmercancia as e where estado="GUARDADO"
			and e.sucursal = '.$_SESSION[IDSUCURSAL].' '.$losands.'');
			break;
	}
	
	$total =@mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ajax.js"></script>
<script>
	function obtenerEvaluacion(folio){
		if(folio!=""){
			mostrarEvaluacion(folio,'<?=$_GET[funcion] ?>','<?=$_GET[ands] ?>');
		}else{
		window.location = "buscarEvaluacionGen.php?funcion=<?=$_GET[funcion] ?>&ands=<?=$_GET[ands]?>&tipo=<?=$_GET['tipo'] ?>";
		}
	}
</script>
<script src="select.js"></script>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head><body>
<form name="buscar" onsubmit="return false">
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="11%" class="FondoTabla">Folio
      <input type="hidden" name="ands" value="<?=$losands?>"></td>
      <td width="25%" class="FondoTabla">Fecha</td>
      <td class="FondoTabla"><?=($_GET[ands]!="1")?"Recoleccion":"Folio Empresarial";?></td>
      <td class="FondoTabla">Destino</td>
    </tr>
    <tr>
      <td colspan="2" class="FondoTabla">
        <input name="evaluacion" type="text" class="Tablas" id="evaluacion" style="width:80px" onKeyPress="if(event.keyCode==13){obtenerEvaluacion(this.value)}">
      </td>
      <td width="32%" class="FondoTabla">&nbsp;</td>
      <td width="32%" class="FondoTabla">&nbsp;</td>
    </tr>
    <tr>
      <td height="300px" colspan="4" valign="top" class="Tablas" >
	  <div id="txtHint" style="width:100%; height:300px; overflow: scroll;">
	  <table width="100%" border="0" align="center" class="Tablas">
          <?
		  switch ($tipo) {				
			case "evaluacion":			
		$get =@mysql_query('SELECT e.folio, DATE_FORMAT(e.fechaevaluacion,"%d/%m/%Y") AS fechaevaluacion, e.estado,
		e.guiaempresarial, e.recoleccion, e.destino, e.sucursaldestino,	e.bolsaempaque, 
		e.cantidadbolsa, e.totalbolsaempaque, e.emplaye, e.totalemplaye, e.sucursal, e.usuario, e.fecha,
		cd.descripcion AS ndestino FROM evaluacionmercancia e
		INNER JOIN catalogodestino cd ON e.destino = cd.id
		WHERE e.estado="GUARDADO" and e.sucursal = '.$_SESSION[IDSUCURSAL].' '.$losands.' limit '.$st.','.$pp,$link);
				break;			
		}
			while($row=@mysql_fetch_array($get)){
			?>
				<tr >
       <td width="45" class="Tablas" >
<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[0];?></span></td>
            <td width="124" class="Tablas"><?=$row[1]; ?></td>
            <td width="155" class="Tablas"><?=($_GET[ands]!="1")?$row[4]:$row[3];?></td>
            <td width="156" class="Tablas"><?=htmlentities($row[ndestino]); ?></td>
		  </tr>	
		<?	}
		
		?>      </table>
		
		</div></td>
    </tr>
    <tr>
      <td colspan="4" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarEvaluacionGen.php?funcion=$_GET[funcion]&ands=$_GET[ands]&tipo=".$tipo."&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>
<? //} ?>