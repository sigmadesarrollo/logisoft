<?
$registros=$_POST['registros'];

if ($registros>0){
		for($i=0;$i<$registros;$i++){
			/*echo $_POST["tablaArribaDer_No_GUIA"][$i];
			echo $_POST["tablaArribaDer_ORIGEN"][$i];
			echo $_POST["tablaArribaDer_FECHA"][$i];
			echo $_POST["tablaArribaDer_CODIGOBARRAS"][$i];*/
		$sqlins=mysql_query("INSERT INTO repartomercanciadetalle
		(reparto,guia,origen,fecha, codigobarras)
		VALUES
		('".$_POST["tablaArribaDer_No_GUIA"][$i]."','".$_POST["tablaArribaDer_ORIGEN"][$i]."',
	'".$_POST["tablaArribaDer_FECHA"][$i]."','".$_POST["tablaArribaDer_CODIGOBARRAS"][$i]."',
	'$usuario', current_timestamp()) ;",$link);
		}	
	}
	

?>
<script>
function validar(){
		u.registros.value = tabla3.getRecordCount();
		if (tabla3.getRecordCount()==0){
			alert('no');
		}else{
			document.form1.submit();	
		}
}

</script>