@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h4 class="mb-3">Peta Wilayah</h4>
  <div id="map" style="height: 80vh; width: 100%;"></div>
</div>

<!-- Modal Input Wilayah -->
<div class="modal fade" id="inputModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="inputForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Wilayah Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="inputLatitude" name="latitude" />
          <input type="hidden" id="inputLongitude" name="longitude" />

          <div class="mb-3">
            <label for="inputNama" class="form-label">Nama Wilayah</label>
            <input type="text" id="inputNama" name="nama" class="form-control" required />
          </div>
          <div class="mb-3">
            <label for="inputDeskripsi" class="form-label">Deskripsi</label>
            <textarea id="inputDeskripsi" name="deskripsi" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Load CSS Leaflet -->
<link
  rel="stylesheet"
  href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
  crossorigin=""
/>

<!-- Bootstrap CSS & JS -->
<link
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
  rel="stylesheet"
/>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<script>
  // Setup CSRF token untuk AJAX Laravel
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  });

  // Inisialisasi peta
  var map = L.map("map").setView([-0.8871, 119.8604], 13);

  // Tambahkan tile layer OpenStreetMap
  L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "&copy; OpenStreetMap contributors",
  }).addTo(map);

  // Data wilayah dari controller
  const wilayah = @json($wilayah);

  // Pasang marker wilayah yang sudah ada
  wilayah.forEach(w => {
    L.marker([w.latitude, w.longitude])
      .addTo(map)
      .bindPopup(`<b>${w.nama}</b><br>${w.deskripsi ?? ''}`);
  });

  // Variable untuk modal Bootstrap supaya tidak buat instance berulang
  var inputModal = new bootstrap.Modal(document.getElementById('inputModal'));

  // Saat klik peta, isi koordinat dan tampilkan modal input
  map.on('click', function(e) {
    $('#inputLatitude').val(e.latlng.lat);
    $('#inputLongitude').val(e.latlng.lng);
    $('#inputNama').val('');
    $('#inputDeskripsi').val('');
    inputModal.show();
  });

  // Submit form tambah wilayah via AJAX
  $('#inputForm').submit(function(e) {
    e.preventDefault();

    // Data form yang akan dikirim
    const formData = {
      nama: $('#inputNama').val(),
      deskripsi: $('#inputDeskripsi').val(),
      latitude: $('#inputLatitude').val(),
      longitude: $('#inputLongitude').val(),
    };

    $.ajax({
      url: "{{ route('wilayah.store') }}",
      method: 'POST',
      data: formData,
      success: function(res) {
        // Tambah marker baru di peta sesuai response
        L.marker([res.latitude, res.longitude])
          .addTo(map)
          .bindPopup(`<b>${res.nama}</b><br>${res.deskripsi ?? ''}`);

        // Tutup modal
        inputModal.hide();

        alert('Wilayah berhasil ditambahkan!');
      },
      error: function(xhr) {
        let msg = 'Gagal menyimpan wilayah!';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          msg += '\n' + xhr.responseJSON.message;
        }
        alert(msg);
        console.error(xhr);
      }
    });
  });
</script>
@endsection
