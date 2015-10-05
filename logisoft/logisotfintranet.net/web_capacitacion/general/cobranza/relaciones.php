<?  
principal de cobranza=consultaTexto("mostrardetalle","principal_con.php?accion=1&fecha="+u.fecha.value);

clientes con credito=consultaTexto("mostrarmesfechainicio","principal_con.php?accion=2&fecha="+u.fecha.value);

estado de cuenta=(clientes con credito=(carteravigente)"ESTADO DE CUENTA",2,"nombredelcliente.php?fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&cliente="+arr.cliente+"&mes="+u.mes.value+"&nombrecliente="+arr.nombrecliente);)

antiguedad de saldos=("ANTIGUEDAD DE SALDOS",1,"carteravigente.php?sucursal="+arr.sucursal+"&fecha="+u.fechaini.value+"&fecha2="+u.fecha.value);)

monto autorizado=(clientes con credito + MONTO AUTORIZADO",2,"montoautorizado.php?cliente="+arr.cliente);)


?>