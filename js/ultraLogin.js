
    ultraLogin_q=function($){
        $.fn.ultraLogin=function(options){
            
            ultraLogin_h.ddbb.push(this);
            this.payID=ultraLogin_h.ddbb.length-1;
            this.init=ultraLogin_h.init;
            this.changeForm=ultraLogin_h.changeForm;
            this.appendForm=ultraLogin_h.appendForm;
            this.createMenu=ultraLogin_h.createMenu;
            this.menuclick=ultraLogin_h.menuclick;
            this.options=options;
            
            this.init($);
            return this;
        }
    }
    ultraLogin_h={
        ddbb:[],
        init:function($){
            var payID=this.payID;
            var Key=this.options.key;
            var initform=this.options.initform;
            var avaliableForms=this.options.avaliableForms;
            
            $(this).click(function(){
                
                var options2=ultraLogin_h.parseClassString(this.className);
                $.colorbox({inline:true,href:".loginform_0_"+Key});
                ultraLogin_h.ddbb[payID].createMenu();
                ultraLogin_h.ddbb[payID].changeForm(initform,options2);
                $.colorbox.resize({height: "300px"});
                
                
                
            });
         
        },
      createMenu:function(){
          var payID=this.payID;
                var contenedor=$(".loginform_0_"+this.options.key);
                contenedor.find(".loginform_0_menu").empty();
                for(var i=0;i<this.options.avaliableForms.length;i++){
                    var line=document.createElement("li")
                    line.className="loginform_menu_line menuline_"+this.options.avaliableForms[i]+(i==0?" loginform_menuline_first":"")+(i==(this.options.avaliableForms.length-1)?" loginform_menuline_last":"");
                    $(line).append($(".loginform_"+this.options.avaliableForms[i]+"_"+this.options.key).find(".loginform_button").get()[0].cloneNode(true));
                    $(line).click(function(){
                        ultraLogin_h.ddbb[payID].menuclick(this);
                    });
                    contenedor.find(".loginform_0_menu").append(line);
                }
      },
      menuclick:function(line){
         var ops= ultraLogin_h.parseClassString(line.className);
         this.changeForm(ops.menuline);
                $.colorbox.resize();
                $.colorbox.resize({height: "300px"});
      },
      changeForm:function(id,options2){
                var contenedor=$(".loginform_0_"+this.options.key);
                contenedor.find(".loginform_0_body").empty();
                contenedor.find(".loginform_0_menu").find(".loginform_menuline_selected").removeClass("loginform_menuline_selected");
                contenedor.find(".loginform_0_menu").find(".menuline_"+id).addClass("loginform_menuline_selected");
                this.appendForm(id,options2);
      },
      appendForm:function(id,options2){
                var contenedor=$(".loginform_0_"+this.options.key);
                var formulario=$(".loginform_"+id+"_"+this.options.key);
                
                contenedor.find(".loginform_0_head>h1").text(formulario.find(".loginform_title").text());
                contenedor.find(".loginform_0_body").append(formulario.find(".loginform_body").get()[0].cloneNode(true));
                switch(id){
                    case 1:
                        contenedor.find("input[name=email]").get()[0].focus();
                        $.colorbox.resize();
                        $.colorbox.resize({height: "300px"});
                        break;
                    case 3:
                        contenedor.find("input[name=id]").val(this.options.inmueble);
                        contenedor.find("input[name=ulid]").val(this.payID);
                        break;
                    case 5:
                        if(!(this.options.contactUser&&this.options.contactUser.length>0)){
                            this.changeForm(6);
                           // $.colorbox.resize();
                           return false;
                        }
                        else{
                            contenedor.find(".loginform_0_body").showPhones({telefonos:this.options.contactUser});
                            $.colorbox.resize();
                            $.colorbox.resize({height: "300px"});
                        }
                        break;
                    case 7:
                           contenedor.find(".changePassword").changePasswordForm({});

                        break;
                    case 9:
                          if(this.options.chunk){
                              $.get("api/chunk/"+this.options.chunk+"?options="+JSON.stringify(this.options.params_chunk),function(data){
                                  data=data.replace("({","({");
                                  data=data.replace("},'');","})");
                                  data=eval(data);
                                  contenedor.find(".loginform_body").empty();
                                  contenedor.find(".loginform_body").append(data.chunk);
                                  console.log(contenedor.find(".loginform_body"));
                                  $.colorbox.resize();
                                  contenedor.find(".button_borrar").click(function(){
                                      var id=options2.borrarid.split("-");
                                      inmueble_manager.borrar(id[0],'',id[1])
                                  });
                                  contenedor.find(".vendidobutton").click(function(){
                                      var id=options2.borrarid.split("-");
                                      javascript:inmueble_manager.attachProduct(id[0],id[1],14);
                                  });
                              });
                          }

                        break;
                    default:
                        $.colorbox.resize();
                        $.colorbox.resize({height: "300px"});
                        break;
                }
      },
    parseClassString:function(cs){
      cs=cs.split(" ");
      var cs2={};
      for(var i=0; i<cs.length;i++){
        cs[i]=cs[i].split("_",2);
        cs2[cs[i][0]]=cs[i].length>2?ultraLogin_h.parseClassString(cs[i][1]):cs[i][1];
        
        }
      return cs2;
      }
    };

ultraLogin_q(jQuery);