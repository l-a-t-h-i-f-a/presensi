<!DOCTYPE html>
<html lang="en">
@include('partial.header')

<body>
  <div class="container-scroller">
    @include('partial.navbar')
    <div class="container-fluid page-body-wrapper">
      @include('partial.sidebar')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Data Shift</h4>
                  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalShift">Tambah Shift</button><br><br>

                  <div class="table-responsive">
                    <table class="table table-bordered" id="tabel_shift">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Shift</th>
                          <th>Jam Masuk</th>
                          <th>Jam Pulang</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($shifts as $shift)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $shift->nama_shift }}</td>
                          <td>{{ $shift->jam_masuk }}</td>
                          <td>{{ $shift->jam_pulang }}</td>
                          <td>
                            <button class="btn btn-warning btn-edit" data-id="{{ $shift->id }}">Edit</button>
                            <button class="btn btn-danger btn-delete" data-id="{{ $shift->id }}">Hapus</button>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>

                  <!-- Modal Tambah/Edit -->
                  <div class="modal fade" id="modalShift" tabindex="-1">
                    <div class="modal-dialog">
                      <form id="formShift">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Form Shift</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                            <input type="hidden" name="shift_id" id="shift_id">

                            <div class="mb-3">
                              <label>Nama Shift</label>
                              <select name="nama_shift" id="nama_shift" class="form-control" required>
                                <option value="" disabled selected>Pilih Shift</option>
                                <option value="Shift Pagi">Shift Pagi</option>
                                <option value="Shift Siang">Shift Siang</option>
                                <option value="Shift Sore">Shift Sore</option>
                                <option value="Shift Malam">Shift Malam</option>
                              </select>
                            </div>

                            <div class="mb-3">
                              <label>Jam Masuk</label>
                              <input type="time" name="jam_masuk" id="jam_masuk" class="form-control" required>
                            </div>

                            <div class="mb-3">
                              <label>Jam Pulang</label>
                              <input type="time" name="jam_pulang" id="jam_pulang" class="form-control" required>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="btn-save">Simpan</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
        @include('partial.footer')
      </div>
    </div>
  </div>

  {{-- Script --}}
  <script src="{{asset('adminpage')}}/template/vendors/js/vendor.bundle.base.js"></script>
  <script src="{{asset('adminpage')}}/template/js/off-canvas.js"></script>
  <script src="{{asset('adminpage')}}/template/js/hoverable-collapse.js"></script>
  <script src="{{asset('adminpage')}}/template/js/template.js"></script>
  <script src="{{asset('adminpage')}}/template/js/settings.js"></script>
  <script src="{{asset('adminpage')}}/template/js/todolist.js"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

  <script>
    const SITEURL = "{{ url('/') }}/";

    $(function () {
      $('#tabel_shift').DataTable();

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      // Edit Button
      $('.btn-edit').on('click', function () {
        const id = $(this).data('id');
        $.get(SITEURL + 'shift/' + id + '/edit', function (data) {
            $('#shift_id').val(data.id);
            $('#nama_shift').val(data.nama_shift).trigger('change');
            $('#jam_masuk').val(data.jam_masuk);
            $('#jam_pulang').val(data.jam_pulang);
            $('#modalShift').modal('show');
        });
        });

      // Submit Form
      $('#formShift').on('submit', function (e) {
        e.preventDefault();

        const id = $('#shift_id').val();
        const formData = {
          nama_shift: $('#nama_shift').val(),
          jam_masuk: $('#jam_masuk').val(),
          jam_pulang: $('#jam_pulang').val(),
        };

        let method = id ? 'PUT' : 'POST';
        let url = id ? SITEURL + 'shift/' + id : SITEURL + 'shift';

        $.ajax({
          url: url,
          type: method,
          data: formData,
          success: function () {
            $('#formShift').trigger("reset");
            $('#modalShift').modal('hide');
            location.reload();
          },
          error: function (xhr) {
            console.error(xhr.responseText);
          }
        });
      });

      // Delete
      $('.btn-delete').on('click', function () {
        const id = $(this).data('id');
        if (confirm('Yakin ingin menghapus data ini?')) {
          $.ajax({
            type: 'DELETE',
            url: SITEURL + 'shift/' + id,
            success: function () {
              location.reload();
            },
            error: function (xhr) {
              console.error(xhr.responseText);
            }
          });
        }
      });
    });
  </script>
</body>
</html>
