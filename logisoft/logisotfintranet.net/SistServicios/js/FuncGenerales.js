// JavaScript Document
function bloquearPantalla(Bool){
	if(Bool){			
		var v_Men = "<table align='center' style='background-color:transparent;'><tr><td colspan='2'>&nbsp;</td></tr><tr><td valign='middle' align='center'><img src='img/logo2.png'></td><td valign='middle' align='center'>&nbsp;</td></tr><tr><td colspan='2' align='center'>Procesando la informaci&oacute;n,<br> favor de esperar...</td></tr><tr><td colspan='2'>&nbsp;</td></tr></table>";	
		jQuery.blockUI({ 
			message: v_Men, 
			css:{width:'250px', border: "1 solid", '-webkit-border-radius': '10px', '-moz-border-radius': '10px', 'border-radius': '10px'}
			});
	}else{
		jQuery.unblockUI();
		return false;
	}
}

function Reloj(){
   	rel = new Date() 
   	hora = rel.getHours();
   	minuto = rel.getMinutes();
   	segundo = rel.getSeconds();
   	document.getElementById("eHora").value = hora + ":" + minuto + ":" + segundo;
	setTimeout("Reloj()",1000);
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

function AbrirSlide(){
	jQuery("div#panel").slideDown("slow");
}

function SubirSlide(){
	jQuery("div#panel").slideUp("slow");
}

function botonSlide(){
	jQuery("#toggle a").toggle();
}

jQuery(document).ready(function() {
// Expand Panel
	jQuery("#open").click(function(){
		$("div#panel").slideDown("slow");
	});	
	
	// Collapse Panel
	jQuery("#close").click(function(){
		$("div#panel").slideUp("slow");	
	});		
	
	// Switch buttons from "Log In | Register" to "Close Panel" on click
	jQuery("#toggle a").click(function () {
		$("#toggle a").toggle();
	});	
});

/*
			\u00e1 -> á 
			\u00e9 -> é 
			\u00ed -> í 
			\u00f3 -> ó 
			\u00fa -> ú 
			\u00c1 -> Á 
			\u00c9 -> É 
			\u00cd -> Í 
			\u00d3 -> Ó 
			\u00da -> Ú 
			\u00f1 -> ñ 
			\u00d1 -> Ñ
		*/