<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=acuserecibo.xls");
	//header("Pragma: no-cache");
	//header("Expires: 0"); 
	
	require_once("../../Conectar.php");
	$l = Conectarse('webpmm');
	
	$s = "SELECT * FROM catalogosucursal where id = '$_GET[sucursal]'";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$nsucursal = $f->descripcion;
	
	function num2letras($num, $fem = false, $dec = true) { 
	   $matuni[2]  = "dos"; 
	   $matuni[3]  = "tres"; 
	   $matuni[4]  = "cuatro"; 
	   $matuni[5]  = "cinco"; 
	   $matuni[6]  = "seis"; 
	   $matuni[7]  = "siete"; 
	   $matuni[8]  = "ocho"; 
	   $matuni[9]  = "nueve"; 
	   $matuni[10] = "diez"; 
	   $matuni[11] = "once"; 
	   $matuni[12] = "doce"; 
	   $matuni[13] = "trece"; 
	   $matuni[14] = "catorce"; 
	   $matuni[15] = "quince"; 
	   $matuni[16] = "dieciseis"; 
	   $matuni[17] = "diecisiete"; 
	   $matuni[18] = "dieciocho"; 
	   $matuni[19] = "diecinueve"; 
	   $matuni[20] = "veinte"; 
	   $matunisub[2] = "dos"; 
	   $matunisub[3] = "tres"; 
	   $matunisub[4] = "cuatro"; 
	   $matunisub[5] = "quin"; 
	   $matunisub[6] = "seis"; 
	   $matunisub[7] = "sete"; 
	   $matunisub[8] = "ocho"; 
	   $matunisub[9] = "nove"; 

	   $matdec[2] = "veint"; 
	   $matdec[3] = "treinta"; 
	   $matdec[4] = "cuarenta"; 
	   $matdec[5] = "cincuenta"; 
	   $matdec[6] = "sesenta"; 
	   $matdec[7] = "setenta"; 
	   $matdec[8] = "ochenta"; 
	   $matdec[9] = "noventa"; 
	   $matsub[3]  = 'mill'; 
	   $matsub[5]  = 'bill'; 
	   $matsub[7]  = 'mill'; 
	   $matsub[9]  = 'trill'; 
	   $matsub[11] = 'mill'; 
	   $matsub[13] = 'bill'; 
	   $matsub[15] = 'mill'; 
	   $matmil[4]  = 'millones'; 
	   $matmil[6]  = 'billones'; 
	   $matmil[7]  = 'de billones'; 
	   $matmil[8]  = 'millones de billones'; 
	   $matmil[10] = 'trillones'; 
	   $matmil[11] = 'de trillones'; 
	   $matmil[12] = 'millones de trillones'; 
	   $matmil[13] = 'de trillones'; 
	   $matmil[14] = 'billones de trillones'; 
	   $matmil[15] = 'de billones de trillones'; 
	   $matmil[16] = 'millones de billones de trillones'; 
   
	   //Zi hack
	   $float=explode('.',$num);
	   $num=$float[0];

	   $num = trim((string)@$num); 
	   if ($num[0] == '-') { 
		  $neg = 'menos '; 
		  $num = substr($num, 1); 
	   }else 
		  $neg = ''; 
	   while ($num[0] == '0') $num = substr($num, 1); 
	   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
	   $zeros = true; 
	   $punt = false; 
	   $ent = ''; 
	   $fra = ''; 
	   for ($c = 0; $c < strlen($num); $c++) { 
		  $n = $num[$c]; 
		  if (! (strpos(".,'''", $n) === false)) { 
			 if ($punt) break; 
			 else{ 
				$punt = true; 
				continue; 
			 } 
		  }elseif (! (strpos('0123456789', $n) === false)) { 
			 if ($punt) { 
				if ($n != '0') $zeros = false; 
				$fra .= $n; 
			 }else 
				$ent .= $n; 
		  }else 
			 break; 
	   } 
	   $ent = '     ' . $ent; 
	   if ($dec and $fra and ! $zeros) { 
		  $fin = ' coma'; 
		  for ($n = 0; $n < strlen($fra); $n++) { 
			 if (($s = $fra[$n]) == '0') 
				$fin .= ' cero'; 
			 elseif ($s == '1') 
				$fin .= $fem ? ' una' : ' un'; 
			 else 
				$fin .= ' ' . $matuni[$s]; 
		  } 
	   }else 
		  $fin = ''; 
	   if ((int)$ent === 0) return 'Cero ' . $fin; 
	   $tex = ''; 
	   $sub = 0; 
	   $mils = 0; 
	   $neutro = false; 
	   while ( ($num = substr($ent, -3)) != '   ') { 
		  $ent = substr($ent, 0, -3); 
		  if (++$sub < 3 and $fem) { 
			 $matuni[1] = 'una'; 
			 $subcent = 'as'; 
		  }else{ 
			 $matuni[1] = $neutro ? 'un' : 'uno'; 
			 $subcent = 'os'; 
		  } 
		  $t = ''; 
		  $n2 = substr($num, 1); 
		  if ($n2 == '00') { 
		  }elseif ($n2 < 21) 
			 $t = ' ' . $matuni[(int)$n2]; 
		  elseif ($n2 < 30) { 
			 $n3 = $num[2]; 
			 if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
			 $n2 = $num[1]; 
			 $t = ' ' . $matdec[$n2] . $t; 
		  }else{ 
			 $n3 = $num[2]; 
			 if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
			 $n2 = $num[1]; 
			 $t = ' ' . $matdec[$n2] . $t; 
		  } 
		  $n = $num[0]; 
		  if ($n == 1) { 
			 $t = ' ciento' . $t; 
		  }elseif ($n == 5){ 
			 $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
		  }elseif ($n != 0){ 
			 $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
		  } 
		  if ($sub == 1) { 
		  }elseif (! isset($matsub[$sub])) { 
			 if ($num == 1) { 
				$t = ' mil'; 
			 }elseif ($num > 1){ 
				$t .= ' mil'; 
			 } 
		  }elseif ($num == 1) { 
			 $t .= ' ' . $matsub[$sub] . '?n'; 
		  }elseif ($num > 1){ 
			 $t .= ' ' . $matsub[$sub] . 'ones'; 
		  }   
		  if ($num == '000') $mils ++; 
		  elseif ($mils != 0) { 
			 if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
			 $mils = 0; 
		  } 
		  $neutro = true; 
		  $tex = $t . $tex; 
	   } 
	   $tex = $neg . substr($tex, 1) . $fin; 
	   //Zi hack --> return ucfirst($tex);
	   $end_num=' '.ucfirst($tex).' pesos '.$float[1].'/100 M.N.';
	   return $end_num; 
	} 
	
	$mes = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	
	$s="SELECT mc.folio,cs.nconcesionario,cs.descripcion AS oficina,
	CONCAT(IF(DAY(mc.fechainicio)=0,1,DAY(mc.fechainicio)),' AL ',DAY(mc.fechafin),' DE ',
	CASE WHEN MONTH(mc.fechafin) = 1 THEN 'ENERO'
	WHEN MONTH(mc.fechafin) = 2 THEN 'FEBRERO'
	WHEN MONTH(mc.fechafin) = 3 THEN 'MARZO'
	WHEN MONTH(mc.fechafin) = 4 THEN 'ABRIL'
	WHEN MONTH(mc.fechafin) = 5 THEN 'MAYO'
	WHEN MONTH(mc.fechafin) = 6 THEN 'JUNIO'
	WHEN MONTH(mc.fechafin) = 7 THEN 'JULIO'
	WHEN MONTH(mc.fechafin) = 8 THEN 'AGOSTO'
	WHEN MONTH(mc.fechafin) = 9 THEN 'SEPTIEMBRE'
	WHEN MONTH(mc.fechafin) = 10 THEN 'OCTUBRE'
	WHEN MONTH(mc.fechafin) = 11 THEN 'NOVIEMBRE'
	WHEN MONTH(mc.fechafin) = 12 THEN 'DICICIEMBRE'
	END ,' DEL ',YEAR(mc.fechafin))fecha2,
	SUM(IF(rcd.tipo='V' AND rcd.condicion='PAGADA-CONTADO',rcd.totalgral,0))pagcont,
	SUM(IF(rcd.tipo='V' AND rcd.condicion='PAGADA-CREDITO',rcd.totalgral,0))pagcred,
	SUM(IF(rcd.tipo='R' AND rcd.condicion='POR COBRAR-CONTADO',rcd.totalgral,0))cobcont,
	SUM(IF(rcd.tipo='R' AND rcd.condicion='POR COBRAR-CREDITO',rcd.totalgral,0))cobcred,
	ROUND(SUM(rcd.totalgral),2)total,ROUND(SUM(rcd.totalcom),2)importe,
	ROUND(SUM(rcd.totalgral)-SUM(rcd.totalcom),2)liquidar
	FROM moduloconcesiones mc
	INNER JOIN reporte_concesiondetalle rcd ON mc.folio=rcd.folio AND mc.sucursal=rcd.sucursal
	INNER JOIN catalogosucursal cs ON mc.sucursal=cs.id
	WHERE mc.folio='$_GET[folio]' AND mc.sucursal=$_GET[sucursal] ";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
