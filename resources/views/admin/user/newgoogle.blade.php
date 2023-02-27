<input id="pac-input" class="controls form-control" name="address" value="{{$data->address}}" type="text" placeholder="Enter a location">
                  
<br>

<div class="row">
<div class="form-group col-md-6"><input type="hidden" name="lat" id="lat" class="form-control" required placeholder="Latitude" value="{{ $data->lat }}"></div>
<div class="form-group col-md-6"><input type="hidden" name="lng" id="lng" class="form-control" required placeholder="Longitude" value="{{ $data->lng }}"></div>
</div>

<div id="map" style="width: 100%;height: 400px;"></div>

<div id="infowindow-content">
<span id="place-name"  class="title"></span>
<span id="place-id"></span><br>
<span id="place-address"></span>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<script>
/** Funciones del mapa */
  function initMap() {
    var markers = [];
    var address = document.getElementById('pac-input').value;
    var latitud = parseFloat(document.getElementById('lat').value);
    var longitud = parseFloat(document.getElementById('lng').value);

    if (address.length == 0 ) {
      var map = new google.maps.Map(
            document.getElementById('map'),
            {center: {lat: 19.4326296, lng: -99.1331785}, zoom: 13});
    } else {

      var map = new google.maps.Map(
            document.getElementById('map'),
            {center: {lat: latitud, lng: longitud}, zoom: 13});
    }
    var input = document.getElementById('pac-input');

    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.bindTo('bounds', map);

    // Specify just the place data fields that you need.
    autocomplete.setFields(['place_id', 'geometry', 'name', 'formatted_address']);

    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var infowindow = new google.maps.InfoWindow();
    var infowindowContent = document.getElementById('infowindow-content');
    infowindow.setContent(infowindowContent);

    var geocoder = new google.maps.Geocoder;

    if (address.length == 0) {
        var marker = new google.maps.Marker({map: map,
          draggable:true,
          position: {lat: 19.4326296, lng: -99.1331785}});

        markers.push(marker);

        google.maps.event.addListener(marker, 'dragend', function(evt)
        {
          $("#lat").val(evt.latLng.lat().toFixed(6));
          $("#lng").val(evt.latLng.lng().toFixed(6));

          map.panTo(evt.latLng);

          const latLng = {
              lat: parseFloat(evt.latLng.lat()),
              lng: parseFloat(evt.latLng.lng()),
              };

              geocoder.geocode({location: latLng }, (results, status)=> {
                if(status == "OK"){
                  if(results[0]){
                    document.getElementById('pac-input').value = results[0].formatted_address;
                  } else{
                    window.alert("No results found");
                  }
                } else{
                  window.alert("Geocoder failed due to: " + status);
                }
              });
        });

    }else {
      var marker = new google.maps.Marker({map: map,
        draggable:true,
        position: {lat: latitud, lng: longitud}});

        markers.push(marker);

        google.maps.event.addListener(marker, 'dragend', function(evt)
        {
          $("#lat").val(evt.latLng.lat().toFixed(6));
          $("#lng").val(evt.latLng.lng().toFixed(6));

          map.panTo(evt.latLng);

          const latLng = {
              lat: parseFloat(evt.latLng.lat()),
              lng: parseFloat(evt.latLng.lng()),
              };

              geocoder.geocode({location: latLng }, (results, status)=> {
                if(status == "OK"){
                  if(results[0]){
                    document.getElementById('pac-input').value = results[0].formatted_address;
                  } else{
                    window.alert("No results found");
                  }
                } else{
                  window.alert("Geocoder failed due to: " + status);
                }
              });
        });
    }

    marker.addListener('click', function() {
      infowindow.open(map, marker);
    });

    autocomplete.addListener('place_changed', function() {
      infowindow.close();
      var place = autocomplete.getPlace();

      if (!place.place_id) {
        return;
      }

      geocoder.geocode({'placeId': place.place_id}, function(results, status) {

        if (status !== 'OK') {
          window.alert('Geocoder failed due to: ' + status);
          return;
        }


        map.setZoom(18);
        map.setCenter(results[0].geometry.location);

        document.getElementById('lat').value = results[0].geometry.location.lat();
        document.getElementById('lng').value = results[0].geometry.location.lng();

        var lat = results[0].geometry.location.lat();
        var lng = results[0].geometry.location.lng();
        marker.setMap()
        deleteMArkers();


        var vMarker = new google.maps.Marker({
            position: {lat, lng},
            draggable: true
        });

        google.maps.event.addListener(vMarker, 'dragend', function (evt){
            $("#lat").val(evt.latLng.lat().toFixed(6));
            $("#lng").val(evt.latLng.lng().toFixed(6));

            const latLng = {
              lat: parseFloat(evt.latLng.lat()),
              lng: parseFloat(evt.latLng.lng()),
            };

            geocoder.geocode({location: latLng }, (results, status)=> {
              if(status == "OK"){
                if(results[0]){
                  document.getElementById('pac-input').value = results[0].formatted_address;
                } else{
                  window.alert("No results found");
                }
              } else{
                window.alert("Geocoder failed due to: " + status);
              }
            });
        });

        vMarker.setMap(map);
        markers.push(vMarker);
      });
    });

    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }

    function deleteMArkers(){
        setMapOnAll(null);
        markers = [];
    }
  }
/** Funciones del mapa */
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{$ApiKey}}&libraries=places&callback=initMap"></script>

<style>
    .gm-style-mtc{
        display: none;
    }
    .gmnoprint{
        display: none;
    }
   .gm-fullscreen-control{
        display: none;
   }
</style>