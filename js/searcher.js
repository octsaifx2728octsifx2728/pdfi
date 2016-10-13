var searcher_functions={
  search:function(string){
    if(this.searching){
      return false;
    }
    
    this.searching=true;
    var script=document.createElement("script");
    script.src="api/search/"+encodeURIComponent(string)+"?callback=searcher_functions.searchCallbackSelector&idcallback="+this.id+"&filters="+this.getParsedFilters();
    script.type="text/javascript";
    document.getElementsByTagName("head")[0].appendChild(script);
    wait(true);
    //
   // console.log(script.src);
  },
  applyFilter:function(filtro,mapa){
      switch(filtro.name){
          case "precio":
            this.filters[filtro.name]=filtro.value;
            enviroment.filters[filtro.name]=filtro.value;
            this.filters[filtro.name+"1"]=filtro.value;
            enviroment.filters[filtro.name+"1"]=filtro.value;
            break;
          default:
            this.filters[filtro.name]=filtro.value;
            enviroment.filters[filtro.name]=filtro.value;
      }
    console.log(filtro);
    switch(this.lastSearchType){
      case "string":
	this.search(this.lastSearch);
	break;
      case "bounds":
	this.searchBounds(mapa.mapa.getBounds());
       
	break;
    }
  },
  getParsedFilters:function(){
    var filter="";
    for(var i in enviroment.filters){
        //console.log(i+"="+this.filters[i]+":::"+enviroment.filters[i]);
        filter+=i+"="+(enviroment.filters[i])+":";
        //filter.push(this.filters[i]?this.filters[i]:enviroment.filters[i]);
        //  console.log(filter[filter.length-1]);
    }
    //console.log(filter);
  //filter=filter.join(":");
    return filter;
  },
  searchplaces:function(string,map,container){
    var query={
      query:string
    };
    service.textSearch(query, function(results, status){
	if (status == google.maps.places.PlacesServiceStatus.OK) {
	  for (var i = 0; i < results.length; i++) {
	    alert(results[i].name);
	  }
	}
    });
  },
  searchCallbackSelector:function(respuesta,id){
    if(searcherDDBB[id]){
      
      
      
      //alert(JSON.stringify(respuesta));
      
      searcherDDBB[id].searchCallback(respuesta);
       
    }
    
  },
  searchCallback:function(respuesta){
    
    
    this.lastSearch=respuesta.query;
    this.lastSearchType=respuesta.type;
    if(respuesta.error>0){
        
      this.searching=false;
      if(respuesta.error==2){
        
      	var mensaje=respuesta.errorDescription;
      	var div=document.createElement("div");
      	$(div).text(mensaje);
      	$(div).addClass("busqueda_error");
      	$("#resultsContainer").empty();
      	$("#resultsContainer").append(div);
      }
      else{
      alert(respuesta.errorDescription);
      console.log(respuesta);
      }
    }
    else {
      
      this.resultContainer=respuesta.results;
      $(this.resultbox).html("");
      resultados=respuesta.results;
      if(respuesta.parsedresults){
          //alert(respuesta.results.length);
          /*
          var sd ="";
        for(var x=0;x<respuesta.parsedresults.length-1;x++){
            
            sd=respuesta.parsedresults[x];
            
            try{
                $(this.resultbox).append(sd);
            }catch(err) {

                console.log(err);
                console.log(x);
                console.log(respuesta.parsedresults.length);
            }
            break;
         
        }
        
        */
       
       var str_aux = "";
       var str_aux1 = "";
       for(var x=0;x<respuesta.results.length;x++){
                    
          var template=this.resultTemplate.cloneNode(true);

          $(template).find(".result").attr("id","search_inmueble_result_"+respuesta.results[x].id+"_"+respuesta.results[x].casa);
          $(template).find(".result").addClass("result_"+respuesta.results[x].id+"_"+respuesta.results[x].casa);
          $(template).find(".result").removeClass("result_#ID#");
          if(respuesta.results[x].destacado){
                  $(template).find(".result").addClass("destacado_1");
          }

          $(template).find(".imagen").find("img").attr("src","cache/250/190/"+respuesta.results[x].imagen);
          
          $(template).find(".link>a").attr("href",respuesta.results[x].url);
          $(template).find(".link>a").attr("target","_blank");
          $(template).find("a.link").attr("href",respuesta.results[x].url);
          $(template).find("a.link").attr("target","_blank");
          $(template).find(".caracteristicas>.casa").append(respuesta.results[x].casa_title);
          $(template).find(".caracteristicas>.amueblado").append(respuesta.results[x].amueblado_title);
          $(template).find(".caracteristicas>.casa").addClass("casa_"+respuesta.results[x].casa);
          $(template).find(".caracteristicas>.casa").removeClass("casa");
          $(template).find(".caracteristicas>.amueblado").addClass("amueblado_"+respuesta.results[x].amueblado);
          $(template).find(".caracteristicas>.amueblado").removeClass("amueblado");
          
          $(template).find(".title1").append(respuesta.results[x].title);
          
          $(template).find(".title1").click(function() {              
              window.location.href = $(this).find("a")[0].href;

          });
          
          $(template).find(".title1").css('cursor', 'pointer');
          
                  
          $(template).find(".description").append(respuesta.results[x].descripcion);
          $(template).find(".precio>.precio_divisa").append(respuesta.results[x].moneda);
          $(template).find(".precio>.precio_data").append("$ " +respuesta.results[x].precio + " "+respuesta.results[x].moneda);
          $(template).find(".precio_orig>.precio_divisa").append(respuesta.results[x].monedaorig);
          $(template).find(".precio_orig>.precio_data").append(respuesta.results[x].precioorig);
          $(template).find(".preciom2").append(respuesta.results[x].preciom2);
          $(template).find(".habitaciones").append(respuesta.results[x].habitaciones);
          $(template).find(".m2").append(respuesta.results[x].m2);
          $(template).find(".superficie").append(respuesta.results[x].superficie);
          $(template).find(".banos").append(respuesta.results[x].banos);
          $(template).find(".estacionamientos").append(respuesta.results[x].estacionamientos);
          $(template).find(".construccion").append(respuesta.results[x].m2a);


          $(template).find(".ranking").append(respuesta.results[x].ranking);
          $(template).find(".share").append(respuesta.results[x].share);
          $(template).find(".contact").append(respuesta.results[x].contact);
          
          
          
                  var res = respuesta.results[x].contact.match( /<script\b[^>]*>([\s\S]*?)<\/script>/gm);
         if(res != null && res[0] != null)
            str_aux += res[0];
         if(res != null && res[1] != null)
            str_aux1 += res[1];
         
         
         
         /*
         var res = respuesta.results[x].contact.match( /"#contact_tel_.*"/gm);
        
         res = res[0].replace(/"/g, "");
        alert(res);
        
        

    $(res).ultraLogin({
        key:"login",
        inmueble:"12834_4597_1",
        initform:5,
        avaliableForms:[5],
        contactUser:[]
    });
    */
          
        
         
          $(template).find(".favs").append(respuesta.results[x].favorito);

          $(template).find(".jardin").addClass("jardin_"+respuesta.results[x].jardin);
          $(template).find(".lavado").addClass("lavado_"+respuesta.results[x].lavado);
          $(template).find(".servicio").addClass("servicio_"+respuesta.results[x].servicio);
          $(template).find(".vestidor").addClass("vestidor_"+respuesta.results[x].vestidor);
          $(template).find(".estudio").addClass("estudio_"+respuesta.results[x].estudio);
          $(template).find(".tv").addClass("tv_"+respuesta.results[x].tv);
          $(template).find(".cocina").addClass("cocina_"+respuesta.results[x].cocina);
          $(template).find(".chimenea").addClass("chimenea_"+respuesta.results[x].chimenea);
          $(template).find(".terraza").addClass("terraza_"+respuesta.results[x].terraza);
          $(template).find(".jacuzzi").addClass("jacuzzi_"+respuesta.results[x].jacuzzi);
          $(template).find(".alberca").addClass("alberca_"+respuesta.results[x].alberca);
          $(template).find(".vista").addClass("vista_"+respuesta.results[x].vista);

          $(template).find(".aire").addClass("aire_"+respuesta.results[x].aire);
          $(template).find(".calefaccion").addClass("calefaccion_"+respuesta.results[x].calefaccion);
          $(template).find(".bodega").addClass("bodega_"+respuesta.results[x].bodega);
          $(template).find(".elevador").addClass("elevador_"+respuesta.results[x].elevador);
          $(template).find(".elevadors").addClass("elevadors_"+respuesta.results[x].elevadors);
          $(template).find(".portero").addClass("portero_"+respuesta.results[x].portero);
          $(template).find(".seguridad").addClass("seguridad_"+respuesta.results[x].sistema_seguridad);
          $(template).find(".circuito").addClass("circuito_"+respuesta.results[x].circuito);
          $(template).find(".red").addClass("red_"+respuesta.results[x].red);
          $(template).find(".gimnasio").addClass("gimnasio_"+respuesta.results[x].gimnasio);
          $(template).find(".spa").addClass("spa_"+respuesta.results[x].spa);
          $(template).find(".golf").addClass("golf_"+respuesta.results[x].golf);
          
            try{
                this.resultbox.appendChild(template);
            }catch(err) {

                console.log(err);
              
            }
          
        }
        
        $("head").append(str_aux);
        
        $("head").append(str_aux1);
           

        
      }
      else{
          
        for(var x=0;x<respuesta.results.length;x++){
            
            
            
          var template=this.resultTemplate.cloneNode(true);

          $(template).find(".result").attr("id","search_inmueble_result_"+respuesta.results[x].id+"_"+respuesta.results[x].casa);
          $(template).find(".result").addClass("result_"+respuesta.results[x].id+"_"+respuesta.results[x].casa);
          $(template).find(".result").removeClass("result_#ID#");
          if(respuesta.results[x].destacado){
                  $(template).find(".result").addClass("destacado_1");
          }

          $(template).find(".imagen").find("img").attr("src","cache/250/190/"+respuesta.results[x].imagen);
          $(template).find(".link>a").attr("href",respuesta.results[x].url);
          $(template).find(".link>a").attr("target","_blank");
          $(template).find("a.link").attr("href",respuesta.results[x].url);
          $(template).find("a.link").attr("target","_blank");
          $(template).find(".caracteristicas>.casa").append(respuesta.results[x].casa_title);
          $(template).find(".caracteristicas>.amueblado").append(respuesta.results[x].amueblado_title);
          $(template).find(".caracteristicas>.casa").addClass("casa_"+respuesta.results[x].casa);
          $(template).find(".caracteristicas>.casa").removeClass("casa");
          $(template).find(".caracteristicas>.amueblado").addClass("amueblado_"+respuesta.results[x].amueblado);
          $(template).find(".caracteristicas>.amueblado").removeClass("amueblado");
          $(template).find(".title").append(respuesta.results[x].title);
          $(template).find(".description").append(respuesta.results[x].descripcion);
          $(template).find(".precio>.precio_divisa").append(respuesta.results[x].moneda);
          $(template).find(".precio>.precio_data").append(respuesta.results[x].precio);
          $(template).find(".precio_orig>.precio_divisa").append(respuesta.results[x].monedaorig);
          $(template).find(".precio_orig>.precio_data").append(respuesta.results[x].precioorig);
          $(template).find(".preciom2").append(respuesta.results[x].preciom2);
          $(template).find(".habitaciones").append(respuesta.results[x].habitaciones);
          $(template).find(".m2").append(respuesta.results[x].m2);
          $(template).find(".superficie").append(respuesta.results[x].superficie);
          $(template).find(".banos").append(respuesta.results[x].banos);
          $(template).find(".estacionamientos").append(respuesta.results[x].estacionamientos);
          $(template).find(".construccion").append(respuesta.results[x].m2a);


          $(template).find(".ranking").append(respuesta.results[x].ranking);
          $(template).find(".share").append(respuesta.results[x].share);
          $(template).find(".contact").append(respuesta.results[x].contact);
          $(template).find(".favs").append(respuesta.results[x].favorito);

          $(template).find(".jardin").addClass("jardin_"+respuesta.results[x].jardin);
          $(template).find(".lavado").addClass("lavado_"+respuesta.results[x].lavado);
          $(template).find(".servicio").addClass("servicio_"+respuesta.results[x].servicio);
          $(template).find(".vestidor").addClass("vestidor_"+respuesta.results[x].vestidor);
          $(template).find(".estudio").addClass("estudio_"+respuesta.results[x].estudio);
          $(template).find(".tv").addClass("tv_"+respuesta.results[x].tv);
          $(template).find(".cocina").addClass("cocina_"+respuesta.results[x].cocina);
          $(template).find(".chimenea").addClass("chimenea_"+respuesta.results[x].chimenea);
          $(template).find(".terraza").addClass("terraza_"+respuesta.results[x].terraza);
          $(template).find(".jacuzzi").addClass("jacuzzi_"+respuesta.results[x].jacuzzi);
          $(template).find(".alberca").addClass("alberca_"+respuesta.results[x].alberca);
          $(template).find(".vista").addClass("vista_"+respuesta.results[x].vista);

          $(template).find(".aire").addClass("aire_"+respuesta.results[x].aire);
          $(template).find(".calefaccion").addClass("calefaccion_"+respuesta.results[x].calefaccion);
          $(template).find(".bodega").addClass("bodega_"+respuesta.results[x].bodega);
          $(template).find(".elevador").addClass("elevador_"+respuesta.results[x].elevador);
          $(template).find(".elevadors").addClass("elevadors_"+respuesta.results[x].elevadors);
          $(template).find(".portero").addClass("portero_"+respuesta.results[x].portero);
          $(template).find(".seguridad").addClass("seguridad_"+respuesta.results[x].sistema_seguridad);
          $(template).find(".circuito").addClass("circuito_"+respuesta.results[x].circuito);
          $(template).find(".red").addClass("red_"+respuesta.results[x].red);
          $(template).find(".gimnasio").addClass("gimnasio_"+respuesta.results[x].gimnasio);
          $(template).find(".spa").addClass("spa_"+respuesta.results[x].spa);
          $(template).find(".golf").addClass("golf_"+respuesta.results[x].golf);

          this.resultbox.appendChild(template);
        }
        
      }
      if(addMapMarkers){
	      addMapMarkers();
      }
    this.searching=false;
    }
    if(respuesta.type=="places"||respuesta.type=="search"){
    	this.centerOnMarkers();   
    }
      else {
      if(mapa.mapa.getZoom()<19){
           // this.autotrack=true;
          //mapa.mapa.setZoom(mapa.mapa.getZoom()+2);
      }
      }
      
      try{
              var loader = $('.loader').ClassyLoader({
                    percentage: 0,
                    speed: 0,
                    fontSize: '50px',
                    diameter: 80,
                    lineColor: 'rgba(155,155,155,1)',
                    remainingLineColor: 'rgba(200,200,200,0.4)',
                    lineWidth: 10,
                    displayOnLoad: false
                });
                loader.show();
                loader.draw(100);       
            }catch(ex){
                console.log(ex);
            }
    //wait(false);
    
    
    
  },
  searchBounds:function(bounds){
    if(this.searching||this.autotrack){
        this.autotrack=false;
        return false;
    }
    this.searching = true;
    var ne=bounds.getNorthEast();
    var sw=bounds.getSouthWest();
    console.log(enviroment.filters);
    var script=document.createElement("script");
    var path=ne.lat()+":"+ne.lng()+":"+sw.lat()+":"+sw.lng()+"?callback=searcher_functions.searchCallbackSelector&idcallback="+this.id+"&filters="+this.getParsedFilters();
    script.src="/api/searchbounds/"+path;

    if(history&&history.replaceState){
      history.replaceState({'return':true},"","/app/searchbounds/"+ne.lat()+":"+ne.lng()+":"+sw.lat()+":"+sw.lng()+"?tipoobjeto="+enviroment.filters["tipoobjeto"]);
   }
    document.getElementsByTagName("head")[0].appendChild(script);
    //document.getElementsByTagName("head")[0].appendChild("<p>puto</>");
    
    //console.log("searchbounds = " + script.src);

    //alert("sd");
    //wait(true);
    
    
            
   
    
    
  }
};
var searcherDDBB=[];


var searcher_class=function(id){
  this.filters={};
  this.searching=false;
  searcherDDBB[id]=this;
  this.id=id;
  this.search=searcher_functions.search;
  this.searchplaces=searcher_functions.searchplaces;
  this.searchBounds=searcher_functions.searchBounds;
  this.applyFilter=searcher_functions.applyFilter;
  this.getParsedFilters=searcher_functions.getParsedFilters;
  this.resultbox=null;
  this.resultContainer=null;
  this.resultTemplate=null;
  this.searchCallback=searcher_functions.searchCallback;
}
