

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script>

	var pesar = new clsBalanza

	pesar.LeerConfig("balanza.cfg");

	

function ObtenerPeso(){

		if (pesar.Pesar == 0){           

            alert(pesar.Peso.Valor);

            alert(pesar.Peso.Unidad);

        }else{           

            alert("Ocurrió un error al pesar");        

		}

}

</script>

<OBJECT ID="clsBalanza"

CLASSID="CLSID:9FFC4EAB-D1CB-4E7B-8582-A8DD90AD57E5"

CODEBASE="DpsDrvBal.CAB#version=1,0,0,0">

</OBJECT>



<OBJECT ID="cPeso"

CLASSID="CLSID:195ED6FA-B663-49A4-AA32-69398F264F2B"

CODEBASE="DpsDrvBal.CAB#version=1,0,0,0">

</OBJECT>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

</head>



<body>

<label>

<input type="button" name="Submit" value="Bot&oacute;n" onClick="ObtenerPeso">

</label>

</body>

</html>

