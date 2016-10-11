var filters_q=function($){
    $.fn.filtro=function(opciones){
        filters_c.ddbb.push(this);
        this.payID=filters_c.ddbb.length-1;
        this.init=filters_c.init;
        this.parseClassString=filters_c.parseClassString;
        this.change=filters_c.change;
        this.init($);
        return this;
        }
    }
var filters_c={
    ddbb:[],
    init:function($){
        var payID=this.payID;
        this.filter=$(this).find(".filter").each(function(i){
            this.ops=filters_c.parseClassString(this.className);
            $(this).find("select").change(function(){
                var padre=$(this).parents(".filter").get()[0];
                var param=padre.ops.filter;
                var valor=$(this).val();
                   console.log(valor);    
                enviroment.filters[param]=valor;
                if(param=="tipoobjeto"){
                    $(padre.parentNode).find(".filter_tipovr").prop("className","filter filter_tipovr type_radio selected_"+valor);
                    $(padre.parentNode).find(".filter_tipovr>.fieldParent_"+valor).val("tipovrventa");
                    }
            });
        });
        return this;
        
        },
    parseClassString:function(cs){
      cs=cs.split(" ");
      var cs2={};
      for(var i=0; i<cs.length;i++){
        cs[i]=cs[i].split("_",2);
        cs2[cs[i][0]]=cs[i].length>2?loginForm_c.parseClassString(cs[i][1]):cs[i][1];
        
        }
      return cs2;
      }
    }

filters_q(jQuery);

$(document).ready(function(){
    $(".searchFilter").filtro({});
    });