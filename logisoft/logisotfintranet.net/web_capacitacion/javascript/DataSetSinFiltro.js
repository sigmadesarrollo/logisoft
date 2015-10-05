// JavaScript Document
function DataSet(){
	this.estructura = null;
	this.registros = null;
	this.registrosBase = null;
	this.totalRegistros = 0;
	this.paginasDe = 30;
	this.totalPaginas = 0;
	this.indice = 0;
	this.objetoPaginador = null;
	this.objetoTabla = null;
	this.seleccionInicio = 0;
	this.seleccionFin = 0;
	this.registroSeleccionado = 0;
	this.nombreVariable = null;
	this.ubicacion = null;
	this.funcionOrdenar = null;
	this.filtro = null;
	
	this.crear = crear;
	this.setJsonData = setJsonData;
	this.mostrarRegistros = mostrarRegistros;
	this.ponerPaginado = ponerPaginado;
	this.siguiente = siguiente;
	this.anterior = anterior;
	this.primero = primero;
	this.ultimo = ultimo;
	this.asignaPagina = asignaPagina;
	this.buscarYMostrar = buscarYMostrar;
	this.buscarYMostrarRevueltos = buscarYMostrarRevueltos;
	this.actualizarRegistro = actualizarRegistro;
	this.actualizarRegistroSinMostrar = actualizarRegistroSinMostrar;
	this.borrarRegistro = borrarRegistro;
	this.agregarRegistro = agregarRegistro;
	this.limpiar = limpiar;
	this.refrescar = refrescar;
	this.buscar = buscar;
	
	function crear(objeto){
		this.estructura = objeto;
		this.nombreVariable = this.estructura.nombreVariable;
		this.ubicacion = this.estructura.ubicacion;
		if(this.estructura.funcionOrdenar != null){
			this.funcionOrdenar = this.estructura.funcionOrdenar;
		}
		if(this.estructura.objetoTabla != null){
			this.objetoTabla = this.estructura.objetoTabla;
		}
		if(this.estructura.paginasDe != null){
			this.paginasDe = this.estructura.paginasDe;
		}
		if(this.estructura.objetoPaginador != null){
			this.objetoPaginador = this.estructura.objetoPaginador;
		}
	}
	
	function setJsonData(registros){
		if(registros!=null){
			this.limpiar();
			this.registros = registros;
			this.registros = this.registros.sort(this.funcionOrdenar);
			this.totalRegistros = registros.length;
			this.totalPaginas = Math.ceil(registros.length/this.paginasDe);
			this.mostrarRegistros();
		}
	}
	
	function mostrarRegistros(){
		if(this.objetoTabla != null && this.registros != null){
			this.seleccionInicio = this.paginasDe*this.indice;
			this.seleccionFin = this.paginasDe*(this.indice+1);
			var registros = this.registros.slice(this.seleccionInicio,this.seleccionFin);
			this.objetoTabla.setJsonData(registros);
			this.ponerPaginado();
		}
	}
	
	function ponerPaginado(){
		if(this.objetoPaginador != null && this.registros.length != null  && this.registros.length > 0){
			if(this.indice == 0){
				var anterior = '';
			}else{
				var anterior = '<img src="'+this.ubicacion+'img/first.gif" width="16" height="16" style="cursor:pointer"  onclick="'+
					this.nombreVariable+'.primero()" /> '+
					'<img src="'+this.ubicacion+'img/previous.gif" width="16" height="16" style="cursor:pointer" onclick="'+this.nombreVariable+'.anterior()" />';
			}
			
			if(this.indice+1 == this.totalPaginas){
				var siguiente = '';
			}else{
				var siguiente = '<img src="'+this.ubicacion+'img/next.gif" width="16" height="16" style="cursor:pointer" onclick="'+
					this.nombreVariable+'.siguiente()" /> '+
					'<img src="'+this.ubicacion+'img/last.gif" width="16" height="16" style="cursor:pointer" onclick="'+this.nombreVariable+'.ultimo()" />';
			}
				
			this.objetoPaginador.innerHTML = '<div style="width:100%; height:18px">'+
                '<div style="width:33%; height:18px; float:left; text-align:center">'+
                    anterior+
                '</div>'+
                '<div style="width:33%; height:14px; float:left; text-align:center; padding-top:4px">'+
                    (this.indice+1)+' - '+this.totalPaginas+
                '</div>'+
                '<div style="width:33%; height:18px; float:left; text-align:center">'+
                    siguiente+
                '</div>'+
            '</div>';
		}
	}
	
	function siguiente(){
		if((this.indice+1) < this.totalPaginas){
			this.indice++;
			var registros = this.asignaPagina();
			this.objetoTabla.setJsonData(registros);
			this.ponerPaginado();
		}
	}
	
	function anterior(){
		if((this.indice-1) > -1){
			this.indice--;
			var registros = this.asignaPagina();
			this.objetoTabla.setJsonData(registros);
			this.ponerPaginado();
		}
	}
	
	function primero(){
		this.indice = 0;
		var registros = this.asignaPagina();
		this.objetoTabla.setJsonData(registros);
		this.ponerPaginado();
	}
	
	function ultimo(){

		this.indice = this.totalPaginas-1;
		var registros = this.asignaPagina();
		this.objetoTabla.setJsonData(registros);
		this.ponerPaginado();
	}
	
	function asignaPagina(){
		if(this.indice+1 > this.totalPaginas)
			this.indice = this.totalPaginas-1;
		this.seleccionInicio = this.paginasDe*this.indice;
		this.seleccionFin = this.paginasDe*(this.indice+1);
		return this.registros.slice(this.seleccionInicio,this.seleccionFin);
	}
	
	function buscarYMostrar(valor,campo){
		var valorinicio = 0;
		var valorfin = 0;
		var encontro = false;
		for(var i=0; i<this.totalPaginas; i++){
			if(this.totalPaginas-1 == i){
				valorfin = this.registros[this.totalRegistros-1][campo];
			}else{
				valorfin = this.registros[(((i+1)*this.paginasDe)-1)][campo]
			}
			valorinicio = this.registros[i*this.paginasDe][campo];
			
			var seleccionar = false;
			if(valor.toUpperCase() >= valorinicio.toUpperCase() && valor.toUpperCase() <= valorfin.toUpperCase()){
				if(this.objetoTabla!=null){
					if(this.objetoTabla.attributes.seleccion){
						seleccionar = true;
					}
				}
				this.indice = i;
				break;
			}
		}
		var registros = this.asignaPagina();
		this.objetoTabla.setJsonData(registros);
		this.ponerPaginado();
		if(seleccionar){
			var finPagina = 0;
			if(this.indice+1==this.totalPaginas)
				finPagina = (this.indice*this.paginasDe)+((this.totalRegistros)-((this.indice)*this.paginasDe));
			else
				finPagina = (this.paginasDe)+(this.indice*this.paginasDe);
				
			for(var i=this.indice*this.paginasDe; i<finPagina; i++){
				if(this.registros[i][campo].toUpperCase()==valor.toUpperCase()){
					encontro = true;
					this.objetoTabla.setSelectedByIndex(i-(this.indice*this.paginasDe));
					this.objetoTabla.setSelectedById(this.objetoTabla.attributes.nombre+"_id"+(i-(this.indice*this.paginasDe)))
					break;
				}
			}
			return encontro;
		}else{
			var finPagina = 0;
			if(this.indice+1==this.totalPaginas)
				finPagina = (this.indice*this.paginasDe)+((this.totalRegistros)-((this.indice)*this.paginasDe));
			else
				finPagina = (this.paginasDe)+(this.indice*this.paginasDe);
			
			for(var i=this.indice*this.paginasDe; i<finPagina; i++){
				if(this.registros[i][campo].toUpperCase()==valor.toUpperCase()){
					encontro = true;
					this.registroSeleccionado = i;
					return this.registroSeleccionado;
				}
			}
			return encontro;
		}
	}
	
	function buscarYMostrarRevueltos(valor,campo,tipo){
		var seleccionar = false;
		if(this.objetoTabla.attributes.seleccion){
			seleccionar = true;
		}
		var encontro = false;
		for(var i=0; i<this.totalRegistros; i++){
			if((tipo==null && this.registros[i][campo].toUpperCase()==valor.toUpperCase()) || 
			(tipo=='like' && this.registros[i][campo].toUpperCase().indexOf(valor.toUpperCase())>-1)){
				this.indice = Math.floor(i/this.paginasDe);
				
				var registros = this.asignaPagina();
				this.objetoTabla.setJsonData(registros);
				this.ponerPaginado();
				
				if(seleccionar==true){
					encontro = true;
					this.objetoTabla.setSelectedByIndex(i-(this.indice*this.paginasDe));
					this.objetoTabla.setSelectedById(this.objetoTabla.attributes.nombre+"_id"+(i-(this.indice*this.paginasDe)));
					break;
				}else{
					encontro = true;
					this.registroSeleccionado = i-(this.indice*this.totalPaginas);
					return this.registroSeleccionado;
				}
			}
		}
		
		return encontro;
	}
	
	function actualizarRegistro(objeto,pagina,indice){
		if(objeto!=null && indice != null){
			if(pagina==null)
				pagina = this.indice;
			else
				pagina--;
			this.registros[parseInt((pagina)*this.paginasDe)+parseInt(indice)] = objeto;
			var registros = this.asignaPagina();
			this.objetoTabla.setJsonData(registros);
			this.ponerPaginado();
			return true;
		}
		return false;
	}
	
	function actualizarRegistroSinMostrar(objeto,pagina,indice){
		if(objeto!=null && indice != null){
			if(pagina==null)
				pagina = this.indice;
			else
				pagina--;
			this.registros[parseInt((pagina)*this.paginasDe)+parseInt(indice)] = objeto;
			return true;
		}
		return false;
	}
	
	function borrarRegistro(pagina,indice){
		if(indice != null){
			if(pagina==null)
				pagina = this.indice;
			else
				pagina--;
				
			delete this.registros[((pagina)*this.paginasDe)+parseInt(indice)];
			this.registros = this.registros.sort(this.funcionOrdenar);
			this.registros = this.registros.slice(0,this.registros.length-1);
			this.totalRegistros = this.registros.length;
			this.totalPaginas = Math.ceil(this.registros.length/this.paginasDe);
			var registros = this.asignaPagina();
			this.objetoTabla.setJsonData(registros);
			this.ponerPaginado();
			return true;
		}
		return false;
	}
	
	function agregarRegistro(objeto){
		if(objeto!=null){
			if(this.indice < 0)
				this.indice = 0;
			if(this.registros==null)
				this.registros = Array();
			this.registros[this.registros.length] = objeto;
			this.registros = this.registros.sort(this.funcionOrdenar);
			this.totalRegistros = this.registros.length;
			this.totalPaginas = Math.ceil(this.registros.length/this.paginasDe);
			var registros = this.asignaPagina();
			this.objetoTabla.setJsonData(registros);
			this.ponerPaginado();
			if(this.filtro!=null)
				this.filtrar(this.filtro);
			return true;
		}
		return false;
	}
	
	function limpiar(){
		this.indice = 0;
		this.registros = null;
		this.registrosFiltro = null;
		this.filtro = null;
		this.totalRegistros = 0;
		this.totalPaginas = 0;
		this.seleccionInicio = 0;
		this.seleccionFin = 0;
		if(this.objetoTabla != null){
			this.objetoTabla.clear();
		}
		if(this.objetoPaginador != null){
			this.objetoPaginador.innerHTML = "";
		}
	}
	
	function refrescar(){
		var registros = this.asignaPagina();
		this.objetoTabla.setJsonData(registros);
		this.ponerPaginado();
	}
	
	function buscar(valor,campo){
		var valorinicio = 0;
		var valorfin = 0;
		var encontro = false;
		for(var i=0; i<this.totalPaginas; i++){
			if(this.totalPaginas-1 == i){
				valorfin = this.registros[this.totalRegistros-1][campo];
			}else{
				valorfin = this.registros[(((i+1)*this.paginasDe)-1)][campo]
			}
			valorinicio = this.registros[i*this.paginasDe][campo];
			
			var seleccionar = false;
			if(valor.toUpperCase() >= valorinicio.toUpperCase() && valor.toUpperCase() <= valorfin.toUpperCase()){
				this.indice = i;
				var finPagina = 0;
				if(this.indice+1==this.totalPaginas)
					finPagina = (this.indice*this.paginasDe)+((this.totalRegistros)-((this.indice)*this.paginasDe));
				else
					finPagina = (this.paginasDe)+(this.indice*this.paginasDe);
					
				for(var j=this.indice*this.paginasDe; j<finPagina; j++){
					if(this.registros[j][campo].toUpperCase()==valor.toUpperCase()){
						return true;						
					}
				}
				break;
			}
		}
		return false;
	}
}