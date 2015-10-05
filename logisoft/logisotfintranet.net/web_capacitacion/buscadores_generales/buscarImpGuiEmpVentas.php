<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

	require_once('../Conectar.php');

	$link = Conectarse('webpmm');

	$get = @mysql_query("SELECT COUNT(*) FROM solicitudguiasempresariales WHERE prepagada='".$_GET[prepagada]."' and id > 2875");	

	$total = mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }

	$pp = 20;
?>

<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr>

    <td width="10%" class="FondoTabla">ID </td>

    <td width="90%" class="FondoTabla">EMPRESA</td>

  </tr>

  <tr>

    <td colspan="3" class="Tablas" height="300px" valign="top">

      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">

        <?		
			$sql="SELECT id, CONCAT(nombre,' ',apepat,' ', apemat) AS nombre_completo 
				  FROM solicitudguiasempresariales
				  WHERE prepagada='".$_GET[prepagada]."'  and id > 2875
				  order by id
				  limit ".$st.",".$pp;
				 $result=mysql_query($sql,$link);
			if(@mysql_num_rows($result)>0)
			{	 
			while($row=@mysql_fetch_array($result)){
		?>
	
        <tr>

          <td width="49"><span style="cursor:pointer;color:#0000FF" onclick="window.parent.pedirFolios('<?=$row['id'];?>');parent.VentanaModal.cerrar();">

            <? echo $row['id']; ?>

          </span></td>

          <td width="405" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['nombre_completo']; ?>" readonly="true" style="width:320px; border:none; cursor:default"></td>         

          <td width="42"></td>

        </tr>

        <? } 
		}else{
		echo "No se encontro ningun Empresa";
		}			
		?>

      </table>
      
  </td>
	
  </tr>

  <tr>

    <td colspan="3" align="center"><font color="#FF0000" size="-1">
	<? echo paginacion($total, $pp, $st, "buscarImpGuiEmpVentas.php?funcion=$_GET[funcion]&prepagada=$_GET[prepagada]&st="); ?></font>
    </td>

  </tr>

</table>

