var enMensaje = false;
var messageWritter_q=function($){
    $.fn.messageWritter=function(options){
        messageWritter_h.ddbb.push(this);
        this.payID=messageWritter_h.ddbb.length-1;
        this.init=messageWritter_h.init;
        this.options=options;
        this.textareakeyup=messageWritter_h.textareakeyup;
        this.textareafocus=messageWritter_h.textareafocus;
        this.submit=messageWritter_h.submit;
        this.messageToSend=messageWritter_h.messageToSend;
        this.loginAndSubmit=messageWritter_h.loginAndSubmit;
        this.messageReceiveToken=messageWritter_h.messageReceiveToken;
        this.sendMessage=messageWritter_h.sendMessage;
        this.init($);
    }
}

var messageWritter_h={
    ddbb:[],
    messageToSend:{},
    init:function($){
	var payID=this.payID;
        $(this).find(".field_message>textarea").keyup(function(){
		messageWritter_h.ddbb[payID].textareakeyup(this);
		});
        $(this).find(".field_message>textarea").focus(function(){
		messageWritter_h.ddbb[payID].textareafocus(this);
		});
        $(this).find("#formulario_"+this.options.key).submit(function(){
		messageWritter_h.ddbb[payID].submit(this);
            return false;
        });
    },
	textareafocus:function(caja){
		if($(caja).hasClass("untouched")){
			$(caja).val("");
			$(caja).removeClass("untouched");
			}
		},
	textareakeyup:function(caja){
		var text=$(caja).val();
		var counter=$(this).find(".counter");
		counter.removeClass("counter_error");
		counter.removeClass("counter_ok");

		if(text.length<1){
			counter.addClass("counter_error");
			}
		else{
			counter.addClass("counter_ok");
			}

		if(text.length>500){
			text=text.substring(0,500);
			$(caja).val(text);
			}
		counter.text(text.length+"/500");
		},
                submit:function(formulario){
                    var payID=this.payID;
                    var mensaje=$(formulario).find("textarea").val();
                    var id=$(formulario).find("input[name=id]").val();
                    var ul=ultraLogin_h.ddbb[$(formulario).find("input[name=ulid]").val()];
                    this.ulid=$(formulario).find("input[name=ulid]").val();
                    var user=enviroment.user;
                    if($(formulario).find("textarea").hasClass("untouched")){
                        mensaje="";
                    }
                    if(mensaje.length<1){
                        alertSystem.alert(enviroment.translations.pleasewrittemessage,1,$(this).find(".alert"));
                        return false;
                    }
                       this.messageToSend.message=mensaje;
                       this.messageToSend.id=id;
                    if(!user.id){
                       ul.appendForm(1);
                       $.colorbox.resize();
                       var loginForm=$("#cboxLoadedContent").find(".loginform_body>.loginForm>form");
                       loginForm.submit(function(){
                            enMensaje = true;
                            messageWritter_h.ddbb[payID].loginAndSubmit(this);
                           return false;
                       });
                       $(this).css("overflow","hidden");
                        var h=$(this).height();
                        var sel=this;
                        $(document).bind('cbox_closed', function(){
                            $(messageWritter_h.ddbb[payID]).css("height","")
                        });
                       $(this).animate({height:"0px"}, 1000, function(){
                           $.colorbox.resize();
                       });
                        return false;
                    }
                    else {
                        this.sendMessage();
                    ultraLogin_h.ddbb[this.ulid].changeForm(4);
                    $.colorbox.resize();
                    }
                },
                loginAndSubmit:function(formulario){
                    var payID=this.payID;
                    var email=$(formulario).find("input[name=email]").val();
                    var pass=$(formulario).find("input[name=password]").val();
                    var url="/api/login/getToken/?email="+encodeURI(email)+"&password="+encodeURI(pass)+"&callback=messageWritter_h.ddbb["+payID+"].messageReceiveToken";
                   console.log(url);
                    var script=document.createElement("script");
                    script.src=url;
                    document.getElementsByTagName("head")[0].appendChild(script);
                },
                messageReceiveToken:function(respuesta,id){
                   if(respuesta.token){
                    //alert(respuesta.token);
                       enviroment.user.token=respuesta.token;
                       this.sendMessage();
                    ultraLogin_h.ddbb[this.ulid].changeForm(4);
                    $.colorbox.resize();
                    $(document).bind('cbox_closed', function(){ location.reload() });
                   }
                },
                sendMessage:function(){
                    
                    var url="/api/message/send/?id="+
                            encodeURI(this.messageToSend.id)+
                            "&email=&subject=&message="+encodeURI(this.messageToSend.message)+"&usertoken="+enviroment.user.token;
                    //var script=document.createElement("script");
                    //script.src=url;
                    //document.getElementsByTagName("head")[0].appendChild(script);
                    callAjax(url);
                    enMensaje = false;
                    //location.reload();
                }
}
function callAjax(strUrl){
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
      //return xmlhttp.responseText;
      //document.getElementById("res").innerHTML=xmlhttp.responseText;
      // = xmlhttp.responseText;
      //window.location.href = "/app/inmueble/";
                        //alert(xmlhttp.responseText);
                        //window.location.href="/app/inmueble/fremiumsucc";
                        enMensaje = false;
                        location.reload();
      }
    }
  xmlhttp.open("GET",strUrl,true);
  xmlhttp.send();
}
messageWritter_q(jQuery);
