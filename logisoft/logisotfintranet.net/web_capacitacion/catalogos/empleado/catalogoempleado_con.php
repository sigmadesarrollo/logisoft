<? 	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion] == "0"){
		$row = folio('catalogoempleado','webpmm');
		echo $row[0];
			
	}else if($_GET[accion] == 1){
		$row = split(",",$_GET[arre]);
		$dir = split(",",$_GET['dir']);
		$s = "INSERT INTO catalogoempleado
		(numempleado,sucursal, sexo, estadocivil, nombre, apellidopaterno,
		apellidomaterno, rfc, curp, nimss, celular, email, lugarnacimiento,
		fechanacimiento, tipocontrato, alta, baja, reingreso, bajareingreso,
		departamento, turno, puesto, licenciamanejo, subcuentacontable,
		pagoelectronico,celularemp, licencia, tipolicencia, vigencia, lentes,
		user, password, grupo, calle,numero,cp,colonia,poblacion,municipio,estado,pais,telefono, usuario, fecha,activado)
		VALUES
		(UCASE('$row[0]'), UCASE('$row[1]'), UCASE('$row[2]'), UCASE('$row[3]'),
		 UCASE('$row[4]'), UCASE('$row[5]'), UCASE('$row[6]'), UCASE('$row[7]'),
		 UCASE('$row[8]'), UCASE('$row[9]'), UCASE('$row[10]'), UCASE('$row[11]'),
		 UCASE('".$_GET[lugarn]."'), '".(($row[14]!='')? cambiaf_a_mysql($row[14]):'0000-00-00')."', UCASE('$row[15]'),
		 '".(($row[16]!='')?cambiaf_a_mysql($row[16]):'0000-00-00')."',
		 '".(($row[17]!='')?cambiaf_a_mysql($row[17]):'0000-00-00')."',
		 '".(($row[18]!='')?cambiaf_a_mysql($row[18]):'0000-00-00')."',
		 '".(($row[19]!='')?cambiaf_a_mysql($row[19]):'0000-00-00')."',
		 UCASE('$row[20]'), UCASE('$row[21]'), UCASE('$row[22]'), UCASE('$row[23]'),
		 UCASE('$row[24]'), UCASE('$row[25]'), UCASE('$row[12]'), UCASE('$row[28]'),
		 UCASE('$row[31]'), '".(($row[29]!='')?cambiaf_a_mysql($row[29]):'0000-00-00')."', UCASE('$row[30]'),
		 UCASE('$row[26]'), UCASE('$row[27]'),'$row[33]', UCASE('$dir[0]'), UCASE('$dir[1]'),
		 '$dir[2]', UCASE('$dir[3]'), UCASE('$dir[4]'), UCASE('$dir[5]'), UCASE('$dir[6]'),
		 UCASE('$dir[7]'), UCASE('$dir[8]'), '".$_SESSION[NOMBREUSUARIO]."',
		 current_timestamp(),$row[34])";
		 mysql_query($s,$l) or die($s);			
		 $folio = mysql_insert_id();
		 
		 $s = "DELETE FROM permisos_empleadospermisos WHERE idempleado = '$folio'";
		 mysql_query($s,$l) or die($s);
		 
		 $s = "INSERT INTO permisos_empleadospermisos
		 SELECT idpermiso,$folio FROM permisos_grupospermisos WHERE idgrupo = '$row[33]'";
		 mysql_query($s,$l) or die($s);		
		
		$pest = split(",",$_GET['pest']);
		$s = "INSERT INTO configuradorpestanas SET 
		pestana1 = '".$pest[0]."',
		pestana2 = '".$pest[1]."',
		pestana3 = '".$pest[2]."',
		pestana4 = '".$pest[3]."',
		pestana5 = '".$pest[4]."',
		idusuario = ".$folio."";
		mysql_query($s,$l) or die($s);
		 
		 echo "ok,guardado,".$folio;
		 
	}else if($_GET[accion] == 2){		
		$row = split(",",$_GET[arre]);
		$dir = split(",",$_GET['dir']);
		$s = "UPDATE catalogoempleado SET
		numempleado=UCASE('$row[0]'), sucursal=UCASE('$row[1]'),sexo='$row[2]',estadocivil=UCASE('$row[3]'),
		nombre=UCASE('$row[4]'), apellidopaterno=UCASE('$row[5]'), apellidomaterno=UCASE('$row[6]'),
		rfc=UCASE('$row[7]'),curp=UCASE('$row[8]'), nimss=UCASE('$row[9]'), celular=UCASE('$row[10]'),
		email='$row[11]', lugarnacimiento=UCASE('".$_GET[lugarn]."'),
		fechanacimiento='".(($row[14]!='')? cambiaf_a_mysql($row[14]):'0000-00-00')."',
		tipocontrato=UCASE('$row[15]'), alta='".(($row[16]!='')?cambiaf_a_mysql($row[16]):'0000-00-00')."',
		baja='".(($row[17]!='')? cambiaf_a_mysql($row[17]):'0000-00-00')."',
		reingreso='".(($row[18]!='')? cambiaf_a_mysql($row[18]):'0000-00-00')."',
		bajareingreso='".(($row[19]!='')? cambiaf_a_mysql($row[19]):'0000-00-00')."', 
		departamento=UCASE('$row[20]'), turno=UCASE('$row[21]'), puesto='$row[22]',
		licenciamanejo='$row[23]', subcuentacontable='$row[24]', pagoelectronico='$row[25]',
		celularemp='$row[12]', licencia=UCASE('$row[28]'), tipolicencia=UCASE('$row[31]'),
		vigencia='".(($row[29]!='')? cambiaf_a_mysql($row[29]):'0000-00-00')."', 
		lentes='$row[30]', user=UCASE('$row[26]'), password=UCASE('$row[27]'), grupo='$row[33]',
		calle=UCASE('$dir[0]'), numero=UCASE('$dir[1]'), cp='$dir[2]',
		colonia=UCASE('$dir[3]'), poblacion=UCASE('$dir[4]'), municipio=UCASE('$dir[5]'),
		estado=UCASE('$dir[6]'), pais=UCASE('$dir[7]'),	telefono=UCASE('$dir[8]'),
		usuario='".$_SESSION[NOMBREUSUARIO]."', fecha=current_timestamp() ,activado='$row[34]'
		WHERE id=".$_GET[codigo]."";
		//die($s);
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM permisos_empleadospermisos WHERE idempleado = '$_GET[codigo]'";
		 mysql_query($s,$l) or die($s);
		 
		 $s = "INSERT INTO permisos_empleadospermisos
		SELECT idpermiso,$_GET[codigo] FROM permisos_grupospermisos WHERE idgrupo = '$row[33]'";
		 mysql_query($s,$l) or die($s);		
		
		if($_GET[motivos]!=""){
			$s = "DELETE FROM motivosbaja WHERE empleado='".$_GET[codigo]."'";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO motivosbaja (empleado, motivos, usuario, fecha)
			VALUES ('".$_GET[codigo]."', UCASE('".$_GET[motivos]."'),
			'".$_SESSION[NOMBREUSUARIO]."',current_timestamp())";
			mysql_query($s,$l) or die($s);
		}
		
		$s = "SELECT * FROM configuradorpestanas WHERE idusuario = ".$_GET[codigo]."";
		$r = mysql_query($s,$l) or die($s);
		$pest = split(",",$_GET['pest']);
		if(mysql_num_rows($r)==0){
			$s = "INSERT INTO configuradorpestanas SET 
			pestana1 = '".$pest[0]."',
			pestana2 = '".$pest[1]."',
			pestana3 = '".$pest[2]."',
			pestana4 = '".$pest[3]."',
			pestana5 = '".$pest[4]."',
			idusuario = ".$_GET[codigo]."";
			mysql_query($s,$l) or die($s);
		}else{
			$s = "UPDATE configuradorpestanas SET 
			pestana1 = '".$pest[0]."',
			pestana2 = '".$pest[1]."',
			pestana3 = '".$pest[2]."',
			pestana4 = '".$pest[3]."',
			pestana5 = '".$pest[4]."'
			WHERE idusuario = ".$_GET[codigo]."";
			mysql_query($s,$l) or die($s);
		}
		echo "ok,modificar";
		
	}else if($_GET[accion] == 3){
		$s = "SELECT descripcion FROM catalogopuesto WHERE id=".$_GET[puesto]."";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->descripcion = cambio_texto($f->descripcion);
				$registros[] = $f;
			}
			echo str_replace('null','""', json_encode($registros));
		}else{
			echo "0";		
		}
		
	}else if($_GET[accion] == 4){
		$s="SELECT E.id,E.numempleado, E.sucursal, E.sexo, E.estadocivil, E.nombre, E.apellidopaterno,
			E.apellidomaterno, E.rfc, E.curp, E.nimss, E.celular, E.email, E.lugarnacimiento, E.user, E.password,
			DATE_FORMAT(E.fechanacimiento,'%d/%m/%Y')AS fechanacimiento,
			E.tipocontrato, DATE_FORMAT(E.alta,'%d/%m/%Y')AS alta,
			DATE_FORMAT(E.baja,'%d/%m/%Y')AS baja,
			DATE_FORMAT(E.reingreso,'%d/%m/%Y')AS reingreso,
			DATE_FORMAT(E.bajareingreso,'%d/%m/%Y')AS bajareingreso,
			E.departamento, E.turno, E.licenciamanejo, E.subcuentacontable,
			E.pagoelectronico, E.puesto, cp.descripcion as descripcionpuesto, E.calle,
			E.numero, E.cp, E.colonia, E.poblacion, E.municipio, E.estado, E.pais,
			E.telefono, E.licencia, E.tipolicencia, DATE_FORMAT(E.vigencia,'%d/%m/%Y')AS vigencia,
			E.lentes, E.celularemp, E.grupo,E.activado FROM catalogoempleado E			
			LEFT JOIN catalogopuesto cp ON E.puesto=cp.id WHERE E.id=".$_GET[id];

		$mo=@mysql_query("SELECT motivos FROM motivosbaja WHERE empleado='".$_GET[id]."'",$l);
		$ro=@mysql_fetch_array($mo); $motivos=$ro[0];
		$r = mysql_query($s,$l) or die("error en linea ".__LINE__."<br><br>".$s);
		
		$s = "SELECT pestana1,pestana2,pestana3,pestana4,pestana5 FROM configuradorpestanas WHERE idusuario = ".$_GET[id]."";
		$pe= mysql_query($s,$l) or die($s); $pes = mysql_fetch_object($pe);
		
		$registros = array();
	
			while($f = mysql_fetch_object($r)){			
				$f->numempleado=cambio_texto($f->numempleado);
				$f->sucursal=cambio_texto($f->sucursal);
				$f->sexo=cambio_texto($f->sexo);
				$f->estadocivil=cambio_texto($f->estadocivil);
				$f->nombre=cambio_texto($f->nombre);
				$f->apellidopaterno=cambio_texto($f->apellidopaterno);
				$f->apellidomaterno=cambio_texto($f->apellidomaterno);
				$f->rfc=cambio_texto($f->rfc);
				$f->curp=cambio_texto($f->curp);
				$f->nimss=cambio_texto($f->nimss);
				$f->calle=cambio_texto($f->calle);
				$f->numero=cambio_texto($f->numero);
				$f->colonia=cambio_texto($f->colonia);
				$f->cp=cambio_texto($f->cp);
				$f->poblacion=cambio_texto($f->poblacion);
				$f->municipio=cambio_texto($f->municipio);
				$f->estado=cambio_texto($f->estado);
				$f->pais=cambio_texto($f->pais);
				$f->telefono=cambio_texto($f->telefono);
				$f->celular=cambio_texto($f->celular);
				$f->celularemp=cambio_texto($f->celularemp);
				$f->email=cambio_texto($f->email);
				$f->lugarnacimiento=cambio_texto($f->lugarnacimiento);
				$f->fechanacimiento=cambio_texto($f->fechanacimiento);
				$f->tipocontrato=cambio_texto($f->tipocontrato);
				$f->alta=cambio_texto($f->alta);
				$f->baja=cambio_texto($f->baja);
				$f->reingreso=cambio_texto($f->reingreso);
				$f->bajareingreso=cambio_texto($f->bajareingreso);
				$f->departamento=cambio_texto($f->departamento);
				$f->turno=cambio_texto($f->turno);
				$f->puesto=cambio_texto($f->puesto);
				$f->licenciamanejo=cambio_texto($f->licenciamanejo);
				$f->subcuentacontable=cambio_texto($f->subcuentacontable);
				$f->pagoelectronico=cambio_texto($f->pagoelectronico);
				$f->licencia=cambio_texto($f->licencia);
				$f->tipolicencia=cambio_texto($f->tipolicencia);
				$f->vigencia=cambio_texto($f->vigencia);
				$f->lentes=cambio_texto($f->lentes);
				$f->motivos=cambio_texto($motivos);
				$f->descripcionpuesto=cambio_texto($f->descripcionpuesto);
				$f->user=cambio_texto($f->user);
				$f->password = cambio_texto($f->password);
				$f->grupo = cambio_texto($f->grupo);
				$f->pestana1 = cambio_texto($pes->pestana1);
				$f->pestana2 = cambio_texto($pes->pestana2);
				$f->pestana3 = cambio_texto($pes->pestana3);
				$f->pestana4 = cambio_texto($pes->pestana4);
				$f->pestana5 = cambio_texto($pes->pestana5);
				$registros[] = $f;
			}	
			
			echo str_replace('null','""',json_encode($registros));		
			
	}else if($_GET[accion] == 5){
		$s = "SELECT nombre AS modulo FROM catalogomenu WHERE nombre IS NOT NULL";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		
			while($f = mysql_fetch_object($r)){
				$f->nombre = cambio_texto($f->nombre);
				$registros[] = $f;
			}
			
		echo str_replace('null','""',json_encode($registros));	
			
	}else if($_GET[accion] == 6){
		$s = "SELECT CONCAT_WS(' ', nombre, apellidopaterno, apellidomaterno) AS nombre FROM catalogoempleado WHERE id = '$_GET[idempleado]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->nombre = cambio_texto($f->nombre);
		echo '({"nombre":"'.$f->nombre.'"})';
	}
?>