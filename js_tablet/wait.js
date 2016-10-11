
var waitdiv=document.createElement("div");
waitdiv.className="wait";
function wait(show){
	console.log("wait");
}
wait(true);
$(document).ready(function(){
    wait(false);
});