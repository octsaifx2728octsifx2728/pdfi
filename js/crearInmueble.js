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
function callAjaxPremium(strUrl,payID){
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
                    var url="/app/inmueble/add2?id="+crearInmueble_c.ddbb[payID].editID+
                      "&tipo="+crearInmueble_c.ddbb[payID].editTipo+
                      "&productos=5"+
                      "&method="+$(".formadepago>input:checked").val()
                      ;
                      callAjax(url);
        }
      }
    xmlhttp.open("GET",strUrl,true);
    xmlhttp.send();
}
var crearInmueble_q=function ($){
    $.fn.crearInmueble=function(opciones){
        crearInmueble_c.ddbb.push(this);
        
        this.payID=crearInmueble_c.ddbb.length-1;
        this.opciones=opciones;
        this.init=crearInmueble_c.init;
        
        this.submitTipoInmueble=crearInmueble_c.submitTipoInmueble;
        this.submitTipoInmuebleHook=crearInmueble_c.submitTipoInmuebleHook;
        this.parseField=crearInmueble_c.parseField;
        this.powerForm=crearInmueble_c.powerForm;
        this.submitField=crearInmueble_c.submitField;
        this.set=crearInmueble_c.set;
        this.values=crearInmueble_c.values;
        this.submitPartial=crearInmueble_c.submitPartial;
        this.showMedia=crearInmueble_c.showMedia;
        this.showLogin=crearInmueble_c.showLogin;
        this.showRegister=crearInmueble_c.showRegister;
        this.showConnect=crearInmueble_c.showConnect;
        this.filePreload360=crearInmueble_c.filePreload360;
        this.filePreload=crearInmueble_c.filePreload;
        this.images=crearInmueble_c.images;
        this.images360=crearInmueble_c.images360;
        this.addVideo=crearInmueble_c.addVideo;
        this.submitMedia=crearInmueble_c.submitMedia;
        this.updateCurrencyFormat=crearInmueble_c.updateCurrencyFormat;
        this.updateCounter=crearInmueble_c.updateCounter;
        this.updateRealFoto=crearInmueble_c.updateRealFoto;
        this.updateRealFoto360=crearInmueble_c.updateRealFoto360;
        this.delFoto=crearInmueble_c.delFoto;
        this.updateTotalImages=crearInmueble_c.updateTotalImages;
       
        this.init();
        
        
        return this;
        
    }
}
var crearInmueble_c={
    ddbb:[],
    images:[],
    images360:[],
    values:{},
    zero:function(){},
    init:function(){
        var payID=this.payID;
        $(this).find(".tiposInmueble" ).selectable({
            selected: function( event, ui ) {
                crearInmueble_c.ddbb[payID].submitTipoInmueble(crearInmueble_c.parseClassString(ui.selected.className).inmuebleTipo,(crearInmueble_c.ddbb[payID].editID?crearInmueble_c.ddbb[payID].editID:null));
                }
            });
        $(this).find(".panel").hide();
        $(this).find(".selectTipo").show();
        $(this).find(".selectTransaccion").show();
        $(this).find(".selectSubtipo").show();
        if(this.opciones&&this.opciones.incompletos&&this.opciones.incompletos.length){
           var inc=this.opciones.incompletos[0];
           
           this.submitTipoInmueble(inc.tipoobjeto,inc.id);
           $(this).find(".tiposInmueble>li").eq(inc.tipoobjeto-1).addClass('ui-selected').removeClass('ui-noselected');
        }
    },
    set:function(campo,valor){
        this.values[campo]=valor;
        var script=document.createElement("script");
        script.src="api/inmueble/update?id="+this.editID+"&tipo="+this.editTipo+"&atributo="+campo+"&valor="+valor+"&callback=crearInmueble_c.zero&idcallback="+this.editID;
        
        document.getElementsByTagName("head")[0].appendChild(script);
        console.log(script.src);
    },
    submitTipoInmueble:function(tipo,id){
        var payID=this.payID;
        $(this).find(".selectTipo").addClass("old");
        $(this).find(".tiposInmueble>.inmueble").each(function(i){
            $(this).removeClass("ui-noselected");
            if(!$(this).hasClass("ui-selected"))$(this).addClass("ui-noselected");
            });
        
        
        
        
        if(!id){
         wait(true);
         var script=document.createElement("script");
         
         script.src="api/inmueble/getNewForm?tipo="+tipo+"&callback=crearInmueble_c.ddbb["+payID+"].submitTipoInmuebleHook&idcallback="+payID;
         document.getElementsByTagName("head")[0].appendChild(script);
        console.log(script.src);
        }
        else {
         wait(true);
         var script=document.createElement("script");
         script.src="api/inmueble/getNewForm?id="+id+"&tipo="+tipo+"&callback=crearInmueble_c.ddbb["+payID+"].submitTipoInmuebleHook&idcallback="+payID;
         document.getElementsByTagName("head")[0].appendChild(script);
        console.log(script.src);
        }
    },
    parseField:function(field){
        var fieldp="";
        switch(field.type){
            case "posicion":
                fieldp="<div class='field field_"+field.name+" "+field.params+" fieldType_posicion'>"+
                                "<label>"+field.label+"</label>"+
                                 "<input type='text' name='place'>"+
                                "<div class='mapContainer'></div>"+
                                "<input type='hidden' class='preplace' value='"+(field.value?field.value:"")+"'>"+
                                "<div class='hint'>"+(field.hint?field.hint:"")+"</div>"+
                                "</div>";
                
            break;
            case "checkbox":
                fieldp="<div class='field field_"+field.name+" "+field.params+" fieldType_checkbox'>"+
                                "<input id='_checkbox_"+field.name+"' type='checkbox' name='"+field.name+"' value='"+field.label+"' "+(field.value*1?"checked='checked'":"")+">"+
                                "<label for='_checkbox_"+field.name+"'>"+field.label+"</label>"+
                                "<div class='hint'>"+(field.hint?field.hint:"")+"</div>"+
                                "</div>";
            break;
            case "button":
                var fields="";
                if(field.options){
                    for(var i=0;i<field.options.length;i++){
                        fields+=this.parseField(field.options[i]);
                    }
                }
                fieldp="<div class='field field_"+field.name+" "+field.params+"'>"+
                                "<input type='button' name='"+field.name+"' value='"+field.label+"'>"+
                      "<div class='options'>"+fields+"</div>"+
                                "<div class='hint'>"+(field.hint?field.hint:"")+"</div>"+
                     "</div>";
            break;                
            case "select":
                var inputs="";
                for(var k in field.options){
                    inputs+="<option value='"+k+"' "+(field.value==k?"selected='selected'":"")+" >"+field.options[k]+"</option>";
                }
                fieldp="<div class='field field_"+field.name+" "+field.params+" fieldType_select'>"+
                                "<label>"+field.label+":</label>"+
                                "<select name='"+field.name+"'>"+inputs+"</select>"+
                                "<div class='hint'>"+(field.hint?field.hint:"")+"</div>"+
                                "</div>";
            break;                
            case "selectmoneda":
                var inputs="";
                for(var k in field.options){
                    inputs+="<option value='"+k+"' "+(field.value==k?"selected='selected'":"")+" >"+field.options[k]+"</option>";
                }
                fieldp="<div class='field field_"+field.name+" "+field.params+" fieldType_selectmoneda'>"+
                                "<label>"+field.label+":</label>"+
                                "<select name='"+field.name+"'>"+inputs+"</select>"+
                                "<div class='hint'>"+(field.hint?field.hint:"")+"</div>"+
                                "</div>";
            break;
            case "currency":
                fieldp="<div class='field field_"+field.name+" "+field.params+" fieldType_currency"+(field.value?" changed":"")+"'>"+
                                "<label>"+field.label+":</label>"+
                                "<span class='simbol'>"+field.symbol+"</span>"+
                                " <input type='text' name='"+field.name+"' value='"+(field.value?field.value:"")+"'> "+
                                "<span class='key'>"+field.key+"</span>"+
                                "<span class='formated'>"+
                                    " <span class='simbol'></span> "+
                                    "<span class='value'></span>"+
                                    " <span class='key'></span>"+
                                "</span>"+
                                "<div class='hint'>"+(field.hint?field.hint:"")+"</div>"+
                                "</div>";
            break;
            case "number":
                fieldp="<div class='field field_"+field.name+" "+field.params+" fieldType_number"+(field.value?" changed":"")+"'>"+
                                "<label>"+field.label+":</label>"+
                                "<input type='text' name='"+field.name+"' value='"+(field.value?field.value:"")+"'>"+
                                "<div class='hint'>"+(field.hint?field.hint:"")+"</div>"+
                                "</div>";
            break;
            case "text":
                fieldp="<div class='field field_"+field.name+" "+field.params+" fieldType_text"+(field.value?" changed":"")+"'>"+
                                "<label>"+field.label+":</label>"+
                                "<input type='text' name='"+field.name+"' value='"+(field.value?field.value:"")+"'>"+
                                "<div class='hint'>"+(field.hint?field.hint:"")+"</div>"+
                                "</div>";
            break;
            case "textarea":
                fieldp="<div class='field field_"+field.name+" "+field.params+" fieldType_textarea"+(field.value?" changed":"")+"'>"+
                    "<label>"+field.label+":</label><span class='counter'></span>"+
                   "<textarea name='"+field.name+"'>"+(field.value?field.value:"")+"</textarea>"+
                   "<div class='hint'>"+(field.hint?field.hint:"")+"</div>"+
                   "</div>";
            break;
            case "selectable":
                var options="";
                var extra="";
                for(var i=0;i<field.options.length;i++){
                    options+="<li title='"+field.options[i].name+"' class='"+(field.value==field.options[i].name?" ui-selected":"")+" "+(field.options[i].params?field.options[i].params:"")+"'><div class='icon'></div>"+field.options[i].label+"</li>";
                    if(field.options[i].options&&field.options[i].options.length>0){
                        var optsextra="";
                        for(var j=0;j<field.options[i].options.length;j++){
                            optsextra+=this.parseField(field.options[i].options[j]);
                            
                        }
                        extra+="<div class='extra extra_"+field.options[i].name+"'>"+optsextra+"</div>";
                    }
                }
                fieldp="<div  class='field field_"+field.name+" "+field.params+" fieldType_selectable'>"+
                        "<label>"+field.label+":</label>"+
                        "<ul>"+options+"</ul>"+
                        "<div class='extras'>"+extra+"</div>"+
                        "<div class='hint'>"+(field.hint?field.hint:"")+"</div>"+
                        "</div>";
            break;
            default:
                console.log(field);
            }
        return fieldp;
    },
    submitTipoInmuebleHook:function(respuesta,id){
        
        
        
        if(respuesta.error=="0"){
           
            $(this).find(".dinamicFields").show();
           /* if(this.editID>0){
                var script=document.createElement("script");
                script.src="api/inmueble/borrar?id="+this.editID+"&tipo="+this.editTipo+"&callback=crearInmueble_c.zero&idcallback="+this.editID;
                document.getElementsByTagName("head")[0].appendChild(script);
                console.log(script.src);
            }*/
            this.editID=respuesta.id;
            this.editUser=respuesta.user;
            this.editTipo=respuesta.tipo;
            $(this).find(".dinamicFields").empty();
            for(var i=0;i<respuesta.form.length;i++){
                if(respuesta.form[i].fields.length){
                var form="<div class='seccion seccion_"+respuesta.form[i].name+"'><p>"+respuesta.form[i].title+"</p>"+
                            "<div class='descripcion'>"+respuesta.form[i].description+"</div>"+
                        "</div>";
                $(this).find(".dinamicFields").append(form);
                for(var j=0; j<respuesta.form[i].fields.length;j++){
                    var field=this.parseField(respuesta.form[i].fields[j]);
                    $(this).find(".dinamicFields>.seccion_"+respuesta.form[i].name).append(field);
                }
                var control="<div class='control'><input type='button' value='"+respuesta.okText+"' class='submit gray-dark'><input class='cancel gray-dark' type='button' value='"+respuesta.cancelText+"'></div>";
                $(this).find(".dinamicFields>.seccion_"+respuesta.form[i].name).append(control);
                }
            }

            $('div.field_m2 input').keypress(function(e) {
                var a = [];
                var k = e.which;

                for (i = 48; i < 58; i++)
                    a.push(i);

                if (!($.inArray(k,a)>=0))
                    e.preventDefault();
            });
            $('div.field_m2s input').keypress(function(e) {
                var a = [];
                var k = e.which;

                for (i = 48; i < 58; i++)
                    a.push(i);

                if (!($.inArray(k,a)>=0))
                    e.preventDefault();
            });
            $('div.field_habitaciones input').keypress(function(e) {
                var a = [];
                var k = e.which;

                for (i = 48; i < 58; i++)
                    a.push(i);

                if (!($.inArray(k,a)>=0))
                    e.preventDefault();
            });
            $('div.field_banos input').keypress(function(e) {
                var a = [];
                var k = e.which;

                for (i = 48; i < 58; i++)
                    a.push(i);

                if (!($.inArray(k,a)>=0))
                    e.preventDefault();
            });
            $('div.field_estacionamientos input').keypress(function(e) {
                var a = [];
                var k = e.which;

                for (i = 48; i < 58; i++)
                    a.push(i);

                if (!($.inArray(k,a)>=0))
                    e.preventDefault();
            });
            $('div.field_estacionamientos2 input').keypress(function(e) {
                var a = [];
                var k = e.which;

                for (i = 48; i < 58; i++)
                    a.push(i);

                if (!($.inArray(k,a)>=0))
                    e.preventDefault();
            });

            this.powerForm();
            $('.field.field_habitaciones.fieldMin_0.fieldMax_10.fieldType_number.changed').css("margin-left","32px");
            
            wait(false);

        }


        switch(location.hash){
            case "#media":
                $(this).find(".control>.submit").trigger("click");
                break;
            case "#pay":
                $(this).find(".control>.submit").trigger("click");
                break;
        }
    },
    submitField:function(field,nosave){
        var payID=this.payID;
        var params=crearInmueble_c.parseClassString(field.className);
        var value="";
        $(field).addClass("changed");
        $(field).removeClass("error");
        switch(params.fieldType){
            case "textarea":
                value=$(field).find("textarea").val();
                break;
            case "text":
                value=$(field).find("input").val();
                break;
            case "selectmoneda":
                value=$(field).find("select").val();
                $(this).find(".fieldType_currency").find(".key").text(value);
                break;
            case "select":
                value=$(field).find("select").val();
                break;
            case "currency":
                value=parseFloat($(field).find("input").val())*1;
                if(isNaN(value)){
                    value=0;
                    $(field).find("input").val(0);
                }
                value=(Math.round(parseFloat(value?value.toString().replace(/[^0-9\s^\.]+/ig,""):0)*100)/100);
                $(field).find("input").val(value);
                break;
            case "number":
                value=parseFloat($(field).find("input").val())*1;
                if(isNaN(value)){
                    value=0;
                    $(field).find("input").val(0);
                }
                
                break;
            case "selectable":
                value=$(field).find("ul>.ui-selected").attr("title");
                $(field).find(".extras>.extra").hide();
                $(field).find(".extras>.extra_"+value).show();
                break;
            case "checkbox":
                value=$(field).find("input").is(':checked')?1:0;
                break;
            case "marker":
                break;
            case "posicion":
                value=0;
                if(field.completador){
                    
                    var place = field.completador.getPlace();
                    if(!place){
                        var posicion=$(this).find(".preplace").val();
                        posicion=posicion.split("_");
                        posicion=new google.maps.LatLng(posicion[0],posicion[1]);
                    }
                    if(place||posicion){
                        if(place&&place.geometry&&place.geometry.viewport)
                                {
                                        var bound=place.geometry.viewport;
                                        var posicion=bound.getCenter();
                                }
                                else{
                                    if(place){
                                        var sw=new google.maps.LatLng(place.geometry.location.lat()+0.0045,place.geometry.location.lng()-0.0045);
                                        var ne=new google.maps.LatLng(place.geometry.location.lat()-0.0045,place.geometry.location.lng()+0.0045);
                                        var bound=new google.maps.LatLngBounds(sw,ne);
                                        var posicion=bound.getCenter();
                                    }
                                }
                         
                        value=posicion;
                        value=value.lat()+"_"+value.lng();
                        if(!field.mapa){
                            field.mapa=new google.maps.Map($(field).find(".mapContainer").get()[0]
                                    ,{center: posicion,
                                        zoom: 8,
                                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                                        streetViewControl:false,
                                        mapTypeControl:false
                                    });
                        }
                        if(!field.marcador){
                                field.marcador = new google.maps.Marker({
                                    position: posicion,
                                    map: field.mapa,
                                    draggable:true,
                                    optimized:false,
                                    icon:"img/casa.gif"
                                });
                            google.maps.event.addListener(field.marcador, 'dragend',function(){
                                var value=this.getPosition();
                                value=value.lat()+"_"+value.lng();
                                crearInmueble_c.ddbb[payID].set("posicion",value);
                            })
                        }
                        if(bound&&field.mapa){
                            field.mapa.setCenter(new google.maps.LatLng(0,0));
                            field.mapa.setZoom(20);
                            field.mapa.fitBounds(bound);
                            field.mapa.setZoom((field.mapa.getZoom()<18?field.mapa.getZoom()+2:field.mapa.getZoom()));
                        }
                        field.mapa.setCenter(posicion);
                        
                        field.marcador.setPosition(posicion);
                      }
                   else {
                    value=0;
                   }
                }
                if(value=="0_0"){
                    value=null;
                }
                break;
            case "media":
                value=this.fotos.length>=1;
                break;
            default:
                
                $(window).scrollTop($(field).offset().top);
                console.log(field);
                return false;
                break;
        }
        if(params.fieldRequired*1 && (!value || value.length<1)){
            
                $(window).scrollTop($(field).offset().top);
            $(field).addClass("error");
            return false;
        }
        if(params.fieldMinSize&&value.length<params.fieldMinSize){
            
                $(window).scrollTop($(field).offset().top);
            $(field).addClass("error");
            return false;
        }
        if(params.fieldMaxSize&&value.length>params.fieldMaxSize){
            
                $(window).scrollTop($(field).offset().top);
            $(field).addClass("error");
            return false;
        }
        if(params.fieldMin&&value<params.fieldMin){
            value=params.fieldMin;
            $(field).find("input").val(value);
        }
        if(params.fieldMax&&value>params.fieldMax){
            value=params.fieldMax;
            $(field).find("input").val(value);
        }
        if(!nosave){
            this.set(params.field,value);

        }
        return true;
    },
    updateCurrencyFormat:function(field,e){
      var simbol=$(field).find(".simbol").eq(0).text();
      var value=$(field).find("input").eq(0).val();
      var key=$(field).find(".key").eq(0).text();  
      $(field).find(".simbol").text(simbol==null?"":simbol);
      $(field).find(".key").text(key);
      
      value=(Math.round(parseFloat(value?value.replace(/[^0-9\s^\.]+/ig,""):0)*100)/100).toString().split(".");
      
      var enteros=value[0].split( /(?=(?:\d{3})+(?:\.|$))/g ).join(",");
      //var decimales=value[1]?value[1]:"00";
      $(field).find(".value").empty();
        $(field).find(".value").append(enteros);
      //$(field).find(".value").append(enteros+".<span class='decimal'>"+decimales+"</span>");

        var input=$(field).find("input").eq(0);
        input.click(function(){
            input.select();
        });
    },
    updateCounter:function(field){
        var value=$(field).find("textarea").val();
        var params=crearInmueble_c.parseClassString(field.className);
        var max=params.fieldMaxSize*1;
        if(max){
            var size=value.length?value.length:0;
            if(size>max){
                value=value.substring(0, max);
                size=value.length?value.length:0;
                $(field).find("textarea").val(value);
            }
            $(field).find(".counter").text(size+"/"+max);
        }
    },
    powerForm:function(){
        var payID=this.payID;
       //$(this).find(".seccion").hide();
       $(this).find(".seccion").show();
       $(this).find(".seccion>.fieldType_select>select").change(function(){if(!$(this.parentNode).hasClass("changed"))$(this.parentNode).addClass("changed");});
       $(this).find(".seccion>.fieldType_text>input").change(function(){crearInmueble_c.ddbb[payID].submitField(this.parentNode)});
       $(this).find(".seccion>.fieldType_number>input").change(function(){crearInmueble_c.ddbb[payID].submitField(this.parentNode)});
       $(this).find(".seccion>.fieldType_currency>input").change(function(){crearInmueble_c.ddbb[payID].submitField(this.parentNode)});
       $(this).find(".seccion>.fieldType_currency>input").keyup(function(e){crearInmueble_c.ddbb[payID].updateCurrencyFormat(this.parentNode,e)});
       $(this).find(".seccion>.fieldType_select>select").change(function(){crearInmueble_c.ddbb[payID].submitField(this.parentNode)});
       $(this).find(".seccion>.fieldType_selectmoneda>select").change(function(){crearInmueble_c.ddbb[payID].submitField(this.parentNode)});
       $(this).find(".seccion>.fieldType_textarea>textarea").change(function(){crearInmueble_c.ddbb[payID].submitField(this.parentNode)});
       $(this).find(".seccion>.fieldType_textarea>textarea").keyup(function(){crearInmueble_c.ddbb[payID].updateCounter(this.parentNode)});
       $(this).find(".seccion").find(".fieldType_checkbox>input").change(function(){crearInmueble_c.ddbb[payID].submitField(this.parentNode)});
       $(this).find(".seccion>.fieldType_selectable>ul").selectable({selected:function(){crearInmueble_c.ddbb[payID].submitField(this.parentNode)}});
       $(this).find(".seccion>.fieldType_selectable>.extras>.extra").hide();
       $(this).find(".seccion>.field_posicion").each(function(){
           var campo=this;
           this.completador= new google.maps.places.Autocomplete($(this).find("input").get()[0], {});
           google.maps.event.addListener(this.completador, 'place_changed',function(){crearInmueble_c.ddbb[payID].submitField(campo)});
           var val=$(this).find(".preplace").val();
           if(val&&val!="0.00000000000000000000_0.00000000000000000000"){
               crearInmueble_c.ddbb[payID].submitField(this);
           }
       });
       $(this).find(".seccion>.fieldType_currency>input").trigger("keyup");
       $(this).find(".seccion>.fieldType_textarea>textarea").trigger("keyup");
       $(this).find(".seccion>.control>.submit").click(function(){crearInmueble_c.ddbb[payID].submitPartial(this.parentNode.parentNode)});
       $(this).find(".selectTransaccion").empty();
       $(this).find(".selectTransaccion").append($(this).find(".seccion_transaccion").eq(0));
        $(this).find(".selectSubtipo").empty();
       $(this).find(".selectSubtipo").append($(this).find(".seccion_tipo").eq(0));
    },
    submitPartial:function(obj){
        var pasa=true;
        var campos=$(obj.parentNode).find(".field").get();
        for(var i=0; i<campos.length;i++){
            if(!this.submitField(campos[i],true)){
                pasa=false;
                break;
            }
        }
        if(!pasa){
            return false;
        }
        $(obj).addClass("old");
        $(obj).find(".control").hide();
        var params=crearInmueble_c.parseClassString(obj.className);
        switch(params.seccion){
            case "posicion":
                    $(obj.parentNode.parentNode).find(".panel").hide();


                    var script=document.createElement("script");
                    script.src="api/inmueble/getAnuncioPretend?id="+this.editID+"&tipo="+this.editTipo+"&callback=crearInmueble_c.ddbb["+this.payID+"].showMedia&idcallback="+this.editID;

                    document.getElementsByTagName("head")[0].appendChild(script);
                console.log(script.src);
                wait(true);
                break;
            default:
                console.log(params);
        }
    },
     showMedia:function(respuesta,id){
                wait(false);
         if(respuesta.error=="0"){
            var payID=this.payID;
            this.pretend=respuesta.pretend;
            $(this).find(".multimedia").show();
            $(this).find(".uploadFotoFrame").attr("src","/app/uploadFile/Image?id=1&callback=crearInmueble_c.ddbb["+payID+"].filePreload&target=imagen");
            $(this).find(".uploadFoto360Frame").attr("src","/app/uploadFile/Image?id=1&callback=crearInmueble_c.ddbb["+payID+"].filePreload360&target=imagen360");
            for(var i=0;i<this.pretend.videos;i++){
                $(this).find(".video_"+i+"").removeClass("prohibido");
                $(this).find(".video_"+i+">input").focus(function(){this.value=""} );
                $(this).find(".video_"+i+">input").change(function(){crearInmueble_c.ddbb[payID].addVideo(this.value,this.parentNode,i)} );
                }
            $(this).find(".fotosContainer>.top").find(".max").text(respuesta.pretend.fotos);
            $(this).find(".fotos360Container>.top").find(".max").text(respuesta.pretend.fotos360);
            $(this).find(".multimedia>.control>.submit").click(function(){crearInmueble_c.ddbb[payID].submitMedia()});
            for(var i=0;i<respuesta.fotos.length;i++){
                
                    var div=document.createElement("div");
                    var img=document.createElement("img");
                    img.src=respuesta.fotos[i].path;
                    div.className=("imagen imagen_"+id+i);
                    div.title=id+i;
                    if(respuesta.fotos[i].panoramica=="1"){
                        this.images360.push(respuesta.fotos[i].path);
                        var total=this.images360.length;
                    $(this).find(".fotos360Container>.top").find(".total").text(total);
                        $(this).find(".fotos360Container>.wrap").append(div);
                        }
                    else{
                        this.images.push(respuesta.fotos[i].path);
                        var total=this.images.length;
                    $(this).find(".fotosContainer>.top").find(".total").text(total);
                        $(this).find(".fotosContainer>.wrap").append(div);
                        }
                   $(this).find(".uploadFotoFrame").addClass("totalImages_"+id+i);
                  //  if(total<permitidas){
                    if(true){
                            if(this.editID){
                                var del=document.createElement("input");
                                del.type="button";
                                del.className="button";
                                del.value="X";
                                $(del).click(function(){
                                    var params=crearInmueble_c.parseClassString(this.parentNode.className);
                                    crearInmueble_c.ddbb[payID].delFoto(params.imagenID,this.parentNode);
                                });
                                $(div).addClass("imagenID_"+respuesta.fotos[i].id);
                                div.appendChild(del);
                                }
                            }
                    else{
                            $(this).find(".fotosContainer>.wrap>.new").css("display","none");
                            $(this).find(".uploadFotoFrame").attr("src","/app/uploadFile/Image?id="+(id+i+1)+"&callback=crearInmueble_c.ddbb["+payID+"].filePreload&target=imagen");
                            }
                            
                    div.appendChild(img);
            }
         }
         
        switch(location.hash){
            case "#pay":
                this.submitMedia();
                break;
        }
         
    },
    showLogin:function(resp){
        if(resp.error=="0"){
            var div=document.createElement("div");
            var h1=document.createElement("h2");
            $(h1).text(enviroment.translations.ENTRAR);
            $(div).append(h1);
            div.className="panel panel_login";
            $(this).append(div);
            $(div).append("<div class='login'>"+resp.chunk+"</div>");
        }
    },
    showRegister:function(resp){
        if(resp.error=="0"){
            var div=document.createElement("div");
            var h1=document.createElement("h2");
            $(h1).text(enviroment.translations.REGISTRARSE);
            $(div).append(h1);
            div.className="panel panel_registro";
            $(this).append(div);
            $(div).append("<div class='register'>"+resp.chunk+"</div>");
        }
    },
    showConnect:function(resp){
        if(resp.error=="0"){
            var div=document.createElement("div");
            var h1=document.createElement("h2");
            $(h1).text(enviroment.translations.CONECTAR);
            $(div).append(h1);
            div.className="panel panel_connect";
            $(this).append(div);
            $(div).append("<div class='connect'>"+resp.chunk+"</div>");
        }
    },
    submitMedia:function(){
        var payID=this.payID;
        
        
        $(this).find(".mediaform").removeClass("error");
        if(this.images.length<1){
            $(this).find(".mediaform").addClass("error");
            $(window).scrollTop($(this).find(".mediaform").offset().top);
            return false;
        }
        $(this).find(".multimedia").hide();
        
        if(!enviroment.user.id){
            var div=document.createElement("div");
            var h1=document.createElement("h1");
            div.className="panel panel_unloggedTitle";
            $(h1).text(enviroment.translations.User_registration);
            $(this).append(div);
            $(div).append(h1);
            
            var payID=this.payID;
            var url="/api/chunk/connect?callback=crearInmueble_c.ddbb["+payID+"].showConnect&options="+encodeURI('{"return":"'+location.href+'"}');
            console.log(url);
            var script=document.createElement("script");
            script.src=url;
            document.getElementsByTagName("head")[0].appendChild(script);
            url="/api/chunk/registerForm?callback=crearInmueble_c.ddbb["+payID+"].showRegister&options="+encodeURI('{"return":"'+location.href+'"}');
            script=document.createElement("script");
            script.src=url;
            document.getElementsByTagName("head")[0].appendChild(script);
            url="/api/chunk/loginForm?callback=crearInmueble_c.ddbb["+payID+"].showLogin&options="+encodeURI('{"return":"'+location.href+'"}');
            script=document.createElement("script");
            script.src=url;
            document.getElementsByTagName("head")[0].appendChild(script);
            return false;
        }
        if(this.pretend.gratis){
            
            $(this).find(".submitPago").show();
            $(this).find(".submitPago").show();
            $(this).find(".formasdepago").hide();
            $(this).find(".formasdepago1").hide();
            if(this.pretend.product){
                var div="<div class='producto standar module3'>"+
                    "<div class='wrapSFX cabecera'>"+enviroment.translations.anuncioFreemium+"</div>"+
                    "<div class='wrapSFX'><div class='product standar module2'>"+
                    "<div class='wrapSFX'><div class='label'><div class='title'>"+this.pretend.product.nombre+"</div>"+
                    "<div class='des'>"+this.pretend.product.descripcion+"</div>"+
                    "</div></div></div></div></div>";
              $(this).find(".submitPago").append(div);
              $(this).find(".producto").find(".label").click(function(){
                  var url="/app/inmueble/add2?id="+crearInmueble_c.ddbb[payID].editID+
                      "&tipo="+crearInmueble_c.ddbb[payID].editTipo+
                      "&productos="+crearInmueble_c.ddbb[payID].pretend.product.id+
                      "&method=1"
                      ;
                      
                      //alesubmitPagort(url);
                    callAjax(url);
                  wait(true);
                  
                  //location.href=url;
              });
              var div="<div class='productoPremium standar module4'>"+
                    "<div class='wrap cabecera'>"+enviroment.translations.anuncioPremium+"</div>"+
                    "<div class='wrap3'>"+
                    "</div></div>";
            
              
              var div2="<div class='productoOferta standar module5'>"+
                    "<div class='wrap cabecera'>"+enviroment.translations.anuncioOferta+"</div>"+
                    "<div class='wrap3'>"+
                    "</div></div>";
            
              
              $(this).find(".submitPago").append(div);
              
              $(this).find(".submitPago").append(div2);
              
              var btnPrem = "<div class='productPrem standar module2'><div class='wrapSFX'><div class='label'><div class='title'>"+enviroment.translations.btnPremium+"</div></div></div></div>";
              var infoPremium = "<div class='infoPremium'><span>"+enviroment.translations.descripcionPremium+"</span>"+
                      "<div class='ulbox'><ul class='leftul'><li>"+enviroment.translations.destacadoPremium+"</li><li>"+enviroment.translations.recuadroPremium+"</li><li>"+enviroment.translations.iconoPremium+"</li></ul><ul class='rightul'><li>"+enviroment.translations.rankeoPremium+"</li><li>"+enviroment.translations.emailSemanal+"</li><li>"+enviroment.translations.promoSociales+"</li></ul></div>"+"<div class='clear'></div></div>";
              $(".module4 .wrap3").append(infoPremium);
              $(".module4 .wrap3").append(btnPrem);
              
              
              $(".btnSubmitPremium").click(function(){
                  
                     
                  
                     var url="/app/inmueble/add2?id="+crearInmueble_c.ddbb[payID].editID+
                      "&tipo="+crearInmueble_c.ddbb[payID].editTipo+
                      "&productos="+"5"+
                      "&method=1&newAndPremium=foo";
                      ;
                      
                      
                      //alert(url);
                      wait(true);
                      callAjaxPremium(url,payID);
                      //location.href= url;
                  
              });
               var btn = "<div class='btnSubmitPremium'>"+enviroment.translations.btnPremium+"</div>";
              $(this).find(".formasdepago").append(btn);
              
              $(this).find(".productoPremium").find(".label").click(function(){
                  
                alert("lalalalqqqq");
              
                $.colorbox({inline:true,href:".formasdepago",onOpen:function(){$(".formasdepago").show()},onCleanup:function(){$(".formasdepago").hide()}});
              
                var url="/app/inmueble/add2?id="+crearInmueble_c.ddbb[payID].editID+
                      "&tipo="+crearInmueble_c.ddbb[payID].editTipo+
                      "&productos="+crearInmueble_c.ddbb[payID].pretend.product.id+
                      "&method="+$(crearInmueble_c.ddbb[payID]).find(".formadepago>input:checked").val()
                      ;
                      
              });
              
              
              var infoOferta = "<div class='infoOferta'><span>"+enviroment.translations.descripcionOferta+"</span>"+
                      "<div class='ulbox'><ul class='leftul'><li>"+enviroment.translations.destacadoPremium+"</li><li>"+enviroment.translations.recuadroPremium+"</li><li>"+enviroment.translations.iconoPremium+"</li></ul><ul class='rightul'><li>"+enviroment.translations.rankeoPremium+"</li><li>"+enviroment.translations.emailSemanal+"</li><li>"+enviroment.translations.promoSociales+"</li></ul></div>"+"<div class='clear'></div></div>";

              $(".module5 .wrap3").append(infoOferta);
              
              
              var btnOferta = "<div class='productOfer standar module2'><div class='wrapSFX'><div class='label'><div class='title'>"+enviroment.translations.btnOferta+"</div></div></div></div>";
              
              
              
              $(".module5 .wrap3").append(btnOferta);
              
               var btn1 = "<div class='btnSubmitOferta'>"+enviroment.translations.btnOferta+"</div>";
              $(this).find("#formasdepago1").append(btn1);
             
              $(this).find(".productOfer").find(".label").click(function(){
                  
                  
                 $.colorbox({inline:true,href:".formasdepago1",onOpen:function(){$(".formasdepago1").show()},onCleanup:function(){$(".formasdepago1").hide()}});
                 
                var url="/app/inmueble/add2?id="+crearInmueble_c.ddbb[payID].editID+
                      "&tipo="+crearInmueble_c.ddbb[payID].editTipo+
                      "&productos="+"15"+
                      "&method=1&newAndPremium=foo";
                      ;
                      
                      
                      //alert(url);
                      wait(true);
                      callAjaxPremium(url,payID);
                
                

                  
                  
              });
             
              
              

            }
        }
        else {
            $(this).find(".submitPago").show();
            $(this).find(".formasdepago").show();
            if(this.pretend.product){
                var div="<div class='producto standar module3'>"+
                    "<div class='wrap cabecera'>"+this.pretend.product.nombre+"</div>"+
                    "<div class='wrap2'><div class='product standar module2'>"+
                    "<div class='wrap'><div class='label'><div class='title'>"+this.pretend.product.nombre+" $ "+this.pretend.product.precio+" USD</div>"+
                    "<div class='des'>"+this.pretend.product.descripcion+"</div>"+
                    "</div></div></div></div></div>";
              $(this).find(".submitPago").append(div);
              $(this).find(".producto").find(".label").click(function(){
                 
                  var url="/app/inmueble/add2?id="+crearInmueble_c.ddbb[payID].editID+
                      "&tipo="+crearInmueble_c.ddbb[payID].editTipo+
                      "&productos="+crearInmueble_c.ddbb[payID].pretend.product.id+
                      "&method="+$(crearInmueble_c.ddbb[payID]).find(".formadepago>input:checked").val()
                      ;
                callAjax(url);
                wait(true);
                //location.href=url;
              });
            }
        }
    },
    
    filePreload:function(respuesta,id){
        var payID=this.payID;
            if((respuesta.error instanceof Array)||(typeof(respuesta.error)=="object")){
                for(var i=0;i<respuesta.error.length;i++){
                    if(this.pretend.fotos<=this.images.length){
                        wait(false);
                        $(this).find(".fotosContainer").find(".new").hide();
                        return false;
                    }
                    if(respuesta.error[i]!="0"){
                        $(this).find(".uploadFotoFrame").attr("src","/app/uploadFile/Image?id="+(id+i+1)+"&callback=crearInmueble_c.ddbb["+payID+"].filePreload&target=imagen");
                        wait(false);
                        return false;
                    }
                    this.images.push(respuesta.error[i]);
                    var total=this.images.length;
                    var div=document.createElement("div");
                    var img=document.createElement("img");
                    $(this).find(".fotosContainer>.top").find(".total").text(total);
                    img.src=respuesta.path[i];
                    div.className=("imagen imagen_"+id+i);
                //    div.appendChild(del);
                    div.appendChild(img);
                    div.title=id+i;
                    $(this).find(".fotosContainer>.wrap").append(div);
                   $(this).find(".uploadFotoFrame").addClass("totalImages_"+id+i);
                  //  if(total<permitidas){
                    if(true){
                            $(this).find(".uploadFotoFrame").attr("src","/app/uploadFile/Image?id="+(id+i+1)+"&callback=crearInmueble_c.ddbb["+payID+"].filePreload&target=imagen");
                            if(this.editID){
                                var script=document.createElement("script");
                                script.src="/api/inmueble/addFoto?id="+this.editID+"&tipo="+this.editTipo+"&path="+respuesta.path[i]+"&callback=crearInmueble_c.ddbb["+payID+"].updateRealFoto&idcallback="+(total-1)+"&target=imagen";
                                document.getElementsByTagName("head")[0].appendChild(script);
                                console.log(script.src);
                                }
                            }
                    else{
                            $(this).find(".fotosContainer>.wrap>.new").css("display","none");
                            $(this).find(".uploadFotoFrame").attr("src","/app/uploadFile/Image?id="+(id+i+1)+"&callback=crearInmueble_c.ddbb["+payID+"].filePreload&target=imagen");
                            }
                    }
                }
                wait(false);
    },
    updateRealFoto:function(result,id){
        var payID=this.payID;
       if(result.error=="0"){
           $(this).find(".fotosContainer>.wrap>.imagen").each(function(){
               var src=$(this).find("img").attr("src");
               if(src==result.oldfoto){
                   var del=document.createElement("input");
                   del.type="button";
                   del.className="button";
                   del.value="X";
                   $(del).click(function(){
                       var params=crearInmueble_c.parseClassString(this.parentNode.className);
                       crearInmueble_c.ddbb[payID].delFoto(params.imagenID,this.parentNode);
                   });
                   $(this).find("img").before(del);
                   $(this).find("img").attr("src",result.foto.path);
                   $(this).addClass("imagenID_"+result.foto.id);
               }
           });
       }
    },
    updateRealFoto360:function(result,id){
        var payID=this.payID;
       if(result.error=="0"){
           $(this).find(".fotos360Container>.wrap>.imagen").each(function(){
               var src=$(this).find("img").attr("src");
               if(src==result.oldfoto){
                   var del=document.createElement("input");
                   del.type="button";
                   del.className="button";
                   del.value="X";
                   $(del).click(function(){
                       var params=crearInmueble_c.parseClassString(this.parentNode.className);
                       crearInmueble_c.ddbb[payID].delFoto(params.imagenID,this.parentNode);
                   });
                   $(this).find("img").before(del);
                   $(this).find("img").attr("src",result.foto.path);
                   $(this).addClass("imagenID_"+result.foto.id);
               }
           });
       }
    },
    updateTotalImages:function(contenedor){
        var total=this.images.length;
        $(contenedor).find(".top").find(".total").text(total);
    },
    delFoto:function(id,target){
	var script=document.createElement("script");
        this.images.pop();
	script.src="/api/inmueble/delFoto?id="+this.editID+"&tipo="+this.editTipo+"&foto="+id+"";
	script.type="text/javascript";
	document.getElementsByTagName("head")[0].appendChild(script);
        console.log(script.src);
        this.updateTotalImages(target.parentNode.parentNode);
        $(target).remove();
	},
    filePreload360:function(respuesta,id){
        var payID=this.payID;
            if((respuesta.error instanceof Array)||(typeof(respuesta.error)=="object")){
                for(var i=0;i<respuesta.error.length;i++){
                    if(this.pretend.fotos360<=this.images360.length){
                        $(this).find(".fotos360Container").find(".new").hide();
                        wait(false);
                        return false;
                    }
                    this.images360.push(respuesta.path[i]);
                    var div=document.createElement("div");
                    var img=document.createElement("img");
                   // var del=document.createElement("input");
                  //  var total=freemium.inmueble.countimages();
                    var total=this.images360.length;
                    $(this).find(".fotos360Container>.top").find(".total").text(total);
                    //var permitidas=freemium.calcularTotalFotos();
                    var permitidas=100;
                  //  del.type="button";
                   // del.className="button";
                   // del.value="X";
                  //  $(del).click(function(){
                 //       crearInmueble_c.ddbb[payID].deletePhoto(this.parentNode)
                  //  })
                    img.src=respuesta.path[i];
                    div.className=("imagen imagen_"+id+i);
                   // div.appendChild(del);
                    div.appendChild(img);
                    div.title=id+i;
                    $(this).find(".fotos360Container>.wrap").append(div);
                   $(this).find(".uploadFoto360Frame").addClass("totalImages_"+id+i);
                  //  if(total<permitidas){
                    if(true){
                            $(this).find(".uploadFoto360Frame").attr("src","/app/uploadFile/Image?id="+(id+i+1)+"&callback=crearInmueble_c.ddbb["+payID+"].filePreload360&target=imagen360");
                            if(this.editID){
                                var script=document.createElement("script");
                                script.src="/api/inmueble/addFoto360?id="+this.editID+"&tipo="+this.editTipo+"&path="+respuesta.path[i]+"&callback=crearInmueble_c.ddbb["+payID+"].updateRealFoto360&idcallback="+(total-1)+"&target=imagen360";
                                document.getElementsByTagName("head")[0].appendChild(script);
                                console.log(script.src);
                                }
                            }
                    else{
                            $(this).find(".fotos360Container>.wrap>.new").css("display","none");
                            $(this).find(".uploadFoto360Frame").attr("src","/app/uploadFile/Image?id="+(id+i+1)+"&callback=crearInmueble_c.ddbb["+payID+"].filePreload360&target=imagen360");
                            }
                    }
                }
                wait(false);
        
    },
    addVideo:function (url,contenedor,id){
		var videoregex=/(http[s]?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/[a-z0-9=\/\.&\?\-_]*/i;
		var videoclean=/(http[s]?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\//i;
                
		if(videoregex.test(url)){
			var key=url.replace(videoclean,"");
			var key=key.replace("watch?v=","");
			var video=document.createElement("iframe");
			video.className="video";
			video.src="http://www.youtube.com/embed/"+key+"?enablejsapi=0&origin=http://www.e-spacios.com";
			video.frameborder="0";
			$(contenedor).find(".videoContainer").empty();
			$(contenedor).find(".videoContainer").append(video);
			if(this.editID){
				var url="/api/inmueble/addVideo?id="+this.editID+"&tipo="+this.editTipo+"&key="+key+"&index="+id;
				var script=document.createElement("script");
				script.type="text/javascript";
				script.src=url+"&callback=&idcallback=0";
				document.getElementsByTagName("head")[0].appendChild(script);
                                console.log(script.src); 
				
			}
		}
		else {
			$(contenedor).find(".videoContainer").empty();

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

crearInmueble_q(jQuery);