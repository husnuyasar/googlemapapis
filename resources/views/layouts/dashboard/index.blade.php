@extends('masters.index.fixed')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<style>
	#map-canvas{
		width: 1800px;
		height: 700px;
	}
</style>
<div class="portlet light bordered">                              
    <div class="form-group">
        <label class="control-label bold">Map</label>
        <input type="text" id="searchmap" name="searchmap" class="control-control">
        <div id="map-canvas"></div>
    </div>	
</div>
@endsection

@section('extrarunnablejavascript')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyB6K1CFUQ1RwVJ-nyXxd6W0rfiIBe12Q&libraries=places" type="text/javascript"></script>
<script type="text/javascript">
var markers = [];
var map = new google.maps.Map(document.getElementById('map-canvas'),{
    center:{
        lat: 41.015137,
        lng: 28.979530
    },
    zoom:12.5
});
var symbolList = google.maps.SymbolPath;
var marker = new google.maps.Marker({
    position: {
        lat: 41.015137,
        lng: 28.979530
    },
    map: map,
    draggable: true,
    animation: google.maps.Animation.BOUNCE,
    icon: {
            path: symbolList.BACKWARD_CLOSED_ARROW,
            strokeColor: '#f74545',
            scale: 10
          },
    title: 'Im Here!'
});
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$.ajax({
    url: '{{route('dashboard::list')}}',
    type: 'POST',
    data: {_token: CSRF_TOKEN},
    dataType: 'JSON',
    success: function (data) { 
        $.each(data, function(k, v) {
            //alert(v.name);
            var lat = parseFloat(v.lat);
            var lng = parseFloat(v.lng);
            var marker = new google.maps.Marker({
                position: {
                    lat: lat,
                    lng: lng
                },
                map: map,
                draggable: true,
                title: v.name
            });
            markers.push(marker);
        });
        
    }
});
function setMapOnAll(map) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}
function clearMarkers() {
    setMapOnAll(null);
}
var searchBox = new google.maps.places.SearchBox(document.getElementById('searchmap'));
google.maps.event.addListener(searchBox,'places_changed',function(){
    clearMarkers();
    markers = [];
    var places = searchBox.getPlaces();
    var bounds = new google.maps.LatLngBounds();
    var i, place;
    for(i=0; place=places[i];i++){
        bounds.extend(place.geometry.location);
        marker.setPosition(place.geometry.location); 
        marker.setAnimation(google.maps.Animation.BOUNCE);
        marker.setIcon(symbolList.BACKWARD_CLOSED_ARROW,10,'#f74545');
    }
    var lat = marker.getPosition().lat();
    var lng =marker.getPosition().lng();
    map.fitBounds(bounds);
    map.setZoom(12.5);
    var circle = new google.maps.Circle({
        map: map,
        radius: 5000,    
    });
    circle.bindTo('center', marker, 'position');
    $.ajax({
        url: '{{route('dashboard::list')}}',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {lat:lat,lng:lng},
        dataType: 'JSON',
        success: function (data) { 
            $.each(data, function(k, v) {
                //alert(v.name);
                var lat = parseFloat(v.lat);
                var lng = parseFloat(v.lng);
                var marker = new google.maps.Marker({
                    position: {
                        lat: lat,
                        lng: lng
                    },
                    map: map,
                    draggable: true,
                    title: v.name
                });
                markers.push(marker);
            });
        }
    });
});


</script>
@endsection