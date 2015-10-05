// JavaScript Document
function trim(cadena,caja)
{
	for(i=0;i<cadena.length;)
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(i+1, cadena.length);
		else
			break;
	}

	for(i=cadena.length-1; i>=0; i=cadena.length-1)
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(0,i);
		else
			break;
	}
	
	document.getElementById(caja).value=cadena;
}

function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=530,height=380,left = 470,top = 200');");
}

function tabular(e,obj){
	tecla=(document.all) ? e.keyCode : e.which;
	if(tecla!=13) return;
	frm=obj.form;
	for(i=0;i<frm.elements.length;i++) 
		if(frm.elements[i]==obj) 
		{ 
			if (i==frm.elements.length-1) 
				i=-1;
			break 
		}
	/*ACA ESTA EL CAMBIO*/
	if (frm.elements[i+1].disabled ==true )    
		tabular(e,frm.elements[i+1]);
	else if (frm.elements[i+1].readOnly ==true )    
		tabular(e,frm.elements[i+1]);
	else frm.elements[i+1].focus();
	return false;
}  
function validar_email() { 
	if (document.getElementById('email').value.indexOf('@') == -1){
	alert ("Debes colocar una \"Dirección de Email\" válida"); 
	document.getElementById('email').focus() //Esto recorna el cursor al campo "Email" 
	}else { 
	document.formu.submit(); 
	} 
} 

	function solonumeros(evnt){
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){		
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));		
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
		}
	}

	function solonumeros2(evnt){		
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode!=46) {
				return false;
			}			
			return true;
		}
	}

	function tiposMoneda(evnt,valor){
		caja = valor;
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
				return false;
			}else{
				if(charCode==46){
					if(caja.indexOf(".")>-1){
						return false;
					}
				}
			}
			return true;
		}
	}
	
	function validarFormularios(f){
		var ele = f.elements.length;
		var obj = f.elements;
		for(i=0;i<ele; i++){
			if(obj[i].validar==1){
				if(obj[i].value=="" || obj[i].value=="0"){
					alerta('Debe capturar '+obj[i].devolver, '¡Atención!',obj[i].name);
					return false;
				}
			}
		}
		return true;
	}
	
	function numcredvar(cad){
		var flag = false; 
		if(cad.indexOf('.') == cad.length - 1) flag = true; 
		var num = cad.split(',').join(''); 
		cad = Number(num).toLocaleString(); 
		if(flag) cad += '.'; 
		return cad;
	}
	
	function fechahora(cad){
		var fecha = new Date();
		dia = fecha.getDate();
		mes = fecha.getMonth()+1;
		ano = fecha.getFullYear();
		hr	= fecha.getHours();
		mi	= fecha.getMinutes();
		seg	= fecha.getSeconds();	
		mFecha = ano+"-"+((mes.toString().length == 1)? "0"+mes : mes)+"-"+((dia.toString().length == 1)?"0"+dia : dia)+" "+hr+":"+((mi.toString().length == 1)? "0"+mi : mi)+":"+seg;
		return mFecha;
	}
	
	function obtenerHora(){
		var ahora = new Date();
		var horas = ahora.getHours();
		var minutos = ahora.getMinutes();
		var segundos = ahora.getSeconds();
		var ValorHora;
	
		//establece las horas
		if (horas < 10)
				ValorHora = "0" + horas;
		else
			ValorHora = "" + horas;
	
		//establece los minutos
		if (minutos < 10)
			ValorHora += ":0" + minutos;
		else
			ValorHora += ":" + minutos;
	
		//establece los segundos
		if (segundos < 10)
			ValorHora += ":0" + segundos;
		else
			ValorHora += ":" + segundos;
			
		return ValorHora;
		
	}
	
	function esNumeric(valor){
		valor = valor.replace("$ ","").replace(/,/g,"").replace(".","");
		var log	=	valor.length;
		var sw	=	"S"; 
		for (x=0; x<log; x++){
			v1	=	valor.substr(x,1);
			v2	= 	parseFloat(v1);
			//Compruebo si es un valor numérico
			if (isNaN(v2)){
				sw	= "N";
			} 
		} 
		if (sw=="S"){			
			return true;
		}else{			
			return false;
		}
	}
	
var patron = new Array(2,2,4)
function mascara(d,sep,pat,nums){
if(d.valant != d.value){
	val = d.value
	largo = val.length
	val = val.split(sep)
	val2 = ''
	for(r=0;r<val.length;r++){
		val2 += val[r]	
	}
	if(nums){
		for(z=0;z<val2.length;z++){
			if(isNaN(val2.charAt(z))){
				letra = new RegExp(val2.charAt(z),"g")
				val2 = val2.replace(letra,"")
			}
		}
	}
	val = ''
	val3 = new Array()
	for(s=0; s<pat.length; s++){
		val3[s] = val2.substring(0,pat[s])
		val2 = val2.substr(pat[s])
	}
	for(q=0;q<val3.length; q++){
		if(q ==0){
			val = val3[q]
		}
		else{
			if(val3[q] != ""){
				val += sep + val3[q]
				}
		}
	}
	d.value = val
	d.valant = val
	}
}

	function validarFecha(fecha, caja){
		if(fecha!=""){			
			var mes  =  parseInt(fecha.substring(3,5),10);
			var dia  =  parseInt(fecha.substring(0,2),10);				
			var year = 	parseInt(fecha.substring(6,10),10);
			if (!/^\d{2}\/\d{2}\/\d{4}$/.test(fecha)){
				mens.show('A','La fecha no es valida', '¡Atención!',caja);
				return false;
			}
			
			if(dia > 29 && (mes=="02" || mes==2)){
				if((year % 4 == 0 && year % 100 != 0) || year % 400 == 0){
					mens.show('A','La fecha no es valida, por que el año '+year
							  +' es bisiesto su maximo dia es 29', '¡Atención!');
					return false;
				}else{
					mens.show('A','La fecha no es valida, por que el año '+year
							  +' no es bisiesto su maximo dia es 28', '¡Atención!');
					return false;
				}
			}
			
			if(dia >= 29 && (mes=="02" || mes=="2")){
				if(!((year % 4 == 0 && year % 100 != 0) || year % 400 == 0)){
					mens.show('A','La fecha no es valida, por que el año '+year
							  +' no es bisiesto su maximo dia es 28', '¡Atención!');
						return false;
				}
			}
			
			if (dia>"31" || dia=="0" ){
				mens.show('A','La fecha no es valida, capture correctamente el Dia', '¡Atención!',caja);
				return false;	
			}
			
			if (mes>"12" || mes=="0" ){
				mens.show('A','La fecha no es valida, capture correctamente el Mes', '¡Atención!',caja);
				return false;	
			}	
		}
	}