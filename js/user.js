var user_interface={
	showAvatarForm:function(params){
		$(params.form).find(".actual>img").attr("src",params.actual);
		$(params.form).find(".uploadFrame").attr("src",params.uploadApi+"?id="+this.id+"&callback=user_interface.filePreloadProxy");
		$.colorbox({inline:true,href:params.form,width:350,height:400});
		if(enviroment&&enviroment.user&&enviroment.user.fb_id){
			$(params.form).find(".accion2>img").attr("src","https://graph.facebook.com/"+context.user.fb_id+"/picture");
		}
		else {
			$(params.form).find(".accion2>img").attr("src","galeria/perfil/avatar.png");
		}
		this.avatarForm=params.form
	},
	filePreloadProxy:function(respuesta,id){
		if(user_DDBB[id]&&respuesta.path){
			user_DDBB[id].submitAvatar(respuesta.path,true);
		}
	},
	submitAvatar:function(imagen,internal){
		if(this.avatarForm){
			$(this.avatarForm).find(".actual>img").attr("src",(internal?"cache/85/95/":"")+imagen);
			$(this.avatarForm).find(".submit").css("display","block");
			
		}
		wait(false);
	},
	updateHandler:function(respuesta,id){
		$(".user_"+id).find(".password2").css("display","none");
		$(".user_"+id).find(".password2>input").val("");
		$(".user_"+id).find(".password>input").val("");
		wait(false);
	},
	update:function(params){
		var script=document.createElement("script");
		script.type="text/javascript";
		script.src="/api/updateUser/"+params.name+"?value="+encodeURI(params.value)+"&id="+this.id+"&callback=user_interface.updateHandler&idcallback="+this.id;
		document.getElementsByTagName("head")[0].appendChild(script);
                console.log(script.src);
		if(params.name=="avatar"){
			$(".user_"+this.id).find(".avatar_image").attr("src",params.value);
			$(".lblock").find(".avatar>img").attr("src",params.value);
			}
		if(params.name=="nombre_pant"){
			$(".lblock").find(".hello>.name").text(params.value);
			}
			wait(true);
	},
	changePassword_start:function(container){
		$(container).find(".password2").css("display","block");
		this.changePassword_progess(container);
	},
	changePassword_progess:function(container){
		if($(container).find(".password2").find(".inputtext").attr("value").length>5&&$(container).find(".password2").find(".inputtext").attr("value")==$(container).find(".password").find(".inputtext").attr("value")){
			$(container).find(".password2").removeClass("error");
			$(container).find(".password2").find(".button").css("display","inline");
		}
		else {
			$(container).find(".password2").addClass("error");
			$(container).find(".password2").find(".button").css("display","none");
		}
	}
};

var user_DDBB={};

var user_class=function(id){
	user_DDBB[id]=this;
	this.id=id;
	this.avatarForm=null;
	this.showAvatarForm=user_interface.showAvatarForm;
	this.submitAvatar=user_interface.submitAvatar;
	this.update=user_interface.update;
	this.changePassword_start=user_interface.changePassword_start;
	this.changePassword_progess=user_interface.changePassword_progess;
};
