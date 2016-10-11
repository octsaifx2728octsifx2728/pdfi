<?
if (!isset($_SESSION)) {
  session_start();
}

header('Cache-Control: no-cache');
header('Pragma: no-cache');

include ("valida_usuario.php");
$_SESSION['MM_ADS'] = '';
$_SESSION['MM_espacios'] = '2';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#" 
      xmlns:fb="http://www.facebook.com/2008/fbml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="es" />

<title>Encuentra cientos de inmuebles en venta o renta - e-spacios.com</title>

<meta name="description" content="Bienes raices e-spacios.com venta y renta de casas, propiedades, departamentos y condominios" />

<meta name="keywords" content="vendo casa, vender casa, vender casas, vender apartamento, anunciar casa, vender departamentos, urge vender casa, vender departamento, vender casa gratis, vender propiedad, vende tu casa, para vender casas, vende casas, vender casa rapido, como vender casa, vender casa internet,  para vender una casa, quiero vender una casa, como vender una casa rapido, cómo vender una casa, como puedo vender una casa, consejos para vender una casa " />

<link rel="shortcut icon" href="iconoespacios.ico" />
<meta property="og:title" content="Encuentra cientos de inmuebles en venta o renta - e-spacios.com"/>
<meta property="og:type" content="article"/>
<meta property="og:url" content="http://www.e-spacios.com/"/>
<meta property="og:image" content="http://www.e-spacios.com/img/espaciosch.gif"/>
<meta property="og:site_name" content="e-spacios.com"/>
<meta property="og:description" content="Bienes raices e-spacios.com venta y renta de casas, propiedades, departamentos y condominios"/>

<!-- DUBLIN CORE -->
<meta name="DC.Title" content="Encuentra cientos de inmuebles en venta o renta - e-spacios.com" /> 
<meta name="DC.Publisher" content="e-spacios.com" /> 
<meta name="DC.Creator" content="e-spacios.com" /> 
<meta name="Revisit" content="5 days" /> 
<meta name="revisit-after" content="5 days" /> 
<meta name="language" content="es" /> 
<meta name="distribution" content="global" /> 
<meta name="resource-type" content="document" />
<meta name="classification" content="public" /> 
<meta name="rating" content="General" /> 
<meta name="generator" content="http://www.e-spacios.com/" /> 
<meta name="Pagetype" content="web" /> 
<meta name="Audience" content="All" /> 

<!-- Geo Meta tags -->
<meta name="geo.region" content="MX-MEX" />
<meta name="geo.placename" content="Ciudad de M&eacute;xico" />
<meta name="geo.position" content="19.431643;-99.185706" />
<meta name="ICBM" content="19.431643, -99.185706" />

<link media="screen" rel="stylesheet" href="css/style.css" />

<script src="js/jquery.min.js"></script>
<link media="screen" rel="stylesheet" href="css/colorbox.css" />
<link media="screen" rel="stylesheet" href="css/global.css" />
<script src="js/jquery.colorbox.js"></script>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&amp;language=es" type="text/javascript"></script>

<script type="text/javascript">
	$(function() {
			$(".example1").colorbox({width:"600px" , height:"500px", iframe:true, scrolling:false});
			// login
			$(".example2").colorbox({width:"600px" , height:"400px", iframe:true, scrolling:false});
			$("a[rel='example3']").colorbox({transition:"none", width:"75%", height:"75%"});
			$("a[rel='example4']").colorbox({slideshow:true});
			$(".example5").colorbox({width:"600px" , height:"500px", iframe:true, scrolling:false});
			$(".example6").colorbox({iframe:true, innerWidth:425, innerHeight:344});
			$(".example7").colorbox({width:"80%", height:"80%", iframe:true});
			$(".example8").colorbox({width:"50%", inline:true, href:"#inline_example1"});
			$(".example9").colorbox({
				onOpen:function(){ alert('onOpen: colorbox is about to open'); },
				onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
				onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
				onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
				onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
			});
			
			//Example of preserving a JavaScript event for inline calls.
			$("#click").click(function(){ 
				$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
				return false;
			});	
			
          $('form1').keypress(function(e){    
             if(e == 13){ 
                return false; 
             } 
          }); 
 
          $('input').keypress(function(e){ 
            if(e.which == 13){ 
               return false; 
            } 
          }); 
  			
   });				
