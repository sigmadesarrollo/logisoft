<? 
/* Definimos donde esta el key en formato PEM */
$key='aaa010101aaa_CSD_01.key.pem';
 
/* Introducimos la cadena original provista por el SAT */
$cadenaoriginal='||A|1|2005-09-02T16:30:00|1|ISP900909Q88|Industrias del Sur Poniente, S.A. de C.V.|Alvaro Obregón|37|3|Col. Roma Norte|México|Cuauhtémoc|Distrito Federal|México|06700|Pino Suarez|23|Centro|Monterrey|Monterrey|Nuevo Léon|México|95460|CAUR390312S87|Rosa María Calderón Uriegas|Topochico|52|Jardines del Valle|Monterrey|Monterrey|Nuevo León|México|95465|10|Caja|Vasos decorados|20|200|1|pieza|Charola metálica|150|150|IVA|52.5||';
 
/* Transformamos la cadena a UTF8 */
$cadena = utf8_encode($cadenaoriginal) ;
 
/* Hacemos un echo para ver la cadena en UTF8 (no es necesario pero es para ir paso por paso) */
echo '<h5>UTF8</h5>'.$cadena;
 
/* Hacemos un echo para ver la cadena en MD5, aquí obtenemos el mismo resultado que el SAT */
$cadena=md5($cadena);
echo '<h5>MD5</h2>'.$cadena;
 
/* Aquí lo que hacemos es escribir un txt (md5.txt) con la digestión MD5 para usarlo en el sellado */
$fp = fopen ("md5.txt", "w+");
       fwrite($fp, $cadena);
fclose($fp);
 
/* Aquí sellamos con el MD5 con el key para obtener el sello y guardarlo en sello.txt */
exec("openssl dgst -sign $key md5.txt | openssl enc -base64 -A > sello.txt");
 
/* Aquí mostramos el sello que en teoría debería ser el correcto */
echo "<h5>Seal</h5>";
readfile("sello.txt");
?>
