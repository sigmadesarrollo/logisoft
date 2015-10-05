/***************************************
*                                      *
*           OUTLOOK-LIKE BAR           *
*                                      *
*             Written by               *
*           Massimo Guccione           *
*            Multimedia Lab            *
*       Intersiel S.p.A. (W)1999       *
*                                      *
* Important: to use this script don't  *
*            remove these comments     *
*                                      *
* Version 1.0Beta Freeware (MSIE only) *
*                                      *
* mail : m.guccione@telcal.it          *
*        obyone@antares.it             *
*        please report for bugs        *
*                                      *
*  for both Netscape and MSIE version  *
*  contact me! (freeware of course)    *
****************************************/


/********************************************
folder name must be OutBarFolder# where # start with 1 and increase by 1
first element of array is the folder label, next elements are :
1) url for icon of item
2) label for item
3) action link : put 'javascript:MyFunction()' to execute javascript instead of hyperlink
4) target frame : ignored if you use 'javascript:' in the action link (use 'window' instead of 'parent.MAIN' if you wish the link to load in the CURRENT page
********************************************/

OutBarFolder1=new Array(
"Herramientas",
"../../img/netm.gif","Pedidos Nuevos","","",
"../../img/organigrama.bmp","Remisiones","asignacion.php","top.parent.mainFrame",""
);

OutBarFolder2=new Array(
"Control de Pedidos",
"../../img/bar2.gif","Analisis de Medios","citas_medios.php","top.parent.mainFrame",
"../../img/agenda2.gif","Agenda de Publicidad","../../TVJULIO2004.htm","top.parent.mainFrame",""
);

OutBarFolder3=new Array(
"Clientes Morosos",
"../../img/cobranza.gif","Pros. Telefonica","javascript:Run(1)","",
"../../img/tv.gif","Comercial","javascript:Run(2)","",
"../../img/sustituyea.gif","Filtracion","javascript:Run(3)","",""
);

OutBarFolder4=new Array(
"Catalogos",
"../../img/calendario.gif","Periodos","javascript:Run(1)","",
"../../img/caja_azul.gif","Productos","javascript:Run(1)","",
"../../img/oficinas.gif","Oficinas","javascript:Run(1)","",
"../../img/users.gif","Vendedores","javascript:Run(2)","",
"../../img/users.gif","Gerentes","javascript:Run(3)","",
"../../img/users.gif","Supercisores","javascript:Run(1)","",
"../../img/users.gif","Promotores","javascript:Run(1)","",""
);



