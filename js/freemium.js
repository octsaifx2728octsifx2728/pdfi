var inmueble=function(data){
	this.data=data;
	this.images={};
	this.images360={};
	this.videos={};
	this.countimages=function(){
		var cuenta=0;
		for(var i in this.images){
			cuenta++;
		}
		return cuenta*1;
	}
	this.countimages360=function(){
		var cuenta=0;
		for(var i in this.images360){
			cuenta++;
		}
		return cuenta*1;
	}
}
var freemium={
	form:null,
	mapa:null,
	marcador:null,
	inmueble:null,
	mapOptions:{
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		center: new google.maps.LatLng(19.426, -99.123),
		zoom:8
		},
	calcpm2:function(){
		var precio=$("#_freemiumform").find(".precio>input").attr("value");
		var superficie=$("#_freemiumform").find(".m2>input").attr("value");
		$("#_freemiumform").find(".preciom2>input").attr("value",precio/superficie)
	},
	tipoChanged:function(valor){
		$("#_freemiumform").find(".plazo").css("display",valor==2?"block":"none");
	},
	openForm:function(id){
		freemium.form=document.getElementById(id);
		
		$(freemium.form).find(".filtros>.checkbox>input").change(function(){
			if(this.checked){
				$(this.parentNode).find(".icon").addClass("icon_0");
				$(this.parentNode).find(".icon").removeClass("icon");
				}
			else {
				$(this.parentNode).find(".icon_0").addClass("icon");
				$(this.parentNode).find(".icon_0").removeClass("icon_0");
				}
			});
		
		$.colorbox({inline:true,
			href:freemium.form,
			onComplete:function(){
				freemium.mapa=new google.maps.Map($(freemium.form).find(".mapa>.mapa").get()[0],freemium.mapOptions);
				google.maps.event.trigger(freemium.mapa, "resize");
				freemium.marcador=new google.maps.Marker({
					draggable:true,
					map:freemium.mapa,
					position:freemium.mapOptions.center,
				      icon:"img/casa.gif",
				      optimized:false
					});
				google.maps.event.addListener(freemium.marcador, 'position_changed', function(evento){
					var pos=this.getPosition();
					$(freemium.form).find(".mapa>h3>.posicion").text("("+pos.lat()+","+pos.lng()+")");
				});
				
				
				
				var input = document.getElementById('_freemiumPlaces');
				  var options = {
				  };
				  $(input).keypress(freemium.placesFix)
				  freemium.placesBox = new google.maps.places.Autocomplete(input, options);
				
				
  				google.maps.event.addListener(freemium.placesBox, 'place_changed',freemium.placesChanged);
				
				}
			});
	},
	placesFix:function(){
		$(".pac-container").css("z-index",10000);
	},
	placesChanged:function(){
		var place = freemium.placesBox.getPlace();
		if(place){
			freemium.mapa.setCenter(place.geometry.location);
			freemium.mapa.setZoom(15);
			freemium.marcador.setPosition(place.geometry.location);
			}
	},
	placesBox:null,
	submit2:function(){
		var toc=document.getElementById("TOC").checked;
			$("#_freemiumform2").find(".alertForm").css("display","none");
		
		if(!toc){
			
			$("#_freemiumform2").find(".alertForm").css("display","block");
			return false;
		}
		var fotos=[];
		var fotos360=[];
		var videos=[];
		var id=freemium.inmueble.id;
		var pago=null;
		var method=null;
		var premium=null;
		
		
		var methods=$("#_freemiumform2").find(".formasdepago>.fp>.option>input").get();
		for(var i in freemium.inmueble.images){
			fotos.push(freemium.inmueble.images[i]);
		}
		for(var i in freemium.inmueble.images360){
			fotos360.push(freemium.inmueble.images360[i]);
		}
		if(fotos.length<1){
			alert("foto?");
			$("#_freemiumform2").find(".alertForm").css("display","block");
			return false;
		}
		for(i in freemium.inmueble.videos){
			videos.push(freemium.inmueble.videos[i]);
		}
		
		
		var productos=$("#_freemiumform2").find(".optionbox>input").get();
		var prods=[];
		for (var i=0;i<productos.length;i++){
			if(productos[i].checked){
				prods.push(productos[i].value);
			}
		}
		
		if(prods.length<1){
			$("#_freemiumform2").find(".alertForm").css("display","block");
			return false;
		}
		for(i=0;i<methods.length;i++){
			if(methods[i].checked){
				method=methods[i].value
			}
			
		}
		
		url="/api/inmueble/add2?id="+id+
		"&fotos="+encodeURI(fotos.join(","))+
		"&fotos360="+encodeURI(fotos360.join(","))+
		"&videos="+encodeURI(videos.join(","))+
		"&productos="+prods.join(",")+
		"&method="+method+
		"&callback=freemium.setp2_callback&idcallback="+id;
		
		var script=document.createElement("script");
		script.type="text/javascript";
		script.src=url;
		//alert(url);
		wait(true);
		return document.getElementsByTagName("head")[0].appendChild(script);
	},
	submit:function(){
		var titulo=$(freemium.form).find(".titulo>input").attr("value");
		var telefono=$(freemium.form).find(".telefono>input").attr("value");
		var descripcion=$(freemium.form).find(".descripcion>textarea").attr("value");
		var tipo_anuncio=$(freemium.form).find(".tipo_anuncio>select").attr("value");
		var precio=$(freemium.form).find(".precio>input").attr("value");
		var habitaciones=$(freemium.form).find(".habitaciones>select").attr("value");
		var banyos=$(freemium.form).find(".banyos>select").attr("value");
		var estacionamientos=$(freemium.form).find(".estacionamientos>select").attr("value");
		var m2=$(freemium.form).find(".m2>input").attr("value");
		var preciom2=$(freemium.form).find(".preciom2>input").attr("value");
		var m2s=$(freemium.form).find(".m2s>input").attr("value");
		var anyo=$(freemium.form).find(".anyo>select").attr("value");
		var tipos=$(freemium.form).find(".tipo>.option>input").get();
		var amueblado=$(freemium.form).find(".amueblado>input").get()[0].checked?"1":"0";
		var jardin=$(freemium.form).find(".jardin>input").get()[0].checked?"1":"0";
		var lavado=$(freemium.form).find(".lavado>input").get()[0].checked?"1":"0";
		var servicio=$(freemium.form).find(".servicio>input").get()[0].checked?"1":"0";
		var vestidor=$(freemium.form).find(".vestidor>input").get()[0].checked?"1":"0";
		var estudio=$(freemium.form).find(".estudio>input").get()[0].checked?"1":"0";
		var tv=$(freemium.form).find(".tv>input").get()[0].checked?"1":"0";
		var cocina=$(freemium.form).find(".cocina>input").get()[0].checked?"1":"0";
		var chimenea=$(freemium.form).find(".chimenea>input").get()[0].checked?"1":"0";
		var terraza=$(freemium.form).find(".terraza>input").get()[0].checked?"1":"0";
		var jacuzzi=$(freemium.form).find(".jacuzzi>input").get()[0].checked?"1":"0";
		var alberca=$(freemium.form).find(".alberca>input").get()[0].checked?"1":"0";
		var vista=$(freemium.form).find(".vista>input").get()[0].checked?"1":"0";
		var aire=$(freemium.form).find(".aire>input").get()[0].checked?"1":"0";
		var calefaccion=$(freemium.form).find(".calefaccion>input").get()[0].checked?"1":"0";
		var bodega=$(freemium.form).find(".bodega>input").get()[0].checked?"1":"0";
		var elevador=$(freemium.form).find(".elevador>input").get()[0].checked?"1":"0";
		var elevadors=$(freemium.form).find(".elevadors>input").get()[0].checked?"1":"0";
		var portero=$(freemium.form).find(".portero>input").get()[0].checked?"1":"0";
		var seguridad=$(freemium.form).find(".seguridad>input").get()[0].checked?"1":"0";
		var circuito=$(freemium.form).find(".circuito>input").get()[0].checked?"1":"0";
		var red=$(freemium.form).find(".red>input").get()[0].checked?"1":"0";
		var gimnasio=$(freemium.form).find(".gimnasio>input").get()[0].checked?"1":"0";
		var spa=$(freemium.form).find(".spa>input").get()[0].checked?"1":"0";
		var golf=$(freemium.form).find(".golf>input").get()[0].checked?"1":"0";
		var tenis=$(freemium.form).find(".tenis>input").get()[0].checked?"1":"0";
		var juegos=$(freemium.form).find(".juegos>input").get()[0].checked?"1":"0";
		var sjuegos=$(freemium.form).find(".juegos>input").get()[0].checked?"1":"0";
		var fiestas=$(freemium.form).find(".fiestas>input").get()[0].checked?"1":"0";
		var biblioteca=$(freemium.form).find(".biblioteca>input").get()[0].checked?"1":"0";
		var eventos=$(freemium.form).find(".eventos>input").get()[0].checked?"1":"0";
		var mascotas=$(freemium.form).find(".mascotas>input").get()[0].checked?"1":"0";
		var cava=$(freemium.form).find(".cava>input").get()[0].checked?"1":"0";
		var hotel=$(freemium.form).find(".hotel>input").get()[0].checked?"1":"0";
		var playa=$(freemium.form).find(".playa>input").get()[0].checked?"1":"0";
		var muelle=$(freemium.form).find(".muelle>input").get()[0].checked?"1":"0";
		var parque=$(freemium.form).find(".parque>input").get()[0].checked?"1":"0";
		var tintoreria=$(freemium.form).find(".tintoreria>input").get()[0].checked?"1":"0";
		var ecologico=$(freemium.form).find(".ecologico>input").get()[0].checked?"1":"0";
		var discapacitados=$(freemium.form).find(".discapacitados>input").get()[0].checked?"1":"0";
		var helipuerto=$(freemium.form).find(".helipuerto>input").get()[0].checked?"1":"0";
		var condominios=$(freemium.form).find(".condominios>input").get()[0].checked?"1":"0";
		var club=$(freemium.form).find(".club>input").get()[0].checked?"1":"0";
		var posicion=$(freemium.form).find(".posicion").text();
		
		for(var i=0; i<tipos.length;i++){
			if(tipos[i].checked){
				var tipo=tipos[i].value;
			}
		}
		posicion=posicion.replace("(","");
		posicion=posicion.replace(")","");
		
		$(freemium.form).find(".titulo").removeClass("error");
		$(freemium.form).find(".descripcion").removeClass("error");
		$(freemium.form).find(".tipo_anuncio").removeClass("error");
		$(freemium.form).find(".precio").removeClass("error");
		$(freemium.form).find(".m2").removeClass("error");
		$(freemium.form).find(".preciom2").removeClass("error");
		$(freemium.form).find(".tipo").removeClass("error");
		$(freemium.form).find(".alertForm").css("display","none");
		
		if(!titulo||titulo.length<2){
			$(freemium.form).find(".titulo").addClass("error");
			$(freemium.form).find(".alertForm").css("display","block");
			alert($(freemium.form).find(".titulo>label").text()+"  ?");
			return false;
		}
		if(!descripcion||descripcion.length<2){
			$(freemium.form).find(".descripcion").addClass("error");
			$(freemium.form).find(".alertForm").css("display","block");
			alert($(freemium.form).find(".descripcion>label").text()+"  ?");
			return false;
		}
		if(!tipo_anuncio||tipo_anuncio.length<1){
			$(freemium.form).find(".tipo_anuncio").addClass("error");
			$(freemium.form).find(".alertForm").css("display","block");
			alert($(freemium.form).find(".tipo_anuncio>label").text()+"  ?");
			return false;
		}
		if(!precio||precio.length<1){
			$(freemium.form).find(".precio").addClass("error");
			$(freemium.form).find(".alertForm").css("display","block");
			alert($(freemium.form).find(".precio>label").text()+"  ?");
			return false;
		}
		if(!m2||m2.length<1){
			$(freemium.form).find(".m2").addClass("error");
			$(freemium.form).find(".alertForm").css("display","block");
			alert($(freemium.form).find(".m2>label").text()+"  ?");
			return false;
		}
		if(!preciom2||preciom2.length<1){
			$(freemium.form).find(".preciom2").addClass("error");
			$(freemium.form).find(".alertForm").css("display","block");
			alert($(freemium.form).find(".preciom2>label").text()+"  ?");
			return false;
		}
		if(!tipo||tipo.length<1){
			$(freemium.form).find(".tipo").addClass("error");
			$(freemium.form).find(".alertForm").css("display","block");
			alert($(freemium.form).find(".tipo>label").text()+"  ?");
			return false;
		}
		
		var url="api/inmueble/add?titulo="+encodeURI(titulo)+
			"&telefono="+encodeURI(telefono)+
			"&descripcion="+encodeURI(descripcion)+
			"&tipo_anuncio="+encodeURI(tipo_anuncio)+
			"&precio="+encodeURI(precio)+
			"&habitaciones="+encodeURI(habitaciones)+
			"&banyos="+encodeURI(banyos)+
			"&estacionamientos="+encodeURI(estacionamientos)+
			"&m2="+encodeURI(m2)+
			"&preciom2="+encodeURI(preciom2)+
			"&m2s="+encodeURI(m2s)+
			"&anyo="+encodeURI(anyo)+
			"&tipo="+encodeURI(tipo)+
			"&amueblado="+encodeURI(amueblado)+
			"&jardin="+encodeURI(jardin)+
			"&lavado="+encodeURI(lavado)+
			"&servicio="+encodeURI(servicio)+
			"&vestidor="+encodeURI(vestidor)+
			"&estudio="+encodeURI(estudio)+
			"&tv="+encodeURI(tv)+
			"&cocina="+encodeURI(cocina)+
			"&chimenea="+encodeURI(chimenea)+
			"&terraza="+encodeURI(terraza)+
			"&jacuzzi="+encodeURI(jacuzzi)+
			"&alberca="+encodeURI(alberca)+
			"&vista="+encodeURI(vista)+
			"&aire="+encodeURI(aire)+
			"&calefaccion="+encodeURI(calefaccion)+
			"&bodega="+encodeURI(bodega)+
			"&elevador="+encodeURI(elevador)+
			"&elevadors="+encodeURI(elevadors)+
			"&portero="+encodeURI(portero)+
			"&seguridad="+encodeURI(seguridad)+
			"&circuito="+encodeURI(circuito)+
			"&red="+encodeURI(red)+
			"&gimnasio="+encodeURI(gimnasio)+
			"&spa="+encodeURI(spa)+
			"&golf="+encodeURI(golf)+
			"&tenis="+encodeURI(tenis)+
			"&juegos="+encodeURI(juegos)+
			"&sjuegos="+encodeURI(sjuegos)+
			"&fiestas="+encodeURI(fiestas)+
			"&biblioteca="+encodeURI(biblioteca)+
			"&eventos="+encodeURI(eventos)+
			"&mascotas="+encodeURI(mascotas)+
			"&cava="+encodeURI(cava)+
			"&hotel="+encodeURI(hotel)+
			"&playa="+encodeURI(playa)+
			"&muelle="+encodeURI(muelle)+
			"&parque="+encodeURI(parque)+
			"&tintoreria="+encodeURI(tintoreria)+
			"&ecologico="+encodeURI(ecologico)+
			"&discapacitados="+encodeURI(discapacitados)+
			"&helipuerto="+encodeURI(helipuerto)+
			"&condominios="+encodeURI(condominios)+
			"&club="+encodeURI(club)+
			"&posicion="+encodeURI(posicion)+
			"";
		freemium.inmueble=new inmueble({"titulo":titulo,
			"telefono":telefono,
			"descripcion":descripcion,
			"tipo_anuncio":tipo_anuncio,
			"precio":precio,
			"habitaciones":habitaciones,
			"banyos":banyos,
			"estacionamientos":estacionamientos,
			"m2":m2,
			"preciom2":preciom2,
			"m2s":m2s,
			"anyo":anyo,
			"tipo":tipo,
			"amueblado":amueblado,
			"jardin":jardin,
			"lavado":lavado,
			"servicio":servicio,
			"vestidor":vestidor,
			"estudio":estudio,
			"tv=":tv,
			"cocina":cocina,
			"chimenea":chimenea,
			"terraza":terraza,
			"jacuzzi":jacuzzi,
			"alberca":alberca,
			"vista":vista,
			"aire":aire,
			"calefaccion":calefaccion,
			"bodega":bodega,
			"elevador":elevador,
			"elevadors":elevadors,
			"portero":portero,
			"seguridad":seguridad,
			"circuito":circuito,
			"red":red,
			"gimnasio":gimnasio,
			"spa":spa,
			"golf":golf,
			"tenis":tenis,
			"juegos":juegos,
			"sjuegos":sjuegos,
			"fiestas":fiestas,
			"biblioteca":biblioteca,
			"eventos":eventos,
			"mascotas":mascotas,
			"cava":cava,
			"hotel":hotel,
			"playa":playa,
			"muelle":muelle,
			"parque":parque,
			"tintoreria":tintoreria,
			"ecologico":ecologico,
			"discapacitados":discapacitados,
			"helipuerto":helipuerto,
			"condominios":condominios,
			"club":club,
			"posicion":posicion});
		var script=document.createElement("script");
		script.type="text/javascript";
		script.src=url+"&callback=freemium.setp1_callback&idcallback=0";
		document.getElementsByTagName("head")[0].appendChild(script);
		//alert(script.src);
		wait(true);
	},
	setp1_callback:function(respuesta){
		if(respuesta.inmuebleKey){
			freemium.inmueble.id=respuesta.inmuebleKey;
			freemium.showMediaform(freemium.inmueble);
		}
		else {
			alert(respuesta.errorDescription);
		}
		wait(false);
	},
	setp2_callback:function(respuesta,id){
		if(respuesta.token){
			if(respuesta.follow){
				location.href=respuesta.follow;
			}
			else{
				alert(respuesta.paymentMessage);
				$.colorbox.close();
				location.reload();
			}
			//freemium.showPaypalButton(url,respuesta.token.total);
		}
		else {
			alert(respuesta.paymentMessage);
				$.colorbox.close();
				location.reload();
		}
	},
	showPaypalButton:function(url,precio){
		
		$("#_freemiumform3").find(".total>.total").text(precio);
		$("#_freemiumform3").find(".control").find(".amount").attr("value",precio);
		$.colorbox({inline:true,href:"#_freemiumform3"});
	},
	showMediaform:function(inmueble){
		$("#_freemiumform2").find("#_uploadFotoFrame").attr("src","/app/uploadFile/Image?id=1&callback=freemium.filePreload&target=imagen");
		$("#_freemiumform2").find("#_uploadFoto360Frame").attr("src","/app/uploadFile/Image?id=1&callback=freemium.filePreload360&target=imagen360");
			$.colorbox({inline:true,href:"#_freemiumform2"});
			freemium.refreshProductStatus();
	},
	refreshProductStatus:function(){
		
		if($("#product_1").attr("checked"))freemium.productChanged('1');
		if($("#product_2").attr("checked"))freemium.productChanged('2');
		if($("#product_3").attr("checked"))freemium.productChanged('3');
		if($("#product_4").attr("checked"))freemium.productChanged('4');
		if($("#product_10").attr("checked"))freemium.productChanged('10');
		
	},
	calcularTotalFotos:function(){
		var total=0;
		if($("#product_1").attr("checked"))total=5;
		if($("#product_2").attr("checked"))total=20;
		if($("#product_3").attr("checked"))total=20;
		if($("#product_4").attr("checked"))total=20;
		if($("#product_10").attr("checked"))total=20;
		if($("#product_11").attr("checked"))total=20;
		if($("#product_12").attr("checked"))total=20;
		
		total=total>0?total:5;
		return total;
	},
	calcularTotalFotos360:function(){
		var total=0;
		if($("#product_1").attr("checked"))total=3;
		if($("#product_2").attr("checked"))total=10;
		if($("#product_3").attr("checked"))total=10;
		if($("#product_4").attr("checked"))total=10;
		if($("#product_10").attr("checked"))total=10;
		if($("#product_11").attr("checked"))total=10;
		if($("#product_12").attr("checked"))total=10;
		
		total=total>0?total:3;
		return total;
	},
	filePreload:function(respuesta,id){
            if((respuesta.error instanceof Array)||(typeof(respuesta.error)=="object")){
                for(var i=0;i<respuesta.error.length;i++){
                    freemium.inmueble.images[id+i]=respuesta.path[i];
                    var div=document.createElement("div");
                    var img=document.createElement("img");
                    var del=document.createElement("input");
                    var total=freemium.inmueble.countimages();
                    var permitidas=freemium.calcularTotalFotos();
                    del.type="button";
                    del.className="button";
                    del.value="X";
                    $(del).click(freemium.delFoto)
                    img.src=respuesta.path[i];
                    div.className=("imagen imagen_"+id+i);
                    div.appendChild(del);
                    div.appendChild(img);
                    div.title=id+i;
                    $("#_freemiumform2").find(".fotosContainer>.wrap").append(div);
                    $("#_freemiumform2").find("#_uploadFotoFrame").addClass("totalImages_"+id+i);
                    if(total<permitidas){
                            $("#_freemiumform2").find("#_uploadFotoFrame").attr("src","/app/uploadFile/Image?id="+(id+i+1)+"&callback=freemium.filePreload&target=imagen");
                            }
                    else{
                            $("#_freemiumform2").find(".fotosContainer>.wrap>.new").css("display","none");
                            $("#_freemiumform2").find("#_uploadFotoFrame").attr("src","/app/uploadFile/Image?id="+(id+i+1)+"&callback=freemium.filePreload&target=imagen");
                            }
                    }
                }
            else {
		freemium.inmueble.images[id]=respuesta.path;
		var div=document.createElement("div");
		var img=document.createElement("img");
		var del=document.createElement("input");
		var total=freemium.inmueble.countimages();
		var permitidas=freemium.calcularTotalFotos();
		del.type="button";
		del.className="button";
		del.value="X";
		$(del).click(freemium.delFoto)
		img.src=respuesta.path;
		div.className=("imagen imagen_"+id);
		div.appendChild(del);
		div.appendChild(img);
		div.title=id;
		$("#_freemiumform2").find(".fotosContainer>.wrap").append(div);
		$("#_freemiumform2").find("#_uploadFotoFrame").addClass("totalImages_"+id);
		if(total<permitidas){
			$("#_freemiumform2").find("#_uploadFotoFrame").attr("src","/app/uploadFile/Image?id="+(id+1)+"&callback=freemium.filePreload&target=imagen");
			}
		else{
			$("#_freemiumform2").find(".fotosContainer>.wrap>.new").css("display","none");
			$("#_freemiumform2").find("#_uploadFotoFrame").attr("src","/app/uploadFile/Image?id="+(id+1)+"&callback=freemium.filePreload&target=imagen");
			}
            }
		wait(false);
	},
	filePreload360:function(respuesta,id){
            if((respuesta.error instanceof Array)||(typeof(respuesta.error)=="object")){
                for(var i=0;i<respuesta.error.length;i++){
                freemium.inmueble.images360[id+i]=respuesta.path[i];
		var div=document.createElement("div");
		var img=document.createElement("img");
		var del=document.createElement("input");
		var total=freemium.inmueble.countimages360();
		var permitidas=freemium.calcularTotalFotos360();
		del.type="button";
		del.className="button";
		del.value="X";
		$(del).click(freemium.delFoto360)
		img.src=respuesta.path[i];
		div.className=("imagen imagen_"+id+i);
		div.appendChild(del);
		div.appendChild(img);
		div.title=id+i;
		$("#_freemiumform2").find(".fotos360Container>.wrap").append(div);
		$("#_freemiumform2").find("#_uploadFoto360Frame").addClass("totalImages_"+id+i);
		if(total<permitidas){
			$("#_freemiumform2").find("#_uploadFoto360Frame").attr("src","/app/uploadFile/Image?id="+(id+i+1)+"&callback=freemium.filePreload360&target=imagen360");
			}
		else{
			$("#_freemiumform2").find(".fotos360Container>.wrap>.new").css("display","none");
			$("#_freemiumform2").find("#_uploadFoto360Frame").attr("src","/app/uploadFile/Image?id="+(id+i+1)+"&callback=freemium.filePreload360&target=imagen360");
			}
                    }
                }
            else{
                freemium.inmueble.images360[id]=respuesta.path;
		var div=document.createElement("div");
		var img=document.createElement("img");
		var del=document.createElement("input");
		var total=freemium.inmueble.countimages360();
		var permitidas=freemium.calcularTotalFotos360();
		del.type="button";
		del.className="button";
		del.value="X";
		$(del).click(freemium.delFoto360)
		img.src=respuesta.path;
		div.className=("imagen imagen_"+id);
		div.appendChild(del);
		div.appendChild(img);
		div.title=id;
		$("#_freemiumform2").find(".fotos360Container>.wrap").append(div);
		$("#_freemiumform2").find("#_uploadFoto360Frame").addClass("totalImages_"+id);
		if(total<permitidas){
			$("#_freemiumform2").find("#_uploadFoto360Frame").attr("src","/app/uploadFile/Image?id="+(id+1)+"&callback=freemium.filePreload360&target=imagen360");
			}
		else{
			$("#_freemiumform2").find(".fotos360Container>.wrap>.new").css("display","none");
			$("#_freemiumform2").find("#_uploadFoto360Frame").attr("src","/app/uploadFile/Image?id="+(id+1)+"&callback=freemium.filePreload360&target=imagen360");
			}
            }
		wait(false);
	},
	delFoto:function(e){
		var id=(e.target.parentNode.title)*1;
		var permitidas=freemium.calcularTotalFotos();
		delete freemium.inmueble.images[id];
		$(e.target.parentNode).remove();
		var total=freemium.inmueble.countimages();
		if(total<permitidas){
			$("#_freemiumform2").find(".fotosContainer>.wrap>.new").css("display","block");
		}
		else {
			$("#_freemiumform2").find(".fotosContainer>.wrap>.new").css("display","none");
		}
	},
	delFoto360:function(e){
		var id=(e.target.parentNode.title)*1;
		var permitidas=freemium.calcularTotalFotos360();
		delete freemium.inmueble.images360[id];
		$(e.target.parentNode).remove();
		var total=freemium.inmueble.countimages360();
		if(total<permitidas){
			$("#_freemiumform2").find(".fotos360Container>.wrap>.new").css("display","block");
		}
		else {
			$("#_freemiumform2").find(".fotos360Container>.wrap>.new").css("display","none");
		}
	},
	addVideo:function (url,id,formulario,id_inmueble,tipo){
		var videoregex=/(http[s]?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/[a-z0-9=\/\.&\?\-_]*/i;
		var videoclean=/(http[s]?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\//i;
		var form=formulario?formulario:"#_freemiumform2";
		if(videoregex.test(url)){
			var key=url.replace(videoclean,"");
			var key=key.replace("watch?v=","");
			var video=document.createElement("iframe");
			video.className="video";
			video.src="http://www.youtube.com/embed/"+key+"?enablejsapi=0&origin=http://www.e-spacios.com";
			video.frameborder="0";
			$(form).find(".video_"+id+">.videoContainer").empty();
			$(form).find(".video_"+id+">.videoContainer").append(video);
			if(freemium.inmueble){
				freemium.inmueble.videos[id]=key;
				}
			if(id_inmueble){
				var url="/api/inmueble/addVideo?id="+id_inmueble+"&tipo="+tipo+"&key="+key+"&index="+id;
				var script=document.createElement("script");
				script.type="text/javascript";
				script.src=url+"&callback=&idcallback=0";
				document.getElementsByTagName("head")[0].appendChild(script);
                                console.log(script.src); 
				
			}
		}
		else {
			delete freemium.inmueble.videos[id];
			$("#_freemiumform2").find(".video_"+id+">.videoContainer").empty();

		}
	},
	productChanged:function(id){
		var permitidas=freemium.calcularTotalFotos();
		var total=freemium.inmueble.countimages();
		if(total<permitidas){
			$("#_freemiumform2").find(".fotosContainer>.wrap>.new").css("display","block");
		}
		else {
			$("#_freemiumform2").find(".fotosContainer>.wrap>.new").css("display","none");
		}
		var permitidas360=freemium.calcularTotalFotos360();
		var total360=freemium.inmueble.countimages360();
		if(total360<permitidas360){
			$("#_freemiumform2").find(".fotos360Container>.wrap>.new").css("display","block");
		}
		else {
			$("#_freemiumform2").find(".fotos360Container>.wrap>.new").css("display","none");
		}
		total=[1,2,3,4];
		if($("#product_2").attr("checked"))total=[];
		if($("#product_3").attr("checked"))total=[];
		if($("#product_4").attr("checked"))total=[];
		if($("#product_10").attr("checked"))total=[];
		if($("#product_11").attr("checked"))total=[];
		if($("#product_12").attr("checked"))total=[];
		$(".videos").find(".prohibido").removeClass("prohibido");
		for(var i=0;i<total.length;i++){
			$(".videos").find(".video_"+total[i]).addClass("prohibido");
		}
		switch(id*1){
			case 2:
				$("#_freemiumform2").find(".premi").find(".optionbox>input").attr("checked",false);
				$("#_freemiumform2").find(".premi").css("display","none");
				$("#_freemiumform2").find(".premium_5").css("display","block");
				break;
			case 3:
				$("#_freemiumform2").find(".premi").find(".optionbox>input").attr("checked",false);
				$("#_freemiumform2").find(".premi").css("display","none");
				$("#_freemiumform2").find(".premium_7").css("display","block");
				break;
			case 4:
				$("#_freemiumform2").find(".premi").find(".optionbox>input").attr("checked",false);
				$("#_freemiumform2").find(".premi").css("display","none");
				$("#_freemiumform2").find(".premium_8").css("display","block");
				break;
			case 10:
				$("#_freemiumform2").find(".premi").find(".optionbox>input").attr("checked",false);
				$("#_freemiumform2").find(".premi").css("display","none");
				$("#_freemiumform2").find(".premium_5").css("display","block");
				break;
			case 11:
				$("#_freemiumform2").find(".premi").find(".optionbox>input").attr("checked",false);
				$("#_freemiumform2").find(".premi").css("display","none");
				$("#_freemiumform2").find(".premium_7").css("display","block");
				break;
			case 12:
				$("#_freemiumform2").find(".premi").find(".optionbox>input").attr("checked",false);
				$("#_freemiumform2").find(".premi").css("display","none");
				$("#_freemiumform2").find(".premium_8").css("display","block");
				break;
			default:
		}
	}
}
