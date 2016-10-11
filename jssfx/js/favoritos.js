var favoritos_c={
	DDBB:{conexiones:[]},
	setFavorito:function(id_cliente,id_inmueble,tipo, origen){
		var status=$(origen).hasClass("fav");
		if(status){
			$(origen).removeClass("fav");
			favoritos_c.connect(id_cliente,id_inmueble,tipo,0);
			}
		else {
			$(origen).addClass("fav");
			favoritos_c.connect(id_cliente,id_inmueble,tipo,1);
			}
	},
	connect:function(id_cliente,id_inmueble,tipo,valor){
		var script=document.createElement("script");
		script.type="text/javascript";
		script.src="/api/favoritos/change?id_cliente="+id_cliente+"&id_inmueble="+id_inmueble+"&tipo="+tipo+"&valor="+valor+"&callback=favoritos_c.changeHandler";
		favoritos_c.DDBB.conexiones.push(script);
		document.getElementsByTagName("head")[0].appendChild(script);
                console.log(script.src);
	},
	changeHandler:function(){
		
	}
}
