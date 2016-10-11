
var formularioPago_q=function($){
    $.fn.formularioPago=function(options){
        this.options=options;
        formularioPago_c.ddbb.push(this);
        this.payID=formularioPago_c.ddbb.length-1;
        this.init=formularioPago_c.init;
        this.vsubmit=formularioPago_c.vsubmit;
        this.verify=formularioPago_c.verify;
        this.CCTAGS=formularioPago_c.CCTAGS;
        this.init($);
        return this;
    }
    
}

var formularioPago_c={
        ddbb:[],
        CCTAGS:{
            VISA:/^4[0-9]{12}(?:[0-9]{3})?$/,
            MC:/^5[1-5][0-9]{14}$/
        },
        init:function($){
            var payID=this.payID;
            $(this).find(".fieldType_monthyear>input").each(function(){
                var options = {
                    pattern: 'mm/yy', // Default is 'mm/yyyy' and separator char is not mandatory
                    selectedYear: 2013,
                    startYear: 2013,
                    finalYear: 2027,
                    monthNames: enviroment.mesesLong
                };

                $(this).monthpicker(options);
            });
            $(this).find(".fieldType_monthyear>input").change(function(){
                var params=formularioPago_c.parseClassString(this.parentNode.className);
                return formularioPago_c.verify(this.parentNode,params);
            });
            $(this).find(".fieldType_creditcard>input").change(function(){
                var params=formularioPago_c.parseClassString(this.parentNode.className);
                return formularioPago_c.verify(this.parentNode,params);
            });
            $(this).find(".fieldType_text>input").keyup(function(){
                var params=formularioPago_c.parseClassString(this.parentNode.className);
                return formularioPago_c.verify(this.parentNode,params);
            });
            $(this).find(".fieldType_tel>input").keyup(function(){
                var params=formularioPago_c.parseClassString(this.parentNode.className);
                return formularioPago_c.verify(this.parentNode,params);
            });
            $(this).find(".clearOnFocus>input").focus(function(){
                if(this.value==this.title){
                    this.value="";
                }
            });
            $(this).find(".field>input").focus(function(){
                    $(this.parentNode).removeClass("unchanged");
                    $(this.parentNode).addClass("changed");
            });
            $(this).find(".field>input").focusout(function(){
                    if($(this).val()==$(this).attr("title")||!$(this).val()){
                        $(this).val($(this).attr("title"));
                        $(this.parentNode).removeClass("changed");
                        $(this.parentNode).addClass("unchanged");
                    }
            });
            $(this).find(".field").each(function(){
                if($(this).find("input").val()==$(this).find("input").attr("title")){
                    $(this).addClass("unchanged");
                }
                else {
                    $(this).addClass("changed");
                }
            })
            $(this).find(".submit").click(function(){
              formularioPago_c.ddbb[payID].vsubmit();
            })
        },
        verify:function(field,params){
           switch(params.fieldType){
               case "creditcard":
                   val=$(field).find("input").val();
                   for(i in this.CCTAGS){
                       if(this.CCTAGS[i].test(val)){
                        var tipoCC=i;
                       }
                   }
                   if(!tipoCC){
                       val="";
                   }
                   else {
                       $("#_CardType").val(tipoCC);
                   }
                    break;
               case "monthyear":
                   var val=$(field).find("input").val();
                   var d=val.split("/");
                   var fecha=new Date();
                   if((("20"+d[1])*1)<=fecha.getFullYear()){
                       if((d[0]*1)<(fecha.getMonth()+1)){
                           val=false;
                       }
                   }
                break;
               case "text":
                   var val=$(field).find("input").val();
                   if(val==$(field).find("input").attr("title")){
                       val="";
                        }
                    break;
               case "tel":
                   var val=$(field).find("input").val();
                   if(val==$(field).find("input").attr("title")){
                       val=false;
                        }
                    break;
               default:
                   return true;
           }
           $(field).removeClass("error");
           
           if(params.fieldStripAlpha){
               var val2=val.replace(/[a-z]+/, "");
               if(val2!=val){
                $(field).addClass("error");
                return false;
               }
           }
           if(params.fieldMin&&(params.fieldMin*1)>val.length){
                $(field).addClass("error");
                return false;
           }
           if(params.fieldMax&&(params.fieldMax*1)<val.length){
                $(field).addClass("error");
                return false;
           }
           if(params.fieldRequired&&!val){
                $(field).addClass("error");
                return false;
           }
           $(field).addClass("ok");
           return true;
        },
	fields:{
			factura:"#_factura",
			nombre:"#_nombre",
			apellidos:"#_apellidos",
			compania:"#_compania",
			telefono:"#_telefono",
			fax:"#_fax",
			direccion1:"#_direccion1",
			direccion2:"#_direccion2",
			pais:"#_pais",
			estado:"#_estado",
			ciudad:"#_ciudad",
			nacimiento:"#_nacimiento",
			email:"#_email",
			rfc:"#_rfc",
			tarjeta:"#_numTarjeta",
			ccv:"#_ccv",
			exp:"#_expire",
			comentarios:"#_comentarios",
			CardType:"#_CardType"
		},
	vsubmit:function(){
            var fields=$(this).find(".fieldRequired_1").get();
            var params;
            for(var i=0;i<fields.length;i++){
                params=formularioPago_c.parseClassString(fields[i].className);
                if(!this.verify(fields[i], params)){
                    $(window).scrollTop($(fields[i]).offset().top);
                    return false;
                }
            }
            $(this).find("form").get()[0].submit();
	},
	parceFields:function(fields){
		var url=[];
		for(var i in fields){
			url.push(i+"="+encodeURI(fields[i]));
		}
		url=url.join("&");
		return url;
	},
    parseClassString:function(cs){
      cs=cs.split(" ");
      var cs2={};
      for(var i=0; i<cs.length;i++){
        cs[i]=cs[i].split("_");
        cs2[cs[i][0]]=cs[i].length>2?loginForm_c.parseClassString(cs[i][1]):cs[i][1];
        
        }
      return cs2;
      }
}
formularioPago_q(jQuery);