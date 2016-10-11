
    var resetPasswordForm_q=function($){
        $.fn.resetPasswordForm=function(opciones){
            resetPasswordForm_h.ddbb.push(this);
            this.payID=resetPasswordForm_h.ddbb.length-1;
            this.options=opciones;
            this.init=resetPasswordForm_h.init;
            this.fieldKeyup=resetPasswordForm_h.fieldKeyup;
            this.fieldfocus=resetPasswordForm_h.fieldfocus;
            this.submit=resetPasswordForm_h.submit;
            this.submited=resetPasswordForm_h.submited;
            
            this.init($);
            return this;
        }
    };
    var resetPasswordForm_h={
        ddbb:[],
        init:function($){
            var payID=this.payID;
            $(this).find(".formulario").show();
            $(this).find(".mensaje").hide();
            $(this).find("input[name=email]").focus(function(){
              resetPasswordForm_h.ddbb[payID].fieldfocus(this);  
            });
            $(this).find("input[name=email]").keyup(function(){
              resetPasswordForm_h.ddbb[payID].fieldKeyup(this);  
            });
            $(this).find("input[name=email]").change(function(){
              resetPasswordForm_h.ddbb[payID].fieldKeyup(this);  
            });
            
            $(this).find("input[name=email]").on("input",function() {
              resetPasswordForm_h.ddbb[payID].fieldKeyup(this);  
            });
            $(this).find("form").submit(function(){
                resetPasswordForm_h.ddbb[payID].submit(this);
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
            var email=$(campo).val();
                $(campo).removeClass("ok");
                $(campo).removeClass("error");
            if(resetPasswordForm_h.validateEmail(email)){
                $(campo).addClass("ok");
            }
            else{
                $(campo).addClass("error");
            }
        },
         validateEmail:function(email) { 
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        } ,
        submit:function(form){
            
            var payID=this.payID;
            var email=$(form).find("input[name=email]").val();
            if(!resetPasswordForm_h.validateEmail(email)){
                console.log("emailInvalido");
               return false
            }
            wait(true);
            var url="api/user/resetPassword?email="+email+"&callback=resetPasswordForm_h.ddbb["+payID+"].submited";
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
    resetPasswordForm_q(jQuery);