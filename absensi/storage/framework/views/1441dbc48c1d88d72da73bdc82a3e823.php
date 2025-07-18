<!DOCTYPE html>
<html lang="en">
<?php echo $__env->make('partial.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<body>
  <div class="container-scroller">
    <?php echo $__env->make('partial.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="container-fluid page-body-wrapper">
      <?php echo $__env->make('partial.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Data Lokasi Presensi</h4>
                  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLokasi">Tambah Lokasi</button><br><br>

                  <div class="table-responsive">
                    <table class="table table-bordered" id="tabel_lokasi">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Lokasi</th>
                          <th>Latitude</th>
                          <th>Longitude</th>
                          <th>Radius (meter)</th>
                          <th>Status</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $__currentLoopData = $lokasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                          <td><?php echo e($loop->iteration); ?></td>
                          <td><?php echo e($lokasi->nama_lokasi); ?></td>
                          <td><?php echo e($lokasi->latitude); ?></td>
                          <td><?php echo e($lokasi->longitude); ?></td>
                          <td><?php echo e($lokasi->radius); ?></td>
                          <td>
                            <?php if($lokasi->status == 'aktif'): ?>
                              <button class="btn btn-sm btn-secondary btn-nonaktif" data-id="<?php echo e($lokasi->id); ?>">Nonaktifkan</button>
                            <?php else: ?>
                              <button class="btn btn-sm btn-success btn-aktif" data-id="<?php echo e($lokasi->id); ?>">Aktifkan</button>
                            <?php endif; ?>
                          </td>
                          <td>
                            <button class="btn btn-warning btn-edit" data-id="<?php echo e($lokasi->id); ?>">Edit</button>
                            <button class="btn btn-danger btn-delete" data-id="<?php echo e($lokasi->id); ?>">Hapus</button>
                          </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </tbody>
                    </table>
                  </div>

                  <!-- Modal Tambah/Edit -->
                  <div class="modal fade" id="modalLokasi" tabindex="-1">
                    <div class="modal-dialog">
                      <form id="formLokasi">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="lokasiCrudModal">Form Lokasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                            <input type="hidden" name="lokasi_id" id="lokasi_id">
                            <div class="mb-3">
                              <label>Nama Lokasi</label>
                              <input type="text" name="nama_lokasi" id="nama_lokasi" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label>Latitude</label>
                              <input type="text" name="latitude" id="latitude" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label>Longitude</label>
                              <input type="text" name="longitude" id="longitude" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label>Radius (meter)</label>
                              <input type="number" name="radius" id="radius" class="form-control" required>
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
        <?php echo $__env->make('partial.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      </div>
    </div>
  </div>

  
  <script src="<?php echo e(asset('adminpage')); ?>/template/vendors/js/vendor.bundle.base.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/off-canvas.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/template.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

  <script>
    const SITEURL = "<?php echo e(url('/')); ?>/";

    $(document).ready(function () {
      $('#tabel_lokasi').DataTable();

      $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
      });

      // Simpan / Update Lokasi
      $('#formLokasi').on('submit', function (e) {
        e.preventDefault();
        const id = $('#lokasi_id').val();
        const url = id ? SITEURL + 'lokasi/' + id + '?_method=PUT' : SITEURL + 'lokasi';
        const data = $(this).serialize();

        $.post(url, data, function () {
          $('#modalLokasi').modal('hide');
          $('#formLokasi')[0].reset();
          location.reload();
        }).fail(function (xhr) {
          alert("Terjadi kesalahan");
          console.error(xhr.responseText);
        });
      });

      // Event Delegation untuk tombol Edit
      $('body').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(SITEURL + 'lokasi/' + id + '/edit', function (data) {
          $('#lokasi_id').val(data.id);
          $('#nama_lokasi').val(data.nama_lokasi);
          $('#latitude').val(data.latitude);
          $('#longitude').val(data.longitude);
          $('#radius').val(data.radius);
          $('#modalLokasi').modal('show');
        });
      });

      // Event Delegation untuk tombol Hapus
      $('body').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        if (confirm("Yakin ingin menghapus lokasi ini?")) {
          $.ajax({
            type: 'DELETE',
            url: SITEURL + 'lokasi/' + id,
            success: function () {
              location.reload();
            },
            error: function (xhr) {
              console.error(xhr.responseText);
              alert('Gagal menghapus lokasi.');
            }
          });
        }
      });

      // Tombol Aktifkan Lokasi
     $('body').on('click', '.btn-aktif', function () {
      const id = $(this).data("id");
      $.ajax({
        type: "PUT",
        url: "/lokasi/" + id + "/aktifkan",
        data: {
          _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          alert(response.success);
          location.reload();
        },
        error: function (xhr) {
          console.log(xhr.responseText);
          alert("Gagal mengaktifkan lokasi.");
        }
      });
    });

      // Tombol Nonaktifkan Lokasi
      $('body').on('click', '.btn-nonaktif', function () {
        const id = $(this).data('id');
        $.ajax({
          type: "PUT",
          url: SITEURL + "lokasi/" + id + "/nonaktifkan",
          data: { _token: $('meta[name="csrf-token"]').attr('content') },
          success: function (response) {
            alert(response.success);
            location.reload();
          },
          error: function () {
            alert("Gagal menonaktifkan lokasi.");
          }
        });
      });

    });
  </script>
</body>
</html>
<?php /**PATH C:\laragon\www\absen-fiks\absensi\resources\views/lokasi/index.blade.php ENDPATH**/ ?>