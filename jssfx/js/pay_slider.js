var paySlider_c={
	DDBB:{
		objects:[]
	},
	imagedetector:/(?:jpg|gif|png|jpeg)/i,
	init:function(options){
		paySlider_c.DDBB.objects.push(this);
		this.payID=paySlider_c.DDBB.objects.length-1;
		this.options=options;
		this.thumbs=this.children("a");
		this.render();
		this.rendedthumbs[0].trigger("click");
	},
	loadImage:function(contenedor,enlace){
		var img=$(document.createElement("img"));
		img.attr("src",enlace.attr("href"));
		img.attr("title",enlace.attr("title"));
		img.attr("alt",enlace.attr("title"));
		contenedor.append(img);
		
	},
	scrollPanoramica:null,
	scrollPanoramicaLCom:0,
	loadImage360:function(contenedor,enlace){
		var img=$(document.createElement("img"));
		var img2=$(document.createElement("img"));
		var w=$(document.createElement("div"));
		var wr=$(document.createElement("div"));
		var controles=$(document.createElement("div"));
		var s=$(document.createElement("input"));
		var n=$(document.createElement("input"));
		var e=$(document.createElement("input"));
		var o=$(document.createElement("input"));
		
		
		img.attr("src",enlace.attr("href"));
		img.addClass("image360");
		img2.attr("src",enlace.attr("href"));
		img2.addClass("image360");
		controles.addClass("controles");
		
		
		
		s.attr("type","button");
		s.addClass("down control");
		n.attr("type","button");
		n.addClass("up control");
		e.attr("type","button");
		e.addClass("right control");
		o.attr("type","button");
		o.addClass("left control");
		wr.width(2700);
		w.addClass("w");
		wr.addClass("wr");
		wr.append(img);
		wr.append(img2);
		w.append(wr);
		contenedor.append(w);
		controles.append(n);
		controles.append(o);
		controles.append(e);
		controles.append(s);
		contenedor.append(controles);
		
		s.click(function(){
			var cont=$(this.parentNode.parentNode).find(".w");
			cont.stop();
			
			cont.animate({scrollTop:(cont.scrollTop()+50)},1000);
		});
		n.click(function(){
			var cont=$(this.parentNode.parentNode).find(".w");
			cont.stop();
			cont.animate({scrollTop:(cont.scrollTop()+50)},1000);
		});
		e.click(function(){
			var cont=$(this.parentNode.parentNode).find(".w");
			cont.stop();
			if(cont.scrollLeft()>=1350){
				cont.scrollLeft(0);
			}
			cont.animate({scrollLeft:(cont.scrollLeft()+300)},800);
		});
		o.click(function(){
			var cont=$(this.parentNode.parentNode).find(".w");
			cont.stop();
			if(cont.scrollLeft()<=0){
				cont.scrollLeft(1350);
			}
			cont.animate({scrollLeft:(cont.scrollLeft()-300)},800);
		});
		
		img.load(
			function(e){
				var img=$(e.target);
				var hi=img.height();
				var hc=$(e.target.parentNode).height();
				var m=Math.round((hc-hi)/2);
				$(e.target.parentNode).find("img").css("margin-top",m);
				}
		);
		//var altoimg=img.height();
		//var altoparent=530;
		//alert(altoimg);
		//var margen=Math.round((altoparent-altoimg)/2);
		//img.css("margin-top",margen);
		//img2.css("margin-top",margen);
	},
	loadVideo:function(contenedor,enlace){
		var ifr=$(document.createElement("iframe"));
		ifr.attr("src",enlace.attr("href"));
		ifr.css({width:"100%",height:"100%"});
		contenedor.append(ifr);
	},	
	render:function(){
		
		this.empty();
		
		this.tcontainer=this.$(document.createElement("div"));
		this.mainViewer=this.$(document.createElement("div"));
		this.mainViewerWrap=this.$(document.createElement("div"));
		
		
		
		this.tcontainer.addClass("thumbnails");
		this.mainViewer.addClass("mainViewer");
		this.mainViewerWrap.addClass("wrapper");
		
		this.mainViewer.append(this.mainViewerWrap);
		this.append(this.mainViewer);
		this.append(this.tcontainer);
		
		this.rendedthumbs=[];
		this.rendedFulls=[];
		this.posiciones=[];
		
		var mvw=this.mainViewerWrap.width();
			
		for(var i=0,leng=this.thumbs.length;i<leng;i++){
			var cont=this.$(document.createElement("div"));
			var cont2=this.$(document.createElement("div"));
			
			cont.addClass("item item_"+i+" slider_"+this.payID);
			cont2.addClass("fullitem fullitem_"+i+" slider_"+this.payID);
			
			if(i==0)cont.addClass("first");
			if(i==(leng-1))cont.addClass("last");
			var thum=this.thumbs.eq(i).children("img");
			if(thum){
				cont.append(thum);
			}
			var link =this.thumbs.eq(i).attr("href");
			if(this.thumbs.eq(i).hasClass("image")){
				cont.addClass("type_image");
				cont2.addClass("type_image");
				this.thumbs.eq(i).tipo="image";
				this.loadImage(cont2,this.thumbs.eq(i));
			}
			if(this.thumbs.eq(i).hasClass("image360")){
				cont.addClass("type_image360");
				cont2.addClass("type_image360");
				this.thumbs.eq(i).tipo="image360";
				this.loadImage360(cont2,this.thumbs.eq(i));
			}
			if(this.thumbs.eq(i).hasClass("video")){
				cont.addClass("type_video");
				cont2.addClass("type_video");
				this.thumbs.eq(i).tipo="video";
				this.loadVideo(cont2,this.thumbs.eq(i));
			}
			cont.mouseover(paySlider_c.changeHandler);
			this.mainViewerWrap.append(cont2);
			this.tcontainer.append(cont);
			var nw=this.mainViewerWrap.width()+mvw;
			var pos=this.mainViewerWrap.width()-mvw-10;
			this.mainViewerWrap.width(nw);
			
			this.posiciones.push(pos);
			this.rendedthumbs.push(cont);
			this.rendedFulls.push(cont2);
		}
	
	},
	changeHandler:function(){
		var clases=this.className;
		clases=clases.split(" ");
		var type=false;
		var item=false;
		var slider=false;
		for(var i=0;i<clases.length;i++){
			var cl=clases[i].split("_");
			switch(cl[0]){
				case "type":
				   type=cl[1];
				break;
				case "item":
				   item=cl[1];
				break;
				case "slider":
				   slider=cl[1];
				break;
			}
		}
		paySlider_c.DDBB.objects[slider].change(item);
	},
	change:function(id){
		this.mainViewer.stop();
		this.mainViewer.animate({scrollLeft:this.posiciones[id]},1000);
		//this.mainViewerWrap.find(".fullitem").effect("scale",{percent:50},500,function(){jQuery(this).effect("scale",{percent:200},500)});
	}
}
var paySlider_h=function ($){
	$.fn.paySlider=function(options){
		if(this.get().length<=0){
			return false;
		}
		this.init=paySlider_c.init;
		this.render=paySlider_c.render;
		this.change=paySlider_c.change;
		this.loadImage=paySlider_c.loadImage;
		this.loadImage360=paySlider_c.loadImage360;
		this.loadVideo=paySlider_c.loadVideo;
		this.$=jQuery;
		this.init(options);
	}
}

paySlider_h(jQuery);
