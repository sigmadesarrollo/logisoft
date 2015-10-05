<?
		include_once("../clases/ValidaConvenio.php");

		if($_GET[idsucursalorigen]==""){
			$_GET[idsucursalorigen] = $_SESSION[IDSUCURSAL];
		}
		
		if($_GET[idpagina]==""){
			$_GET[idpagina]=$_SESSION[IDUSUARIO];
		}

		if($_GET[accion]==1){
			$vc = new ValidaConvenio($_GET[idremitente],$_GET[iddestinatario],$_GET[iddestino],$_GET[idsucdestino],$_GET[idsucursalorigen]);
			echo $vc->getJsonDataVentanilla();
		}

		if($_GET[accion]==2){
			//todas las descripciones distintas
			$s = "SELECT DISTINCT descripcion FROM cconvenio_configurador_caja";

			//consulta para as evaluaciones
			$s = "SELECT cantidad, descripcion, contenido, peso, volumen FROM evaluacionmercanciadetalle 
			WHERE evaluacion = 2";

			//por cada una hay que cargar el flete
			$s = "SELECT em.cantidad, em.descripcion, em.contenido, em.peso, em.volumen, IF(ISNULL(t1.descripcion),1,'') AS modificable
			FROM evaluacionmercanciadetalle AS em
			LEFT JOIN (
				SELECT DISTINCT descripcion 
				FROM cconvenio_configurador_caja 
				WHERE idconvenio = 10
			) AS t1 ON em.descripcion = t1.descripcion
			WHERE em.evaluacion = 2";
		}

?>