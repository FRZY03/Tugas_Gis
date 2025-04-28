<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tugas GIS</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    #map {
      height: 100vh;
      width: 100%;
    }
  </style>

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@2.0.1/dist/Control.Geocoder.css" />
</head>

<body>

  <div id="map"></div>

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
  <script src="https://unpkg.com/leaflet-control-geocoder@2.0.1/dist/Control.Geocoder.js"></script>

  <script>
    // Setup CSRF token
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    // Buat base layers (tampilan peta)
    var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    });

    // Peta Satelit terbaru dari ESRI
    var esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
      attribution: '&copy; Esri, Maxar, Earthstar Geographics'
    });

    // Label Jalan dari ESRI
    var esriLabels = L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}', {
      attribution: '&copy; Esri'
    });

    // Gabungan Satelit + Label
    var satelliteWithLabels = L.layerGroup([esriSat, esriLabels]);

    var map = L.map('map', {
      center: [-0.8871595143462079, 119.86048290907013],
      zoom: 13,
      layers: [osm] // default tampilan pertama
    });

    var baseMaps = {
      "OpenStreetMap": osm,
      "Satellite + Street": satelliteWithLabels
    };

    // Tambahkan control layer di pojok kanan atas
    L.control.layers(baseMaps, null, { position: 'topright' }).addTo(map);

    var searchMarker;

    // Tambahkan geocoder di kiri atas
    var geocoder = L.Control.geocoder({
      defaultMarkGeocode: false,
      geocoder: L.Control.Geocoder.nominatim(),
      position: 'topleft'
    }).on('markgeocode', function (e) {
      var center = e.geocode.center;
      var name = e.geocode.name;

      if (searchMarker) {
        map.removeLayer(searchMarker);
      }

      searchMarker = L.marker(center).addTo(map)
        .bindPopup(name)
        .openPopup();

      map.setView(center, 15);
    }).addTo(map);

    // Klik map
    map.on('click', function (e) {
      const lat = e.latlng.lat;
      const lng = e.latlng.lng;

      alert(`You clicked the map at latitude: ${lat} and longitude: ${lng}`);

      $.ajax({
        url: '/your-endpoint',
        method: 'POST',
        data: {
          id_wilayah: '1',
          longitude: lng,
          latitude: lat
        },
        success: function (result) {
          console.log('Data successfully sent: ', result);
        },
        error: function (e) {
          alert('Error: ' + JSON.stringify(e));
        }
      });
    });

    // Geolokasi
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        var userLat = position.coords.latitude;
        var userLng = position.coords.longitude;

        map.setView([userLat, userLng], 13);

        L.marker([userLat, userLng]).addTo(map)
          .bindPopup('You are here!')
          .openPopup();
      }, function () {
        alert('Geolocation failed or is not supported by this browser.');
      });
    } else {
      alert('Geolocation is not supported by this browser.');
    }
  </script>

</body>

</html>
