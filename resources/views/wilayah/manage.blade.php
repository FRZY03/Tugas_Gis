@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h4>Manajemen Wilayah</h4>

  <button class="btn btn-primary mb-3" id="btnTambah">Tambah Wilayah Baru</button>
  <button class="btn btn-danger mb-3" id="btnHapusSemua">Hapus Semua Data</button>

  <table class="table table-bordered" id="wilayahTable">
    <thead>
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

<!-- Modal Input -->
<div class="modal fade" id="inputModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('wilayah.store') }}" method="POST" id="inputForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Wilayah Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Wilayah</label>
            <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label>Latitude</label>
            <input type="text" name="latitude" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Longitude</label>
            <input type="text" name="longitude" class="form-control" required>
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

<!-- Modal Detail Deskripsi -->
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

<!-- Script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Tambah Wilayah
  $('#btnTambah').click(function () {
    $('#inputForm')[0].reset();
    $('#inputModal').modal('show');
  });

  // Edit Wilayah
  $(document).on('click', '.btn-edit', function () {
    const id = $(this).data('id');
    $('#editNama').val($(this).data('nama'));
    $('#editDeskripsi').val($(this).data('deskripsi'));
    $('#editLatitude').val($(this).data('lat'));
    $('#editLongitude').val($(this).data('lng'));

    $('#editForm').attr('action', '/wilayah/' + id);
    $('#editModal').modal('show');
  });

  // Detail Deskripsi
  $(document).on('click', '.btn-detail', function () {
    $('#deskripsiContent').text($(this).data('deskripsi'));
    $('#deskripsiModal').modal('show');
  });

  // Konfirmasi hapus per item
  $(document).on('submit', '.deleteForm', function () {
    return confirm('Yakin ingin menghapus wilayah ini?');
  });

  // Hapus Semua
  $('#btnHapusSemua').click(function () {
    if (confirm('Yakin ingin menghapus SEMUA data wilayah?')) {
      $.ajax({
        url: "{{ route('wilayah.hapusSemua') }}",
        method: 'DELETE',
        data: {
          _token: "{{ csrf_token() }}"
        },
        success: function(res) {
          alert(res.message);
          location.reload();
        },
        error: function(xhr) {
          alert('Gagal menghapus semua data!');
          console.error(xhr);
        }
      });
    }
  });
</script>
@endsection
