 var payFilters_q=function($){
        $.fn.payfilter=function(options){
            payFilters_c.ddbb.push(this);
            this.payID=payFilters_c.ddbb.length-1;
            this.options=options;
            this.init=payFilters_c.init;
            this.parseClassString=payFilters_c.parseClassString;
            this.doublerangezoomParse=payFilters_c.doublerangezoomParse;
            this.doublerangezoom=payFilters_c.doublerangezoom;
            this.vals=payFilters_c.vals;
            this.setVals=payFilters_c.setVals;
            this.init();
            return this;
            }
        };
        
    var payFilters_c={
        ddbb:[],
        vals:[],
        init:function(){
            $(this).addClass("payfilter");
            var tipo=this.options.filtertype;
            switch(tipo){
                case "doublerangezoom":
                    this.doublerangezoomParse();
                break;
                default:
                    console.log("el tipo de campo: "+tipo+" no esta soportado");
                }
            if(this.options.currencyconverter){
                enviroment.currencyconvertercallback.push(this);
                this.currencyconverter=payFilters_c.currencyconverter;
            }
            },
         setVals:function(i,val){
            this.vals[i]=val;
            },
         currencyconverter:function(moneda){
           var script=document.createElement("script");
           script.src="api/currencyconverter/convert2?valor="+this.options.maximo+
               "&monedaOrig="+enviroment.currency+
               "&moneda="+moneda+
               "&callback=payFilters_c.ddbb["+this.payID+"].setMax&idcallback="+1;
           script.type="text/javascript";
           //console.log(script.src);
           //document.getElementsByTagName("head")[0].appendChild(script);
         },
    doublerangezoomParse:function(){
       var payID=this.payID;
       
        this.set=this.doublerangezoom.set;
        
        this.setMin=this.doublerangezoom.setMin;
        this.setMax=this.doublerangezoom.setMax;
        this.render=this.doublerangezoom.render;
        
        this.setMin(this.options.minimo);
        this.setMax(this.options.maximo);
        
            
        this.set(0,this.options.startMin);
        this.set(1,this.options.startMax);
        this.doublerangezoom.move($(this).find(".agujaLeft").position().left+50,$(this).find(".agujaLeft"),payID,true);
        this.doublerangezoom.move($(this).find(".agujaRight").position().left+50,$(this).find(".agujaRight"),payID,true);
        },
    doublerangezoom:{
        setMin:function(val){
            this.options.minimo=val;
            if(this.options.minimo>this.vals[0]){
                this.vals[0]=this.options.minimo;
                }
            if(this.options.maximo<this.options.minimo){
                this.options.maximo=this.options.minimo;
                }
            this.render();
            },
        setMax:function(val){
            //val=val.valor?val.valor:val;
            this.options.maximo=val;
            if(this.options.maximo<this.vals[1]){
                this.vals[1]=this.options.maximo;
                }
            if(this.options.minimo>this.options.maximo){
                this.options.minimo=this.options.maximo;
                }
            this.render();
            },
        render:function(){
            var payID=this.payID;
           $(this).addClass("payfilter_doublerangezoomParse");
            $(this).empty();
            $(this).append('<div class="filterTop">'+
                    '<span class="numberMin"></span>'+
                    ' - '+
                    '<span class="numberMax"></span>'+
                '</div>'+
                '<div class="filterRegleta">'+
                    '<div class="agujaLeft" ondrag="alert(\'hihi crocodile\')" onmousedown="payFilters_c.ddbb['+payID+'].doublerangezoom.dragStart(event,this,'+payID+');"></div>'+
                    '<div class="agujaRight" onmousedown="payFilters_c.ddbb['+payID+'].doublerangezoom.dragStart(event,this,'+payID+');"></div>'+
                    '<div class="escala"></div>'+
                '</div>');
            },
        set:function(i,val){
            if(this.vals.length<2){
                this.vals=[this.options.minimo,this.options.maximo];
            }
            if(val<this.options.minimo)val=this.options.minimo;
            if(val>this.options.maximo)val=this.options.maximo;
            switch(i){
                case 0:
                    if(val>this.vals[1])val=this.vals[1];
                    var obj=$(this).find(".agujaLeft");
                    $(this).find(".numberMin").text(val.toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( this.options.separadorMiles ));
               
                    break;
                case 1:
                    if(val<this.vals[0])val=this.vals[0];
                    var obj=$(this).find(".agujaRight");
                    $(this).find(".numberMax").text(val.toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( this.options.separadorMiles ));
                    break;
                default: return false;
                }
            this.vals[i]=val;
            var x=(((val-this.options.minimo)*$(this).width())/(this.options.maximo-this.options.minimo));
            
            this.doublerangezoom.move(x,obj,this.payID,true);
            },
        reportMove:function(x,obj,payID){
            var left=payFilters_c.ddbb[payID].position().left;
            var right=payFilters_c.ddbb[payID].position().left+payFilters_c.ddbb[payID].width();
            var val=x-left;
            val=Math.round(val);
            var distancia1=payFilters_c.ddbb[payID].options.maximo-payFilters_c.ddbb[payID].options.minimo;
            var distancia2=right-left;
            var valorAbsoluto=((val*distancia1)/distancia2)+payFilters_c.ddbb[payID].options.minimo;
            
                
            if(valorAbsoluto<payFilters_c.ddbb[payID].options.minimo)valorAbsoluto=payFilters_c.ddbb[payID].options.minimo;
            if(valorAbsoluto>payFilters_c.ddbb[payID].options.maximo)valorAbsoluto=payFilters_c.ddbb[payID].options.maximo;
            
            
            valorAbsoluto=Math.round(valorAbsoluto);
            if($(obj).hasClass("agujaLeft")){
                if(val<0){
                    return false;
                }
                if(valorAbsoluto>=payFilters_c.ddbb[payID].vals[1]){
                    valorAbsoluto=payFilters_c.ddbb[payID].vals[1];
                    return false;
                     }
                payFilters_c.ddbb[payID].setVals(0,valorAbsoluto);
                $(payFilters_c.ddbb[payID]).find(".numberMin").text(valorAbsoluto.toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( payFilters_c.ddbb[payID].options.separadorMiles ));
                }
            else{
                if(val>right){
                    return false;
                    }
                if(valorAbsoluto<=payFilters_c.ddbb[payID].vals[0]){
                    valorAbsoluto=payFilters_c.ddbb[payID].vals[0];
                    return false;
                    }
                payFilters_c.ddbb[payID].setVals(1,valorAbsoluto);
                $(payFilters_c.ddbb[payID]).find(".numberMax").text(valorAbsoluto.toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( payFilters_c.ddbb[payID].options.separadorMiles )+(payFilters_c.ddbb[payID].options.maximo<=valorAbsoluto?"+":""));
                }
                
                
                    if(payFilters_c.ddbb[payID].options.callBack){
                        payFilters_c.ddbb[payID].options.callBack(payFilters_c.ddbb[payID].vals,payFilters_c.ddbb[payID]);
                        }
            return true;
            },
        move:function(x,obj,payID,skipReport){
                var obj=$(obj);
                if(skipReport||payFilters_c.ddbb[payID].doublerangezoom.reportMove(x,obj,payID)){
                    var ofx=(obj.width()/2);
                    
                    if(obj.css("left")<(x-ofx)){
                        ofx-=5;
                        }
                    else {
                        ofx+=5;
                    }
                    obj.css({position:"absolute",left:x-ofx});
                    var escala=payFilters_c.ddbb[payID].find(".escala");
                    if(obj.hasClass("agujaLeft")){
                        escala.css(
                            {
                                "margin-left":x-ofx-1
                            });
                        }
                    escala.css(
                            {
                                "width":payFilters_c.ddbb[payID].find(".agujaRight").position().left-payFilters_c.ddbb[payID].find(".agujaLeft").position().left
                            });
                    }
                },
        
        dragStart:function(e,obj,payID){
                    e.stopPropagation();
                var payID=payID;
                var obj=obj;
                $("body").css({"-webkit-user-select":"none",
                              "-moz-user-select": "none",
                                  "-ms-user-select": "none",
                                      "-o-user-select": "none",
                                "user-select": "none"
                            });
                $(obj).addClass("moving");
                
                $(window).mousemove(function(e){
                    e.stopPropagation();
                    payFilters_c.ddbb[payID].doublerangezoom.move(e.pageX,obj,payID);
                    });
                $(window).mouseup(function(e){
                    e.stopPropagation();
                    $(this).unbind("mousemove");
                    $(this).unbind("mouseup");
                    $(this).removeClass("moving");
                    $("body").css({"-webkit-user-select":"auto",
                              "-moz-user-select": "auto",
                                  "-ms-user-select": "auto",
                                      "-o-user-select": "auto",
                                "user-select": "auto"
                            });
                    });
                payFilters_c.ddbb[payID].doublerangezoom.move(e.pageX,obj,payID);
            },
        minDragStop:function(e,obj,payID){
            e.stopPropagation();
            console.log("detener");
            }
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
        };
        
    payFilters_q(jQuery);