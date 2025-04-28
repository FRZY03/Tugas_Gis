<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GIS Tugas - Map with Satellite View</title>

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />

  <!-- Geocoder CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@2.0.1/dist/Control.Geocoder.css" />

  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      width: 100%;
    }

    #map {
      height: 100vh;
      width: 100%;
    }

    /* Tombol Custom */
    .custom-control {
      position: absolute;
      top: 10px;
      left: 10px;
      z-index: 1000;
      background: white;
      padding: 10px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    .custom-control button {
      display: block;
      width: 120px;
      margin: 5px 0;
      padding: 8px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }

    .custom-control button:hover {
      background-color: #45a049;
    }
  </style>
</head>

<body>

  <!-- Tombol untuk ganti tampilan -->
  <div class="custom-control">
    <button onclick="setNormal()">Normal</button>
    <button onclick="setSatelit()">Satelit</button>
    <button onclick="toggleView()">Ubah Tampilan</button> <!-- Tombol baru -->
  </div>

  <div id="map"></div>

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

  <!-- Leaflet Geocoder -->
  <script src="https://unpkg.com/leaflet-control-geocoder@2.0.1/dist/Control.Geocoder.js"></script>

  <script>
    // Setup CSRF token untuk semua AJAX
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    // Layers
    var normalLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    });

    var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
      attribution: '&copy; Esri &mdash; Source: Esri, Earthstar Geographics'
    });

    var currentLayer = 'normal'; // Tracking state

    // Inisialisasi Map
    var map = L.map('map', {
      center: [-0.8871595143462079, 119.86048290907013],
      zoom: 13,
      layers: [normalLayer]
    });

    // Fungsi untuk ganti ke Normal
    function setNormal() {
      map.eachLayer(function (layer) {
        map.removeLayer(layer);
      });
      normalLayer.addTo(map);
      currentLayer = 'normal';
    }

    // Fungsi untuk ganti ke Satelit
    function setSatelit() {
      map.eachLayer(function (layer) {
        map.removeLayer(layer);
      });
      satelliteLayer.addTo(map);
      currentLayer = 'satelit';
    }

    // Fungsi untuk Ubah Tampilan (Normal ⇄ Satelit)
    function toggleView() {
      if (currentLayer === 'normal') {
        setSatelit();
      } else {
        setNormal();
      }
    }

    // Geocoder Pencarian
    var searchMarker;
    var geocoder = L.Control.geocoder({
      defaultMarkGeocode: false,
      geocoder: L.Control.Geocoder.nominatim()
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

    // Event Klik Map
    map.on('click', function (e) {
      const lat = e.latlng.lat;
      const lng = e.latlng.lng;

      alert(`You clicked the map at latitude: ${lat} and longitude: ${lng}`);

      $.ajax({
        url: '/your-endpoint', // Ganti URL endpoint Laravel kamu
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

    // Geolokasi Browser
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
