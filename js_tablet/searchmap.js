
var map_kernel={
  mapCreatedCallback:[],
  mapDragEndCallback:[],
  init:function(config){
    if(config.geoloc){
      alert("geolocalizacion no implementada al inicio... proximamente");
      }
    else {
      var map = new google.maps.Map(config.contenedor,config);
      console.log(config.zoom);
      console.log(map.getZoom());
      for(var x=0;x<config.mapCreatedCallback.length;x++){
	config.mapCreatedCallback[x](map);
      }
      map.id=this.id;
      google.maps.event.addListener(map,"dragend",map_kernel.reportBounds);
      google.maps.event.addListener(map,"zoom_changed",map_kernel.reportBounds);
      return map;
    }
  },
  reportTrack:function(){
   paymapaddbb[this.id].autotrack=false;
  },
  reportBounds:function(){
    if(paymapaddbb[this.id].autotrack)return false;
    for(var x=0;x<paymapaddbb[this.id].mapDragEndCallback.length;x++){
      paymapaddbb[this.id].mapDragEndCallback[x](this.getBounds());
    }
  },
  addMarker:function(config){
    config.map=this.mapa;
    var marcador=new google.maps.Marker(config);
    if(config.infowindow){
      marcador.infodata=config.infodata;
      marcador.configdata=config;
      marcador.infoWindowVisible=false;
      google.maps.event.addListener(marcador,"click",function(){
	location.href=this.configdata.infolink;
	});
    }
    this.markers.push(marcador);
  },
  centerOnMarkers:function(){
    if(this.markers.length){
      var bounds=new google.maps.LatLngBounds();
      for(var x=0;x<this.markers.length;x++){
	bounds=bounds.extend(this.markers[x].getPosition());
      }
      this.autotrack=true;
      this.mapa.setCenter(bounds.getCenter());
      this.mapa.fitBounds(bounds);
    }
  },
  clear:function(){
    while(this.markers.length){
      var m=this.markers.pop();
      m.setMap(null);
    }
  },
  getPlacesService:function(){
    if(this.mapa){
      var service = new google.maps.places.PlacesService(this.mapa);
      return service;
    }
  }
}

var paymapaddbb=[];

var paymapa=function (config){
  paymapaddbb[config.id]=this;
  this.id=config.id;
  this.autotrack=false;
  this.mapa=null;
  this.markers=[];
  this.boundsChangeCallback=[];
  this.boundsDragEndCallback=[];
  this.contenedor=document.getElementById(config.contenedor);
  this.init=map_kernel.init;
  this.addMarker=map_kernel.addMarker;
  this.clear=map_kernel.clear;
  this.centerOnMarkers=map_kernel.centerOnMarkers;
  this.mapCreatedCallback=map_kernel.mapCreatedCallback;
  this.mapDragEndCallback=map_kernel.mapDragEndCallback;
  this.getPlacesService=map_kernel.getPlacesService;
  if(config.mapCreatedCallback){
    this.mapCreatedCallback.push(config.mapCreatedCallback);
  }
  this.mapCreatedCallback.push(
    function(mapa){
      paymapaddbb[config.id].mapa=mapa;
      }
    );

  if(config.init){
    this.map=this.init({
      contenedor:this.contenedor,
      geoloc:config.geoloc,
      backgroundColor:null,
      center: config.center,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      zoom:config.zoom?config.zoom:6,
      mapCreatedCallback:this.mapCreatedCallback
      });
  }
}
 
