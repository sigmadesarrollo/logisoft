<?
	require_once("conexion2.php");
	$link=conexion();
?>



<?php
include_once("cTarifa.php");


if ($_POST['txtclave']==''){


     $sql="Select * from configuraciondetalles where vazio is null";
     mysql_query($sql,$link) or die ("Error en la linea " .__LINE__.  " Llamar a webmaster <br> $sql");
     $res=mysql_query($sql,$link);
     $row=mysql_fetch_array($res);
     $_POST['txtclave']=$row['id_folio'];
}

//consulta todos los empleados
$objempleado = new ctarifa;
$consulta=$objempleado->consultar2($_POST['txtclave']);

//muestra los datos consultados
//haremos uso de tabla para tabular los resultados


?>


<table width=570 border=0 cellspacing=0 cellpadding=0>
<tr>
     	<td  width="200px"  height="40px"  align="center" class="estilo_relleno">
        
         	<input type=text size=20 value="     Tarifas KG/Zona" class="estilo_relleno2"> 
        </td> 


<?
$vpcol=0;
while($row=mysql_fetch_array($consulta)){?>
            
                
	        <td width="100px" height="40px"  class="estilo_relleno" align=center>
        		zona <?=$vpcol?><br>
	        	<?=$row['zoi']?>-<?=$row['zof']?>
	        </td>        
		<?$vpcol++;?>

<?}?>
<input name="txtcolumnas" type=hidden value="<?=$vpcol?>">


</tr>    	

<?
$objempleado = new ctarifa;
$consulta=$objempleado->consultar($_POST['txtclave']);
$sw=0;
$vpcol=0;
$vpren=0;
while($row=mysql_fetch_array($consulta)){?>
      
     <? if ($vprenglon!=$row['renglon'] && $sw==0){
      $vpcol++; ?>
      <tr>
        <td  height="40px" class="estilo_relleno" align="center" valign="middle">Tarifa <?=$row['renglon']?> &nbsp; <?=$row['kgi']?>-<?=$row['kgf']?> KG  </td>                   
            	<td width="100px" class=estilo_div2 align=center><input name="costo<?=$vpcol?>" type=text class=style2 id="costo<?=$vpcol?>" size=8 maxlength=10 value="<?=$row['costo']?>" onKeyPress="if(event.keyCode==13){document.all.costo<?=$vpcol+1?>.focus();return false;}else{return solonumeros(event);}" onKeyDown="if (window.event.keyCode==121){window.event.keyCode = 505;document.form1.txtstatus.value=1;document.form1.submit();}">					
            	</td>
                <?$vprenglon=$row['renglon'];   
                 if (sw==0){$row=mysql_fetch_array($consulta);}
                  
	}?>




         <?$sw=0;
           while($vprenglon==$row['renglon']){
            $vpcol++; ?>
         
            	<td width="100px" class=estilo_div2 align=center><input name="costo<?=$vpcol?>" type=text class=style2 id="costo<?=$vpcol?>" size=8 maxlength=10 value="<?=$row['costo']?>" onKeyPress="if(event.keyCode==13){document.all.costo<?=$vpcol+1?>.focus();return false;}else{return solonumeros(event);}" onKeyDown="if (window.event.keyCode==121){window.event.keyCode = 505;document.form1.txtstatus.value=1;document.form1.submit();}">					
            </td>
	     <?$row=mysql_fetch_array($consulta);               
                $sw=1; 
           }?> 
         
         
      <?if ($sw==1 && $row['id_folio']!=''){
        $vpcol++; ?>
      </tr>
      <tr>  
               <?if ($vpren==7){?>
 	               <td  height="40px" class="estilo_relleno" align="center" valign="middle">Tarifa <?=$row['renglon']?> &nbsp; Precio X KG   </td>                   
               <?}else{?>
 
 	               <td  height="40px" class="estilo_relleno" align="center" valign="middle">Tarifa <?=$row['renglon']?> &nbsp; <?=$row['kgi']?>-<?=$row['kgf']?> KG   </td>                   
                <?}?>
            	<td width="100px" class=estilo_div2 align=center><input name="costo<?=$vpcol?>" type=text class=style2 id="costo<?=$vpcol?>" size=8 maxlength=10 value="<?=$row['costo']?>" onKeyPress="if(event.keyCode==13){document.all.costo<?=$vpcol+1?>.focus();return false;}else{return solonumeros(event);}" onKeyDown="if (window.event.keyCode==121){window.event.keyCode = 505;document.form1.txtstatus.value=1;document.form1.submit();}">					
            	</td>
                <?$vprenglon=$row['renglon'];                    
                  ?>

       <? $vpren++;
           }?>


           
      


<? } ?>

 
</tr>      
</table>


<input name="txtrenglones" type=hidden value="<?=$vpren?>">
