<?php 
/** 
 * OEOG Ejemplo de uso de Class para convertir numeros en palabras  
 * Probado en/Tested on PHP 5-Apache2-XP 
 *  
 *  
 * @version   $Id: CNumeroaLetra_ejemplo.php,v 1.0.0 2004-10-29 13:20 ortizom Exp $ 
 * @author    Omar Eduardo Ortiz Garza <ortizom@siicsa.com> 
 * @copyright (c) 2004-2005 Omar Eduardo Ortiz Garza 
 * @since     Friday, October 29, 2004 
 **/ 

//incluyes la clase que vas a utilizar 
include("CNumeroaLetra.php"); 

//creas un objeto 
$numalet= new CNumeroaletra; 

//le pones el número que quieras que despliegue 
$numalet->setNumero(500.76); 

//imprime una frase que dice la cantidad con letra 
echo $numalet->letra(); 

?> 