// JavaScript Document
var var_rt000 = 0;
var var_obj00 = this;

function agregar_una_tabla(nombre, idfila, limite, estilos, imgborrar){
	var_obj00["var_tab00_"+var_rt000]=nombre;
	var_obj00["var_idf00_"+var_rt000]=idfila;
	var_obj00["var_ini00_"+var_rt000]=0;
	var_obj00["var_reg00_"+var_rt000]=0;
	var_obj00["var_lim00_"+var_rt000]=limite;
	var_obj00["var_est00_"+var_rt000]=estilos;
	var_obj00["var_imgb0_"+var_rt000]=imgborrar;
	
	var_rt000++;
	return var_rt000-1;
}

function ordenamiento_tabla(num,ordenamiento){
	var_obj00["var_ord00_"+num]=ordenamiento;
}

function insertar_en_tabla(num,valores){
	tbody = document.all[var_obj00["var_tab00_"+num]];
	
	var_obj00["var_ini00_"+num]++;
	var_obj00["var_reg00_"+num]++;
	paraid		= var_obj00["var_reg00_"+num];
	numfila		= var_obj00["var_ini00_"+num];
	nombretabla = var_obj00["var_tab00_"+num];
	idfila		= var_obj00["var_ini00_"+num];
	nomfila		= var_obj00["var_idf00_"+num];
	ordenamiento= var_obj00["var_ord00_"+num];
	if(ordenamiento!=undefined){
		arreord		= ordenamiento.split(",");
	}
	cant 		= valores.split("└").length;
	valarreglo	= valores.split("└");
	estilos		= var_obj00["var_est00_"+num].split("└");
	
	if(paraid > var_obj00["var_lim00_"+num]){
		tr = tbody.insertRow(tbody.rows.length);
		tr.id = nomfila+paraid;
		if(tbody.rows.length%2==0){
			tr.className = estilos[0];
		}else{
			tr.className = estilos[1];
		}
		for(var i=0;i<cant;i++){
			td = tr.insertCell(tr.cells.length);
			td.innerHTML = "&nbsp;";
			if(ordenamiento!=undefined){
				td.style.textAlign = arreord[i];
			}
		}
	}
	for(var i=0;i<cant;i++){
		valrem = valarreglo[i].replace(/xxBORRARxx/g,"<img src='"+var_obj00["var_imgb0_"+num]+"' "+
			"style='cursor:hand;' onclick='borrar_fila_tabla("+num+", \""+nomfila+(paraid-1)+"\")'>");
		valrem = valrem.replace(/xxIDFILAxx/g,(nomfila+(paraid-1)));
		valrem = valrem.replace(/xxNOFILAxx/g,numfila);
		
		tbody.rows[numfila].cells[i].innerHTML = valrem;
	}
	if(document.all[nombretabla].alagregar!="")
		eval(document.all[nombretabla].alagregar+"()");
}

function reacomodar_colores(num){
	tbody 		= document.all[var_obj00["var_tab00_"+num]];
	estilos		= var_obj00["var_est00_"+num].split("└");
	
	for (var i=0;i<tbody.rows.length;i++){
		if (tbody.rows[i].className==estilos[0] || tbody.rows[i].className==estilos[1]){ 
			if(i%2==0){
				tbody.rows[i].className = estilos[1];
			}else{
				tbody.rows[i].className = estilos[0];
			}
		}
	}
}

function borrar_fila_tabla(num, idn){
	tbody 		= document.all[var_obj00["var_tab00_"+num]];
	nombretabla = var_obj00["var_tab00_"+num];
	var_obj00["var_ini00_"+num]--;
	
	for (var i=1;i<tbody.rows.length;i++){
		if (tbody.rows[i].id==idn) { 
			tbody.deleteRow(i);
		}
	}
	reacomodar_colores(num);
	if(document.all[nombretabla].alagregar!="")
		eval(document.all[nombretabla].alborrar+"()");
}

function reiniciar_indice(num){
	var_obj00["var_ini00_"+num]=0;
	var_obj00["var_reg00_"+num]=0;
}

function modificar_fila(num, idn, valores){
	tbody 		= document.all[var_obj00["var_tab00_"+num]];
	cant 		= valores.split("└").length;
	valarreglo	= valores.split("└");
	
	for (var i=1;i<tbody.rows.length;i++){
		if (tbody.rows[i].id==idn) { 
			numfila = i;
		}
	}
	
	for(var i=0;i<cant;i++){
		tbody.rows[numfila].cells[i].innerHTML = valarreglo[i];
	}
}