//http://jafrancov.com/2011/12/trazar-rutas-gmaps-api-v3/  https://developers.google.com/maps/documentation/javascript/places-autocomplete?hl=es#get_place_information

//Variables Globales
var directionsDisplay;
var directionsService;
var map = null;


//----------FUNCIONES-----------------------------------------------------------

//Funcion para inicializar el mapa, punto donde se carga, zoom, tipo de mapa
// y servicios que carga. 

function inicio() {
     directionsDisplay = new google.maps.DirectionsRenderer();
    directionsService = new google.maps.DirectionsService();

    var myLatlng = new google.maps.LatLng(37.178056, -3.600833);
    var myOptions = {
        zoom: 13,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var divMapa = document.getElementById('map_canvas')
    map = new google.maps.Map(divMapa, myOptions);
    directionsDisplay = new google.maps.DirectionsRenderer();
    directionsService = new google.maps.DirectionsService();
    
    
}


//Obtiene las direcciones y calcula la ruta entre el punto de origen y destino.


function getDirections() {
    var origen = document.getElementById('origen').value;
   // var destino = document.getElementById('destino').value;
   var destino="Calle Santa Margarita, Granada, España";
    var modoViaje = document.getElementById('modo_viaje').value;
    var tipoS ='metrico';
      var panel = document.getElementById('panel_ruta');

    if (!origen || !destino) {
        alert("El origen y el destino son obligatorios");
        return;
    }


    var request = {
        origin: origen,
        destination: destino,

        travelMode: google.maps.DirectionsTravelMode[modoViaje],
        unitSystem: google.maps.DirectionsUnitSystem[tipoS],
        provideRouteAlternatives: true
    };
  

    directionsService.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setMap(map);
            directionsDisplay.setPanel(panel);
            directionsDisplay.setDirections(response);
            
        } else {
            alert("No existen rutas entre ambos puntos");
        }
    });

}


//---------PRUEBAS


map = new google.maps.Map(map_canvas, {
    mapTypeId: 'IGN',
    scaleControl: true,
    streetViewControl: true,
    panControl: true,
    mapTypeControl: true,
    overviewMapControl: true,
    overviewMapControlOptions: {
        opened: true,
        position: google.maps.ControlPosition.BOTTOM_CENTER
    },


    mapTypeControlOptions: {
        mapTypeIds: [
      'IGN', 'IGNScanExpress',
      google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.TERRAIN, google.maps.MapTypeId.HYBRID, google.maps.MapTypeId.ROADMAP
    ],
        style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
    },
    center: new google.maps.LatLng(47, 3),
    zoom: 6,
    draggableCursor: "crosshair"
});

// Create the search box and link it to the UI element.


//No funciona esta parte en JQUERY
var input = document.getElementById("origen");
var searchBox = new google.maps.places.SearchBox(input);
map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);


var input2 = document.getElementById("destino");
var searchBox2 = new google.maps.places.SearchBox(input2);
map.controls[google.maps.ControlPosition.TOP_LEFT].push(input2);


// Bias the SearchBox results towards current map's viewport.
map.addListener('bounds_changed', function () {
    searchBox.setBounds(map.getBounds());
});

var markers = [];
// Listen for the event fired when the user selects a prediction and retrieve
// more details for that place.
searchBox.addListener('places_changed', predicciones, false);
searchBox2.addListener('places_changed', predicciones, false);


function predicciones() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
        return;
    }

    // Clear out the old markers.
    markers.forEach(function (marker) {
        marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function (place) {
        var icon = {
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(25, 25)
        };

        /* Create a marker for each place.
        markers.push(new google.maps.Marker({
          map: map,
          title: place.name,
          animation: google.maps.Animation.BOUNCE,
          position: place.geometry.location
        }));*/

        if (place.geometry.viewport) {
            // Only geocodes have viewport.
            bounds.union(place.geometry.viewport);
        } else {
            bounds.extend(place.geometry.location);
        }
    });
    map.fitBounds(bounds);
}



//---------------MANEJADORES DE EVENTOS-----------------------------------------
var buscar = document.getElementById('buscar');
//Evento controla boton buscar. JQUERY: .on() ==addEventListener();
buscar.addEventListener('click', function () {
    getDirections();
});




//Evento controla selects de transporte y medida. 
//('.opciones_ruta').on('change', function() {
//  getDirections();
//});




//--------------INICIO----------------------------------------------------------
//Funcion inicio
window.onload = inicio();
