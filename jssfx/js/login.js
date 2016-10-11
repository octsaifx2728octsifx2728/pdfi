var login_q=function($){
  $.fn.login=function(options){
    login_c.ddbb.push(this);
    this.payID=login_c.ddbb.length-1;
    this.options=options;
    

    this.init=login_c.init;
    this.entrarClick=login_c.entrarClick;
    this.registrarClick=login_c.registrarClick;
    this.recordarClick=login_c.recordarClick;

    this.init();
    return this;
    }
}

var login_c={
  ddbb:[],
  init:function(){
    var payID=this.payID;
    $(this).find(".recordar>.button").click(function(){
      login_c.ddbb[payID].recordarClick(this);
      });
    switch(location.hash){
        case "#register":
            if(!(enviroment.user.id>0)){
                login_c.ddbb[payID].registrarClick();
                }
            break;
    }
    },
  entrarClick:function(obj){
    if(true){
      $(this).find(".box_loginform").find('.iframeContainer').empty();
      $(this).find(".box_loginform").find('.iframeContainer').append("<iframe frameborder='0' scrolling='no' src='/app/login/form'></iframe>");
      var container=$(this).find(".box_loginform").get()[0];
      $.colorbox({inline:true,href:container});
      this.entrarMostrado=(true);
      }
      else {
        $.colorbox.close();
      this.entrarMostrado=(false);
        }
    },
  registrarClick:function(obj){
    if(true){
      $(this).find(".box_registerform").find('.iframeContainer').empty();
      $(this).find(".box_registerform").find('.iframeContainer').append("<iframe frameborder='0' scrolling='no' src='/app/login/registerform'></iframe>");
      var container=$(this).find(".box_registerform").get()[0];
      $.colorbox({inline:true,href:container});
      this.registrarMostrado=(true);
      }
      else {
        $.colorbox.close();
        this.registrarMostrado=(false);
        }
    },
  recordarClick:function(obj){
    if(true){
      $(this).find(".box_reminderform").find('.iframeContainer').empty();
      $(this).find(".box_reminderform").find('.iframeContainer').append("<iframe frameborder='0' scrolling='no' src='/app/login/reminderform'></iframe>");
      var container=$(this).find(".box_reminderform").get()[0];
      $.colorbox({inline:true,href:container});
      this.reminderMostrado=(true);
      }
      else {
        $.colorbox.close();
        this.reminderMostrado=(false);
        }
    }
}

login_q(jQuery);

$(document).ready(function(){
  $(".login").login({});
})