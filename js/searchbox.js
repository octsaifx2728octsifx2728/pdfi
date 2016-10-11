var searchbox_q=function($){
    $.fn.searchbox=function(opciones){
           searchbox_c.ddbb.push(this);
          this.payID=searchbox_c.ddbb.length-1;
          this.init=searchbox_c.init;
          this.search=searchbox_c.search;
          this.parseResults=searchbox_c.parseResults;
          this.results=searchbox_c.results;
          this.fireFirst=searchbox_c.fireFirst;
          this.init();
           return this;
        }
    }

var searchbox_c={
    ddbb:[],
    results:[],
    init:function(){
        this.lastkey=0;
        var payID=this.payID;
        this.mapCanvas=document.createElement("div");
        this.map = new google.maps.Map(this.mapCanvas,{
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoom: 15,
            center: new google.maps.LatLng(-34.397, 150.644)});
        this.service=new google.maps.places.PlacesService(this.map);
        

        $(this).find(".box").keyup(function(e){
            if (e.which == 13&&searchbox_c.ddbb[payID].results.length) {
                    searchbox_c.ddbb[payID].fireFirst();
                  }
            var d=new Date();
            if(this.value.length<1){
                if(this.timeout)clearTimeout(this.timeout);
                searchbox_c.ddbb[payID].parseResults([])
                return false;
                }
            if(this.timeout)clearTimeout(this.timeout);
            this.timeout=setTimeout("searchbox_c.ddbb["+payID+"].search('"+this.value+"')",1000);
            });
        },
    search:function(cadena){
        var request={
            query:cadena
            }
        var payID=this.payID;
        this.service.textSearch(request, function(respuesta,status){
            searchbox_c.ddbb[payID].parseResults(respuesta);
            });
        },
        parseResults:function(resultados){
            this.results=resultados;
            $(this).find(".resultados").empty();
            for(var i=0; i<resultados.length; i++){
                if(resultados[i].geometry&&resultados[i].geometry.viewport){
                    $(this).find(".resultados").append(
                        '<div class="resultado resultado_'+i+'">'+
                        '<a href="app/searchbounds/'+
                            resultados[i].geometry.viewport.Z.d+":"+
                            resultados[i].geometry.viewport.ca.d+":"+
                            resultados[i].geometry.viewport.Z.b+":"+
                            resultados[i].geometry.viewport.ca.b+":"+
                            encodeURI(resultados[i].formatted_address)+'">'+
                        (resultados[i].icon?"<img src='"+resultados[i].icon+"' class='icon'>":"")+
                        '<span class="titulo">'+resultados[i].formatted_address+'</span>'+
                        '</a></div>'
                        );
                    }
                }
            },
            fireFirst:function(){
                location.href=($(this).find(".resultados>.resultado_0>a").attr("href"));
                }
    }

searchbox_q(jQuery);

$(document).ready(function(){
    $(".searchbox").searchbox({});
    });