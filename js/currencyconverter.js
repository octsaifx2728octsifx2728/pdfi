var currencyconverter={
    currency:null,
    currency_default:null,
    cache:{},
    pendientes:{},
    init:function(){
      if(currencyconverter.currency!=currencyconverter.currency_default){
	currencyconverter.solveAllPage(currencyconverter.currency);
      }
    },
    muere:function(){
        },
CallBack:function(respuesta,id){
 if(respuesta.error==0&&currencyconverter.pendientes[id]){
    if(id=="precio_filter"){
        $(nuevosfiltros_c.ddbb[0].ddbb.precio).slider("option","max",respuesta.valorraw);
        $(nuevosfiltros_c.ddbb[0].ddbb.precio).slider("option","values",[0,respuesta.valorraw]);
        $(".searchfilter").find(".filter_precio").find(".val1").text(Math.round(respuesta.valorraw).toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( "," ));
        return false;
    }
    $(currencyconverter.pendientes[id]).text(respuesta.valor);
    $(currencyconverter.pendientes[id]).parents(".moneda45convert").each(function(){
        this.className=this.className.replace(/moneda45_[a-z][a-z]/i,"")+" moneda45_"+respuesta.moneda;
    });
    $(currencyconverter.pendientes[id]).parents(".moneda30convert").each(function(){
        this.className=this.className.replace(/moneda30_[a-z][a-z]/i,"")+" moneda30_"+respuesta.moneda;
    });
    $(currencyconverter.pendientes[id].parentNode).find(".converDivisa").empty();
    $(currencyconverter.pendientes[id].parentNode).find(".converDivisa").append("<img src='img/icons/monedas/"+respuesta.moneda+".jpg'>");
    $(currencyconverter.pendientes[id]).removeClass(id);
    if($(currencyconverter.pendientes[id]).parents(".precio").get().length){
        var id2=$(currencyconverter.pendientes[id]).parents(".result").get()[0];
            var key="result";
        if(typeof(id2)==="undefined"){
            key="item";
            var id2=$(currencyconverter.pendientes[id]).parents(".item").get()[0];
        }
        if(typeof(id2)!="undefined"){
            id2=currencyconverter.parseClassString(id2.className);
            id2=id2[key]+"_"+id2.user+"_"+id2.tipo;
            if(typeof(resultados)==="object"){
                for(i in resultados){
                    if(resultados[i].id==id2){
                        resultados[i].precio=respuesta.valor;
                        resultados[i]["precio-raw"]=respuesta.valorraw;
                        resultados[i]["preciom2"]=(Math.round((respuesta.valorraw/(resultados[i]["m2"].replace(",","")>0?resultados[i]["m2"].replace(",",""):resultados[i]["superficie"].replace(",",""))))).toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( "," );
                        resultados[i]["preciom2metros"]=(Math.round((respuesta.valorraw/(resultados[i]["m2-metros"].replace(",","")>0?resultados[i]["m2-metros"].replace(",",""):resultados[i]["m2s-metros"].replace(",",""))))).toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( "," );
                        resultados[i]["preciom2pies"]=(Math.round((respuesta.valorraw/(resultados[i]["m2-pies"].replace(",","")>0?resultados[i]["m2-pies"].replace(",",""):resultados[i]["m2s-pies"].replace(",",""))))).toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( "," );
                       
                       if($("#search_inmueble_result_"+id2).find(".preciom2").html()){
                             var ddd=$("#search_inmueble_result_"+id2).find(".preciom2").text().replace(/\s+/g, "");
                            $("#search_inmueble_result_"+id2).find(".preciom2").html($("#search_inmueble_result_"+id2).find(".preciom2").html().replace(ddd,resultados[i]["preciom2"+enviroment.metrica]));
                       }
                           
                    }
                }
            }
            if(typeof(resultados_usuario)==="object"){
                for(i in resultados_usuario){
                    if(resultados_usuario[i].id==id2){
                        resultados_usuario[i].precio=respuesta.valor;
                        resultados_usuario[i]["precio-raw"]=respuesta.valorraw;
                        resultados_usuario[i]["preciom2"]=(Math.round((respuesta.valorraw/(resultados_usuario[i]["M2"].replace(",","")>0?resultados_usuario[i]["M2"].replace(",",""):resultados_usuario[i]["SUPERFICIE"].replace(",",""))))).toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( "," );
                        resultados_usuario[i]["preciom2metros"]=(Math.round((respuesta.valorraw/(resultados_usuario[i]["m2-metros"].replace(",","")>0?resultados_usuario[i]["m2-metros"].replace(",",""):resultados_usuario[i]["m2s-metros"].replace(",",""))))).toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( "," );
                        resultados_usuario[i]["preciom2pies"]=(Math.round((respuesta.valorraw/(resultados_usuario[i]["m2-pies"].replace(",","")>0?resultados_usuario[i]["m2-pies"].replace(",",""):resultados_usuario[i]["m2s-pies"].replace(",",""))))).toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( "," );
                       
                       if($(".item_"+resultados_usuario[i].id).find(".preciom2").html()){
                             var ddd=$(".item_"+resultados_usuario[i].id).find(".preciom2").text().replace(/\s+/g, "");
                            
                            $(".item_"+resultados_usuario[i].id).find(".preciom2").find("converPrice").text(resultados_usuario[i]["preciom2"+enviroment.metrica]);
                       }
                           
                    }
                }
            }
            if(typeof(inmueble)==="object"){
                        inmueble.precio=respuesta.valor;
                        inmueble["precio-raw"]=respuesta.valorraw;
                        inmueble["preciom2"]=(Math.round((respuesta.valorraw/(inmueble["m2"].replace(",","")>0?inmueble["m2"].replace(",",""):inmueble["superficie"].replace(",",""))))).toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( "," );
                        inmueble["preciom2metros"]=(Math.round((respuesta.valorraw/(inmueble["m2-metros"].replace(",","")>0?inmueble["m2-metros"].replace(",",""):inmueble["m2s-metros"].replace(",",""))))).toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( "," );
                        inmueble["preciom2pies"]=(Math.round((respuesta.valorraw/(inmueble["m2-pies"].replace(",","")>0?inmueble["m2-pies"].replace(",",""):inmueble["m2s-pies"].replace(",",""))))).toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( "," );
                       
                             var ddd=$(".result").find(".preciom2>.converPrice").text().replace(/\s+/g, "");
                             if($(".result").find(".preciom2>.converPrice").html()){
                                $(".result").find(".preciom2>.converPrice").html($(".result").find(".preciom2>.converPrice").html().replace(ddd,inmueble["preciom2"+enviroment.metrica]));
                             }
                           
                   
                
            }
        }
    };
    currencyconverter.pendientes[id]=null;
    }
    else {
        $(currencyconverter.pendientes[id]).removeClass(id);
            currencyconverter.pendientes[id]=null;

        }
},
    solveAllPage:function(moneda,index){
        $(".converPrice").each(function(x){
            var valor=($(this).text().replace(/[^\d.]/g, ""));
            var key="precio_"+valor.replace(".","_")+"_"+moneda+"_"+x;
                $(this).addClass(key);
                currencyconverter.pendientes[key]=this;
                var script=document.createElement("script");
                script.src="api/currencyconverter/convert2?valor="+valor+"&monedaOrig="+enviroment.currency+"&moneda="+moneda+"&callback=currencyconverter.CallBack&idcallback="+key;
                script.type="text/javascript";
                document.getElementsByTagName("head")[0].appendChild(script);
             
            });
        var script=document.createElement("script");
            script.src="api/currencyconverter/setDefaultCurrency?app=currencyconverter&task=setDefaultCurrency&currency="+moneda+"&callback=currencyconverter.muere&&idcallback="+Math.random()+"--"+Math.random();
            script.type="text/javascript";
            document.getElementsByTagName("head")[0].appendChild(script);
            $(".currencyconverter>select").prop("selectedIndex",index);
            $(".currencyconverter>select").val(moneda);
      for(var i=0;i<enviroment.currencyconvertercallback.length;i++){
          if(enviroment.currencyconvertercallback[i]&&enviroment.currencyconvertercallback[i].currencyconverter){
              enviroment.currencyconvertercallback[i].currencyconverter(moneda);
          }
      }
            
        if(typeof(nuevosfiltros_c)!="undefined"){
                var script=document.createElement("script");
                script.src="api/currencyconverter/convert2?valor="+$(nuevosfiltros_c.ddbb[0].ddbb.precio).slider("option", "max" )+"&monedaOrig="+enviroment.currency+"&moneda="+moneda+"&callback=currencyconverter.CallBack&idcallback="+"precio_filter";
                script.type="text/javascript";
                document.getElementsByTagName("head")[0].appendChild(script);
                console.log(script.src);
            currencyconverter.pendientes["precio_filter"]=nuevosfiltros_c.ddbb[0].ddbb.precio;
        }
            enviroment.currency=moneda;
            $(".filter_precio").find(".precio_divisa>span").attr("class","moneda_"+moneda+"");
      //alert(script.src);
      
    },
    parseAllPage:function(result,id){
      $("#search_inmueble_result_"+id).find(".precio_data").empty();
      $("#search_inmueble_result_"+id).find(".precio_data").append("<img src='img/icons/monedas/"+result.resultado.precio+".jpg'>");
      wait(false);
    },
    parseClassString:function(cs){
      cs=cs.split(" ");
      var cs2={};
      for(var i=0; i<cs.length;i++){
        cs[i]=cs[i].split("_",2);
        cs2[cs[i][0]]=cs[i].length>2?currencyconverter.parseClassString(cs[i][1]):cs[i][1];
        
        }
      return cs2;
      }
};