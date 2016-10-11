
    var changePasswordForm_q=function($){
        $.fn.changePasswordForm=function(opciones){
            changePasswordForm_h.ddbb.push(this);
            this.payID=changePasswordForm_h.ddbb.length-1;
            this.options=opciones;
            this.init=changePasswordForm_h.init;
            this.fieldKeyup=changePasswordForm_h.fieldKeyup;
            this.fieldKeyup2=changePasswordForm_h.fieldKeyup2;
            this.fieldfocus=changePasswordForm_h.fieldfocus;
            this.submit=changePasswordForm_h.submit;
            this.submited=changePasswordForm_h.submited;
            
            this.init($);
            return this;
        }
    };
    var changePasswordForm_h={
        ddbb:[],
        init:function($){
            var payID=this.payID;
            $(this).find(".formulario").show();
            $(this).find(".mensaje").hide();
            $(this).find("input[name=password]").focus(function(){
              changePasswordForm_h.ddbb[payID].fieldfocus(this);  
            });
            $(this).find("input[name=password2]").focus(function(){
              changePasswordForm_h.ddbb[payID].fieldfocus(this);  
            });
            
            $(this).find("form").submit(function(){
                changePasswordForm_h.ddbb[payID].submit(this);
                return false;
            });
        },
        fieldfocus:function(campo){
            if($(campo).hasClass("notouch")){
                $(campo).removeClass("notouch");
                $(campo).addClass("touched");
                $(campo).val("");
            }
        },
        fieldKeyup:function(campo){
            var password=$(campo).val();
                $(campo).removeClass("ok");
                $(campo).removeClass("error");
            if(password.length>4){
                $(campo).addClass("ok");
            }
            else{
                $(campo).addClass("error");
            }
        },
        fieldKeyup2:function(campo){
            var password=$(campo).val();
                $(campo).removeClass("ok");
                $(campo).removeClass("error");
            if(password.length>4){
                $(campo).addClass("ok");
            }
            else{
                $(campo).addClass("error");
            }
        },
        submit:function(form){
            
            var payID=this.payID;
            var password=$(this).find("input[name=password]").val();
            var password2=$(this).find("input[name=password]").val();
            if($(this).find("input[name=password]").hasClass("notouch")){
                password="";
            }
            if($(this).find("input[name=password2]").hasClass("notouch")){
                password2="";
            }
            
            $(this).find(".error").removeClass("error");
                
            if(password.length<4){
               $(this).find("input[name=password]").addClass("error");
               return false;
            }
            if(password!=password2){
               $(this).find("input[name=password2]").addClass("error");
               return false;
            }
            var url="api/updateUser/clave?value="+password+"&callback=changePasswordForm_h.ddbb["+payID+"].submited";
            var script=document.createElement("script");
            script.src=url;
            document.getElementsByTagName("head")[0].appendChild(script);
        },
        submited:function(){
            $(this).find(".formulario").hide();
            $(this).find(".mensaje").show();
            wait(false);
        }
    };
    changePasswordForm_q(jQuery);