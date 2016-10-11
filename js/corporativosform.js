var corporativosform_c={
	pay:function(id){
            var method=$("#_corporativosform").find(".formadepago>input:checked").val();
		var url="/api/payment/procesar?id="+id+"&tipopago="+method+"&callback=corporativosform_c.payhandler&idcallback="+id;
		var script=document.createElement("script");
		script.type="text/javascript";
		script.src=url;
		wait(true);
		document.getElementsByTagName("head")[0].appendChild(script);
	},
	payhandler:function(respuesta){
		if(respuesta.follow){
			location.href=respuesta.follow
		}
		else {
			console.log(respuesta);
			wait(false);
		}
	}
}
