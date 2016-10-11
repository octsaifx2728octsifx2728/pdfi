var actions_c={
	init:function(){
		this.parseSearchString();
		if(this.variables.action){
			var acciones=this.variables.action.split(",");
			for(var  i =0;i<acciones.length;i++){
				if(this[acciones[i]]){
					this[acciones[i]](this.variables);
				}
			}
		}
	},
	parseSearchString:function (){
		
		var querystring = location.search.replace( '?', '' ).split( '&');
		for(var i =0;i<querystring.length;i++){
			var variable=querystring[i].split("=");
			this.variables[variable[0]]=variable[1];
		}
	},
	
	vermensaje:function(params){
		if(params.idmensaje){
			mensaje.showDetails(params.idmensaje);
		}
	}
};

var actions_h=function(){
	this.variables={};
	this.init=actions_c.init;
	this.parseSearchString=actions_c.parseSearchString;
	this.vermensaje=actions_c.vermensaje;
	this.init();
}

var actions;

$(document).ready(
	function(){
		actions=new actions_h();
	}
);
