<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<style type="text/css">

<!--

.style2 {	color: #464442;

	font-size:9px;

	border: 0px none;

	background:none

}

.style5 {	color: #FFFFFF;

	font-size:8px;

	font-weight: bold;

}

-->

</style>

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />

<style type="text/css">

<!--

.Estilo4 {font-size: 12px}

.Balance {background-color: #FFFFFF; border: 0px none}

.Balance2 {background-color: #DEECFA; border: 0px none;}

-->

</style>

</head>

<body>

<form id="form1" name="form1" method="post" action="">

  <br>

  <table width="495" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td width="491" class="FondoTabla Estilo4">REGISTRO DE COMPROMISO</td>

    </tr>

    <tr>

      <td><table width="491" border="0" cellspacing="0" cellpadding="0">

        <tr>

          <td><table width="491" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="491"><table width="486" border="0" cellspacing="0" cellpadding="0">

                <tr>

                  <td width="41">Cliente:<br /></td>

                  <td width="82"><span class="Tablas">

                    <input name="cliente" type="text" class="Tablas" id="cliente" style="width:80px;background:#FFFF99" value="<?=$cliente ?>" readonly=""/>

                  </span></td>

                  <td width="361"><span class="Tablas">

                    <input name="cliente2" type="text" class="Tablas" id="cliente2" style="width:360px;background:#FFFF99" value="<?=$cliente2 ?>" readonly=""/>

                  </span></td>

                </tr>

              </table></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td><table width="490" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="42">Fecha:<br /></td>

              <td width="105"><select name="select5" style="width:100px;">

              </select></td>

              <td width="22">D&iacute;a:<br /></td>

              <td width="115"><span class="Tablas">

                <input name="dia" type="text" class="Tablas" id="dia" style="width:100px;background:#FFFF99" value="<?=$dia ?>" readonly=""/>

              </span></td>

              <td width="29">Hora:<br /></td>

              <td width="177"><span class="Tablas">

                <select name="h1" size="1" onkeypress="if(event.keyCode==13){document.all.h2.focus();}" class="Tablas" id="h1">

                  <? for($h=0;$h<24;$h++){ ?>

                  <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>" <? if($h==$_POST['h1']){echo "selected";}else{echo "00";} ?>>

                  <?=str_pad($h,2,"0",STR_PAD_LEFT);?>

                  </option>

                  <? }?>

                </select>

                :

  <select name="h2" size="1" onkeypress="if(event.keyCode==13){document.all.h3.focus();}" class="Tablas" id="h2">

    <? for($m=0;$m<60;$m++){ ?>

    <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>"

				   <? if($m==$_POST['h2']){echo "selected";}else{echo "00";} ?>>

      <?=str_pad($m,2,"0",STR_PAD_LEFT);?>

      </option>

    <? }?>

  </select>

                a

  <select name="h3" size="1" onkeypress="if(event.keyCode==13){document.all.h4.focus();}" class="Tablas" id="select4">

    <? for($h=0;$h<24;$h++){ ?>

    <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>" <? if($h==$_POST['h3']){echo "selected";}else{echo "00";} ?>>

      <?=str_pad($h,2,"0",STR_PAD_LEFT);?>

      </option>

    <? }?>

  </select>

                :

  <select name="h4" size="1" onkeypress="if(event.keyCode==13){document.all.c1.focus();}" class="Tablas" id="select5">

    <? for($m=0;$m<60;$m++){ ?>

    <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>"

				   <? if($m==$_POST['h4']){echo "selected";}else{echo "00";} ?>>

      <?=str_pad($m,2,"0",STR_PAD_LEFT);?>

      </option>

    <? }?>

  </select>

              </span></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td class="FondoTabla">Observaciones</td>

        </tr>

        <tr>

          <td><label>

            <textarea name="textarea" cols="78"></textarea>

          </label></td>

        </tr>

        <tr>

          <td>&nbsp;</td>

        </tr>

        <tr>

          <td align="right"><div class="ebtn_guardar"></div></td>

        </tr>

        <tr>

          <td width="491">&nbsp;</td>

        </tr>

      </table></td>

    </tr>

  </table>

</form>

</body>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'REGISTRO DE COMPROMISOS';

</script>

</html>