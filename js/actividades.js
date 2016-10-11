var actividades_interface={
	getLast:function(){
		var script=document.createElement("script");
		script.type="text/javascript";
		script.src="api/actividades/get/?index="+this.params.lastid+"&callback=actividades_interface.getLastProxy&idcallback="+this.id;
		//document.getElementsByTagName("head")[0].appendChild(script);
                console.log(script.src);
	},
	getLastProxy:function(resultado,id){
		if(actividades_DDBB[id]){
			actividades_DDBB[id].getLastHandler(resultado);
		}
	},
	getLastHandler:function(resultado){
		if(resultado.error==0&&resultado.resultado.length){
			for(var i=0;i<resultado.resultado.length;i++){
				var div=document.createElement("div");
				var user=document.createElement("div");
				var avatar=document.createElement("div");
				var avatar_a=document.createElement("a");
				var avatar_a_img=document.createElement("img");
				var mensaje=document.createElement("div");
				var link=document.createElement("a");
				div.className="actividad actividad_"+resultado.resultado[i].id+" nivel_"+resultado.resultado[i].id;
				user.className="usuario";
				mensaje.className="mensaje";
				avatar.className="avatar";
				avatar_a.href=resultado.resultado[i].userlink;
				avatar_a_img.src=resultado.resultado[i].avatar;
				link.href=resultado.resultado[i].link;
				avatar_a.appendChild(avatar_a_img);
				avatar.appendChild(avatar_a);
				user.appendChild(avatar);
				div.appendChild(user);
				user.appendChild(document.createTextNode(resultado.resultado[i].nombre_pant));
				div.appendChild(mensaje);
				mensaje.appendChild(link);
				link.appendChild(document.createTextNode(resultado.resultado[i].actividad));
				this.params.contenedor.insertBefore(div,this.params.contenedor.firstChild);
				this.params.lastid=resultado.resultado[i].id;
			}
		}
	},
	getFirstHandler:function(resultado){
		if(resultado.error==0&&resultado.resultado.length){
			for(var i=resultado.resultado.length-1;i>=0;i--){
				var div=document.createElement("div");
				var user=document.createElement("div");
				var mensaje=document.createElement("div");
				var link=document.createElement("a");
				var avatar=document.createElement("div");
				var avatar_a=document.createElement("a");
				var avatar_a_img=document.createElement("img");
				div.className="actividad actividad_"+resultado.resultado[i].id+" nivel_"+resultado.resultado[i].id;
				user.className="usuario";
				mensaje.className="mensaje";
				avatar.className="avatar";
				avatar_a.href=resultado.resultado[i].userlink;
				avatar_a_img.src=resultado.resultado[i].avatar;
				link.href=resultado.resultado[i].link;
				avatar_a.appendChild(avatar_a_img);
				avatar.appendChild(avatar_a);
				user.appendChild(avatar);
				div.appendChild(user);
				user.appendChild(document.createTextNode(resultado.resultado[i].nombre_pant));
				div.appendChild(mensaje);
				mensaje.appendChild(link);
				link.appendChild(document.createTextNode(resultado.resultado[i].actividad));
				this.params.contenedor.appendChild(div);
				this.params.firstid=resultado.resultado[i].id;
			}
		}
	},
	scrollEvent:function(e){
		if(($(this).find(".wrap").height()-this.scrollTop-$(this).height())<5){
			actividades_interface.updateScrollProxy(this.id);
		}
	},
	updateScrollProxy:function(id){
		id=id.replace("_____","");
		if(actividades_DDBB[id]){
			actividades_DDBB[id].getFirst();
		}
	},
	getFirst:function(){
		var index=this.params.firstid-5;
		index=index>=0?index:0;
		var max=this.params.firstid-index-1;
		var script=document.createElement("script");
		script.type="text/javascript";
		script.src="api/actividades/get/?index="+index+"&callback=actividades_interface.getFirstProxy&idcallback="+this.id+"&max="+max;
		//document.getElementsByTagName("head")[0].appendChild(script);
		
	},
	getFirstProxy:function(resultado,id){
		if(actividades_DDBB[id]){
			actividades_DDBB[id].getFirstHandler(resultado);
		}
	}
}
var actividades_DDBB=[];
function actividades(params){
	this.params=params;
	this.id=params.id;
	actividades_DDBB[this.id]=this;
	this.getLast=actividades_interface.getLast;
	this.getLastHandler=actividades_interface.getLastHandler;
	this.getFirstHandler=actividades_interface.getFirstHandler;
	this.getFirst=actividades_interface.getFirst;
	setInterval("actividades_DDBB['"+this.id+"'].getLast()",5000);
	this.params.contenedor.parentNode.id="_____"+this.id;
	$(this.params.contenedor.parentNode).scroll(actividades_interface.scrollEvent);
}
