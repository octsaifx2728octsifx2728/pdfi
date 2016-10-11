/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var alertSystem={
    /**
     * @function alertSystem.alert
     * @param <String> mensaje
     * @param [<int> nivel=6] //0=CriticalError,1=Error,2=Advertence,3=ImportantMessaje,4=Message,5=CorrectMessaje,6>=DefaultMessaje
     * @param [<DOMElement> *contenedor=body]
     * @sumary Muestra un mensaje del sistema.
     * @return null
     * */
    alert:function(mensaje,nivel,contenedor){
        var n=nivel?nivel:6;
        var c;
        if(!contenedor){
            c=document.createElement("div");
            var div=document.createElement("div");
            div.className="icon";
            var div2=document.createElement("div");
            div2.className="message";
            c.appendChild(div);
            c.appendChild(div2);
            
            document.getElementsByTagName("body")[0].insertBefore(c,document.getElementsByTagName("body")[0].firstChild);
        }
        else{
            c=contenedor;
        }
        switch(n){
            case 0:
                alert(mensaje);
                break;
            default:
                $(c).addClass("alert alert_"+n);
                $(c).find(".message").text(mensaje);
                var h=$(c).height();
                $(c).height("0px");
                $(c).animate({"height":h+"px"},1000);
                setTimeout(function(){$(c).animate({"height":"0px"},1000,function(){
                        $(c).height(h+"px");
                        $(c).find(".message").text("");
                        c.removeClass("alert_"+n);
                    });},5000);
        }
    }
}
