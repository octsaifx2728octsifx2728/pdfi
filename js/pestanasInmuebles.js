var pestanasInmuebles_c={
	contenedores:[".inmuebles_propios",".inmuebles_favoritos",".buzon_mensajes"],
	pestanas:[".pestanasinmuebles>.option1",".pestanasinmuebles>.option2",".pestanasinmuebles>.option3"],
	show:function(key){
		for(var i=0;i<pestanasInmuebles_c.contenedores.length;i++){
			$(pestanasInmuebles_c.contenedores[i]).css("display","none");
		}
		$(".pestanasinmuebles>.option").removeClass("active");
		$(pestanasInmuebles_c.pestanas[key]).addClass("active");
		$(pestanasInmuebles_c.contenedores[key]).css("display","block");
	}
}
