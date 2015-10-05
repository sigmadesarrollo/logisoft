<? session_start();


	if(!$_SESSION[IDUSUARIO]!=""){


		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");


	}


	if($_GET[tipo]==29){


		$and = " and puesto = 29 ";


	}


	if($_GET[sucursal]!=""){


		$andsuc = " and sucursal = $_GET[sucursal] ";


	}


	$valor = 0;


	if($_GET[empleadodefault]==1){


		$valor = 1;


	}	


	if($_GET[entrego]!=""){


		$entrego = " AND id BETWEEN ".$_GET[conductor1]." AND ".$_GET[conductor1]."";


	}


	require_once('../Conectar.php');


	$link = Conectarse('webpmm');


	$get = @mysql_query("select count(*) from catalogoempleado where 1=1 $and $andsuc $entrego");	


	$total = mysql_result($get,0) + $valor;


	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }


	$pp = 20;


?>


<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />


<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />





<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


  <tr>


    <td width="10%" class="FondoTabla">ID</td>


    <td width="90%" class="FondoTabla">Nombre</td>


  </tr>


  <tr>


    <td colspan="3" height="300px" valign="top">


      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">


        <?		


		$get = mysql_query("SELECT id, CONCAT(nombre,' ',apellidopaterno,' ',apellidomaterno) as nombre FROM catalogoempleado where id>0 $and $andsuc $entrego limit ".$st.",".$pp,$link);


		while($row=@mysql_fetch_array($get)){


	?>


        <tr>


          <td width="49"><span style="cursor:pointer;color:#0000FF" onclick="parent.<?=$_GET[funcion]?>('<?=$row['id'];?>','<?=$_GET['caja'] ?>'); parent.VentanaModal.cerrar();">


            <?= $row['id'];?>


          </span></td>


          <td width="405" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['nombre']; ?>" readonly="true" style="width:300px; border:none; cursor:default"></td>         


          <td width="42"></td>


        </tr>


        <? } 


		if($_GET[empleadodefault]==1 && $st == 0){


        $get = mysql_query("SELECT id, CONCAT(nombre,' ',apellidopaterno,' ',apellidomaterno) as nombre 
		FROM catalogoempleado where id=0",$link);


		while($row=@mysql_fetch_array($get)){?>


        <tr>


          <td width="49"><span style="cursor:pointer;color:#0000FF" onclick="parent.<?=$_GET[funcion]?>('<?=$row['id'];?>','<?=$_GET['caja'] ?>'); parent.VentanaModal.cerrar();">


            <?= $row['id'];?>


          </span></td>


          <td width="405" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['nombre']; ?>" readonly="true" style="width:300px; border:none; cursor:default"></td>         


          <td width="42"></td>


        </tr>


        <? }


		}?>


      </table>


      <p class="Tablas">&nbsp;</p>


    </td>


  </tr>


  <tr>


    <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarEmpleado.php?funcion=$_GET[funcion]&tipo=$_GET[tipo]&caja=$_GET[caja]&sucursal=$_GET[sucursal]&st="); ?></font></td>


  </tr>


</table>


