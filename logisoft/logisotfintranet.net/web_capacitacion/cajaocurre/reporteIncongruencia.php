<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
$fecha = $_GET['fecha']; $usuario=$_GET['usuario']; $arreglo=$_GET['miArray'];
	$coma=",";	
	$lista=split($coma,$arreglo);		
			for ($i=0;$i<count($lista);$i++){	
				$var = trim($lista[$i]);
				if ($var!=""){
					$arre[$i]=$var;
				}
			}

$gcredito = $arre[10]; $tgcredito = $arre[11]; $gcancelada = $arre[12]; $tgcancelada = $arre[13]; $faturas = $arre[14]; $tfaturas = $arre[15];

$dgcredito = $tgcredito - $gcredito;
$dgcancelada = $tgcancelada - $gcancelada;
$dfaturas = $tfaturas - $faturas;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<object id=factory viewastext style="display:none"
classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"
codebase="smsx.cab#Version=6,4,438,06">
</object>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <table width="500" border="1" align="center" bordercolor="#016193" cellpadding="0" cellspacing="0">
    <tr>
      <td class="FondoTabla">Reporte de Incongruencias - Caja</td>
    </tr>
    <tr>
      <td><table width="489" border="1" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="143">Concepto</td>
          <td width="110">Total Cajero</td>
          <td width="108">Total</td>
          <td width="108">Diferencia</td>
        </tr>
        <tr>
          <td># Guias Credito</td>
          <td><input name="gcredito" type="text" class="Tablas" id="gcredito" value="<?=$gcredito ?>" size="5" readonly="readonly" style="border:none; text-align:right"></td>
          <td><input name="tgcredito" type="text" class="Tablas" id="tgcredito" value="<?=$tgcredito ?>" size="5" readonly="readonly" style="border:none; text-align:right"></td>
          <td><input name="dgcredito" type="text" class="Tablas" id="dgcredito" value="<?=$dgcredito ?>" size="5" readonly="readonly" style="border:none; text-align:right"></td>
        </tr>
        <tr>
          <td># Guias Canceladas</td>
          <td><input name="gcancelada" type="text" class="Tablas" id="gcancelada" value="<?=$gcancelada ?>" size="5" readonly="readonly" style="border:none; text-align:right"></td>
          <td><input name="tgcancelada" type="text" class="Tablas" id="tgcancelada" value="<?=$tgcancelada ?>" size="5" readonly="readonly" style="border:none; text-align:right"></td>
          <td><input name="dgcancelada" type="text" class="Tablas" id="dgcancelada" value="<?=$dgcancelada ?>" size="5" readonly="readonly" style="border:none; text-align:right"></td>
        </tr>
        <tr>
          <td>Facturas Canceladas</td>
          <td><input name="factura" type="text" class="Tablas" id="factura" value="<?=$factura ?>" size="5" readonly="readonly" style="border:none; text-align:right"></td>
          <td><input name="tfactura" type="text" class="Tablas" id="tfactura" value="<?=$tfactura ?>" size="5" readonly="readonly" style="border:none; text-align:right"></td>
          <td><input name="dfactura" type="text" class="Tablas" id="dfactura" value="<?=$dfactura ?>" size="5" readonly="readonly" style="border:none; text-align:right"></td>
        </tr>
      </table></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
</html>
<script>
function printpr()
{
var OLECMDID = 7;
var PROMPT = 1; // 2 DONTPROMPTUSER 
var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 PAGEFOOTER=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
document.body.insertAdjacentHTML('beforeEnd', WebBrowser); 
WebBrowser1.ExecWB(OLECMDID, PROMPT);WebBrowser1.outerHTML = "";
}
printpr();
//window.close();
</script>