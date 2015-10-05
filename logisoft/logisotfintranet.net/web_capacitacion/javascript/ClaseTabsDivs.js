// JavaScript Document
function ClaseTabs(){
		this.iniciar 		= iniciar;
		this.agregarTabs 	= agregarTabs;
		this.seleccionar	= seleccionar;
		this.agregarIframe	= agregarIframe;
		this.posicionIzq	= posicionIzq;
		this.posicionArr	= posicionArr;
		this.moverDerecha	= moverDerecha;
		this.moverIzquierda	= moverIzquierda;
		this.mover			= mover;
		this.detener		= detener;
		this.moverManual	= moverManual;
		
		function iniciar(atributos){
			this.atributos 			= atributos;
			this.tabulador 			= 0;
			this.tabSeleccionado 	= 0;
			this.rutaImagen			= this.atributos.imagenes;
			this.posx				= 0;
			this.posy				= 0;
			this.x					= 0;
			this.moviendo			= false;
			this.titulo				= this.atributos.titulo;
			this.obj = this.atributos.nombre + "Object";
			eval(this.obj + " = this");
			//iniciar los tabs
			var objeto 	= this.atributos;
			//generando celdaa para el div contenedor
			document.getElementById(objeto.nombre).width = objeto.largo+"px";
			var tabla 	= document.getElementById(objeto.nombre);
			var fila 	= tabla.insertRow(tabla.rows.length);
			var celda 	= fila.insertCell(fila.cells.length);
			celda.style.width="18px";
			celda.innerHTML = "<img id='btnMovIzq' src='"+this.rutaImagen+"/mini_izquierda.gif' style='width:19px; height:18px; cursor:pointer' "+
			" onmouseover = '"+this.obj+".mover(-7)' onmouseout='"+this.obj+".detener()'/>";
			//insertando el div
			
			this.posx = this.posicionIzq(document.getElementById('btnMovIzq'));
			this.posy = this.posicionArr(document.getElementById('btnMovIzq'));
			this.posx += 18;
			//alert(posx+" - "+posy);
			
			this.posx += (objeto.ajustex>0)?objeto.ajustex:0;
			this.posy += (objeto.ajustey>0)?objeto.ajustey:0;
			
			var celda 	= fila.insertCell(fila.cells.length);
			celda.style.width=(objeto.largo-38)+"px";
			celda.innerHTML = "<div style='overflow:hidden; width:"+(objeto.largo-38)+
			"px; height:26px; top:"+this.posy+"px; left:"+this.posx+"px; position:absolute'>"+
			"<div id="+objeto.nombre+"_divcontenedor style='top:0px; left:0px; position:absolute'>"+
			"<table id="+objeto.nombre+"_contenedor cellpading=0 cellspacing=0 border=0><tr id="+objeto.nombre+"_filatabs><td id="+
			objeto.nombre+"_contenedor_id0 class=tab_seleccionado2 align='center' onclick='"+
			this.obj+".seleccionar(0)'>"+((this.titulo==undefined)?'Principal':this.titulo)+"</td></tr></table></div></div>";
			
			//insertar delda movimiento
			var celda 	= fila.insertCell(fila.cells.length);
			celda.style.width="18px";
			celda.innerHTML = "<img id='btnMovDer' src='"+this.rutaImagen+"/mini_derecha.gif' style='width:19px; height:18px; cursor:pointer' "+
			" onmouseover = '"+this.obj+".mover(7)' onmouseout='"+this.obj+".detener()' />";
			this.agregarIframe(this.tabulador,this.atributos.paginainicial);
			this.tabulador = 1;
		}
		
		function agregarIframe(indice,pagina){
			var capa = document.getElementById(this.atributos.nombre+"_tab_id"+indice); 
			with(capa.style){
				position = "absolute";
				top = "0px";
				left = "0px";
				height = this.atributos.alto+"px";
				width = this.atributos.largo+"px";        
				left = this.posx-19;
				top = this.posy+26;
				zIndex = "50";        
			}
		}
		
		function posicionIzq(Anchor){
			var left = 0;
			while(Anchor.tagName!='BODY'){ 
				   left+=Anchor.offsetLeft; 
				   Anchor=Anchor.offsetParent; 
				}
			return left; 
		}
		function posicionArr(Anchor){
			var top = 0;
			while(Anchor.tagName!='BODY'){ 
				   top+=Anchor.offsetTop; 
				   Anchor=Anchor.offsetParent; 
				}
			return top; 
		}
		
		function mover(valor){
			this.moviendo = true;
			if(valor>0)
				this.moverDerecha();
			else
				this.moverIzquierda();
		}
		function detener(){
			this.moviendo = false;
		}
		
		function agregarTabs(titulo, indice, pagina){
			var objeto 	= this.atributos;
			
			if(indice<this.tabulador){
				for (var i=indice; i<this.tabulador;i++){
					document.getElementById(objeto.nombre+"_filatabs").removeChild(document.getElementById(objeto.nombre+"_contenedor_id"+i));
					//document.body.removeChild(document.getElementById(objeto.nombre+"_tab_id"+i));
				}
				this.tabulador = indice;
			}
			
			//generando celdaa para el div contenedor
			var fila 	= document.getElementById(objeto.nombre+"_contenedor").rows[0];
			var celda 	= fila.insertCell(fila.cells.length);
			celda.id	= objeto.nombre+"_contenedor_id"+this.tabulador;
			celda.className = "tab_seleccionado2";
			celda.align 	= "center";
			celda.innerHTML = titulo;
			celda.onclick = Function(this.obj+".seleccionar("+this.tabulador+")");
			
			this.tabulador += 1;
			document.getElementById(objeto.nombre+"_contenedor").width = (122*this.tabulador)+"px";
			this.agregarIframe(indice,pagina);
			this.seleccionar(this.tabulador-1);
		}
		function seleccionar(tabs){
			var objeto = this.atributos
			for(var i=0; i<this.tabulador; i++){
				if(tabs==i){
					document.getElementById(objeto.nombre+"_contenedor_id"+i).className="tab_seleccionado2";
					document.getElementById(objeto.nombre+"_tab_id"+i).style.display="";
				}else{
					document.getElementById(objeto.nombre+"_contenedor_id"+i).className="tab_deseleccionado2";
					document.getElementById(objeto.nombre+"_tab_id"+i).style.display="none";
				}
			}
		}
		
		function moverDerecha(){
			var objeto 	= this.atributos;
			this.x += -10;
			
			var minvalor = ((this.tabulador*122)-(this.atributos.largo-38))*-1;
			
			if(this.x<minvalor){
				if(minvalor<0){
					this.x=minvalor;
					document.getElementById(this.atributos.nombre+"_divcontenedor").style.left = this.x;
				}
			}else{
				document.getElementById(this.atributos.nombre+"_divcontenedor").style.left = this.x;
				if(this.moviendo)
					setTimeout(this.obj+".moverDerecha()",50) 
			}
		}
		function moverIzquierda(){
			this.x += 10;
			if(this.x>0){
				this.x=0;
				document.getElementById(this.atributos.nombre+"_divcontenedor").style.left = this.x;
			}else{
				document.getElementById(this.atributos.nombre+"_divcontenedor").style.left = this.x;
				if(this.moviendo)
					setTimeout(this.obj+".moverIzquierda()",50) 
			}
		}
		
		function moverManual(cantidad){
			this.x = cantidad;
			document.getElementById(this.atributos.nombre+"_divcontenedor").style.left = this.x;
		}
	}