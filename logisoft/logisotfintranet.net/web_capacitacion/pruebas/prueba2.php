<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Prueba de Firma</title>
</head>

<script>

  	function OnPageLoad ()
  	{   
	  try
	  {
	    Tablet.Clear();
	  }
	  catch(e)
	  {     
	  }
 	}
	
	
    function OnSubmit(){
			if(Tablet.Firmo()!=0){		
				if(Tablet.SaveImage("pmm_dbpruebas")!=0){
					alert("La firma se obtuvo satisfactoriamente");
				}else{
					alert("La firma no se pudo obtener");
				}
		}else{
			alert("No Firmo");
		}
    }	
	
	function limpiarFirma(){
		Tablet.Clear();
	}
	
</script>

<body >
	<OBJECT ID="Tablet" name="Tablet" width="400" height="200"
		CLASSID="CLSID:1D44B406-4BFA-470C-BDB1-769C89210A41"
		CODEBASE="../activexs/FirmaDigital.CAB#version=1,0,0,8">
    </OBJECT>
	<P>
	<INPUT type="button" 
           Width = "400"
           onclick="limpiarFirma()" 
           value="Borrar">
 
     <INPUT type="button" 
           onclick="OnSubmit()" 
           value="Guardar"> 
</body>
</html>
