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


      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng("23.60426184707018", "-100.986328125"),
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
 
