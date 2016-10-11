
var registerform_q=(function($){
  $.fn.registerform=function(options){
      if(options) this.options=options;
      registerform_c.ddbb.push(this);
      this.payID=registerform_c.ddbb.length-1;
      this.init=registerform_c.init;
      this.subm=registerform_c.subm;
      this.receiveToken=registerform_c.receiveToken;
      this.init();
    }
}(jQuery));

var registerform_c={
  ddbb:[],
  emailFilter: /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
  securePasswordFilter1: /[A-Z]/,
  securePasswordFilter2: /[0-9]/,
  init:function(){
    var payID=this.payID;
    var textFields="input[type=email],input[type=text],input[type=password]";
    $(this).find("input,textarea,select").each(function(){this.origValue=$(this).val(),$(this).addClass("unchanged")});
    $(this).find(textFields).focus(function(){if(this.origValue==this.value&&$(this).hasClass("emptyOnFocus")){this.value="";}$(this).addClass("focus")});
    $(this).find(textFields).focusout(function(){if(this.value==""&&$(this).hasClass("emptyOnFocus")){this.value=this.origValue;$(this).addClass('unchanged');$(this).removeClass('changed')}else{$(this).removeClass("unchanged");$(this).addClass("changed")}});
    $(this).find(textFields).keyup(function(){if($(this).hasClass("verifyOnFly")&&registerform_c.verify(this)){$(this.parentNode).addClass('verified_ok');$(this.parentNode).removeClass('verified_error')}else{$(this.parentNode).addClass('verified_error');$(this.parentNode).removeClass('verified_ok')}});
    $(this).find(textFields).on("input",function(){if($(this).hasClass("verifyOnFly")&&registerform_c.verify(this)){$(this.parentNode).addClass('verified_ok');$(this.parentNode).removeClass('verified_error')}else{$(this.parentNode).addClass('verified_error');$(this.parentNode).removeClass('verified_ok')}});
    $(this).find(".verifyPasswordMatch>.field>input").keyup(function(){registerform_c.verifyMatch($(this).parents(".verifyPasswordMatch"))});
    $(this).submit(function(){$(this).find(".required").trigger("keyup");if($(this).find('.verified_error').get().length)return false;return true});
    
    $(this).find(".emptyOnFocus").focus(function(){
        $(this).val("");
        $(this).removeClass("emptyOnFocus");
    });
    if(location.hash=="#error"){
      $(this).addClass("errorSubmit");
      }
    },
  verifyMatch:function(objeto){
    var campos=$(objeto).find(".field>input").get();
    var val="";
    var match=true;
    for(var i=0;i<campos.length;i++){
      if(i==0){
        val=campos[i].value;
        }
      else{
        if(val!=campos[i].value)match=false;
        }
      }
      if(match){
        $(objeto).addClass("passwordMatch_true");
        $(objeto).find(".field_type_passwordConfirm").removeClass("verified_error");
        $(objeto).find(".field_type_passwordConfirm").addClass("verified_ok");
        $(objeto).removeClass("passwordMatch_false");
        }
      else {
        $(objeto).addClass("passwordMatch_false");
        $(objeto).removeClass("passwordMatch_true");
        }
    },
  verify:function(objeto){
    var opciones=registerform_c.parseClassString(objeto.className);
    if(opciones.minSize&&objeto.value.length<opciones.minSize)return false;
    switch(opciones.verifyType){
      case "email":
        return registerform_c.emailFilter.test(objeto.value);
        break;
      case "securePassword":
        return (
            true 
            //&& registerform_c.securePasswordFilter1.test(objeto.value)
            //&& registerform_c.securePasswordFilter2.test(objeto.value)
            );
        break;
      default:
      return true;
      }
    },
    parseClassString:function(cs){
      cs=cs.split(" ");
      var cs2={};
      for(var i=0; i<cs.length;i++){
        cs[i]=cs[i].split("_");
        cs2[cs[i][0]]=cs[i].length>2?registerform_c.parseClassString(cs[i][1]):cs[i][1];
        
        }
      return cs2;
      },
      subm:function(form){
    var payID=this.payID;
          var email=$(form).find("input[name=email]").val();
          var password=$(form).find("input[name=password]").val();
              $(this).find(".errorMessage").hide();
              wait(true);
          var url="/api/login/getToken/?email="+encodeURI(email)+"&password="+encodeURI(password)+"&callback=registerform_c.ddbb["+payID+"].receiveToken";
                
                    var script=document.createElement("script");
                    script.src=url;
                    document.getElementsByTagName("head")[0].appendChild(script);   
          
      },
      receiveToken:function(result,id){
          if(result.error=="0"){
              location.reload();
          }
          else{
              $(this).find(".errorMessage").show();
          }
              wait(false);
      }
}
