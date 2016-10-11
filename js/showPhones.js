var showPhones_q=function($){
    $.fn.showPhones=function(options){
        showPhones_h.ddbb.push(this);
        this.payID=showPhones_h.ddbb.length-1;
        this.options=options;
        this.init=showPhones_h.init;
        this.init($);
    }
}

var showPhones_h={
    ddbb:[],
    init:function($){
        var payID=this.payID;
        for(var i=0;i<this.options.telefonos.length;i++){
            var line=document.createElement("div");
            var icon=document.createElement("div");
            var link=document.createElement("a");
            var tag=document.createElement("span");
            var text=document.createTextNode(this.options.telefonos[i].telefono);
            var texttag=document.createTextNode(this.options.telefonos[i].tag+": ");
            tag.className="tag";
            line.className="telefono";
            icon.className="icon";
            link.href="tel://"+this.options.telefonos[i];
            line.appendChild(icon);
            line.appendChild(tag);
            line.appendChild(link);
            link.appendChild(text);
            tag.appendChild(texttag);
            $(showPhones_h.ddbb[payID]).find(".telefonos").append(line);
            
        }
    }
}

showPhones_q(jQuery);