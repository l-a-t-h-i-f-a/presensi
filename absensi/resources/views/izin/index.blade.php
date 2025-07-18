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
                  <h4 class="card-title">Data Izin Pegawai</h4>
                    @if(auth()->user()->usertype === 'admin')
                      <div class="d-flex gap-2 mb-3">

                        <!-- Tombol Tambah Izin -->
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalIzin">
                          Tambah Izin
                        </button>

                        <!-- Dropdown Export Excel -->
                        <div class="dropdown">
                          <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Export Excel
                          </button>
                          <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                            <li>
                              <a class="dropdown-item" href="{{ route('izin.export') }}">Semua Role</a>
                            </li>
                            @foreach($roles as $role)
                              <li>
                                <a class="dropdown-item" href="{{ route('izin.export', ['role' => $role->nama]) }}">
                                  Export {{ ucfirst($role->nama) }}
                                </a>
                              </li>
                            @endforeach
                          </ul>
                        </div>

                      </div>
                  @else
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalIzin">Ajukan Izin</button><br><br>
                  @endif

                  <div class="table-responsive">
                    <table class="table table-bordered" id="tabel_izin">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Pegawai</th>
                          <th>Role</th>
                          <th>Jenis Izin</th>
                          <th>Tanggal Mulai</th>
                          <th>Tanggal Selesai</th>
                          <th>Keterangan</th>
                          <th>Status</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($izins as $izin)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $izin->pegawai->nama }}</td>
                          <td>{{ $izin->pegawai->role->nama ?? '-' }}</td>
                          <td>{{ $izin->jenis_izin }}</td>
                          <td>{{ $izin->tanggal_mulai }}</td>
                          <td>{{ $izin->tanggal_selesai }}</td>
                          <td>{{ $izin->keterangan }}</td>
                          <td>{{ $izin->status }}</td>
                          <td>
                            @if(auth()->user()->usertype === 'admin' || auth()->user()->pegawai->id === $izin->pegawai_id)
                            <button class="btn btn-warning btn-edit" data-id="{{ $izin->id }}">Edit</button>
                            <button class="btn btn-danger btn-delete" data-id="{{ $izin->id }}">Hapus</button>
                            @endif
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>

                  <!-- Modal Tambah/Edit -->
                  <div class="modal fade" id="modalIzin" tabindex="-1">
                    <div class="modal-dialog">
                      <form id="formIzin">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="izinCrudModal">Form Izin</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                            <input type="hidden" name="izin_id" id="izin_id">

                            @php $user = auth()->user(); @endphp

                            {{-- Jika admin, bisa pilih pegawai --}}
                            @if($user->usertype === 'admin')
                            <div class="mb-3">
                              <label>Pegawai</label>
                              <select class="form-control" name="pegawai_id" id="pegawai_id" required>
                                <option value="" disabled selected>Pilih Pegawai</option>
                                @foreach(App\Models\Pegawai::all() as $pegawai)
                                  <option value="{{ $pegawai->id }}">{{ $pegawai->nama }}</option>
                                @endforeach
                              </select>
                            </div>
                            @else
                              {{-- User biasa: langsung isi pegawai_id --}}
                              <input type="hidden" name="pegawai_id" id="pegawai_id" value="{{ $user->pegawai->id }}">
                            @endif

                            @if($user->usertype === 'admin')
                            <div class="mb-3">
                              <label>Role</label>
                              <select class="form-control" name="role_id" id="role_id" required>
                                <option value="" disabled selected>Pilih Role</option>
                                @foreach(App\Models\Role::all() as $role)
                                  <option value="{{ $role->id }}">{{ $role->nama }}</option>
                                @endforeach
                              </select>
                            </div>
                            @else
                              <input type="hidden" name="role_id" value="{{ $user->pegawai->role_id }}">
                            @endif

                            <div class="mb-3">
                              <label>Jenis Izin</label>
                              <input type="text" name="jenis_izin" id="jenis_izin" class="form-control" required>
                            </div>

                            <div class="mb-3">
                              <label>Tanggal Mulai</label>
                              <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
                            </div>

                            <div class="mb-3">
                              <label>Tanggal Selesai</label>
                              <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required>
                            </div>

                            <div class="mb-3">
                              <label>Keterangan</label>
                              <textarea name="keterangan" id="keterangan" class="form-control" rows="2"></textarea>
                            </div>

                            {{-- Status hanya bisa diubah admin --}}
                            @if($user->usertype === 'admin')
                            <div class="mb-3">
                              <label>Status</label>
                              <select name="status" id="status" class="form-control">
                                <option value="pending">Pending</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                              </select>
                            </div>
                            @endif
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
  <script src="{{ asset('adminpage') }}/template/vendors/js/vendor.bundle.base.js"></script>
<script src="{{ asset('adminpage') }}/template/js/off-canvas.js"></script>
<script src="{{ asset('adminpage') }}/template/js/hoverable-collapse.js"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script>
    const SITEURL = "{{ url('/') }}/";

    $(function () {
      $('#tabel_izin').DataTable();

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      // Edit Izin
      $('.btn-edit').on('click', function () {
        const id = $(this).data('id');
        $.get(SITEURL + 'izin/' + id + '/edit', function (data) {
          $('#izin_id').val(data.id);
          $('#pegawai_id').val(data.pegawai_id);
          $('#role_id').val(data.role_id);
          $('#jenis_izin').val(data.jenis_izin);
          $('#tanggal_mulai').val(data.tanggal_mulai);
          $('#tanggal_selesai').val(data.tanggal_selesai);
          $('#keterangan').val(data.keterangan);
          $('#status').val(data.status);
          $('#modalIzin').modal('show');
        });
      });

      // Submit Form
      $('#formIzin').on('submit', function (e) {
        e.preventDefault();
        const id = $('#izin_id').val();
        const formData = $(this).serialize();
        const url = id ? SITEURL + 'izin/' + id + '?_method=PUT' : SITEURL + 'izin';

        $.post(url, formData)
          .done(function () {
            $('#modalIzin').modal('hide');
            location.reload();
          })
          .fail(function (xhr) {
            alert('Terjadi kesalahan saat menyimpan data.');
            console.error(xhr.responseText);
          });
      });

      // Delete
      $('.btn-delete').on('click', function () {
        const id = $(this).data('id');
        if (confirm('Yakin ingin menghapus data ini?')) {
          $.ajax({
            type: 'DELETE',
            url: SITEURL + 'izin/' + id,
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
