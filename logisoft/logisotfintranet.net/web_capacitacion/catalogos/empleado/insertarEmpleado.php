<?	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion] == 1){
		$s = "SELECT count(*) AS total FROM empleados WHERE sucursal=".$_GET[sucursal];
		$suc = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($suc);
		
		$contador = 1;
		
		for($i=0;$i<$f->total;$i++){
			$s = "INSERT INTO catalogoempleado
			SELECT 0 AS id, numempleado, sucursal, sexo, UCASE(estadocivil), UCASE(nombre),
			UCASE(apellidopaterno), UCASE(apellidomaterno),
			UCASE(rfc), UCASE(curp), nimss, '0' AS celular, '0' AS celularemp, '' AS email, UCASE(lugarnacimiento),
			IF(fechanacimiento='','0000-00-00',CONCAT_WS('-',SUBSTRING(fechanacimiento,7,4),
			SUBSTRING(fechanacimiento,4,2),SUBSTRING(fechanacimiento,1,2))) AS fechanacimiento, tipocontrato,
			CONCAT_WS('-',SUBSTRING(alta,7,4),SUBSTRING(alta,4,2),SUBSTRING(alta,1,2)) AS alta,	'0000-00-00' AS baja,
			IF(reingreso='','0000-00-00',CONCAT_WS('-',SUBSTRING(reingreso,7,4),SUBSTRING(reingreso,4,2),SUBSTRING(reingreso,1,2)))
			AS reingreso,
			'0000-00-00' AS bajareingreso, departamento, '' AS turno, puesto, 0 AS licenciamanejo,
			0 AS subcuentacontable, pagoelectronico, '' AS licencia, '' AS tipolicencia, '0000-00-00' AS vigencia, 0 AS lentes,
			'' AS USER, '1234' AS PASSWORD, 'ADMIN' AS usuario, CURRENT_TIMESTAMP AS fecha
			FROM empleados WHERE idempleado=".$contador;
			$r = mysql_query($s,$l) or die($s);
			$codigo = mysql_insert_id();
			
			$s = "INSERT INTO direccion
			SELECT 0 AS id, 'emp' AS origen, ".$codigo." AS codigo,calle,numero,'' AS crucecalles,cp,
			'' AS colonia,'' AS poblacion,'' AS municipio, '' AS estado, '' AS pais, telefono,'' AS fax,
			'' AS facturacion,'ADMIN' AS usuario, CURRENT_TIMESTAMP AS fecha FROM empleados
			WHERE idempleado=".$contador;
			mysql_query($s,$l) or die($s);
			
			$contador++; 
		}
			if($contador!=1){
				$s = "DELETE FROM empleados WHERE sucursal=".$_GET[sucursal];
				mysql_query($s,$l) or die($s);
				echo "SE GENERARON ".$contador;				
			}else{
				echo "NO SE GENERO";
			}
	}
?>