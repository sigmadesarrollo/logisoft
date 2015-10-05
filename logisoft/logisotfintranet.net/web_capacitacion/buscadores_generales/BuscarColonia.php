<?


session_start();


	if(!$_SESSION[IDUSUARIO]!=""){


		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");


	}


?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html xmlns="http://www.w3.org/1999/xhtml">


<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<title>Untitled Document</title>








<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />


<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />





<script src="select.js"></script>


<script language="javascript">


function ObtenerColonia(e,obj){


	tecla=(document.all) ? e.keyCode : e.which;		


        if(tecla==13){


			if(document.getElementById('buscar').value!=""){


				ConsultaColoniaProspecto(document.getElementById('buscar').value,'1');


			}


	    }


}


</script>


</head>





<body>


<table width="480"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


  <tr>


    <td width="40%" class="FondoTabla"><label>Colonia</label></td>


    <td width="7%" class="FondoTabla">CP</td>


    <td width="17%" class="FondoTabla">Poblaci&oacute;n</td>


    <td width="16%" class="FondoTabla">Municipio</td>


    <td width="20%" class="FondoTabla">Estado</td>


  </tr>


  <tr>


    <td colspan="5" class="FondoTabla"><input name="buscar" type="text" id="buscar"  style="text-transform:uppercase; font-size:9px; font-family:Tahoma, Geneva, sans-serif" onkeypress="ObtenerColonia(event,this)" size="50" border="0"/></td>


  </tr>


  <tr>


    <td colspan="5" class="Tablas"><div id="txtDir" style="width:100%; height:150px; overflow: scroll;">


      <table width="95%" border="0" align="left" cellpadding="0" cellspacing="0" class="Tablas" id="tab">


        <tr>


          <td width="191" class="Tablas"><input name="text" type="text" style="border:none; visibility:hidden" value="<?= $row['d_asenta'];?>" size="10" readonly=""></td>


          <td width="20" class="Tablas"><input name="text" type="text" style="cursor:pointer; border:none; font-size:9px;visibility:hidden" value="<?= $row['d_codigo'];?>" size="3" readonly="" /></td>


          <td width="72" class="Tablas">&nbsp;


              <input name="text" type="text" style="cursor:pointer; border:none; font-size:9px;visibility:hidden" value="<?= $row['d_ciudad'];?>" size="9" readonly=""></td>


          <td width="72" class="Tablas">&nbsp;


              <input name="text" type="text" style="cursor:pointer; border:none; font-size:9px;visibility:hidden" value="<?= $row['d_mnpio'];?>" size="9" readonly=""></td>


          <td width="90" class="Tablas">&nbsp;


              <input name="text" type="text" style="cursor:pointer; border:none; font-size:9px;visibility:hidden" value="<?= $row['d_estado'];?>" size="9" readonly=""></td>


          <td width="7"></td>


        </tr>


        <? //} ?>


      </table>


    </div></td>


  </tr>


  <tr>


    <td colspan="5" align="center">&nbsp;</td>


  </tr>


</table>


</body>


</html>


<?


//}


?>