<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	/*if ( isset ( $_SESSION['gvalidar'] )!=100 ){
	 echo "<script language='javascript' type='text/javascript'>
						document.location.href='../index.php';
					</script>";
	}else{*/
	include('../../Conectar.php');
	$link=Conectarse('pmm');	
	$codigo=$_GET['codigo'];
	$sql=mysql_query("SELECT * FROM catalogodescripcion WHERE codigo='$codigo'",$link);
	$row=mysql_fetch_array($sql);
	$descripcion=htmlentities($row[descripcion]);
?>
<script>
	function foco(){
		document.getElementById('descripcion').focus();
	}
</script>
<div id="txtHint">
  <table width="299" border="0" align="center">    
    <tr>
      <td width="27%" class="Tablas">Descripci&oacute;n:</td>
      <td width="36%"><input name="descripcion" type="text" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('descripcion').value,'descripcion');" onKeyPress="return tabular(event,this);" value="<?=$descripcion ?>" size="40" />
      <? if($codigo!=""){ echo" <script>setTimeout('foco()', 1000);</script>";} ?>
	
	  </td>
    </tr>
  </table>
</div>
<? //}?>