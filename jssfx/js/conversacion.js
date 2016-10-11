
    var conversaciones_q=function($){
        $.fn.conversaciones=function(opciones){
            conversaciones_h.ddbb.push(this);
            this.payID=conversaciones_h.ddbb.length-1;
            this.opciones=opciones;
            this.init=conversaciones_h.init;
            this.abrirConversacion=conversaciones_h.abrirConversacion;
            this.receiveChunk=conversaciones_h.receiveChunk;
            this.contestar=conversaciones_h.contestar;
            this.contestarHandler=conversaciones_h.contestarHandler;
            this.init($);
            return this;
        };
    };
    var conversaciones_h={
        ddbb:[],
        init:function($){
            var payID=this.payID;
            $(this).find(".item").find(".mensaje").click(function(){
                conversaciones_h.ddbb[payID].abrirConversacion($(this).parent(".item").get()[0]);
            });
            $(this).find(".item").find(".sender").click(function(){
                conversaciones_h.ddbb[payID].abrirConversacion($(this).parent(".item").get()[0]);
            });
            $(this).find(".inm").find(".sender").click(function(){
                conversaciones_h.ddbb[payID].abrirConversacion($(this).parent(".item").get()[0]);
            });
        },
        abrirConversacion:function(item){
        if(!$(item).get().length){
            return false;
        }
            var payID=this.payID;
            $(this).find(".conversacionAbierta").removeClass("conversacionAbierta");
            $(this).find(".convcontainer").empty();
            $(item).addClass("conversacionAbierta");
            var opts=conversaciones_h.parseClassString(item.className);
            
            var url="/api/chunk/conversacion?options={\"id\":"+opts.item+"}&idcallback="+opts.item+"&callback=conversaciones_h.ddbb["+payID+"].receiveChunk";
            var script = document.createElement("script");
            script.src=url;
            console.log(script.src);
            document.getElementsByTagName("head")[0].appendChild(script);
            
        },
        receiveChunk:function(result,id){
            if(result.error=="0"){
            var payID=this.payID;
                var chunk=result.chunk;
                $(this).find(".item_"+id+">.convcontainer").append(chunk);
                $(this).find(".item_"+id+">.convcontainer").find("form").submit(function(){
                    conversaciones_h.ddbb[payID].contestar(this,id);
                    return false;
                });
                $(this).find(".item_"+id+"").removeClass("visto_0");
                $(this).find(".item_"+id+"").addClass("visto_1");
                $(this).find(".conversacion_container").scrollTop($(this).find(".conversacion_container>.w").height());
                
                $.get("/api/user/get",function(resp){
                    resp=eval(resp.replace("},'');","})"));
                    if(resp.error=="0"){
                        var numMensajes=resp.usuario.mensajes;
                        var id=resp.usuario.id;
                        $(".user_"+id).find(".stats").find(".stat_mensajes>.value").text(numMensajes);
                        if((numMensajes*1)<1){
                         $(".profblock").find(".avatar").removeClass("unreadmessages_1");
                         $(".profblock").find(".avatar").addClass("unreadmessages_0");
                        }
                    }
                });
            }
        
        },
        contestar:function(formulario,conID){
            var payID=this.payID;
            var texto=$(formulario).find("textarea").val();
            var id=$(formulario).find("input[name=id]").val();
            if(texto.length<1){
                return false;
            }
            wait(true);
            var url="api/message/send?conversacion="+conID+"&id="+id+"&email=&subject=&message="+encodeURI(texto)+
                    "&callback=conversaciones_h.ddbb["+payID+"].contestarHandler&idcallback="+conID;
            var script = document.createElement("script");
            script.src=url;
            document.getElementsByTagName("head")[0].appendChild(script);
            
        },
        contestarHandler:function(respuesta,id){
            if(respuesta.error=="0"){
                var payID=this.payID;
                conversaciones_h.ddbb[payID].abrirConversacion($(this).find(".w>.item_"+id).get()[0]);
            }
            wait(false);
        },
    parseClassString:function(cs){
      cs=cs.split(" ");
      var cs2={};
      for(var i=0; i<cs.length;i++){
        cs[i]=cs[i].split("_",2);
        cs2[cs[i][0]]=cs[i].length>2?conversaciones_h.parseClassString(cs[i][1]):cs[i][1];
        
        }
      return cs2;
      }
    };
    conversaciones_q(jQuery);
    
    $(document).ready(function(){
        var hash=location.hash;
        hash=hash.split(":",2);
        if(hash[0]=="#conversacion"){
            var id=hash[1];
            pestanasInmuebles_c.show(2);
            conversaciones_h.ddbb[0].abrirConversacion($(conversaciones_h.ddbb[0]).find(".item_"+id).get()[0]);
        }
    });