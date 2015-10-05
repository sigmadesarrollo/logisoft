
function ClaseNavegador(){
	this.asignarContenidos = asignarContenidos;
	this.crearNavegador = crearNavegador;
	this.agregarDireccion = agregarDireccion;
	
	function asignarContenidos(atributos){
		this.seleccionado = 0;
		this.atributos = atributos;
	}
	
	function crearNavegador(){
		this.arreDatos = Array();
		var objeto = this.atributos;
		this.indice = 0;
		var tr = document.getElementById(objeto.fila);
		var td = tr.insertCell(tr.cells.length);
		td.innerHTML = "PRINCIPAL";
		var td = tr.insertCell(tr.cells.length);
		td.innerHTML = "->";
	}
	
	function agregarDireccion(indice){
		var ind = null;
		for(var i=0; i<this.indice; i++){
			if(this.arreDatos[i]==indice){
				ind = i;
				break;
			}
		}
		if(ind<this.indice && ind!=null){
			var cant = borrarEtiquetas(indice,this.indice);
			this.indice -= cant;
		}else if(ind>this.indice || this.ind==null){
			var objeto = this.atributos;
			var tr = document.getElementById(objeto.fila);
			var td = tr.insertCell(tr.cells.length);
			td.setAttribute('id',"nn"+this.indice);
			td.innerHTML = objeto.contenidos[indice].nombre;
			var td = tr.insertCell(tr.cells.length);
			td.setAttribute('id',"nm"+this.indice);
			td.innerHTML = "->";
			this.arreDatos[this.indice]=indice;
			this.indice++;
		}
	}
	
	function borrarEtiquetas(indice,maximoi){
		var ix = parseInt(indice)+1;
		var cont = 0;
		for(var i=ix; i<maximoi; i++){
			document.getElementById('nn'+i).parentNode.removeChild(document.getElementById('nn'+i));
			document.getElementById('nm'+i).parentNode.removeChild(document.getElementById('nm'+i));
			cont++;
		}
		return cont;
	}
}