var parsedResults={
    ddbbObjs:[],
    ddbbintervals:[],
    overIMG:function(obj){
        
       if(obj.interval){
           clearInterval(obj.interval);
           obj.interval=false;
           $(obj).unbind("click");
           
       }
       
        $(obj).click(
            function(){
                if(!this.lock){
                    this.lock=true;
                    if(this.actual>($(this).find(".imgwrap").width()-($(this).width()*2))){
                        var nuevo=0;
                    }
                    else {
                        var nuevo=$(this).width()+this.actual;
                        }
                    $(this).animate({scrollLeft:nuevo+"px"},1000,
                        function(){
                            this.lock=false;
                            this.actual=nuevo;
                        }
                        );
                    }
            }
            );
        obj.actual=obj.actual?obj.actual:0;
        parsedResults.ddbbObjs.push(obj);
        var id=parsedResults.ddbbObjs.length-1;
        obj.interval=setInterval("parsedResults.animIMG("+id+")",3000);
        $(obj).trigger("click");
        
    },
    outIMG:function(obj){
       if(obj.interval){
           clearInterval(obj.interval);
           obj.interval=false;
           $(obj).unbind("click");
       }
    },
    animIMG:function(id){
        $(parsedResults.ddbbObjs[id]).trigger("click");
    }
}