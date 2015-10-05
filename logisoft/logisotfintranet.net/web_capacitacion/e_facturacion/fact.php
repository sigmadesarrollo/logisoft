<? 
/* Definimos donde esta el key en formato PEM */
$key='aaa010101aaa_CSD_01.key.pem';
 
/* Introducimos la cadena original provista por el SAT */
$cadenaoriginal='||A|1|2005-09-02T16:30:00|1|ISP900909Q88|Industrias del Sur Poniente, S.A. de C.V.|Alvaro Obreg�n|37|3|Col. Roma Norte|M�xico|Cuauht�moc|Distrito Federal|M�xico|06700|Pino Suarez|23|Centro|Monterrey|Monterrey|Nuevo L�on|M�xico|95460|CAUR390312S87|Rosa Mar�a Calder�n Uriegas|Topochico|52|Jardines del Valle|Monterrey|Monterrey|Nuevo Le�n|M�xico|95465|10|Caja|Vasos decorados|20|200|1|pieza|Charola met�lica|150|150|IVA|52.5||';
 
/* Transformamos la cadena a UTF8 */
$cadena = utf8_encode($cadenaoriginal) ;
 
/* Hacemos un echo para ver la cadena en UTF8 (no es necesario pero es para ir paso por paso) */
echo '<h5>UTF8</h5>'.$cadena;
 
/* Hacemos un echo para ver la cadena en MD5, aqu� obtenemos el mismo resultado que el SAT */
$cadena=md5($cadena);
echo '<h5>MD5</h2>'.$cadena;
 
/* Aqu� lo que hacemos es escribir un txt (md5.txt) con la digesti�n MD5 para usarlo en el sellado */
$fp = fopen ("md5.txt", "w+");
       fwrite($fp, $cadena);
fclose($fp);
 
/* Aqu� sellamos con el MD5 con el key para obtener el sello y guardarlo en sello.txt */
exec("openssl dgst -sign $key md5.txt | openssl enc -base64 -A > sello.txt");
 
/* Aqu� mostramos el sello que en teor�a deber�a ser el correcto */
echo "<h5>Seal</h5>";
readfile("sello.txt");
?>
