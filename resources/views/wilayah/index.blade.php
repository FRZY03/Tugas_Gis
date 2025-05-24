@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h4>Manajemen Wilayah</h4>

  <div id="map" style="height: 400px; width: 100%;" class="mb-4"></div>

  <div class="d-flex justify-content-start gap-2 mb-3">
    <button class="btn btn-primary" id="btnTambah">Tambah Wilayah Baru</button>
    <button class="btn btn-danger" id="btnHapusSemua">Hapus Semua Data</button>
  </div>

  <table class="table table-bordered" id="wilayahTable">
    <thead class="table-light">
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($wilayah as $w)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $w->nama }}</td>
        <td>{{ $w->latitude }}</td>
        <td>{{ $w->longitude }}</td>
        <td>
          @if($w->deskripsi)
          <button class="btn btn-info btn-sm btn-detail" data-deskripsi="{{ htmlspecialchars($w->deskripsi, ENT_QUOTES) }}">Detail</button>
          @endif
          <button class="btn btn-warning btn-sm btn-edit"
            data-id="{{ $w->id }}"
            data-nama="{{ $w->nama }}"
            data-deskripsi="{{ htmlspecialchars($w->deskripsi, ENT_QUOTES) }}"
            data-lat="{{ $w->latitude }}"
            data-lng="{{ $w->longitude }}"
          >Edit</button>
          <form action="{{ route('wilayah.destroy', $w->id) }}" method="POST" class="d-inline deleteForm">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="inputModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="inputForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Wilayah Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Wilayah</label>
            <input type="text" name="nama" id="inputNama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" id="inputDeskripsi" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label>Latitude</label>
            <input type="text" name="latitude" id="inputLatitude" class="form-control" required readonly>
          </div>
          <div class="mb-3">
            <label>Longitude</label>
            <input type="text" name="longitude" id="inputLongitude" class="form-control" required readonly>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" id="editForm">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Wilayah</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Wilayah</label>
            <input type="text" name="nama" id="editNama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" id="editDeskripsi" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label>Latitude</label>
            <input type="text" name="latitude" id="editLatitude" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Longitude</label>
            <input type="text" name="longitude" id="editLongitude" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Deskripsi -->
<div class="modal fade" id="deskripsiModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Deskripsi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="deskripsiContent"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<style>
  .label-tooltip {
    background-color: rgba(255, 255, 255, 0.8);
    color: #333;
    border-radius: 4px;
    padding: 2px 6px;
    font-size: 12px;
    border: 1px solid #ccc;
  }
</style>

<script>
  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
  });

  var map = L.map("map").setView([-0.8871, 119.8604], 13);
  L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "&copy; OpenStreetMap contributors",
  }).addTo(map);

  const wilayah = @json($wilayah);
  wilayah.forEach(w => {
    L.marker([w.latitude, w.longitude])
      .addTo(map)
      .bindPopup(`<b>${w.nama}</b><br>${w.deskripsi ?? ''}`);
  });

  fetch('{{ asset('map/export.geojson') }}')
    .then(response => response.json())
    .then(geoJson => {
      const colors = ['#FF5733', '#33FF57', '#3357FF', '#FF33A8', '#FF8F33', '#A833FF'];
      let colorIndex = 0;
      L.geoJSON(geoJson, {
        style: function(feature) {
          return {
            color: colors[colorIndex++ % colors.length],
            weight: 2,
            fillOpacity: 0.3
          };
        },
        onEachFeature: function(feature, layer) {
          if (feature.properties?.name) {
            layer.bindPopup(`<strong>${feature.properties.name}</strong>`);
            layer.bindTooltip(feature.properties.name, {
              permanent: true,
              direction: "center",
              className: "label-tooltip"
            }).openTooltip();
          }
        }
      }).addTo(map);
    });

  map.on('click', function(e) {
    $('#inputLatitude').val(e.latlng.lat);
    $('#inputLongitude').val(e.latlng.lng);
    $('#inputNama').val('');
    $('#inputDeskripsi').val('');
    $('#inputModal').modal('show');
  });

  $('#btnTambah').click(() => {
    $('#inputForm')[0].reset();
    $('#inputLatitude').val('');
    $('#inputLongitude').val('');
    $('#inputModal').modal('show');
  });

  $('#inputForm').submit(function(e) {
    e.preventDefault();
    const formData = $(this).serialize();
    $.post("{{ route('wilayah.store') }}", formData, function(data) {
      $('#inputModal').modal('hide');
      $('#inputForm')[0].reset();

      L.marker([data.latitude, data.longitude])
        .addTo(map)
        .bindPopup(`<b>${data.nama}</b><br>${data.deskripsi ?? ''}`);

      const newRow = `
        <tr>
          <td>${$('#wilayahTable tbody tr').length + 1}</td>
          <td>${data.nama}</td>
          <td>${data.latitude}</td>
          <td>${data.longitude}</td>
          <td>
            ${data.deskripsi ? `<button class="btn btn-info btn-sm btn-detail" data-deskripsi="${data.deskripsi}">Detail</button>` : ''}
            <button class="btn btn-warning btn-sm btn-edit"
              data-id="${data.id}"
              data-nama="${data.nama}"
              data-deskripsi="${data.deskripsi ?? ''}"
              data-lat="${data.latitude}"
              data-lng="${data.longitude}">Edit</button>
            <form action="/wilayah/${data.id}" method="POST" class="d-inline deleteForm">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
            </form>
          </td>
        </tr>
      `;
      $('#wilayahTable tbody').append(newRow);
    }).fail(function(xhr) {
      alert('Gagal menyimpan data');
    });
  });

  $(document).on('click', '.btn-edit', function () {
    const id = $(this).data('id');
    $('#editNama').val($(this).data('nama'));
    $('#editDeskripsi').val($(this).data('deskripsi'));
    $('#editLatitude').val($(this).data('lat'));
    $('#editLongitude').val($(this).data('lng'));
    $('#editForm').attr('action', '/wilayah/' + id);
    $('#editModal').modal('show');
  });

  $(document).on('click', '.btn-detail', function () {
    $('#deskripsiContent').text($(this).data('deskripsi'));
    $('#deskripsiModal').modal('show');
  });

  $(document).on('submit', '.deleteForm', function () {
    return confirm('Yakin ingin menghapus wilayah ini?');
  });

  $('#btnHapusSemua').click(function () {
    if (confirm('Yakin ingin menghapus SEMUA data wilayah?')) {
      $.ajax({
        url: "{{ route('wilayah.hapusSemua') }}",
        method: 'DELETE',
        data: { _token: "{{ csrf_token() }}" },
        success: function(res) {
          alert(res.message);
          location.reload();
        },
        error: function(xhr) {
          alert('Gagal menghapus semua data!');
        }
      });
    }
  });
</script>
@endsection
