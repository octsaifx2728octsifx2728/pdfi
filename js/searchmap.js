
var map_kernel={
  mapCreatedCallback:[],
  init:function(config){
    if(config.geoloc){
      alert("geolocalizacion no implementada al inicio... proximamente");
      }
    else {
      
      
      
        
      var map = new google.maps.Map(config.contenedor,config);
      
      
      var styles = [
    {
        "featureType": "landscape",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "lightness": 65
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "poi",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "lightness": 51
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "lightness": 30
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "road.local",
        "stylers": [
            {
                "saturation": 0
            },
            {
                "lightness": 0
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "transit",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "administrative.province",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "lightness": -25
            },
            {
                "saturation": -100
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "hue": "#ffff00"
            },
            {
                "lightness": -25
            },
            {
                "saturation": -97
            }
        ]
      }
    ];

      map.setOptions({styles: styles});

      
      for(var x=0;x<config.mapCreatedCallback.length;x++){
	config.mapCreatedCallback[x](map);
      }
      map.id=this.id;
      google.maps.event.addListener(map,"bounds_changed",map_kernel.reportBounds);
      google.maps.event.addListener(map,"dragstart",map_kernel.reportTrack);
      return map;
    }
  },
  reportTrack:function(){
   paymapaddbb[this.id].autotrack=false;
  },
  reportBounds:function(){
    if(paymapaddbb[this.id].autotrack)return false;
    for(var x=0;x<paymapaddbb[this.id].boundsChangeCallback.length;x++){
      paymapaddbb[this.id].boundsChangeCallback[x](this.getBounds());
    }
  },
  addMarker:function(config){
    config.map=this.mapa;
    var marcador=new google.maps.Marker(config);
    if(config.infowindow){
      marcador.infodata=config.infodata;
      marcador.infoWindowVisible=false;
      google.maps.event.addListener(marcador,"click",function(){
	if(this.infoWindowVisible){
	  this.infowindow.close();
	  this.infoWindowVisible=false;
	  }
	else {
	  this.infowindow = new google.maps.InfoWindow({
	    content: this.infodata,
	    position: this.getPosition()
	    });
	  this.infowindow.open(this.getMap(),this);
	  this.infoWindowVisible=true;
	  }
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
  this.contenedor=document.getElementById(config.contenedor);
  this.init=map_kernel.init;
  this.addMarker=map_kernel.addMarker;
  this.clear=map_kernel.clear;
  this.centerOnMarkers=map_kernel.centerOnMarkers;
  this.mapCreatedCallback=map_kernel.mapCreatedCallback;
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
      center: new google.maps.LatLng(config.center[0], config.center[1]),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      zoom:6,
      mapCreatedCallback:this.mapCreatedCallback
      });
  }
}
 
