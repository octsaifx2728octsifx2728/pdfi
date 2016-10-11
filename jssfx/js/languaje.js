
  $(document).ready(function(){
  $("ul.languajes").selectable({
    selected:function(ev,ui){
      wait(true);
      var lenguaje=ui.selected.title;
      location.href="/app/lang/"+lenguaje+($(ui.selected).hasClass("skip")?"?skip=1":"");
      }
    });
  });