?>
<style>
	table{
		font:Verdana, Geneva, sans-serif;
		font-size:12px;
	}.titulo{
		font-size:14px;
		font-weight:bold;
	}.cabecera{
		font-weight:bold;
		border:1px solid #5FADDC;
	}
</style>
	<table width="588" border="0" cellpadding="1" cellspacing="0">
    	<tr><td colspan="2" align="center" class="titulo">
		<img type="image" name="imageField" src="http://files.pmmintranet.net/files/logopmm.gif" />
		ENTREGAS PUNTUALES S DE RL DE CV</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
    	<tr><td colspan="2" class="titulo" align="right">FOLIO <?=$f->folio; ?></td></tr>
		<tr><td colspan="2" class="titulo">RESUMEN DE INGRESOS</td></tr>
		<tr><td colspan="2" align="right"><b>MAZATLAN, SIN., A: <? echo date(d).' de '.$mes[date(n)].' de '.date(Y); ?></b></td></tr>
    	<tr><td colspan="2"><b>CONCESIONARIO:</b> <?=$f->nconcesionario; ?></td></tr>
    	<tr><td colspan="2"><b>OFICINA:</b> <?=$f->oficina; ?></td></tr>
    	<tr><td colspan="2">ADJUNTO A LA PRESENTE SIRVASE ENCONTRAR LIQUIDACION CORRESPONDIENTE DE LA FECHA <?=$f->fecha2?>. </td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">CON LOS SIGUIENTES IMPORTES A LIQUIDAR</td></tr>
		<tr>
			<td width="372">VENTA DE FLETES ENVIADOS PAGADOS CONTADO</td>
			<td width="212" align="right"><?='$ '.number_format($f->pagcont,2)?></td>
		</tr>
		<tr>
			<td>VENTA DE FLETES ENVIADOS PAGADOS CREDITO</td>
			<td align="right"><?='$ '.number_format($f->pagcred,2)?></td>
		</tr>
		<tr>
			<td>RECIBIDO DE FLETES RECIBIDOS POR COBRAR CONTADO</td>
			<td align="right"><?='$ '.number_format($f->cobcont,2)?></td>
		</tr>
		<tr>
			<td>RECIBIDO DE FLETES RECIBIDOS POR COBRAR CREDITO</td>
			<td align="right"><?='$ '.number_format($f->cobcred,2)?></td>
		</tr>
		<tr>
			<td class="titulo">TOTAL A PAGAR</td>
			<td align="right" class="titulo"><?='$ '.number_format($f->total,2)?></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">EL IMPORTE DE SUS COMISIONES ES DE: <?='$ '.number_format($f->importe,2); echo num2letras($f->importe);?></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2" align="justify">EL CUAL APLICARA DE LOS IMPORTES A LIQUIDAR Y ANEXARA FACTURA CORRESPONDIENTE A ESTE DOCUMENTO.</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">SALDO A LIQUIDAR <?='$ '.number_format($f->liquidar,2);?></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2" align="justify">DICHO IMPORTE SERA CUBIERTO POR DEPOSITO EN CADA PLAZA A LA CUENTA ASIGNADA A NOMBRE DE ENTREGAS 
		PUNTUALES S DE RL DE CV., CONVENIO CIE 941492 DE BANCOMER REFERENCIA 3608</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2" align="justify">DEBO Y PAGARE INCONDICIONALMENTE EN ESTA PLAZA A LA ORDEN DE ENTREGAS PUNTUALES S DE RL DE CV, LA CANTIDAD DE 
		<?='$ '.number_format($f->liquidar,2);?> A MAS TARDAR 15 DIAS DESPUES DE EMITIDO EL PRESENTE DOCUMENTO. ESTE PAGARE ES MERCANTIL Y ESTA REGIDO POR 
		LA LEY GENERAL DE TITULOS Y OPERACIONES DE CREDITO EN SU ARTICULO 173 EN SU PARTE FINAL Y ARTICULOS CORRELATIVOS POR NO SER PAGARE DOMICILIADO.
		DE NO HACER EL PAGO QUE ESTE PAGARE EXPRESA A SU VENCIMIENTO, CAUSARA INTERES MORATORIOS DEL C.P.P. MAS 50% DEL MISMO.,
		MAS LOS GASTOS QUE POR ELLO SE ORIGINEN.</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2" align="center">____________________________________________</td></tr>
		<tr><td colspan="2" align="center" class="titulo">ACEPTO DE CONFORMIDAD</td></tr>
    </table>