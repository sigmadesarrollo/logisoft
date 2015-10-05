<?
	require_once("conexion2.php");
	$link=conexion();
?>



<?php
include_once("ctarifa.php");



//consulta todos los empleados
$objempleado = new ctarifa;
$consulta=$objempleado->consultar2();

//muestra los datos consultados
//haremos uso de tabla para tabular los resultados


?>


<table  border=0 cellspacing=0 cellpadding=0>
<tr>
     	<td align="center" class="estilo_relleno">
        
         	<input type=text size=20 value="     Tarifas KG/Zona" class="estilo_relleno2"> 
        </td> 


<?
$vpcol=0;
while($row=mysql_fetch_array($consulta)){?>
            
                
	        <td height="30px" width="100"  class="estilo_relleno" align=center>
        		Zona <?=$vpcol?><br>
	        	<?=$row['zoi']?>-<?=$row['zof']?>
                        
	        </td>        
		<?$vpcol++;?>

<?}?>
<input name="txtcolumnas" type=hidden value="<?=$vpcol?>">


</tr>    	

<?
$objempleado = new ctarifa;
$consulta=$objempleado->consultar();
$sw=0;
$vpcol=0;
$vpren=0;
while($row=mysql_fetch_array($consulta)){?>
      
     <?if ($vprenglon!=$row['renglon'] && $sw==0){
      $vpcol++; ?>
      <tr>
        <?if ($vpren==0){ ?>
        <td id="costo<?=$vprenglon?>_<?=$vpcol?>"  class="estilo_relleno" align="center" valign="middle">Tarifa Mensajeria</td>                   
        <?}else{?>     
          <td id="costo<?=$vprenglon?>_<?=$vpcol?>"  class="estilo_relleno" align="center" valign="middle">Tarifa <?=$row['renglon']-1?> &nbsp; <?=$row['kgi']?>-<?=$row['kgf']?> KG  </td>                   
        <?}?>     

            	<td id="celda<?=$row['renglon']?>_<?=$row['columna']?>" class=estilo_celda align=center onDblClick="deseleccionar();escoger(<?=$row['renglon']?>,<?=$row['columna']?>,celda<?=$row['renglon']?>_<?=$row['columna']?>,document.all.costo<?=$row['renglon']?>_<?=$row['columna']?>.value)"><input name="costo<?=$row['renglon']?>_<?=$row['columna']?>" type=text class=style2 id="costo<?=$vpcol?>" size=8 maxlength=10 value="<?=$row['costo']?>"  readonly="true">					
            	</td>
                <?$vprenglon=$row['renglon'];   
                 if (sw==0){$row=mysql_fetch_array($consulta);}
                  
	}?>




         <?$sw=0;
           while($vprenglon==$row['renglon']){
            $vpcol++; ?>
         
            	<td id="celda<?=$row['renglon']?>_<?=$row['columna']?>" class=estilo_celda align=center onDblClick="deseleccionar();escoger(<?=$row['renglon']?>,<?=$row['columna']?>,celda<?=$row['renglon']?>_<?=$row['columna']?>,document.all.costo<?=$row['renglon']?>_<?=$row['columna']?>.value)"><input name="costo<?=$row['renglon']?>_<?=$row['columna']?>" type=text class=style2 id="costo<?=$vpcol?>" size=8 maxlength=10 value="<?=$row['costo']?>"  readonly="true">					
            </td>
	     <?$row=mysql_fetch_array($consulta);               
                $sw=1; 
           }?> 
         
         
      <?if ($sw==1 && $row['renglon']!=''){
        $vpcol++; ?>
      </tr>
      <tr>  
               <?if ($vpren==8){?>
 	               <td   class="estilo_relleno" align="center" valign="middle">Tarifa <?=$row['renglon']-1?> &nbsp; Precio X KG   </td>                   
               <?}else{?>
 
 	               <td   class="estilo_relleno" align="center" valign="middle">Tarifa <?=$row['renglon']-1?> &nbsp; <?=$row['kgi']?>-<?=$row['kgf']?> KG   </td>                   
                <?}?>
            	<td id="celda<?=$row['renglon']?>_<?=$row['columna']?>" class=estilo_celda align=center onDblClick="deseleccionar();escoger(<?=$row['renglon']?>,<?=$row['columna']?>,celda<?=$row['renglon']?>_<?=$row['columna']?>,document.all.costo<?=$row['renglon']?>_<?=$row['columna']?>.value)"><input name="costo<?=$row['renglon']?>_<?=$row['columna']?>" type=text class=style2 id="costo<?=$vpcol?>" size=8 maxlength=10 value="<?=$row['costo']?>"  readonly="true">					
            	</td>
                <?$vprenglon=$row['renglon'];                    
                  ?>

       <?$vpren++;
           }?>


           
      


<?} ?>

 
</tr>      
</table>


<input name="txtrenglones" type=hidden value="<?=$vpren?>">
