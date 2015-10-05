<?PHP
if (isset($_REQUEST['cadena'])) {
   $_REQUEST['cadena'];
   $query="SELECT nombre,apellido FROM usuarios WHERE (nombre like '$cadena%')";
}else $query="SELECT nombre,apellido FROM usuarios";
 
      $conexion = mysql_connect ("172.16.40.39", "root","root")
         or die ("No se puede conectar con el servidor");
 
      mysql_select_db ("prueba")
         or die ("No se puede seleccionar la base de datos");
  
      $consulta=mysql_query($query,$conexion);
?>
<TABLE>
<TBODY>
<TR>
<TH>Nombre</TH>
<TH>Apellido</TH>
</TR>
<?PHP
      while($row = mysql_fetch_array($consulta)) {
          echo "<TR>";
          echo "<TD>".$row['nombre']."</TD>";
          echo "<TD>".$row['apellido']."</TD>";
          echo "</TR>";
      }
?>
</TBODY>
</TABLE>

