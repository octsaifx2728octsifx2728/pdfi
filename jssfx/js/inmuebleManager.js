metricaconverter.locked=true;
function callAjax(urlStr){
        if (window.XMLHttpRequest)
      {// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
      }
    else
      {// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    xmlhttp.onreadystatechange=function()
      {
      if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            window.location.href= xmlhttp.responseText;        
        }
      }
    xmlhttp.open("GET",urlStr,true);
    xmlhttp.send();
}
var inmueble_manager={
	lockHandler:false,
	editingID:0,
	borrar:function(id,text,tipo){
		if(true){
			var script=document.createElement("script");
			script.src="/api/inmueble/borrar?id="+id+"&tipo="+tipo+"&callback=inmueble_manager.borrarHandler&idcallback="+id;
			script.type="text/javascript";
			wait(true);
			document.getElementsByTagName("head")[0].appendChild(script);
                        console.log(script.src);
		}
	},
	borrarHandler:function(respuesta,id){
		if(respuesta.error=="0"){
			$(".item_"+id+"_"+respuesta.tipo).remove();
                        console.log(".item_"+id+"_"+respuesta.tipo);
		}
		else {
			alert(respuesta.errorDescription);
		}
			wait(false);
	},
	toggle:function(id,nombre,tipo){
		var valor=document.getElementById("atributo_"+nombre+"_"+id).value*1;
		var nuevovalor;
		switch(nombre){
			case "casa":
			  nuevovalor=(valor=="1")?"2":"1";
			  break;
			default:
			  nuevovalor=(valor=="1")?"0":"1";
		}
			$(".item_"+id).find("."+nombre+"_"+valor+">input").val(nuevovalor);
			$(".item_"+id).find("."+nombre+"_"+valor).addClass(""+nombre+"_"+(nuevovalor=="0"?"0":"1"));
			$(".item_"+id).find("."+nombre+"_"+valor).removeClass(""+nombre+"_"+(valor=="0"?"0":"1"));
                        
			var script=document.createElement("script");
			script.src="/api/inmueble/update?id="+id+"&tipo="+tipo+"&atributo="+nombre+"&valor="+nuevovalor+"&callback=inmueble_manager.toggleHandler&idcallback="+id;
			script.type="text/javascript";
			//wait(true);
			document.getElementsByTagName("head")[0].appendChild(script);
                        console.log(script.src);
	},
	toggleHandler:function(respuesta,id){
		
		if(respuesta.error=="0"){
                    console.log(respuesta);
                    if(respuesta.valor=="true"||respuesta.valor==true){
                        respuesta.valor="1";
                    }
                    if(respuesta.valor=="false"||respuesta.valor==false){
                        respuesta.valor="0";
                    }
			//document.getElementById("atributo_"+respuesta.atributo+"_"+id).value=respuesta.valor;
			
		}
		else {
			alert(respuesta.errorDescription);
		}
			wait(false);
	},
	update:function(id,nombre,valor,tipo){
            if(!id||!nombre||!tipo){
                console.log("falta un valor");
                return false;
            }
			var script=document.createElement("script");
			script.src="/api/inmueble/update?id="+id+"&tipo="+tipo+"&atributo="+nombre+"&valor="+encodeURIComponent(valor)+"&callback=inmueble_manager.updateHandler&idcallback="+id;
			script.type="text/javascript";
			wait(true);
			document.getElementsByTagName("head")[0].appendChild(script);
			console.log(script.src);
	},
	updateHandler:function(respuesta,id){
		
		if(respuesta.error=="0"){
		      if(!inmueble_manager.lockHandler){
			switch(respuesta.atributo){
			  case "preciom2":
			    var pm2=respuesta.valor;
			    var m2=$(".inmueblesUsuario").find(".item_"+id).find(".m2>input");
			    var p=$(".inmueblesUsuario").find(".item_"+id).find(".precio_data>input");
			    p.val(pm2*m2.val());
			    inmueble_manager.lockHandler=true;
			    
			    break;
			  case "m2s":
			    inmueble_manager.lockHandler=true;
                            switch(respuesta.tipo){
                                case "5":
                                    
                                    var pm2=$(".inmueblesUsuario").find(".item_"+id).find(".preciom2>input");
                                    var m2s=respuesta.valor;
                                    var p=$(".inmueblesUsuario").find(".item_"+id).find(".precio_data>input");
                                    pm2.val(Math.round((p.val().replace(",","")/m2s)*10)/100);
                                    break;
                                }
			    break;
			  case "m2":
			    var pm2=$(".inmueblesUsuario").find(".item_"+id).find(".preciom2>input");
			    var m2=respuesta.valor;
			    var p=$(".inmueblesUsuario").find(".item_"+id).find(".precio_data>input");
			    pm2.val(p.val().replace(",","")/m2);
			    inmueble_manager.lockHandler=true;
                            switch(respuesta.tipo){
                                default:
                                    inmueble_manager.update(id,"preciom2",pm2.val(),respuesta.tipo);
                                }
			    break;
			  case "precio":
			    var pm2=$(".inmueblesUsuario").find(".item_"+id).find(".preciom2>input");
                            switch(respuesta.tipo){
                                case "5":
                                    var m2=$(".inmueblesUsuario").find(".item_"+id).find(".m2s>input");
                                    break;
                                default:
                                    var m2=$(".inmueblesUsuario").find(".item_"+id).find(".m2>input");
                            }
			    var p=respuesta.valor;
			    pm2.val(p/m2.val());
			    inmueble_manager.lockHandler=true;
			    break;
                          case "activo":
                              if(respuesta.valor=="paymentRequired"){
                                  var url="api/inmueble/getAnuncioPretend?id="+id+"&tipo="+respuesta.tipo+"&callback=inmueble_manager.paymentHandler&idcallback="+id;
                                 
                                var script=document.createElement("script");
                                script.src=url;
                                script.type="text/javascript";
                                console.log(script.src);
                                wait(true);
                                document.getElementsByTagName("head")[0].appendChild(script);
                                $(".item_"+id+"_"+respuesta.tipo).removeClass("activo_1");
                                $(".item_"+id+"_"+respuesta.tipo).addClass("activo_0");
                              }
                              break;
			  default:
			}
		      }
		      else {
			    inmueble_manager.lockHandler=false;
		      }
		}
		else {
			    inmueble_manager.lockHandler=false;
			alert(respuesta.errorDescription);
		}
			wait(false);
	},
        paymentHandler:function(resp,id){
            
            var resp=resp;
            var id=id;
            
        if(resp.pretend.gratis){
            $("#_renovar").find(".formasdepago").hide();
            $("#_renovar").find(".terminos").hide();
            if(resp.pretend.product){
                var div="<div class='producto standar module3'>"+
                    "<div class='wrap cabecera'>"+resp.pretend.product.nombre+"</div>"+
                    "<div class='wrap2'><div class='product standar module2'>"+
                    "<div class='wrap'><div class='label'><div class='title'>"+resp.pretend.product.nombre+"</div>"+
                    "<div class='des'>"+resp.pretend.product.descripcion+"</div>"+
                    "</div></div></div></div></div>";
              $("#_renovar").find(".pago").empty();
              $("#_renovar").find(".pago").append(div);
              $("#_renovar").find(".producto").find(".label").click(function(){
                 // console.log(resp);
                  var url="/app/inmueble/add2?id="+id+
                      "&tipo="+resp.tipo+
                      "&productos="+resp.pretend.product.id+
                      "&method=1"
                      ;
                      callAjax(url);
                  wait(true);
                  //location.href=url;
              });
            }
        }
        else {
            $("#_renovar").find(".submitPago").show();
            $("#_renovar").find(".terminos").hide();
            $("#_renovar").find(".formasdepago").show();
            if(resp.pretend.product){
                //console.log(resp.pretend);
                var div="<div class='producto standar module3'>"+
                    "<div class='wrap cabecera'>ANUNCIOS ESTÁNDAR</div>"+
                    "<div class='wrap2'><div class='product standar module2'>"+
                    "<div class='wrap'><div class='label'><div class='title'>"+resp.pretend.product.nombre+" $ "+resp.pretend.product.precio+" USD</div>"+
                    "<div class='des'>"+resp.pretend.product.descripcion+"</div>"+
                    "</div></div></div></div></div>";
              $("#_renovar").find(".pago").empty();
              $("#_renovar").find(".pago").append(div);
              $("#_renovar").find(".producto").find(".label").click(function(){
                  var url="/app/inmueble/add2?id="+id+
                      "&tipo="+resp.tipo+
                      "&productos="+resp.pretend.product.id+
                      "&method="+$("#_renovar").find(".formadepago>input:checked").val()
                      ;
                      callAjax(url);
                  wait(true);
                  //location.href=url;
              });
            }
        }
            
            
            $.colorbox({inline:true,href:"#_renovar"});
        },
	showFotoWindow:function(id,tipo){
		inmueble_manager.editingID=[id,tipo];
			var script=document.createElement("script");
			script.src="/api/inmueble/getFotos?id="+id+"&tipo="+tipo+"&callback=inmueble_manager.showfotoHandler1&idcallback="+id;
			script.type="text/javascript";
			console.log(script.src);
			wait(true);
			$("#_editarFotos_"+id+"_"+tipo).find(".fotosContainer").find(".foto").remove();
                        
			$("#_editarFotos_"+id+"_"+tipo).attr("title",id+"_"+tipo);
			document.getElementsByTagName("head")[0].appendChild(script);
                        
	},
	showfotoHandler1:function(respuesta,id){
		if(respuesta.error=="0"){
			for(var i=0; i<respuesta.fotos.length;i++){
				if(respuesta.fotos[i].panoramica==0){
					inmueble_manager.cargarFoto(respuesta.fotos[i].id,respuesta.fotos[i].path,id,respuesta.tipo);
					}
				else{
					inmueble_manager.cargarFoto360(respuesta.fotos[i].id,respuesta.fotos[i].path,id,respuesta.tipo);
				}
				
			}	
			for(var  i=0; i<respuesta.videos.length;i++){
				var video=document.createElement("iframe");
				video.className="video";
				video.src="http://www.youtube.com/embed/"+respuesta.videos[i]+"?enablejsapi=0&origin=http://www.e-spacios.com";
				video.frameborder="0";
				$("#_editarFotos_"+id+"_"+respuesta.tipo).find(".video_"+i+">.videoContainer").empty();
				$("#_editarFotos_"+id+"_"+respuesta.tipo).find(".video_"+i+">.videoContainer").append(video);
			}
                        var uploader1=$("#_editarFotos_"+id+"_"+respuesta.tipo).find("#_uploadFotoFrame");
                        var uploader2=$("#_editarFotos_"+id+"_"+respuesta.tipo).find("#_uploadFoto360Frame");
			uploader1.attr("src","/app/uploadFile/Image?id="+id+"&tipo="+respuesta.tipo+"&callback=inmueble_manager.showfotoHandler2&target=imagen");
			uploader2.attr("src","/app/uploadFile/Image?id="+id+"&tipo="+respuesta.tipo+"&callback=inmueble_manager.showfotoHandler2360&target=imagen360");
                        /*$("#_editarFotos_"+id+"_"+respuesta.tipo).find(".uploadOverlay1").click(function(){
                             var doc = $(uploader1)[0].contentWindow.document;
                             $(doc).find("input[type=file]").focus();
                             console.log( $(doc).find("input[type=file]").get());
                        });
                        uploader1.hide();*/
			$.colorbox({inline:true,href:"#_editarFotos_"+id+"_"+respuesta.tipo});
		}
		else {
			alert(respuesta.errorDescription);
		}
			wait(false)
	},
	cargarFoto:function(id, path,inmueble,tipo){
				var div=document.createElement("div");
				var img=document.createElement("img");
				var del=document.createElement("input");
				path="/cache/250/190/"+path;
				del.type="button";
				del.className="button";
				del.value="X";
				del.name=id+"_"+inmueble+"_"+tipo;
				$(del).click(inmueble_manager.delFoto);
				img.src=path;
				div.className=("imagen foto imagen_"+id);
				div.appendChild(del);
				div.appendChild(img);
				div.title=id;
				if($("#_editarFotos_"+inmueble+"_"+tipo).find(".foto").get().length==0){
					$(".inmueblesUsuario").find(".item_"+inmueble+"_"+tipo).find(".imagen>img").attr("src",path);
				}
				$("#_editarFotos_"+inmueble+"_"+tipo).find(".fotosContainer").append(div);
			inmueble_manager.setUploadButton();
		
	},
	cargarFoto360:function(id, path,inmueble,tipo){
				var div=document.createElement("div");
				var img=document.createElement("img");
				var del=document.createElement("input");
				path="/cache/250/190/"+path;
				del.type="button";
				del.className="button";
				del.value="X";
				del.name=id+"_"+inmueble+"_"+tipo;
				$(del).click(inmueble_manager.delFoto360);
				img.src=path;
				div.className=("imagen foto imagen360_"+id);
				div.appendChild(del);
				div.appendChild(img);
				div.title=id;
				$("#_editarFotos_"+inmueble+"_"+tipo).find(".fotos360Container").append(div);
                                
			inmueble_manager.setUploadButton360();
		
	},
	delFoto:function(e){
		var key=e.target.name.toString().split("_");
		var idfoto=key[0];
		var id=key[1];
		var tipo=key[2];
		var script=document.createElement("script");
		script.src="/api/inmueble/delFoto?id="+id+"&tipo="+tipo+"&foto="+idfoto+"&callback=inmueble_manager.showfotoHandler4&idcallback="+id+"_"+tipo;
		script.type="text/javascript";
			wait(true)
		document.getElementsByTagName("head")[0].appendChild(script);
                console.log(script.src);
	},
	delFoto360:function(e){
		var key=e.target.name.toString().split("_");
		var idfoto=key[0];
		var id=key[1];
		var tipo=key[2];
		var script=document.createElement("script");
		script.src="/api/inmueble/delFoto?id="+id+"&tipo="+tipo+"&foto="+idfoto+"&callback=inmueble_manager.showfotoHandler4360&idcallback="+id+"_"+tipo;
		script.type="text/javascript";
			wait(true)
		document.getElementsByTagName("head")[0].appendChild(script);
	},
	showfotoHandler2:function(respuesta,id,tipo){
		if((respuesta.error instanceof Array)||(typeof(respuesta.error)=="object")){
                    for(var i=0;i<respuesta.error.length;i++){
                        var script=document.createElement("script");
                        script.src="/api/inmueble/addFoto?id="+id+"&tipo="+tipo+"&path="+encodeURI(respuesta.path[i])+"&callback=inmueble_manager.showfotoHandler3&idcallback="+id;
                        $("#_editarFotos_"+id+"_"+tipo).find("#_uploadFotoFrame").attr("src","/app/uploadFile/Image?id="+id+"&tipo="+tipo+"&callback=inmueble_manager.showfotoHandler2&target=imagen");
                        script.type="text/javascript";
                        console.log(script.src);
                        document.getElementsByTagName("head")[0].appendChild(script);
                    }
                    }
                else {
                    var id=inmueble_manager.editingID;
                    var script=document.createElement("script");
                    script.src="/api/inmueble/addFoto?id="+id+"&path="+encodeURI(respuesta.path)+"&callback=inmueble_manager.showfotoHandler3&idcallback="+id;
                    $("#_editarFotos_"+id).find("#_uploadFotoFrame").attr("src","/app/uploadFile/Image?id="+id+"&callback=inmueble_manager.showfotoHandler2&target=imagen");
                    script.type="text/javascript";
                    document.getElementsByTagName("head")[0].appendChild(script);
                }
			
	},
	showfotoHandler2360:function(respuesta,id,tipo){
		
		if((respuesta.error instanceof Array)||(typeof(respuesta.error)=="object")){
                    for(var i=0;i<respuesta.error.length;i++){
                    var script=document.createElement("script");
                    script.src="/api/inmueble/addFoto360?id="+id+"&tipo="+tipo+"&path="+encodeURI(respuesta.path[i])+"&callback=inmueble_manager.showfotoHandler3360&idcallback="+id;
                    $("#_editarFotos_"+id+"_"+tipo).find("#_uploadFoto360Frame").attr("src","/app/uploadFile/Image?id="+id+"&tipo="+tipo+"&callback=inmueble_manager.showfotoHandler2360&target=imagen360");
                    script.type="text/javascript";
                    document.getElementsByTagName("head")[0].appendChild(script);
                        
                    }
                }
                else {
                    var id=inmueble_manager.editingID;
                    var script=document.createElement("script");
                    script.src="/api/inmueble/addFoto360?id="+id+"&path="+encodeURI(respuesta.path)+"&callback=inmueble_manager.showfotoHandler3360&idcallback="+id;
                    $("#_editarFotos_"+id).find("#_uploadFoto360Frame").attr("src","/app/uploadFile/Image?id="+id+"&callback=inmueble_manager.showfotoHandler2360&target=imagen360");
                    script.type="text/javascript";
                    document.getElementsByTagName("head")[0].appendChild(script);
                }
			
	},
	showfotoHandler3:function(respuesta,id){
		
		if(respuesta.error=="0"){
			inmueble_manager.cargarFoto(respuesta.foto.id,respuesta.foto.path,respuesta.id,respuesta.tipo);
                        
                        var newsrc=$("#_editarFotos_"+respuesta.id+"_"+respuesta.tipo).find(".foto>img").attr("src");
                        
			$(".inmueblesUsuario").find(".item_"+respuesta.id).find(".imagen>img").attr("src",newsrc?newsrc:"/cache/250/190/galeria/imagenes/sinimagen.jpg");
                        
		}
		else {
			alert(respuesta.errorDescription);
		}
			wait(false)
	},
	showfotoHandler3360:function(respuesta,id){
		
		if(respuesta.error=="0"){
			inmueble_manager.cargarFoto360(respuesta.foto.id,respuesta.foto.path,respuesta.id,respuesta.tipo);
		}
		else {
			alert(respuesta.errorDescription);
		}
			wait(false)
	},
	setUploadButton:function(){
		total=$("#_editarFotos").find("foto").get().length;
		if(total>=20){
			$("#_editarFotos").find("new").css("display","none");
		}
		else {
			$("#_editarFotos").find("new").css("display","block");
		}
	},
	setUploadButton360:function(){
		total=$("#_editarFotos").find("foto").get().length;
		if(total>=20){
			$("#_editarFotos").find("new").css("display","none");
		}
		else {
			$("#_editarFotos").find("new").css("display","block");
		}
	},
	showfotoHandler4:function(respuesta,id){
		
		if(respuesta.error=="0"){
			var idfoto=respuesta.foto;
			$("#_editarFotos_"+id).find(".imagen_"+idfoto).remove();
			var idi=inmueble_manager.editingID;
			inmueble_manager.setUploadButton();
                        var newsrc=$("#_editarFotos_"+id).find(".foto>img").attr("src");
			$(".inmueblesUsuario").find(".item_"+idi).find(".imagen>img").attr("src",newsrc?newsrc:"/cache/250/190/galeria/imagenes/sinimagen.jpg");

		}
		else {
			alert(respuesta.errorDescription);
		}
			wait(false)
	},
	showfotoHandler4360:function(respuesta,id){
		
		if(respuesta.error=="0"){
			var idfoto=respuesta.foto;
			$("#_editarFotos_"+id).find(".imagen360_"+idfoto).remove();
			inmueble_manager.setUploadButton();
		}
		else {
			alert(respuesta.errorDescription);
		}
			wait(false)
	},
	renovar:function(id){
			$("#_renovar").attr("title",id);
			$.colorbox({inline:true,href:"#_renovar"});
			freemium.inmueble=new inmueble(id);
	},
	renovar2:function(){
		var id=$("#_renovar").attr("title");
		var toc=document.getElementById("TOC2").checked;
		$("#_renovar").find(".alertForm").css("display","none");
		$("#_renovar").find(".alertForm2").css("display","none");
		
		if(!toc){
			
			$("#_renovar").find(".alertForm").css("display","block");
			return false;
		}
		
		
		var productos=$("#_renovar").find(".optionbox>input").get();
		var prods=[];
		for (var i=0;i<productos.length;i++){
			if(productos[i].checked){
				var valor=productos[i].value;
				valor=valor.replace("_renovar","");
				prods.push(valor);
			}
		}
		
		if(prods.length<1){
			$("#_renovar").find(".alertForm").css("display","block");
			return false;
		}
		
		url="/api/inmueble/add2?id="+id+"&productos="+prods.join(",")+"&callback=inmueble_manager.renovarcallback&idcallback="+id;
		//alert(url);
		//return false;
		var script=document.createElement("script");
		script.type="text/javascript";
		script.src=url;
		wait(true);
		return document.getElementsByTagName("head")[0].appendChild(script);
	},
	renovarcallback:function(respuesta,id){
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
	mapa:null,
	marcador:null,
	center:null,
	editmapa:function(id,posicion,tipo,posicionContainer){
         
            var tipo=tipo;
            var posicionContainer=posicionContainer;
            posicion=posicion.split("_");
		inmueble_manager.center=  new google.maps.LatLng(posicion[0], posicion[1]);
		$.colorbox({inline:true,
			href:'#editarMapa_'+id,
			onComplete:function(){
				var container=document.getElementById("editarMapa_"+id+"_container");
				$(container).empty();
				inmueble_manager.mapa=new google.maps.Map(container,freemium.mapOptions);
				inmueble_manager.mapa.setCenter(inmueble_manager.center);
				inmueble_manager.mapa.setZoom(15);
				inmueble_manager.marcador=new google.maps.Marker({
					draggable:true,
					map:inmueble_manager.mapa,
					position:inmueble_manager.center,
				      icon:"img/casa.gif",
				      optimized:false
					});
				inmueble_manager.marcador.inmuebleid=id;
				google.maps.event.addListener(inmueble_manager.marcador, 'dragend',function(){
					var posicion=this.getPosition();
                                        $(posicionContainer).val(posicion.lat()+"_"+posicion.lng());
					inmueble_manager.update(this.inmuebleid,"posicion",posicion.lat()+"_"+posicion.lng(),tipo);
				});
                                
                            var input = $("#editarMapa_"+id+"").find(".inputtext").get()[0];
                            var options = {};
                            var autocomplete = new google.maps.places.Autocomplete(input, options);
                            
                            $(input).keyup(function(){
                                $(".pac-container").css({"z-index":"9999"});
                            });
                            google.maps.event.addListener(autocomplete, 'place_changed',function(){
                                var place = autocomplete.getPlace();
                                if(place){

                                    if(place.geometry.viewport)
                                    {
                                            var bound=place.geometry.viewport;
                                    }
                                    else{
                                            var sw=new google.maps.LatLng(place.geometry.location.lat()+0.0045,place.geometry.location.lng()-0.0045);
                                            var ne=new google.maps.LatLng(place.geometry.location.lat()-0.0045,place.geometry.location.lng()+0.0045);
                                            var bound=new google.maps.LatLngBounds(sw,ne);
                                    }
                                    if(bound){
                                         inmueble_manager.mapa.setCenter(bound.getCenter());
                                         inmueble_manager.mapa.fitBounds(bound);
                                         $(posicionContainer).val(bound.getCenter().lat()+"_"+bound.getCenter().lng());
                                         inmueble_manager.marcador.setPosition(bound.getCenter());
					inmueble_manager.update(id,"posicion",bound.getCenter().lat()+"_"+bound.getCenter().lng(),tipo);
                                    }
                                }
                            });
				
			}
			});
	},
	editDireccion:function(id){
         
		$.colorbox({inline:true,
			href:'#editarDireccion_'+id
			});
	},
	productChanged:function(id){
		id=id.replace("_renovar","");
		switch(id*1){
			case 2:
				$("#_renovar").find(".premi").find(".optionbox>input").attr("checked",false);
				$("#_renovar").find(".premi").css("display","none");
				$("#_renovar").find(".premium_5").css("display","block");
				break;
			case 3:
				$("#_renovar").find(".premi").find(".optionbox>input").attr("checked",false);
				$("#_renovar").find(".premi").css("display","none");
				$("#_renovar").find(".premium_7").css("display","block");
				break;
			case 4:
				$("#_renovar").find(".premi").find(".optionbox>input").attr("checked",false);
				$("#_renovar").find(".premi").css("display","none");
				$("#_renovar").find(".premium_8").css("display","block");
				break;
			default:
		}
	},
        attachProduct:function(id,tipo,producto){
            var url="/api/products/getProductInfo/"+producto+"?callback=inmueble_manager.showProduct&idcallback="+id+"_"+tipo+"&id="+id+"&tipo="+tipo;
            var script=document.createElement("script");
            script.src=url;
            console.log(url);
            document.getElementsByTagName("head")[0].appendChild(script);
        },
        showProduct:function(respuesta,id){
            if(respuesta.error=="0"&&respuesta.datos){
                var div='<div class="section pago"><div class="producto productoid_'+respuesta.datos.id+' standar module3"><div class="wrap cabecera">'+respuesta.datos.nombre+'</div><div class="wrap2"><div class="product standar module2"><div class="wrap"><div class="label productid_'+respuesta.datos.id+' inmueble_'+respuesta.datos.inmueble+'  inmuebletipo_'+respuesta.datos.inmuebleTipo+'"><div class="title">'+respuesta.datos.descripcion+'<br>$ '+respuesta.datos.precio+' USD</div><div class="des"></div></div></div></div></div></div></div>';
                
                
            $("#_renovar").find(".submitPago").show();
            $("#_renovar").find(".terminos").hide();
            $("#_renovar").find(".formasdepago").show();
           
                
              $("#_renovar").find(".pago").empty();
              $("#_renovar").find(".pago").append(div);
              $("#_renovar").find(".producto").find(".label").click(function(){
                  var params=inmueble_manager.parseClassString(this.className);
                  var url="/app/inmueble/add2?id="+params.inmueble+
                      "&tipo="+params.inmuebletipo+
                      "&productos="+params.productid+
                      "&method="+$("#_renovar").find(".formadepago>input:checked").val()
                      ;
                  wait(true);
                  callAjax(url);
                  //location.href=url;
              });
                
              $.colorbox({inline:true,href:"#_renovar"});
            }
        },
        parseClassString:function(cs){
          cs=cs.split(" ");
          var cs2={};
          for(var i=0; i<cs.length;i++){
            cs[i]=cs[i].split("_");
            cs2[cs[i][0]]=cs[i].length>2?cs[i][1]+"_"+cs[i][2]:cs[i][1];

            }
          return cs2;
          }
}