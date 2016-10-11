
var waitdiv=document.createElement("div");
waitdiv.className="wait";
function wait(show){
	if(show&&!waitdiv.parentNode){
		document.getElementsByTagName("body")[0].insertBefore(waitdiv,document.getElementsByTagName("body")[0].firstChild);
	}
	if(!show&&waitdiv.parentNode){
		document.getElementsByTagName("body")[0].removeChild(waitdiv);
	}
}
