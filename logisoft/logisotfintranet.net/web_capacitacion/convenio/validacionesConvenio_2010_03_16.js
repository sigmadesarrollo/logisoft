// JavaScript Document
function validacionesConvenio(){
	this.setDatos					= f_setDatos;
	this.checarCredito				= f_checarCredito;
	this.checarCreditoActivado		= f_checarCreditoActivado;
	this.validarServRest			= f_validarServRest;
	this.validarSucApliEAD			= f_validarSucApliEAD;
	this.validarServCobr    		= f_validarServCobr;
	this.validarDestRestEADF		= f_validarDestRestEADF;
	this.validarConvenioAUsar		= f_validarConvenioAUsar;
	this.validarClienteConvenio		= f_validarClienteConvenio;
	this.validarOrigenDestino		= f_validarOrigenDestino;
	this.validarVendedorConvenio	= f_validarVendedorConvenio;
	this.getDescripciones			= f_getDescripciones;
	this.restringEADDestinatario	= f_restringEADDestinatario;
	this.restringVDDestinatario		= f_restringVDDestinatario;
	this.restringRXCDestinatario	= f_restringRXCDestinatario;
	this.validaEADsucursal			= f_validaEADsucursal;
	this.validaRecsucursal			= f_validaRecsucursal;
	this.restringirDestinoEAD		= f_restringirDestinoEAD;
	this.validaDescuentoSobreFlete  = f_validaDescuentoSobreFlete;
	this.validaConsignacionDescuento= f_validaConsignacionDescuento;
	this.aplicaTarifaMinima			= f_aplicaTarifaMinima;
	//this.getServicios    	= f_getServicios;
	
	//empresarial
	this.validarServRestE			= f_validarServRestE;
	this.validarSucApliEADE			= f_validarSucApliEADE;
	this.validarServCobrE			= f_validarServCobrE;
	this.getDescripcionesE			= f_getDescripcionesE;
	this.restringEADDestinatarioE	= f_restringEADDestinatarioE;
	this.restringVDDestinatarioE	= f_restringVDDestinatarioE;
	this.restringRXCDestinatarioE	= f_restringRXCDestinatarioE;
	this.validaEADsucursalE			= f_validaEADsucursalE;
	this.validaRecsucursalE			= f_validaRecsucursalE;
	
	this.validarServiciosNoCobro	= validarServiciosNoCobro;
	this.validarServiciosNoCobroGV	= validarServiciosNoCobroGV;
	
	this.validaPrepagada			= validaPrepagada;
	
	function f_setDatos(datos){
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
		this.objetoJson = Array();
		this.objetoJson = objeto;
	}
	function f_checarCredito(flete){
		//0 pagado
		//1 por cobrar
		//devuelve true si el cliente tiene credito
		var objeto = this.objetoJson;
		if(flete == 0){
			if(objeto[0].datosremitente.credito==1){
				return true;
			}else{
				return false;
			}
		}else{
			if(objeto[0].datosdestinatario.credito==1){
				return true;
			}else{
				return false;
			}
		}
	}
	
	function f_checarCreditoActivado(flete){
		var objeto = this.objetoJson;
		if(flete == 0){
			if(objeto[0].datosremitente.creditoactivado=="SI"){
				return true;
			}else{
				return false;
			}
		}else{
			if(objeto[0].datosdestinatario.creditoactivado=="SI"){
				return true;
			}else{
				return false;
			}
		}
	}
	
	function f_validarServRest(ocurre, flete){
		//0 pagado
		//1 por cobrar
		//devuelve false si tiene mardado EAD(!ocurre) y restrigido el servicio EAD 
		if(!ocurre && flete==1){
			var objeto = this.objetoJson;
			if(objeto[0].datosdestinatario.convenio==1){
				if(objeto[0].datosdestinatario.serviciosrestringidos!=''){
					var cser = objeto[0].datosdestinatario.serviciosrestringidos.length;
					for(var i=0; i<cser; i++){
						if(objeto[0].datosdestinatario.serviciosrestringidos[i].nombre=="EAD")
							return false;
					}
				}
			}
		}
		return true;
	}
	function f_validarSucApliEAD(sucursal){
		//devuelve false si la sucursal no esta en la lista de ead
			var objeto = this.objetoJson;
			if(objeto[0].datosdestinatario.convenio==1){
				if(objeto[0].datosdestinatario.sucursales!=''){
					var cser = objeto[0].datosdestinatario.sucursales.length;
					for(var i=0; i<cser; i++){
						if(objeto[0].datosdestinatario.sucursales[i].clave==sucursal)
							return true;
					}
				}
			}else if(objeto[0].datosremitente.convenio==1){
				if(objeto[0].datosremitente.sucursales!=''){
					var cser = objeto[0].datosremitente.sucursales.length;
					for(var i=0; i<cser; i++){
						if(objeto[0].datosremitente.sucursales[i].clave==sucursal)
							return true;
					}
				}
			}
		return false;
	}
	
	function f_validarServCobr(flete){
		//0 pagado
		//1 por cobrar
		//devuelve false si el destinatario no acepta envios por cobrar 
		if(flete==1){
			var objeto = this.objetoJson;
			if(objeto[0].datosdestinatario.convenio==1){
				if(objeto[0].datosdestinatario.serviciosrestringidos!=''){
					var cser = objeto[0].datosdestinatario.serviciosrestringidos.length;
					for(var i=0; i<cser; i++){
						if(objeto[0].datosdestinatario.serviciosrestringidos[i].nombre=="SERVICIO POR COBRAR")
							return false;
					}
				}
			}
		}
		return true;
	}
	function f_validarDestRestEADF(ocurre){
		//0 pagado
		//1 por cobrar
		//devuelve true si tiene mardado EAD(!ocurre) y restrigido el servicio EAD 
		var restr  = false;
		var objeto = this.objetoJson;
		if(objeto[0].destino!=''){
			if(objeto[0].destino[0].restringireadapfsinconvenio==1){
				restr = true;
			}
		}
		if(restr){
			if(objeto[0].datosdestinatario!=undefined && objeto[0].datosremitente!=undefined){
				if(objeto[0].datosdestinatario.convenio!="0" || objeto[0].datosremitente.convenio!="0" ){
					return false;
				}else{
					if(objeto[0].datosdestinatario.personamoral == "NO"){
						return true;
					}
				}
			}else if(objeto[0].datosdestinatario!=undefined){
				if(objeto[0].datosdestinatario.convenio!="0"){
					return false;
				}else if(objeto[0].datosdestinatario.personamoral == "NO"){
					return true;
				}
			}else if(objeto[0].datosremitente!=undefined){
				if(objeto[0].datosremitente.convenio!="0"){
					return false;
				}else if(objeto[0].datosdestinatario.personamoral == "NO"){
					return true;
				}
			}else{
				return false;
			}
		}
	}
	function f_validarConvenioAUsar(flete){
		var objeto = this.objetoJson;
		if(objeto[0].datosdestinatario!=undefined && objeto[0].datosremitente!=undefined && 
			objeto[0].datosdestinatario.convenio!="0" && objeto[0].datosremitente.convenio!="0"){
			if(flete==0){
				this.objetoJson.convenioAplicado = 0;
				return objeto[0].datosremitente.convenio;
			}else{
				this.objetoJson.convenioAplicado = 1;
				return objeto[0].datosdestinatario.convenio;
			}
		}else{
			if(objeto[0].datosdestinatario!=undefined && objeto[0].datosdestinatario.convenio!="0"){
				this.objetoJson.convenioAplicado = 1;
				return objeto[0].datosdestinatario.convenio;
			}else if(objeto[0].datosremitente!=undefined && objeto[0].datosremitente.convenio!="0"){
				this.objetoJson.convenioAplicado = 0;
				return objeto[0].datosremitente.convenio;
			}else{
				this.objetoJson.convenioAplicado = -1;
				return -1;
			}
		}
	}
	
	function f_validarClienteConvenio(flete){
		var objeto = this.objetoJson;
		if(objeto[0].datosdestinatario!=undefined && objeto[0].datosremitente!=undefined && objeto[0].datosdestinatario.convenio!="0" && objeto[0].datosremitente.convenio!="0"){
			if(flete==0){
				this.objetoJson.convenioAplicado = 0;
				return objeto[0].datosremitente.idremitente;
			}else{
				this.objetoJson.convenioAplicado = 1;
				return objeto[0].datosdestinatario.iddestinatario;
			}
		}else{
			if(objeto[0].datosdestinatario!=undefined && objeto[0].datosdestinatario.convenio!="0"){
				this.objetoJson.convenioAplicado = 1;
				return objeto[0].datosdestinatario.iddestinatario;
			}else if(objeto[0].datosremitente!=undefined && objeto[0].datosremitente.convenio!="0"){
				this.objetoJson.convenioAplicado = 0;
				return objeto[0].datosremitente.idremitente;
			}else{
				this.objetoJson.convenioAplicado = -1;
				return -1;
			}
		}
	}
	function f_validarOrigenDestino(flete){
		var objeto = this.objetoJson;
		if(objeto[0].datosdestinatario!=undefined && objeto[0].datosremitente!=undefined && objeto[0].datosdestinatario.convenio!="0" && objeto[0].datosremitente.convenio!="0"){
			if(flete==0){
				this.objetoJson.convenioAplicado = 0;
				return 0;
			}else{
				this.objetoJson.convenioAplicado = 1;
				return 1;
			}
		}else{
			if(objeto[0].datosdestinatario!=undefined && objeto[0].datosdestinatario.convenio!="0"){
				this.objetoJson.convenioAplicado = 1;
				return 1;
			}else if(objeto[0].datosremitente!=undefined && objeto[0].datosremitente.convenio!="0"){
				this.objetoJson.convenioAplicado = 0;
				return 0;
			}else{
				this.objetoJson.convenioAplicado = -1;
				return -1;
			}
		}
	}
	function f_validarVendedorConvenio(flete){
		var objeto = this.objetoJson;
		if(objeto[0].datosdestinatario!=undefined && objeto[0].datosremitente!=undefined && objeto[0].datosdestinatario.convenio!="0" && objeto[0].datosremitente.convenio!="0"){
			if(flete==0){
				return objeto[0].datosremitente.vendedorconvenio+","+objeto[0].datosremitente.idvendedorconvenio;
			}else{
				return objeto[0].datosdestinatario.vendedorconvenio+","+objeto[0].datosdestinatario.idvendedorconvenio;
			}
		}else{
			if(objeto[0].datosdestinatario!=undefined && objeto[0].datosdestinatario.convenio!="0"){
				return objeto[0].datosdestinatario.vendedorconvenio+","+objeto[0].datosdestinatario.idvendedorconvenio;
			}else if(objeto[0].datosremitente!=undefined && objeto[0].datosremitente.convenio!="0"){
				return objeto[0].datosremitente.vendedorconvenio+","+objeto[0].datosremitente.idvendedorconvenio;
			}else{
				return -1;
			}
		}
	}
	function f_getDescripciones(cliente){
		objeto = this.objetoJson;
		if(cliente==0){
			return objeto[0].datosremitente.descripciones;
		}else{
			return objeto[0].datosdestinatario.descripciones;
		}
	}
	function f_restringEADDestinatario(){
		//regresa true si esta restringido
		var objeto = this.objetoJson;
		for(var i=0; i<objeto[0].datosdestinatario.serviciosrestringidos.length; i++){
			if(objeto[0].datosdestinatario.serviciosrestringidos[i].nombre=="E.A.D."){
				return true;
			}
		}
		return false;
	}
	function f_restringVDDestinatario(){
		//regresa true si esta restringido
		var objeto = this.objetoJson;
		for(var i=0; i<objeto[0].datosdestinatario.serviciosrestringidos.length; i++){
			if(objeto[0].datosdestinatario.serviciosrestringidos[i].nombre=="VALOR DECLARADO"){
				return true;
			}
		}
		return false;
	}
	function f_restringRXCDestinatario(){
		//regresa true si esta restringido
		var objeto = this.objetoJson;
		for(var i=0; i<objeto[0].datosdestinatario.serviciosrestringidos.length; i++){
			if(objeto[0].datosdestinatario.serviciosrestringidos[i].nombre=="SERVICIO POR COBRAR"){
				return true;
			}
		}
		return false;
	}
	function f_validaEADsucursal(convenio,sucursal){
		
		var objeto = this.objetoJson;
		if(objeto[0].datosdestinatario!=undefined && objeto[0].datosdestinatario.convenio==convenio){
			if(objeto[0].datosdestinatario.serviciosrestringidos!=""){
				var paso=false
				for(var i=0; i<objeto[0].datosdestinatario.sucursales.length; i++){
					if(objeto[0].datosdestinatario.sucursales[i].nombre=="TODOS")
						paso = true
					else if(sucursal==objeto[0].datosdestinatario.sucursales[i].clave)
						paso = true;
				}
				if(paso){
					for(var i=0; i<objeto[0].datosdestinatario.serviciosrestringidos.length; i++){
						if(objeto[0].datosdestinatario.serviciosrestringidos[i].nombre = "EAD"){
							return true;
						}
					}
				}
			}
			return false;
		}else{
			if(objeto[0].datosremitente!=undefined && objeto[0].datosremitente.serviciosrestringidos!=""){
				var paso=false
				for(var i=0; i<objeto[0].datosremitente.sucursales.length; i++){
					if(objeto[0].datosremitente.sucursales[i].nombre=="TODOS")
						paso = true
					else if(sucursal==objeto[0].datosremitente.sucursales[i].clave)
						paso = true;
				}
				if(paso){
					for(var i=0; i<objeto[0].datosremitente.serviciosrestringidos.length; i++){
						if(objeto[0].datosremitente.serviciosrestringidos[i].nombre = "EAD")
							return true;
					}
				}
			}
			return false;
		}
	}
	function f_validaRecsucursal(convenio,sucursal){
		var objeto = this.objetoJson;
		if(objeto[0].datosdestinatario!=undefined && objeto[0].datosdestinatario.convenio==convenio){
			if(objeto[0].datosdestinatario.serviciosrestringidos!=""){
				var paso=false
				for(var i=0; i<objeto[0].datosdestinatario.sucursales.length; i++){
					if(objeto[0].datosdestinatario.sucursales[i].nombre=="TODOS")
						paso = true
					else if(sucursal==objeto[0].datosdestinatario.sucursales[i].clave)
						paso = true;
				}
				if(paso){
					for(var i=0; i<objeto[0].datosdestinatario.serviciosrestringidos.length; i++){
						if(objeto[0].datosdestinatario.serviciosrestringidos[i].nombre = "RECOLECCION"){
							return true;
						}
					}
				}
			}
			return false;
		}else{
			if(objeto[0].datosremitente!=undefined && objeto[0].datosremitente.serviciosrestringidos!=""){
				var paso=false
				for(var i=0; i<objeto[0].datosremitente.sucursales.length; i++){
					if(objeto[0].datosremitente.sucursales[i].nombre=="TODOS")
						paso = true
					else if(sucursal==objeto[0].datosremitente.sucursales[i].clave)
						paso = true;
				}
				if(paso){
					for(var i=0; i<objeto[0].datosremitente.serviciosrestringidos.length; i++){
						if(objeto[0].datosremitente.serviciosrestringidos[i].nombre = "RECOLECCION")
							return true;
					}
				}
			}
			return false;
		}
	}
	function f_restringirDestinoEAD(){
		var objeto = this.objetoJson;
		if(objeto[0].destino.restringiread!=undefined && objeto[0].destino.restringiread==1){
			return true;
		}
		return false;
	}
	function f_validaDescuentoSobreFlete(flete){
		var objeto = this.objetoJson;
		if(objeto[0].datosdestinatario!=undefined && objeto[0].datosremitente!=undefined && objeto[0].datosdestinatario.convenio!="0" && objeto[0].datosremitente.convenio!="0"){
			if(flete==0){
				if( objeto[0].datosremitente.descuentosobreflete!="0")
					return objeto[0].datosremitente.cantidaddescuento;
				else
					return 0;
			}else{
				if( objeto[0].datosdestinatario.descuentosobreflete!="0")
					return objeto[0].datosdestinatario.cantidaddescuento;
				else
					return 0;
			}
		}else{
			if(objeto[0].datosdestinatario!=undefined && objeto[0].datosdestinatario.convenio!="0"){
				if( objeto[0].datosdestinatario.descuentosobreflete!="0")
					return objeto[0].datosdestinatario.cantidaddescuento;
				else
					return 0;
			}else if(objeto[0].datosremitente!=undefined && objeto[0].datosremitente.convenio!="0"){
				if( objeto[0].datosremitente.descuentosobreflete!="0")
					return objeto[0].datosremitente.cantidaddescuento;
				else
					return 0;
			}else{
				return -1;
			}
		}
	}
	
	function f_validaConsignacionDescuento(){
		var objeto = this.objetoJson;
		if( objeto[0].datosremitente.consignaciondescuento!="0")
			return objeto[0].datosremitente.consignaciondescuento;
		else
			return 0;
	}
	
	function f_aplicaTarifaMinima(flete){
		var objeto = this.objetoJson;
		if(objeto[0].datosdestinatario!=undefined && objeto[0].datosremitente!=undefined && objeto[0].datosdestinatario.convenio!="0" && objeto[0].datosremitente.convenio!="0"){
			if(flete==0){
				if(objeto[0].datosremitente.precioporcaja!="1")
					return objeto[0].tarifaminima;
				else
					return 0;
			}else{
				if( objeto[0].datosdestinatario.precioporcaja!="1")
					return objeto[0].tarifaminima;
				else
					return 0;
			}
		}else{
			if(objeto[0].datosdestinatario!=undefined && objeto[0].datosdestinatario.convenio!="0"){
				if( objeto[0].datosdestinatario.precioporcaja!="1")
					return objeto[0].tarifaminima;
				else
					return 0;
			}else if(objeto[0].datosremitente!=undefined && objeto[0].datosremitente.convenio!="0"){
				if( objeto[0].datosremitente.precioporcaja!="1")
					return objeto[0].tarifaminima;
				else
					return 0;
			}else{
				return -1;
			}
		}
	}
	
	//para empresariales
	function f_validarServRestE(ocurre, flete){
		//0 pagado
		//1 por cobrar
		//devuelve false si tiene mardado EAD(!ocurre) y restrigido el servicio EAD 
		if(!ocurre && flete==1){
			var objeto = this.objetoJson;
			if(objeto[0].datosdestinatario.convenio==1){
				if(objeto[0].datosdestinatario.serviciosrestringidose!=''){
					var cser = objeto[0].datosdestinatario.serviciosrestringidose.length;
					for(var i=0; i<cser; i++){
						if(objeto[0].datosdestinatario.serviciosrestringidose[i].nombre=="EAD")
							return false;
					}
				}
			}
		}
		return true;
	}
	function f_validarSucApliEADE(sucursal){
		//devuelve false si la sucursal no esta en la lista de ead
			var objeto = this.objetoJson;
			if(objeto[0].datosdestinatario.convenio==1){
				if(objeto[0].datosdestinatario.sucursalese!=''){
					var cser = objeto[0].datosdestinatario.sucursalese.length;
					for(var i=0; i<cser; i++){
						if(objeto[0].datosdestinatario.sucursalese[i].clave==sucursal)
							return true;
					}
				}
			}else if(objeto[0].datosremitente.convenio==1){
				if(objeto[0].datosremitente.sucursalese!=''){
					var cser = objeto[0].datosremitente.sucursalese.length;
					for(var i=0; i<cser; i++){
						if(objeto[0].datosremitente.sucursalese[i].clave==sucursal)
							return true;
					}
				}
			}
		return false;
	}
	function f_validarServCobrE(flete){
		//0 pagado
		//1 por cobrar
		//devuelve false si el destinatario no acepta envios por cobrar 
		if(flete==1){
			var objeto = this.objetoJson;
			if(objeto[0].datosdestinatario.convenio==1){
				if(objeto[0].datosdestinatario.serviciosrestringidose!=''){
					var cser = objeto[0].datosdestinatario.serviciosrestringidose.length;
					for(var i=0; i<cser; i++){
						if(objeto[0].datosdestinatario.serviciosrestringidose[i].nombre=="SERVICIO POR COBRAR")
							return false;
					}
				}
			}
		}
		return true;
	}
	function f_getDescripcionesE(cliente){
		objeto = this.objetoJson;
		if(cliente==0){
			return objeto[0].datosremitente.descripcionese;
		}else{
			return objeto[0].datosdestinatario.descripcionese;
		}
	}
	function f_restringEADDestinatarioE(){
		//regresa true si esta restringido
		var objeto = this.objetoJson;
		
		if(objeto[0].datosdestinatario==undefined)
			return false;
		
		for(var i=0; i<objeto[0].datosdestinatario.serviciosrestringidose.length; i++){
			if(objeto[0].datosdestinatario.serviciosrestringidose[i].nombre=="E.A.D."){
				return true;
			}
		}
		return false;
	}
	function f_restringVDDestinatarioE(){
		//regresa true si esta restringido
		var objeto = this.objetoJson;
		
		if(objeto[0].datosdestinatario==undefined)
			return false;
		
		for(var i=0; i<objeto[0].datosdestinatario.serviciosrestringidose.length; i++){
			if(objeto[0].datosdestinatario.serviciosrestringidose[i].nombre=="VALOR DECLARADO"){
				return true;
			}
		}
		return false;
	}
	function f_restringRXCDestinatarioE(){
		//regresa true si esta restringido
		var objeto = this.objetoJson;
		
		if(objeto[0].datosdestinatario==undefined)
			return false;
		
		for(var i=0; i<objeto[0].datosdestinatario.serviciosrestringidose.length; i++){
			if(objeto[0].datosdestinatario.serviciosrestringidose[i].nombre=="SERVICIO POR COBRAR"){
				return true;
			}
		}
		return false;
	}
	function f_validaEADsucursalE(convenio,sucursal){
		
		var objeto = this.objetoJson;
		if(objeto[0].datosdestinatario!=undefined && objeto[0].datosdestinatario.convenio==convenio){
			if(objeto[0].datosdestinatario.serviciosrestringidose!=""){
				var paso=false
				for(var i=0; i<objeto[0].datosdestinatario.sucursalese.length; i++){
					if(objeto[0].datosdestinatario.sucursalese[i].nombre=="TODOS")
						paso = true
					else if(sucursal==objeto[0].datosdestinatario.sucursalese[i].clave)
						paso = true;
				}
				if(paso){
					for(var i=0; i<objeto[0].datosdestinatario.serviciosrestringidose.length; i++){
						if(objeto[0].datosdestinatario.serviciosrestringidose[i].nombre = "EAD"){
							return true;
						}
					}
				}
			}
			return false;
		}else{
			if(objeto[0].datosremitente!=undefined && objeto[0].datosremitente.serviciosrestringidose!=""){
				var paso=false
				for(var i=0; i<objeto[0].datosremitente.sucursalese.length; i++){
					if(objeto[0].datosremitente.sucursalese[i].nombre=="TODOS")
						paso = true
					else if(sucursal==objeto[0].datosremitente.sucursalese[i].clave)
						paso = true;
				}
				if(paso){
					for(var i=0; i<objeto[0].datosremitente.serviciosrestringidose.length; i++){
						if(objeto[0].datosremitente.serviciosrestringidose[i].nombre = "EAD")
							return true;
					}
				}
			}
			return false;
		}
	}
	function f_validaRecsucursalE(convenio,sucursal){
		var objeto = this.objetoJson;
		if(objeto[0].datosdestinatario!=undefined && objeto[0].datosdestinatario.convenio==convenio){
			if(objeto[0].datosdestinatario.serviciosrestringidose!=""){
				var paso=false
				for(var i=0; i<objeto[0].datosdestinatario.sucursalese.length; i++){
					if(objeto[0].datosdestinatario.sucursalese[i].nombre=="TODOS")
						paso = true
					else if(sucursal==objeto[0].datosdestinatario.sucursalese[i].clave)
						paso = true;
				}
				if(paso){
					for(var i=0; i<objeto[0].datosdestinatario.serviciosrestringidose.length; i++){
						if(objeto[0].datosdestinatario.serviciosrestringidose[i].nombre = "RECOLECCION"){
							return true;
						}
					}
				}
			}
			return false;
		}else{
			if(objeto[0].datosremitente!=undefined && objeto[0].datosremitente.serviciosrestringidose!=""){
				var paso=false
				for(var i=0; i<objeto[0].datosremitente.sucursalese.length; i++){
					if(objeto[0].datosremitente.sucursalese[i].nombre=="TODOS")
						paso = true
					else if(sucursal==objeto[0].datosremitente.sucursalese[i].clave)
						paso = true;
				}
				if(paso){
					for(var i=0; i<objeto[0].datosremitente.serviciosrestringidose.length; i++){
						if(objeto[0].datosremitente.serviciosrestringidose[i].nombre = "RECOLECCION")
							return true;
					}
				}
			}
			return false;
		}
	}
	
	function validarServiciosNoCobro(flete, servicio, sucursal){
		var objeto = this.objetoJson;
		if(objeto[0].datosdestinatario!=undefined && objeto[0].datosremitente!=undefined && objeto[0].datosdestinatario.convenio!="0" && objeto[0].datosremitente.convenio!="0"){
			if(flete==0){
				if(objeto[0].datosremitente.otrosserviciose!=""){
					for(var i=0; i<objeto[0].datosremitente.otrosserviciose.length;i++){
						if(objeto[0].datosremitente.otrosserviciose[i].idservicio==servicio){
							if(objeto[0].datosremitente.sucursalese!=""){
								if(objeto[0].datosremitente.sucursalese[0].nombre=="TODOS"){
									return true;
								}else{
									for(var j=0; j<objeto[0].datosremitente.sucursalese.length; j++){	
										if(objeto[0].datosremitente.sucursalese[0].clave==sucursal){
											return true;
										}
									}
									return false;
								}
								return true;
							}else{
								return false;
							}
						}
					}
					return false;
				}else
					return false;
			}else{
				if(objeto[0].datosdestinatario.otrosserviciose!=""){
					for(var i=0; i<objeto[0].datosdestinatario.otrosserviciose.length;i++){
						if(objeto[0].datosdestinatario.otrosserviciose[i].idservicio==servicio){
							if(objeto[0].datosdestinatario.sucursalese!=""){
								if(objeto[0].datosdestinatario.sucursalese[0].nombre=="TODOS"){
									return true;
								}else{
									for(var j=0; j<objeto[0].datosdestinatario.sucursalese.length; j++){	
										if(objeto[0].datosdestinatario.sucursalese[0].clave==sucursal){
											return true;
										}
									}
									return false;
								}
								return true;
							}else{
								return false;
							}
						}
					}
					return false;
				}else
					return false;
			}
		}else{
			if(objeto[0].datosdestinatario!=undefined && objeto[0].datosdestinatario.convenio!="0"){
				if(objeto[0].datosdestinatario.otrosserviciose!=""){
					for(var i=0; i<objeto[0].datosdestinatario.otrosserviciose.length;i++){
						if(objeto[0].datosdestinatario.otrosserviciose[i].idservicio==servicio){
							if(objeto[0].datosdestinatario.sucursalese!=""){
								if(objeto[0].datosdestinatario.sucursalese[0].nombre=="TODOS"){
									return true;
								}else{
									for(var j=0; j<objeto[0].datosdestinatario.sucursalese.length; j++){	
										if(objeto[0].datosdestinatario.sucursalese[0].clave==sucursal){
											return true;
										}
									}
									return false;
								}
								return true;
							}else{
								return false;
							}
						}
					}
					return false;
				}else
					return false;
			}else if(objeto[0].datosremitente!=undefined && objeto[0].datosremitente.convenio!="0"){
				if(objeto[0].datosremitente.otrosserviciose!=""){
					for(var i=0; i<objeto[0].datosremitente.otrosserviciose.length;i++){
						if(objeto[0].datosremitente.otrosserviciose[i].idservicio==servicio){
							if(objeto[0].datosremitente.sucursalese!=""){
								if(objeto[0].datosremitente.sucursalese[0].nombre=="TODOS"){
									return true;
								}else{
									for(var j=0; j<objeto[0].datosremitente.sucursalese.length; j++){	
										if(objeto[0].datosremitente.sucursalese[0].clave==sucursal){
											return true;
										}
									}
									return false;
								}
								return true;
							}else{
								return false;
							}
						}
					}
					return false;
				}else
					return false;
			}else{
				return false;
			}
		}
	}
	
	function validarServiciosNoCobroGV(flete, servicio, sucursal){
		var objeto = this.objetoJson;
		if(objeto[0].datosdestinatario!=undefined && objeto[0].datosremitente!=undefined && objeto[0].datosdestinatario.convenio!="0" && objeto[0].datosremitente.convenio!="0"){
			if(flete==0){
				if(objeto[0].datosremitente.otrosservicios!=""){
					for(var i=0; i<objeto[0].datosremitente.otrosservicios.length;i++){
						if(objeto[0].datosremitente.otrosservicios[i].idservicio==servicio){
							if(objeto[0].datosremitente.sucursales!=""){
								if(objeto[0].datosremitente.sucursales[0].nombre=="TODOS"){
									return true;
								}else{
									for(var j=0; j<objeto[0].datosremitente.sucursales.length; j++){	
										if(objeto[0].datosremitente.sucursales[0].clave==sucursal){
											return true;
										}
									}
									return false;
								}
								return true;
							}else{
								return false;
							}
						}
					}
					return false;
				}else
					return false;
			}else{
				if(objeto[0].datosdestinatario.otrosservicios!=""){
					for(var i=0; i<objeto[0].datosdestinatario.otrosservicios.length;i++){
						if(objeto[0].datosdestinatario.otrosservicios[i].idservicio==servicio){
							if(objeto[0].datosdestinatario.sucursales!=""){
								if(objeto[0].datosdestinatario.sucursales[0].nombre=="TODOS"){
									return true;
								}else{
									for(var j=0; j<objeto[0].datosdestinatario.sucursales.length; j++){	
										if(objeto[0].datosdestinatario.sucursales[0].clave==sucursal){
											return true;
										}
									}
									return false;
								}
								return true;
							}else{
								return false;
							}
						}
					}
					return false;
				}else
					return false;
			}
		}else{
			if(objeto[0].datosdestinatario!=undefined && objeto[0].datosdestinatario.convenio!="0"){
				if(objeto[0].datosdestinatario.otrosservicios!=""){
					for(var i=0; i<objeto[0].datosdestinatario.otrosservicios.length;i++){
						if(objeto[0].datosdestinatario.otrosservicios[i].idservicio==servicio){
							if(objeto[0].datosdestinatario.sucursales!=""){
								if(objeto[0].datosdestinatario.sucursales[0].nombre=="TODOS"){
									return true;
								}else{
									for(var j=0; j<objeto[0].datosdestinatario.sucursales.length; j++){	
										if(objeto[0].datosdestinatario.sucursales[0].clave==sucursal){
											return true;
										}
									}
									return false;
								}
								return true;
							}else{
								return false;
							}
						}
					}
					return false;
				}else
					return false;
			}else if(objeto[0].datosremitente!=undefined && objeto[0].datosremitente.convenio!="0"){
				if(objeto[0].datosremitente.otrosservicios!=""){
					for(var i=0; i<objeto[0].datosremitente.otrosservicios.length;i++){
						if(objeto[0].datosremitente.otrosservicios[i].idservicio==servicio){
							if(objeto[0].datosremitente.sucursales!=""){
								if(objeto[0].datosremitente.sucursales[0].nombre=="TODOS"){
									return true;
								}else{
									for(var j=0; j<objeto[0].datosremitente.sucursales.length; j++){	
										if(objeto[0].datosremitente.sucursales[0].clave==sucursal){
											return true;
										}
									}
									return false;
								}
								return true;
							}else{
								return false;
							}
						}
					}
					return false;
				}else
					return false;
			}else{
				return false;
			}
		}
	}
	
	function validaPrepagada(flete){
		var objeto = this.objetoJson;
		if(objeto[0].datosremitente!=undefined && objeto[0].datosremitente.prepagadas=="1"){
			var datos = Object();
			datos.limitekg 			= objeto[0].datosremitente.limitekg;
			datos.costo 			= objeto[0].datosremitente.costo;
			datos.preciokgexcedente = objeto[0].datosremitente.preciokgexcedente;
			return datos;
		}
	}
}