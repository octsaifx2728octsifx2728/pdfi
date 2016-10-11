var loginblock_c={
	filter:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
	checkForm:function(formulario){
		var email=$(formulario).find("#emailAddr");
		var nombre=$(formulario).find("#screen_name");
		var pass1=$(formulario).find("#password");
		var pass2=$(formulario).find("#passwordConfirm");
		email.removeClass("error");
		nombre.removeClass("error");
		pass1.removeClass("error");
		pass2.removeClass("error");
		if(!loginblock_c.filter.test(email.val())){
			email.addClass("error");
			return false;
		}
		if(nombre.val().length<3){
			nombre.addClass("error");
			return false;
		}
		if(pass1.val().length<3){
			pass1.addClass("error");
			return false;
		}
		if(pass1.val()!=pass2.val()){
			pass2.addClass("error");
			return false;
		}
		return true;
	}
}
