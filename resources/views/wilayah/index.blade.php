@extends('layouts.base')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tugas GIS</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    #map {
      height: 80vh;
      width: 100%;
    }

    /* Gaya kontrol lokasi */
    .custom-location-btn {
      position: absolute;
      top: 60px;
      left: 10px;
      z-index: 1000;
      background-color: #fff;
      padding: 6px 10px;
      border-radius: 4px;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
      cursor: pointer;
      font-size: 14px;
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

    // Base layers
    var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    });

    var esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
      attribution: '&copy; Esri, Maxar, Earthstar Geographics'
    });

    var esriLabels = L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}', {
      attribution: '&copy; Esri'
    });

    var satelliteWithLabels = L.layerGroup([esriSat, esriLabels]);

    var map = L.map('map', {
      center: [-0.8871, 119.8604],
      zoom: 13,
      layers: [osm]
    });

    var baseMaps = {
      "OpenStreetMap": osm,
      "Satellite + Street": satelliteWithLabels
    };

    L.control.layers(baseMaps, null, { position: 'topright' }).addTo(map);

    var searchMarker; // Marker global untuk menghapus marker sebelumnya

    // Geocoder (Search box)
    var geocoder = L.Control.geocoder({
      defaultMarkGeocode: false,
      geocoder: L.Control.Geocoder.nominatim(),
      position: 'topleft'
    }).on('markgeocode', function (e) {
      var center = e.geocode.center;
      var name = e.geocode.name;

      if (searchMarker) {
        map.removeLayer(searchMarker); // Hapus marker sebelumnya
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

      // Hapus marker yang ada sebelumnya jika ada
      if (searchMarker) {
        map.removeLayer(searchMarker);
      }

      // Kirim data ke server
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

      // Tambahkan marker baru setelah pengiriman data
      searchMarker = L.marker([lat, lng]).addTo(map)
        .bindPopup(`Latitude: ${lat}, Longitude: ${lng}`)
        .openPopup();
    });

    // Fungsi cari lokasi terkini
    function goToUserLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
          var userLat = position.coords.latitude;
          var userLng = position.coords.longitude;

          // Menggeser peta dan menambahkan marker pada lokasi terkini
          map.setView([userLat, userLng], 13); // Geser peta

          if (searchMarker) {
            map.removeLayer(searchMarker); // Hapus marker sebelumnya jika ada
          }

          // Tambahkan marker pada lokasi terkini
          searchMarker = L.marker([userLat, userLng]).addTo(map)
            .bindPopup("Lokasi Anda Sekarang")
            .openPopup();

        }, function () {
          alert('Gagal mendeteksi lokasi, atau izin ditolak.');
        });
      } else {
        alert('Browser tidak mendukung geolocation.');
      }
    }

    // Tambahkan custom button control untuk cari lokasi
    var locateControl = L.control({ position: 'topright' });

    locateControl.onAdd = function (map) {
      var div = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
      div.innerHTML = '<a href="#" title="Cari Lokasi Terkini">📍</a>';
      div.style.backgroundColor = 'white';
      div.style.width = '34px';
      div.style.height = '34px';
      div.style.display = 'flex';
      div.style.alignItems = 'center';
      div.style.justifyContent = 'center';
      div.style.fontSize = '20px';

      div.onclick = function (e) {
        e.preventDefault();
        goToUserLocation();
      };

      return div;
    };

    locateControl.addTo(map);

  </script>

</body>

</html>
@endsection
