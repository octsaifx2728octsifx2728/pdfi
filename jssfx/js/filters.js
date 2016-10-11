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
            $(this).find(".field").click(function(){
                //console.log("CLICK");
                var padre=$(this).parents(".filter").get()[0];
                var param=padre.ops.filter;
                var valor=$(this).find("input").val();

                $(padre).find(".field").removeClass("selected");
                $(padre).find(".field").find("input").prop("checked",false);

                $(this).addClass("selected");
                $(this).find("input").prop("checked",true);
                var script=document.createElement("script");
                script.src="api/enviroment/set?filters=1&filtername="+param+"&filtervalue="+encodeURI(valor);
                enviroment.filters[param]=valor;
                  //  console.log(enviroment.filters[param]);
                if(padre.ops.statico!="1"){
                    document.getElementsByTagName("head")[0].appendChild(script);
                    console.log(script.src);
                }
                if(param=="tipoobjeto"){
                    $(padre.parentNode).find(".filter_tipovr").prop("className","filter filter_tipovr type_radio selected_"+valor);

                    $(padre.parentNode).find(".filter_tipovr").find(".fieldParent_"+valor+">input[value=tipovrventa]").parents(".field").trigger("click");
                }
            });
        });
        return this;
        this.ops=this.parseClassString(this.filter.className);
        switch(this.ops.type){
            case "radio":
                $(this.filter).find(".field>input[type=radio]").change(function(){
                    filters_c.ddbb[payID].change(this.value);
                    });
                $(this.filter).find(".field").click(function(){
                    $(this).find("input[type=radio]").attr("checked",true);
                    $(this).find("input[type=radio]").trigger("change");
                    });
                break;
            default:
                console.log(this.ops);
                break;
            }

        },
change:function(valor){
        enviroment.filters[this.ops.filter]=valor;
        var script=document.createElement("script");
        script.src="api/enviroment/set?filters=1&filtername="+this.ops.filter+"&filtervalue="+encodeURI(valor);
        document.getElementsByTagName("head")[0].appendChild(script);
        //console.log("CHANGE this.ops =" + this.ops + " enviroment =" + enviroment);
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