/*
	function lookup(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			$.post("rpc.php", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				}
			});
		}
	} // lookup
	
	function fill(thisValue) {
		$('#inputString').val(thisValue);
		setTimeout("$('#suggestions').hide();", 200);
	}
*/	
	
  var map;
  var geocoder;
  var centerChangedLast;
  var reverseGeocodedLast;
  var currentReverseGeocodeResponse;
  var markersArray = [];

<? 
   if ($lat=='') { $lat="23.60426184707018";}
   if ($lng=='') { $lng="-100.986328125";}
?>   

      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(<? echo $lat;?>, <? echo $lng; ?>),
          zoom: 4,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
         map = new google.maps.Map(document.getElementById('map_canvas'),
          mapOptions);
		  
       geocoder = new google.maps.Geocoder();
       setupEvents();
       //centerChanged();		  
		  
        var input = document.getElementById('searchTextField');
        var autocomplete = new google.maps.places.Autocomplete(input);
        
		autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
        map: map
        });
		
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
        infowindow.close();
		  
          var place = autocomplete.getPlace();
          if (place.geometry.viewport) {
             document.getElementById('lat').value = map.getCenter().lat();
             document.getElementById('lng').value = map.getCenter().lng();
             map.fitBounds(place.geometry.viewport);
			 document.form1.submit();	
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(12);  // Why 17? Because it looks good.
          }
        });

		
        // Sets a listener on a radio button to change the filter type on Places
        // Autocomplete.
        function setupClickListener(id, types) {
          var radioButton = document.getElementById(id);
          google.maps.event.addDomListener(radioButton, 'click', function() {autocomplete.setTypes(types);});
        }
 	

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);
		
  function setupEvents() {
     google.maps.event.addListener(map, 'zoom_changed', function() {
     document.getElementById("zoom_level").value = map.getZoom();
     centerChanged();
	 });

    google.maps.event.addListener(map, 'center_changed', centerChanged);
	
 //   google.maps.event.addDomListener(document.getElementById('crosshair'),'dblclick', function() {
//	     map.setZoom(map.getZoom() + 1);
//	    centerChanged();
//	});
	
    google.maps.event.addListener(map, 'idle', function() {    
   //centerChanged();
    }); 		
	
  }
  
  function getCenterLatLngText() {
    return '(' + map.getCenter().lat() +', '+ map.getCenter().lng() +')';
  }

  function centerChanged() {
    centerChangedLast = new Date();
    var latlng = getCenterLatLngText();
    document.getElementById('lat').value = map.getCenter().lat();
    document.getElementById('lng').value = map.getCenter().lng();	
    document.getElementById('formatedAddress').value = '';
//    document.getElementById('formatedAddress').innerHTML = '';	
    currentReverseGeocodeResponse = null;
//	document.form1.submit();
}

  

  function reverseGeocode() {
    reverseGeocodedLast = new Date();
    geocoder.geocode({latLng:map.getCenter()},reverseGeocodeResult);
  }

  function reverseGeocodeResult(results, status) {
    currentReverseGeocodeResponse = results;
    if(status == 'OK') {
      if(results.length == 0) {
        document.getElementById('formatedAddress').value = 'None';
      } else {
        document.getElementById('formatedAddress').value = results[0].formatted_address;
      }
    } else {
      document.getElementById('formatedAddress').value = 'Error';
    }
  }


  function geocode() {
   var address = document.getElementById("address").value;
    geocoder.geocode({
      'address': address,
      'partialmatch': true}, geocodeResult);
  }

  function geocodeResult(results, status) {
    if (status == 'OK' && results.length > 0) {
      map.fitBounds(results[0].geometry.viewport);
    } else {
      alert("Geocode was not successful for the following reason: " + status);
    }
  }

  function addMarkerAtCenter() {
    var marker = new google.maps.Marker({
        position: map.getCenter(),
        map: map
    });
  }	
		
}


  function addMarker(location) {
    marker = new google.maps.Marker({
      position: location,
      map: map
    });
    markersArray.push(marker);
  }

 // Removes the overlays from the map, but keeps them in the array
  function clearOverlays() {
    if (markersArray) {
      for (i in markersArray) {
        markersArray[i].setMap(null);
      }
    }
  }

  // Shows any overlays currently in the array
  function showOverlays() {
    if (markersArray) {
      for (i in markersArray) {
        markersArray[i].setMap(map);
      }
    }
  }

  // Deletes all markers in the array by removing references to them
  function deleteOverlays() {
    if (markersArray) {
      for (i in markersArray) {
        markersArray[i].setMap(null);
      }
      markersArray.length = 0;
    }
  }

