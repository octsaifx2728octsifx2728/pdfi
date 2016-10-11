var mensaje={
	send:function(params){
		var formulario=$("#"+params.key);
		var id=params.id;
		
		var emailField=formulario.find("#"+params.key+"_email");
		var subjectField=formulario.find("#"+params.key+"_subject");
		var messageField=formulario.find("#"+params.key+"_message");
		
		var email=emailField.attr("value");
		var subject=subjectField.val();
		var message=messageField.val();
		
		if(!mensaje.validateSubject(subjectField, 30, 5)){
			return false;
		}
		if(!mensaje.validateSubject(messageField, 500, 1)){
			return false;
		}
		
		subjectField.val("");
		messageField.val("");
		
                wait(true);
		var script=document.createElement("script");
		script.type="text/javascript";
		script.src="api/message/send?id=0_"+id+"&email="+encodeURI(email)+"&subject="+encodeURI(subject)+"&message="+encodeURI(message)+"&callback=mensaje.sendHandler&idcallback="+params.key;
		document.getElementsByTagName("head")[0].appendChild(script);
                
		return true;
	},
	sendHandler:function(respuesta,id){
		if(respuesta.error&&respuesta.error!="0"){
                    alertSystem.alert(respuesta.errorDescription,1,$("#"+id).find(".alert"));
		}
                else{
                    alertSystem.alert(respuesta.errorDescription,5);
                    $.colorbox.close();
                }
                
                wait(false);
	},
	
	showDetails:function(id){
		if($(".item_"+id).get()[0]){
			var url="api/message/setLeido?id="+id;
			var script=document.createElement("script");
			script.type="text/javascript";
			script.src=url;
			document.getElementsByTagName("head")[0].appendChild(script);
			$(".item_"+id).removeClass("nuevo");
			$.colorbox({inline:true,href:"#details_mensaje_"+id});
			}
	},
	borrar:function(id){
		var url="api/message/borrar?id="+id+"&callback=mensaje.borrarHandler";
		var script=document.createElement("script");
		script.type="text/javascript";
		script.src=url;
		$.colorbox.close();
		wait(true);
		document.getElementsByTagName("head")[0].appendChild(script);
	},
	borrarHandler:function(){
		location.reload();
	},
        /**
         * @function mensaje.validateSubject
         * @param <input|textarea[type=text]> *campo
         * @param [<int> maximo]
         * @param [<int> minimo]
         * @sumary Valida *campo como una entrada de titulo de mensaje válido. Asigna a *campo la clase ok/error segun sea el caso.
         * @return <BOOL> valido
         */
	validateSubject:function(campo,max,min){
            var text=$(campo).val();
            min=min>0?min:5;
            $(campo).parent(".field").removeClass("error");
            $(campo).parent(".field").removeClass("ok");
            text=$.trim(text);
            if(text.length < min){
                $(campo).parent(".field").addClass("error");
                return false;
            }
            if(max>0&&text.length>max){
                text=text.substr(0, max);
                $(campo).val(text);
            }
            $(campo).parent(".field").addClass("ok");
            return true;
        },
        /**
         * @function mensaje.counter
         * @param <input|textarea[type=text]> *campo
         * @param [<int> maximo]
         * @param [<div|span|a> *counter]
         * @sumary Cuenta el numero de caracteres validos. Si se proporciona un counter este se actualizara con el valor "cuenta/maximo"
         * @return <int> cuenta
         */
        counter:function(campo,max,counter){
            var text=$(campo).val();
            text=$.trim(text);
            var cuenta=text.length;
            $(counter).text(cuenta+"/"+max);
            return cuenta;
        }
}
