var freemium_h=function($){
	$.fn.freemium=function(options){
		freemium_c.ddbb.push(this);
                this.payID=freemium_c.ddbb.length-1;
		this.setp2_callback=freemium_c.setp2_callback;
		this.addVideo=freemium_c.addVideo;
		this.delFoto=freemium_c.delFoto;
		this.delFoto360=freemium_c.delFoto360;
		this.filePreload360=freemium_c.filePreload360;
		this.filePreload=freemium_c.filePreload;
		this.calcularTotalFotos360=freemium_c.calcularTotalFotos360;
		this.calcularTotalFotos=freemium_c.calcularTotalFotos;
		this.productChanged=freemium_c.productChanged;
		this.refreshProductStatus=freemium_c.refreshProductStatus;
		this.showMediaform=freemium_c.showMediaform;
		this.submit1Handler=freemium_c.submit1Handler;
		this.tipoChanged=freemium_c.tipoChanged;
		this.submit2=freemium_c.submit2;
		this.submit1=freemium_c.submit1;
		this.calcpm2=freemium_c.calcpm2;
		this.placesChanged=freemium_c.placesChanged;
		this.placesFix=freemium_c.placesFix;
		this.init=freemium_c.init;
		this.init(options);
	}
}

var freemium_c={
	ddbb:[],
	init:function(options){
		this.options=options;
                var payID=this.payID;
		freemium_c.ddbb.push(this);
		this.payID=freemium_c.ddbb.length-1;
		$(this).find(".filtros>.checkbox>input").change(function(){
			if(this.checked){
				$(this.parentNode).find(".icon").addClass("icon_0");
				$(this.parentNode).find(".icon").removeClass("icon");
				}
			else {
				$(this.parentNode).find(".icon_0").addClass("icon");
				$(this.parentNode).find(".icon_0").removeClass("icon_0");
				}
			});
		this.mapa=new google.maps.Map($(this).find(".mapa>.mapa").get()[0],options.mapOptions);
		options.markerOptions.map=this.mapa;
		this.marcador=new google.maps.Marker(options.markerOptions);
		var form=this;
		google.maps.event.addListener(this.marcador, 'position_changed', function(evento){
					var pos=this.getPosition();
					$(form).find(".mapa>h3>.posicion").text("("+pos.lat()+","+pos.lng()+")");
				});
		$(options.places)[0].form=this;
		$(options.places).keypress(this.placesFix);
		var index=1;
		this.placesBox = new google.maps.places.Autocomplete($(options.places).get()[0], options.placesOptions);
		this.placesBox.form=this;
		google.maps.event.addListener(this.placesBox, 'place_changed',this.placesChanged);
		$(this).find(".cancel").click(function(){
			location.href="/";
			});
		var form=this;
		$(this).find(".submit1").click(function(){
			form.submit1();
		});
		$(this).find(".submit2").click(function(){
			form.submit2();
		});
		
		$(this).find(".precio>input").change(function(){
			form.calcpm2();
		});
		$(this).find(".m2>input").change(function(){
			form.calcpm2();
		});
		$(this).find(".tipo_anuncio>select").change(function(){
			form.tipoChanged(this.value);
		});
		$(this).find(".video>input").change(function(){
			form.addVideo(this.value,this.title);
		});
		$(this).find(".product").find(".optionbox>input").change(function(){
			form.productChanged(this.value);
		});
	},
	tipoChanged:function(valor){
		$(this).find(".plazo").css("display",valor==2?"block":"none");
	},
	calcpm2:function(){
		var precio=$(this).find(".precio>input").val();
		var superficie=$(this).find(".m2>input").val();
		$(this).find(".preciom2>input").attr("value",precio/superficie)
	},
	placesFix:function(){
		$(this.form).find(".pac-container").css("z-index",10000);
	},
	placesChanged:function(){
		var place = this.form.placesBox.getPlace();
		if(place){
			this.form.mapa.setCenter(place.geometry.location);
			this.form.mapa.setZoom(15);
			this.form.marcador.setPosition(place.geometry.location);
			}
	},
	submit1:function(){
		var titulo=$(this).find(".titulo>input").val();
		var telefono=$(this).find(".telefono>input").val();
		var descripcion=$(this).find(".descripcion>textarea").val();
		var tipo_anuncio=$(this).find(".tipo_anuncio>select").val();
		var precio=$(this).find(".precio>input").val();
		var precio_moneda=$(this).find(".precio_moneda>select").val();
		var habitaciones=$(this).find(".habitaciones>select").val();
		var banyos=$(this).find(".banyos>select").val();
		var estacionamientos=$(this).find(".estacionamientos>select").val();
		var m2=$(this).find(".m2>input").val();
		var preciom2=$(this).find(".preciom2>input").val();
		var m2s=$(this).find(".m2s>input").val();
		var anyo=$(this).find(".anyo>select").val();
		var tipos=$(this).find(".tipo>.option>input").get();
		var amueblado=$(this).find(".amueblado>input").get()[0].checked?"1":"0";
		var jardin=$(this).find(".jardin>input").get()[0].checked?"1":"0";
		var lavado=$(this).find(".lavado>input").get()[0].checked?"1":"0";
		var servicio=$(this).find(".servicio>input").get()[0].checked?"1":"0";
		var vestidor=$(this).find(".vestidor>input").get()[0].checked?"1":"0";
		var estudio=$(this).find(".estudio>input").get()[0].checked?"1":"0";
		var tv=$(this).find(".tv>input").get()[0].checked?"1":"0";
		var cocina=$(this).find(".cocina>input").get()[0].checked?"1":"0";
		var chimenea=$(this).find(".chimenea>input").get()[0].checked?"1":"0";
		var terraza=$(this).find(".terraza>input").get()[0].checked?"1":"0";
		var jacuzzi=$(this).find(".jacuzzi>input").get()[0].checked?"1":"0";
		var alberca=$(this).find(".alberca>input").get()[0].checked?"1":"0";
		var vista=$(this).find(".vista>input").get()[0].checked?"1":"0";
		var aire=$(this).find(".aire>input").get()[0].checked?"1":"0";
		var calefaccion=$(this).find(".calefaccion>input").get()[0].checked?"1":"0";
		var bodega=$(this).find(".bodega>input").get()[0].checked?"1":"0";
		var elevador=$(this).find(".elevador>input").get()[0].checked?"1":"0";
		var elevadors=$(this).find(".elevadors>input").get()[0].checked?"1":"0";
		var portero=$(this).find(".portero>input").get()[0].checked?"1":"0";
		var seguridad=$(this).find(".seguridad>input").get()[0].checked?"1":"0";
		var circuito=$(this).find(".circuito>input").get()[0].checked?"1":"0";
		var red=$(this).find(".red>input").get()[0].checked?"1":"0";
		var gimnasio=$(this).find(".gimnasio>input").get()[0].checked?"1":"0";
		var spa=$(this).find(".spa>input").get()[0].checked?"1":"0";
		var golf=$(this).find(".golf>input").get()[0].checked?"1":"0";
		var tenis=$(this).find(".tenis>input").get()[0].checked?"1":"0";
		var juegos=$(this).find(".juegos>input").get()[0].checked?"1":"0";
		var sjuegos=$(this).find(".juegos>input").get()[0].checked?"1":"0";
		var fiestas=$(this).find(".fiestas>input").get()[0].checked?"1":"0";
		var biblioteca=$(this).find(".biblioteca>input").get()[0].checked?"1":"0";
		var eventos=$(this).find(".eventos>input").get()[0].checked?"1":"0";
		var mascotas=$(this).find(".mascotas>input").get()[0].checked?"1":"0";
		var cava=$(this).find(".cava>input").get()[0].checked?"1":"0";
		var hotel=$(this).find(".hotel>input").get()[0].checked?"1":"0";
		var playa=$(this).find(".playa>input").get()[0].checked?"1":"0";
		var muelle=$(this).find(".muelle>input").get()[0].checked?"1":"0";
		var parque=$(this).find(".parque>input").get()[0].checked?"1":"0";
		var tintoreria=$(this).find(".tintoreria>input").get()[0].checked?"1":"0";
		var ecologico=$(this).find(".ecologico>input").get()[0].checked?"1":"0";
		var discapacitados=$(this).find(".discapacitados>input").get()[0].checked?"1":"0";
		var helipuerto=$(this).find(".helipuerto>input").get()[0].checked?"1":"0";
		var condominios=$(this).find(".condominios>input").get()[0].checked?"1":"0";
		var club=$(this).find(".club>input").get()[0].checked?"1":"0";
		var posicion=$(this).find(".posicion").text();
		
		for(var i=0; i<tipos.length;i++){
			if(tipos[i].checked){
				var tipo=tipos[i].value;
			}
		}
		posicion=posicion.replace("(","");
		posicion=posicion.replace(")","");
		
		$(this).find(".titulo").removeClass("error");
		$(this).find(".descripcion").removeClass("error");
		$(this).find(".tipo_anuncio").removeClass("error");
		$(this).find(".precio").removeClass("error");
		$(this).find(".m2").removeClass("error");
		$(this).find(".preciom2").removeClass("error");
		$(this).find(".tipo").removeClass("error");
		$(this).find(".alertForm").css("display","none");
                    
		if(!titulo||titulo.length<2){
			$(this).find(".titulo").addClass("error");
			$(this).find(".alertForm").css("display","block");
			alert($(this).find(".titulo>label").text()+"  ?");
			return false;
		}
		if(!descripcion||descripcion.length<2){
			$(this).find(".descripcion").addClass("error");
			$(this).find(".alertForm").css("display","block");
			alert($(this).find(".descripcion>label").text()+"  ?");
			return false;
		}
		if(!tipo_anuncio||tipo_anuncio.length<1){
			$(this).find(".tipo_anuncio").addClass("error");
			$(this).find(".alertForm").css("display","block");
			alert($(this).find(".tipo_anuncio>label").text()+"  ?");
			return false;
		}
		if(!precio||precio.length<1){
			$(this).find(".precio").addClass("error");
			$(this).find(".alertForm").css("display","block");
			alert($(this).find(".precio>label").text()+"  ?");
			return false;
		}
		if(!m2||m2.length<1){
			$(this).find(".m2").addClass("error");
			$(this).find(".alertForm").css("display","block");
			alert($(this).find(".m2>label").text()+"  ?");
			return false;
		}
		if(!preciom2||preciom2.length<1){
			$(this).find(".preciom2").addClass("error");
			$(this).find(".alertForm").css("display","block");
			alert($(this).find(".preciom2>label").text()+"  ?");
			return false;
		}
		if(!tipo||tipo.length<1){
			$(this).find(".tipo").addClass("error");
			$(this).find(".alertForm").css("display","block");
			alert($(this).find(".tipo>label").text()+"  ?");
			return false;
		}
		
		var url="api/inmueble/add?titulo="+encodeURI(titulo)+
			"&telefono="+encodeURI(telefono)+
			"&descripcion="+encodeURI(descripcion)+
			"&tipo_anuncio="+encodeURI(tipo_anuncio)+
			"&precio="+encodeURI(precio)+
			"&precio_moneda="+encodeURI(precio_moneda)+
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
		this.inmueble=new inmueble({"titulo":titulo,
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
		script.src=url+"&callback=freemium_c.ddbb["+this.payID+"].submit1Handler&idcallback="+this.payID;
		document.getElementsByTagName("head")[0].appendChild(script);
		//console.log(script.src);
		wait(true);
	},
	submit1Handler:function(respuesta){
		if(respuesta.inmuebleKey){
			this.inmueble.id=respuesta.inmuebleKey;
			this.showMediaform(this.inmueble);
		}
		else {
			alert(respuesta.errorDescription);
		}
		wait(false);
	},
	showMediaform:function(inmueble){
		$(this.options.media).find("#_uploadFotoFrame").attr("src","/app/uploadFile/Image?id=1&callback=freemium_c.ddbb["+this.payID+"].filePreload&target=imagen");
		$(this.options.media).find("#_uploadFoto360Frame").attr("src","/app/uploadFile/Image?id=1&callback=freemium_c.ddbb["+this.payID+"].filePreload360&target=imagen360");
		//$.colorbox({inline:true,href:"#_freemiumform2"});
		$(this).find("#_freemiumform").css("display","none");
		$(this).find("#_freemiumform2").css("display","block");
		this.refreshProductStatus();
	},
	refreshProductStatus:function(){
		
		if($(this).find("#product_1").attr("checked"))this.productChanged('1');
		if($(this).find("#product_2").attr("checked"))this.productChanged('2');
		if($(this).find("#product_3").attr("checked"))this.productChanged('3');
		if($(this).find("#product_4").attr("checked"))this.productChanged('4');
		if($(this).find("#product_10").attr("checked"))this.productChanged('10');
		
	},
	calcularTotalFotos:function(){
		var total=0;
		if($(this).find("#product_1").attr("checked"))total=5;
		if($(this).find("#product_2").attr("checked"))total=20;
		if($(this).find("#product_3").attr("checked"))total=20;
		if($(this).find("#product_4").attr("checked"))total=20;
		if($(this).find("#product_10").attr("checked"))total=20;
		if($(this).find("#product_11").attr("checked"))total=20;
		if($(this).find("#product_12").attr("checked"))total=20;
		
		total=total>0?total:5;
		return total;
	},
	calcularTotalFotos360:function(){
		var total=0;
		if($(this).find("#product_1").attr("checked"))total=3;
		if($(this).find("#product_2").attr("checked"))total=10;
		if($(this).find("#product_3").attr("checked"))total=10;
		if($(this).find("#product_4").attr("checked"))total=10;
		if($(this).find("#product_10").attr("checked"))total=10;
		if($(this).find("#product_11").attr("checked"))total=10;
		if($(this).find("#product_12").attr("checked"))total=10;
		
		total=total>0?total:3;
		return total;
	},
	filePreload:function(respuesta,id){
            if((respuesta.error instanceof Array)||(typeof(respuesta.error)=="object")){
                for(var i=0;i<respuesta.error.length;i++){
                    this.inmueble.images[id+i]=respuesta.path[i];
                    var div=document.createElement("div");
                    var img=document.createElement("img");
                    var del=document.createElement("input");
                    var total=this.inmueble.countimages();
                    var permitidas=this.calcularTotalFotos();
                    del.type="button";
                    del.className="button";
                    del.value="X";
                    var form=this;
                    $(del).click(function(e){form.delFoto(e)})
                    img.src=respuesta.path[i];
                    console.log(img.src);
                    div.className=("imagen imagen_"+id+i);
                    div.appendChild(del);
                    div.appendChild(img);
                    div.title=id+i;
                    $(this).find("#_freemiumform2").find(".fotosContainer>.wrap").append(div);
                    $(this).find("#_freemiumform2").find("#_uploadFotoFrame").addClass("totalImages_"+id+i);
                    if(total<permitidas){
                            $(this).find("#_freemiumform2").find("#_uploadFotoFrame").attr("src","/app/uploadFile/Image?id="+(id+i+1)+"&callback=freemium_c.ddbb["+this.payID+"].filePreload&target=imagen");
                            }
                    else{
                            $(this).find("#_freemiumform2").find(".fotosContainer>.wrap>.new").css("display","none");
                            $(this).find("#_freemiumform2").find("#_uploadFotoFrame").attr("src","/app/uploadFile/Image?id="+(id+i+1)+"&callback=freemium_c.ddbb["+this.payID+"].filePreload&target=imagen");
                            }
                }
            }
            else {
		this.inmueble.images[id]=respuesta.path;
		var div=document.createElement("div");
		var img=document.createElement("img");
		var del=document.createElement("input");
		var total=this.inmueble.countimages();
		var permitidas=this.calcularTotalFotos();
		del.type="button";
		del.className="button";
		del.value="X";
		var form=this;
		$(del).click(function(e){form.delFoto(e)})
		img.src=respuesta.path;
		div.className=("imagen imagen_"+id);
		div.appendChild(del);
		div.appendChild(img);
		div.title=id;
		$(this).find("#_freemiumform2").find(".fotosContainer>.wrap").append(div);
		$(this).find("#_freemiumform2").find("#_uploadFotoFrame").addClass("totalImages_"+id);
		if(total<permitidas){
			$(this).find("#_freemiumform2").find("#_uploadFotoFrame").attr("src","/app/uploadFile/Image?id="+(id+1)+"&callback=freemium_c.ddbb["+this.payID+"].filePreload&target=imagen");
			}
		else{
			$(this).find("#_freemiumform2").find(".fotosContainer>.wrap>.new").css("display","none");
			$(this).find("#_freemiumform2").find("#_uploadFotoFrame").attr("src","/app/uploadFile/Image?id="+(id+1)+"&callback=freemium_c.ddbb["+this.payID+"].filePreload&target=imagen");
			}
            }
		wait(false);
	},
	filePreload360:function(respuesta,id){
            
            if((respuesta.error instanceof Array)||(typeof(respuesta.error)=="object")){
                for(var i=0;i<respuesta.error.length;i++){
                    this.inmueble.images360[id+i]=respuesta.path[i];
                    var div=document.createElement("div");
                    var img=document.createElement("img");
                    var del=document.createElement("input");
                    var total=this.inmueble.countimages360();
                    var permitidas=this.calcularTotalFotos360();
                    del.type="button";
                    del.className="button";
                    del.value="X";
                    var form=this;
                    $(del).click(function(e){form.delFoto360(e)})
                    img.src=respuesta.path[i];
                    div.className=("imagen imagen_"+id+i);
                    div.appendChild(del);
                    div.appendChild(img);
                    div.title=id+i;
                    $(this).find("#_freemiumform2").find(".fotos360Container>.wrap").append(div);
                    $(this).find("#_freemiumform2").find("#_uploadFoto360Frame").addClass("totalImages_"+id+i);
                    if(total<permitidas){
                            $(this).find("#_freemiumform2").find("#_uploadFoto360Frame").attr("src","/app/uploadFile/Image?id="+(id+i+1)+"&callback=freemium_c.ddbb["+this.payID+"].filePreload360&target=imagen360");
                            }
                    else{
                            $(this).find("#_freemiumform2").find(".fotos360Container>.wrap>.new").css("display","none");
                            $(this).find("#_freemiumform2").find("#_uploadFoto360Frame").attr("src","/app/uploadFile/Image?id="+(id+i+1)+"&callback=freemium_c.ddbb["+this.payID+"].filePreload360&target=imagen360");
                            }
                }
            }
            else {
		this.inmueble.images360[id]=respuesta.path;
		var div=document.createElement("div");
		var img=document.createElement("img");
		var del=document.createElement("input");
		var total=this.inmueble.countimages360();
		var permitidas=this.calcularTotalFotos360();
		del.type="button";
		del.className="button";
		del.value="X";
		var form=this;
		$(del).click(function(e){form.delFoto360(e)})
		img.src=respuesta.path;
		div.className=("imagen imagen_"+id);
		div.appendChild(del);
		div.appendChild(img);
		div.title=id;
		$(this).find("#_freemiumform2").find(".fotos360Container>.wrap").append(div);
		$(this).find("#_freemiumform2").find("#_uploadFoto360Frame").addClass("totalImages_"+id);
		if(total<permitidas){
			$(this).find("#_freemiumform2").find("#_uploadFoto360Frame").attr("src","/app/uploadFile/Image?id="+(id+1)+"&callback=freemium_c.ddbb["+this.payID+"].filePreload360&target=imagen360");
			}
		else{
			$(this).find("#_freemiumform2").find(".fotos360Container>.wrap>.new").css("display","none");
			$(this).find("#_freemiumform2").find("#_uploadFoto360Frame").attr("src","/app/uploadFile/Image?id="+(id+1)+"&callback=freemium_c.ddbb["+this.payID+"].filePreload360&target=imagen360");
			}
            }
		wait(false);
	},
	delFoto:function(e){
		var id=(e.target.parentNode.title)*1;
		var permitidas=this.calcularTotalFotos();
		delete this.inmueble.images[id];
		$(e.target.parentNode).remove();
		var total=this.inmueble.countimages();
		if(total<permitidas){
			$(this).find("#_freemiumform2").find(".fotosContainer>.wrap>.new").css("display","block");
		}
		else {
			$(this).find("#_freemiumform2").find(".fotosContainer>.wrap>.new").css("display","none");
		}
	},
	delFoto360:function(e){
		var id=(e.target.parentNode.title)*1;
		var permitidas=this.calcularTotalFotos360();
		delete this.inmueble.images360[id];
		$(e.target.parentNode).remove();
		var total=this.inmueble.countimages360();
		if(total<permitidas){
			$(this).find("#_freemiumform2").find(".fotos360Container>.wrap>.new").css("display","block");
		}
		else {
			$(this).find("#_freemiumform2").find(".fotos360Container>.wrap>.new").css("display","none");
		}
	},
	productChanged:function(id){
		var permitidas=this.calcularTotalFotos();
		var total=this.inmueble.countimages();
		if(total<permitidas){
			$(this).find("#_freemiumform2").find(".fotosContainer>.wrap>.new").css("display","block");
		}
		else {
			$(this).find("#_freemiumform2").find(".fotosContainer>.wrap>.new").css("display","none");
		}
		var permitidas360=this.calcularTotalFotos360();
		var total360=this.inmueble.countimages360();
		if(total360<permitidas360){
			$(this).find("#_freemiumform2").find(".fotos360Container>.wrap>.new").css("display","block");
		}
		else {
			$(this).find("#_freemiumform2").find(".fotos360Container>.wrap>.new").css("display","none");
		}
		total=[1,2,3,4];
		if($(this).find("#product_2").attr("checked"))total=[];
		if($(this).find("#product_3").attr("checked"))total=[];
		if($(this).find("#product_4").attr("checked"))total=[];
		if($(this).find("#product_10").attr("checked"))total=[];
		if($(this).find("#product_11").attr("checked"))total=[];
		if($(this).find("#product_12").attr("checked"))total=[];
		$(".videos").find(".prohibido").removeClass("prohibido");
		for(var i=0;i<total.length;i++){
			$(this).find(".videos").find(".video_"+total[i]).addClass("prohibido");
		}
		switch(id*1){
			case 2:
				$(this).find("#_freemiumform2").find(".premi").find(".optionbox>input").attr("checked",false);
				$(this).find("#_freemiumform2").find(".premi").css("display","none");
				$(this).find("#_freemiumform2").find(".premium_5").css("display","block");
				break;
			case 3:
				$(this).find("#_freemiumform2").find(".premi").find(".optionbox>input").attr("checked",false);
				$(this).find("#_freemiumform2").find(".premi").css("display","none");
				$(this).find("#_freemiumform2").find(".premium_7").css("display","block");
				break;
			case 4:
				$(this).find("#_freemiumform2").find(".premi").find(".optionbox>input").attr("checked",false);
				$(this).find("#_freemiumform2").find(".premi").css("display","none");
				$(this).find("#_freemiumform2").find(".premium_8").css("display","block");
				break;
			case 10:
				$(this).find("#_freemiumform2").find(".premi").find(".optionbox>input").attr("checked",false);
				$(this).find("#_freemiumform2").find(".premi").css("display","none");
				$(this).find("#_freemiumform2").find(".premium_5").css("display","block");
				break;
			case 11:
				$(this).find("#_freemiumform2").find(".premi").find(".optionbox>input").attr("checked",false);
				$(this).find("#_freemiumform2").find(".premi").css("display","none");
				$(this).find("#_freemiumform2").find(".premium_7").css("display","block");
				break;
			case 12:
				$(this).find("#_freemiumform2").find(".premi").find(".optionbox>input").attr("checked",false);
				$(this).find("#_freemiumform2").find(".premi").css("display","none");
				$(this).find("#_freemiumform2").find(".premium_8").css("display","block");
				break;
			default:
		}
	},
	addVideo:function (url,id,formulario,id_inmueble){
		var videoregex=/(http[s]?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/[a-z0-9=\/\.&\?\-_]*/i;
		var videoclean=/(http[s]?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\//i;
		var form=formulario?formulario:this;
		if(videoregex.test(url)){
			var key=url.replace(videoclean,"");
			var key=key.replace("watch?v=","");
			var video=document.createElement("iframe");
			video.className="video";
			video.src="http://www.youtube.com/embed/"+key+"?enablejsapi=0&origin=http://www.e-spacios.com";
			video.frameborder="0";
			$(form).find(".video_"+id+">.videoContainer").empty();
			$(form).find(".video_"+id+">.videoContainer").append(video);
			if(this.inmueble){
				this.inmueble.videos[id]=key;
				}
			if(id_inmueble){
				var url="/api/inmueble/addVideo?id="+id_inmueble+"&key="+key+"&index="+id;
				var script=document.createElement("script");
				script.type="text/javascript";
				script.src=url+"&callback=&idcallback=0";
				document.getElementsByTagName("head")[0].appendChild(script);
				
			}
		}
		else {
			delete this.inmueble.videos[id];
			$(form).find(".video_"+id+">.videoContainer").empty();

		}
	},
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
		var id=this.inmueble.id;
		var pago=null;
		var method=null;
		var premium=null;
		
		
		var methods=$("#_freemiumform2").find(".formasdepago>.fp>.option>input").get();
		for(var i in this.inmueble.images){
			fotos.push(this.inmueble.images[i]);
		}
		for(var i in this.inmueble.images360){
			fotos360.push(this.inmueble.images360[i]);
		}
		if(fotos.length<1){
			alert("foto?");
			$("#_freemiumform2").find(".alertForm").css("display","block");
			return false;
		}
		for(i in this.inmueble.videos){
			videos.push(this.inmueble.videos[i]);
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
		"&callback=freemium_c.ddbb["+this.payID+"].setp2_callback&idcallback="+id;
		
		var script=document.createElement("script");
		script.type="text/javascript";
		script.src=url;
		//alert(url);
		wait(true);
		return document.getElementsByTagName("head")[0].appendChild(script);
	},
	setp2_callback:function(respuesta,id){
		if(respuesta.token){
			if(respuesta.follow){
				location.href=respuesta.follow;
			}
			else{
				alert(respuesta.paymentMessage);
				//$.colorbox.close();
				location.reload();
			}
			//freemium.showPaypalButton(url,respuesta.token.total);
		}
		else {
			alert(respuesta.paymentMessage);
				//$.colorbox.close();
				location.reload();
		}
	}
}



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






freemium_h(jQuery);