google.maps.event.addDomListener(window, 'load', initialize);

</script>

<!-- Analytics ROC -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28147950-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<style type="text/css">
	body {
		font-family: Helvetica;
		font-size: 11px;
		color: #000;
	}
	
    #map_canvas {
        height: 250px;
        width: 600px;
        margin-top: 0.6em;
      }	
	  
	
	h3 {
		margin: 0px;
		padding: 0px;	
	}

	#Layermap {
	visibility:hidden;
	position:absolute;
	left:199px;
	top:433px;
	width:600px;
	height:293px;
	z-index:1000;
	font: Arial;
	font-size: 10px;
    }	
	
  div#map {
    position: relative;
  }

  div#crosshair {
    position: absolute;
    top: 192px;
    height: 19px;
    width: 19px;
    left: 50%;
    margin-left: -8px;
    display: block;
    background: url(../crosshair.gif);
    background-position: center center;
    background-repeat: no-repeat;
}	

#Layer9 {
	position:absolute;
	left:50%px;
	margin-left: 220px;
	top:14px;
	width:229px;
	height:24px;
	z-index:7;
	font: Arial;
	font-size: 10px;
}

#usuario {
	position:absolute;
	left:50%px;
    margin-left: 420px;
	top:12px;
	width:34px;
	height:24px;
	z-index:7;
	font: Arial;
	font-size: 10px;
}

.enlace,.enlace:hover,.enlace:visited{
	font-weight:bold;
	color:#292b66;
	text-decoration:none;
	}

.enlacewhite,.enlacewhite:hover,.enlacewhite:visited{
	font-weight:bold;
	color:#e3ebf8;
	text-decoration:none;
	}
body{
	line-height:1.3;}
	
.h1-404{
	color:#292b66;
	margin-bottom:0px;
	
	}	
.h2-404{
	color:#292b66;
	}		
.h2-404-2{
	color:#e3ebf8;
	}		

</style>
</head>
<body style="font-family:sans-serif, Helvetica; font-size:16px;">

<div style="background:#FFF) top center no-repeat; position:absolute; width:100%; height:100%; border:0px" align="center">
<form id="form1" name="form1" method="post" action="e-spacios2.php">


<table width="950" height="750px" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td width="47%" align="center" valign="middle">
  <div style="height: 470px; vertical-align:top; width:100%; display:block">
  <img src="images/e-spacios.jpg" alt="e-spacios" />
  <br />
  <div style="display:block; width:80%; text-align:left; margin-left:17%">
    <h2 style="margin:10px"> Le sugerimos alguna de las siguientes ligas:</h2>
  	<ul>
    	<li style="list-style:none"><a class="enlace" href="/" title="Inicio">- Inicio</a> <a class="enlacewhite" href="en/index.php" title="Inicio">- Home</a> </li>
        <li style="list-style:none"><a class="enlace" href="/" title="Inicio">- e-spacios</a> <a class="enlacewhite" href="en/e-spacios2.php" title="Inicio">- e-spacios</a></li>
     </ul>
  </div>      
    </div>
  
  </td>
  <td width="6%" align="center" valign="middle"><img src="images/separa.jpg" alt="separador" /></td>
  <td width="47%" align="center" valign="middle">
  <div>
  <div>
  	<h1 class="h1-404"> ERROR 404</h1>
    <h2 class="h2-404">liga rota o archivo no encontrado</h2>
    <h2 class="h2-404-2">broken link or file doesn't exist</h2>  
  </div>
  <img src="images/cubo.jpg" alt="cubo" />
  </div>
  </td>
</tr>
</table>
  </form>  
  
</div>

<div id="Layermap">
  <div id="map_canvas"></div>
    <div id="crosshair"></div>
</div> 
</body>
</html>