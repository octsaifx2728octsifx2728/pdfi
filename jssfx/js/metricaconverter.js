var metricaconverter={
    locked:false,
    equivalencias:{
        metros:1,
        pies:0.09290304,
        acres:4046.856
        },
    solveAllPage:function(seleccionado,index,current){
        if(metricaconverter.locked){
        var script=document.createElement("script");
       script.src="api/enviroment/setEnviroment?filters=1&filtername=metrica&filtervalue="+encodeURI(seleccionado);
                enviroment.metrica=seleccionado;
                    console.log(script.src);
                  document.getElementsByTagName("head")[0].appendChild(script);
            return false;
        }
        if(typeof(seleccionado)==="undefined"){
            seleccionado="metros";
        }
        if(typeof(resultados)==="object"){
            for(i in resultados){
                switch(resultados[i].casa){
                    case "5":
                        var preciom2=$("#search_inmueble_result_"+resultados[i].id).find(".preciom2").text().replace(/\s+/g, "");
                        var m2s=$("#search_inmueble_result_"+resultados[i].id).find(".superficie").text().replace(/\s+/g, "");
                        var np=resultados[i]["preciom2"+seleccionado];
                        $("#search_inmueble_result_"+resultados[i].id).find(".preciom2").html($("#search_inmueble_result_"+resultados[i].id).find(".preciom2").html().replace(preciom2,np));
                       $("#search_inmueble_result_"+resultados[i].id).find(".superficie").html($("#search_inmueble_result_"+resultados[i].id).find(".superficie").html().replace(m2s,resultados[i]["m2s-"+seleccionado]));
                       break;
                    default:
                        var preciom2=$("#search_inmueble_result_"+resultados[i].id).find(".preciom2").text().replace(/\s+/g, "");
                        var m2=$("#search_inmueble_result_"+resultados[i].id).find(".m2").text().replace(/\s+/g, "");
                        var m2s=$("#search_inmueble_result_"+resultados[i].id).find(".superficie").text().replace(/\s+/g, "");
                        var np=resultados[i]["preciom2"+seleccionado];
                        if($("#search_inmueble_result_"+resultados[i].id).find(".preciom2").html()){
                        $("#search_inmueble_result_"+resultados[i].id).find(".preciom2").html($("#search_inmueble_result_"+resultados[i].id).find(".preciom2").html().replace(preciom2,np));
                        $("#search_inmueble_result_"+resultados[i].id).find(".m2").html($("#search_inmueble_result_"+resultados[i].id).find(".m2").html().replace(m2,resultados[i]["m2-"+seleccionado]));
                        $("#search_inmueble_result_"+resultados[i].id).find(".superficie").html($("#search_inmueble_result_"+resultados[i].id).find(".superficie").html().replace(m2s,resultados[i]["m2s-"+seleccionado]));
                        }
                }
            }
        }
        if(typeof(resultados_usuario)==="object"){
            for(i in resultados_usuario){
                var preciom2=$(".item_"+resultados_usuario[i].id).find(".preciom2").text().replace(/\s+/g, "");
                var m2=$(".item_"+resultados_usuario[i].id).find(".m2").text().replace(/\s+/g, "");
                var m2s=$(".item_"+resultados_usuario[i].id).find(".superficie").text().replace(/\s+/g, "");
                var np=resultados_usuario[i]["preciom2"+seleccionado];
                if($(".item_"+resultados_usuario[i].id).find(".preciom2").html()){
                    $(".item_"+resultados_usuario[i].id).find(".preciom2").html($(".item_"+resultados_usuario[i].id).find(".preciom2").html().replace(preciom2,np));
                    $(".item_"+resultados_usuario[i].id).find(".m2").html($(".item_"+resultados_usuario[i].id).find(".m2").html().replace(m2,resultados_usuario[i]["m2-"+seleccionado]));
                    $(".item_"+resultados_usuario[i].id).find(".superficie").html($(".item_"+resultados_usuario[i].id).find(".superficie").html().replace(m2s,resultados_usuario[i]["m2s-"+seleccionado]));
                }
            }
        }
        if(typeof(inmueble)==="object"){
                switch(inmueble.tipoobjeto){
                    case "5":
                        var preciom2=$(".result").find(".preciom2>.converPrice").text().replace(/\s+/g, "");
                        var m2s=$(".result").find(".superficie").text().replace(/\s+/g, "");
                        $(".result").find(".preciom2>.converPrice").html($(".result").find(".preciom2>.converPrice").html().replace(preciom2,inmueble["preciom2"+seleccionado]));
                        $(".result").find(".superficie").html($(".result").find(".superficie").html().replace(m2s,inmueble["m2s-"+seleccionado]));
                        break;
                    default:
                        var preciom2=$(".result").find(".preciom2>.converPrice").text().replace(/\s+/g, "");
                        var m2=$(".result").find(".m2").text().replace(/\s+/g, "");
                        var m2s=$(".result").find(".superficie").text().replace(/\s+/g, "");
                        if($(".result").find(".preciom2>.converPrice").html()){
                            $(".result").find(".preciom2>.converPrice").html($(".result").find(".preciom2>.converPrice").html().replace(preciom2,inmueble["preciom2"+seleccionado]));
                            $(".result").find(".m2").html($(".result").find(".m2").html().replace(m2,inmueble["m2-"+seleccionado]));
                            $(".result").find(".superficie").html($(".result").find(".superficie").html().replace(m2s,inmueble["m2s-"+seleccionado]));
                            }
                        break;
                }

        }
        $(".filter_m21").find(".titulo").empty();
        $(".filter_m2s1").find(".titulo").empty();
        $(".filter_m21").find(".titulo").append(enviroment.translations["metrica_"+seleccionado+"2"]);
        $(".filter_m2s1").find(".titulo").append(enviroment.translations["metrica_"+seleccionado+""]);
        
        $(".parsedResults").find(".result").removeClass("metrica_pies");
        $(".parsedResults").find(".result").removeClass("metrica_metros");
        $(".parsedResults").find(".result").addClass("metrica_"+seleccionado);
        
        $(".detalles_inmueble").find(".result").find(".info").removeClass("metrica_pies");
        $(".detalles_inmueble").find(".result").find(".info").removeClass("metrica_metros");
        $(".detalles_inmueble").find(".result").find(".info").addClass("metrica_"+seleccionado);
        
        $(".inmueblesUsuario").find(".item").removeClass("metrica_pies");
        $(".inmueblesUsuario").find(".item").removeClass("metrica_metros");
         $(".inmueblesUsuario").find(".item").addClass("metrica_"+seleccionado);
         
         
        $(".parsedResults").find(".result").find(".preciom2").attr("title",enviroment.translations["precio_"+seleccionado+"2"]);
        $(".parsedResults").find(".result").find(".m2").attr("title",enviroment.translations[""+seleccionado+"2"]);
        $(".detalles_inmueble").find(".info").find(".preciom2").attr("title",enviroment.translations["precio_"+seleccionado+"2"]);
        $(".detalles_inmueble").find(".info").find(".m2").attr("title",enviroment.translations[""+seleccionado+"2"]);
        $(".inmueblesUsuario").find(".item").find(".preciom2").attr("title",enviroment.translations["precio_"+seleccionado+"2"]);
        $(".inmueblesUsuario").find(".item").find(".m2").attr("title",enviroment.translations[""+seleccionado+"2"]);
        if((typeof nuevosfiltros_c !== 'undefined')&&nuevosfiltros_c.ddbb[0]&&nuevosfiltros_c.ddbb[0].ddbb){
            switch(seleccionado){
                case "pies":
                    var m2max=10000;
                    var m2smax=100000;
                   // $(nuevosfiltros_c.ddbb[0].ddbb.m2s1).slider("value",100000);
                    
                    
                    break;
                default:
                    var m2max=1000;
                    var m2smax=10000;
            }
            
                    
                    var m2cval=$(nuevosfiltros_c.ddbb[0].ddbb.m21).slider("option", "values" );
                    var m2cmax=$(nuevosfiltros_c.ddbb[0].ddbb.m21).slider("option","max");
                    $(nuevosfiltros_c.ddbb[0].ddbb.m21).slider("option","max",m2max);
                    
                    var m2scval=$(nuevosfiltros_c.ddbb[0].ddbb.m2s1).slider("option", "values" );
                    var m2scmax=$(nuevosfiltros_c.ddbb[0].ddbb.m2s1).slider("option","max");
                    $(nuevosfiltros_c.ddbb[0].ddbb.m2s1).slider("option","max",m2smax);
                    
                    if(m2cval[1]==0||m2cval[1]>=m2cmax||m2cval[1]>m2max){
                        $(nuevosfiltros_c.ddbb[0].ddbb.m21).slider("option", "values" ,[0,m2max]);
                        $(".searchfilter").find(".filter_m21").find(".val1").text(m2max.toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( "," ));
                    }
                    if(m2scval[1]==0||m2scval[1]>=m2scmax||m2scval[1]>m2smax){
                        $(nuevosfiltros_c.ddbb[0].ddbb.m2s1).slider("option", "values" ,[0,m2smax]);
                        $(".searchfilter").find(".filter_m2s1").find(".val1").text(m2smax.toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( "," ));
                    }
                    
            $(nuevosfiltros_c.ddbb[0].ddbb.m21).trigger("slidechange");
        }
        var script=document.createElement("script");
       script.src="api/enviroment/setEnviroment?filters=1&filtername=metrica&filtervalue="+encodeURI(seleccionado);
                enviroment.metrica=seleccionado;
                    console.log(script.src);
                  document.getElementsByTagName("head")[0].appendChild(script);
    }
}