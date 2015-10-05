<? session_start();


	include('../../Conectar.php');


	$link=Conectarse('webpmm');


	$usuario=$_SESSION[NOMBREUSUARIO];


	$tipo=$_GET['tipo'];


?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html xmlns="http://www.w3.org/1999/xhtml">


<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<title>Untitled Document</title>


<script src="select.js"></script>


<script language="javascript">


	function enviarMunicipio(municipio,e){


		   tecla = (document.all) ? e.keyCode : e.which;		


           if(tecla!=13){


				 return;	


			}else{


				if(municipio==""){


					document.form1.submit();


				}else{


					FiltroMunicipio_CatMunicipio(municipio,'1');				


				}


				


			}


	}


	function enviarEstado(estado,e){


			tecla = (document.all) ? e.keyCode : e.which;		


			if(tecla!=13){


				 return;	


			}else{


				if(estado==""){


					document.form1.submit();


				}else{


					FiltroPoblacion_CatMunicipio(estado,'2');


				}


			}


	}








</script>








<link href="../../css/FondoTabla.css" rel="stylesheet" type="text/css" />


<link href="FondoTabla.css" rel="stylesheet" type="text/css" />


<link href="Tablas.css" rel="stylesheet" type="text/css" />


</head>





<body>


<form id="form1" name="form1" method="post" action="" onsubmit="return false">


  <? if($tipo==1){ 


	$get = mysql_query('select count(*) from catalogoestado');


	$total = mysql_result($get,0);


	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }


	$pp = 20;


//BUSCAR POBLACION


?>


  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


    <tr>


      <td class="FondoTabla">ID</td>


      <td class="FondoTabla">Descripción</td>


    </tr>


    <tr>


      <td width="7%" class="FondoTabla">&nbsp;</td>


      <td width="85%" class="FondoTabla"><input name="buscar" type="text" class="Tablas" id="buscar" style="text-transform:uppercase;"  onkeydown="enviarEstado(this.value,event)" size="50"/></td>


    </tr>


    <tr>


      <td colspan="2" class="Tablas"><div id="txtHint" style="width:100%; height:300px; overflow: scroll;">


          <table border="0" width="96%">


            <? 


	$get = mysql_query('SELECT ES.id,ES.descripcion,PA.descripcion as pais FROM catalogoestado AS ES


INNER JOIN catalogopais AS PA ON PA.id=ES.pais limit '.$st.','.$pp,$link);	


	while($row=mysql_fetch_array($get)){


?>


            <tr>


              <td width="11%"><span onclick="window.parent.obtenerEstado('<?=$row[0];?>','<?=$row[1];?>','<?=$row[2];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">


                <?=$row[0];?>


              </span></td>


              <td width="89%"><?=$row[1]?></td>


            </tr>


            <? }?>


          </table>


      </div></td>


    </tr>


    <tr>


      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'catalogomunicipio_Buscar.php?tipo='.$tipo.'&st='); ?></font></td>


    </tr>


  </table>


  <? } ?>


  <? if($tipo==2){


	$get = mysql_query('select count(*) from catalogomunicipio');


	$total = mysql_result($get,0);


	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }


	$pp = 20;


//MOSTRAR MUNICIPIOS+ESTADO+PAIS


?>


  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


    <tr>


      <td class="FondoTabla">ID</td>


      <td class="FondoTabla">Descripción</td>


    </tr>


    <tr>


      <td width="7%" class="FondoTabla">&nbsp;</td>


      <td width="85%" class="FondoTabla"><input class="Tablas" name="buscar2" type="text" id="buscar" style="text-transform:uppercase;" onkeydown="enviarMunicipio(this.value,event)" size="50"/></td>


    </tr>


    <tr>


      <td colspan="2" class="Tablas"><div id="txtHint" style="width:100%; height:300px; overflow: scroll;">


          <table border="0" width="96%">


            <? 


	$get = mysql_query('SELECT CM.id AS id_municipio,UCASE(CM.descripcion) AS municipio_descripcion,  CE.id AS id_estado, UCASE(CE.descripcion) as estado_descripcion,


UCASE(CPA.descripcion) as pais_descripcion  from catalogomunicipio AS CM   INNER JOIN catalogoestado AS CE   INNER JOIN catalogopais AS CPA   ON CM.estado=CE.id && CE.pais=CPA.defaul limit '.$st.','.$pp,$link);	


	while($row=mysql_fetch_array($get)){


?>


            <tr >


              <td width="11%"><span onclick="window.parent.obtenerMunicipio('<?=$row[0]?>','<?=$row[1]?>','<?=$row[2]?>','<?=$row[3]?>','<?=$row[4]?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">


                <?=$row[0];?>


              </span></td>


              <td width="89%"><?=$row[1]?></td>


            </tr>


            <? }?>


          </table>


      </div></td>


    </tr>


    <tr>


      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'catalogomunicipio_Buscar.php?tipo='.$tipo.'&st='); ?></font></td>


    </tr>


  </table>


  <? } ?>





</form>


</body>


</html>


<script language="javascript"> document.getElementById('buscar').focus();</script>