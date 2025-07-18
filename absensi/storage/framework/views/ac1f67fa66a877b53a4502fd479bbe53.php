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
                  <h4 class="card-title">Data Pegawai</h4>
                  <div class="d-flex gap-2 mb-3">
                      <!-- Tombol Tambah Pegawai -->
                      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPegawai">
                        Tambah Pegawai
                      </button>

                      <!-- Dropdown Export Excel -->
                      <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                          Export Excel
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                          <li>
                            <a class="dropdown-item" href="<?php echo e(route('pegawai.export')); ?>">Semua Role</a>
                          </li>
                          <?php $__currentLoopData = App\Models\Role::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                              <a class="dropdown-item" href="<?php echo e(route('pegawai.export', ['role' => $role->nama])); ?>">
                                Export <?php echo e(ucfirst($role->nama)); ?>

                              </a>
                            </li>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                      </div>
                    </div>
                  <div class="table-responsive">
                    <table class="table table-bordered" id="tabel_pegawai">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Foto</th>
                          <th>Nama</th>
                          <th>Role</th>
                          <th>User</th>
                          <th>NIP</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $__currentLoopData = $pegawais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                          <td><?php echo e($loop->iteration); ?></td>
                          <td>
                            <?php if($pegawai->foto): ?>
                              <img src="<?php echo e(asset('storage/' . $pegawai->foto)); ?>" class="rounded-circle" style="width: 80px !important; height: 80px !important; object-fit: cover;">
                            <?php else: ?>
                              -
                            <?php endif; ?>
                          </td>
                          <td><?php echo e($pegawai->nama); ?></td>
                          <td><?php echo e($pegawai->role->nama); ?></td>
                          <td><?php echo e($pegawai->user->name ?? '-'); ?></td>
                          <td><?php echo e($pegawai->nip); ?></td>
                          <td>
                            <button class="btn btn-sm btn-warning btn-edit" data-id="<?php echo e($pegawai->id); ?>">Edit</button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="<?php echo e($pegawai->id); ?>">Hapus</button>
                          </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </tbody>
                    </table>
                  </div>

                  <!-- Modal Form -->
                  <div class="modal fade" id="modalPegawai" tabindex="-1">
                    <div class="modal-dialog">
                      <form id="formPegawai" enctype="multipart/form-data">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="pegawaiCrudModal">Form Pegawai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                            <input type="hidden" id="pegawai_id" name="pegawai_id">

                            <div class="mb-3">
                              <label>Nama</label>
                              <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>

                            <div class="mb-3">
                              <label>Role</label>
                              <select class="form-control" name="role_id" id="role_id" required>
                                <option value="" disabled selected>Pilih Role</option>
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($role->id); ?>"><?php echo e($role->nama); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                            </div>

                            <div class="mb-3">
                              <label>User</label>
                              <select class="form-control" name="user_id" id="user_id" required>
                                <option value="" disabled selected>Pilih User</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                            </div>

                            <div class="mb-3">
                              <label>NIP</label>
                              <input type="text" class="form-control" id="nip" name="nip">
                            </div>

                            <div class="mb-3">
                              <label>Foto</label><br>
                              <img id="previewFoto" src="" width="100" class="mb-2 d-none">
                              <input type="file" name="foto" class="form-control" id="foto">
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
    var SITEURL = "<?php echo e(url('/')); ?>/";

    $(document).ready(function () {
      $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
      });

      $('#tabel_pegawai').DataTable();

      $('body').on('click', '.btn-edit', function () {
        var id = $(this).data('id');
        $.get(SITEURL + "pegawai/" + id + "/edit", function (data) {
          $('#pegawaiCrudModal').text("Edit Pegawai");
          $('#btn-save').val("edit-pegawai");
          $('#modalPegawai').modal('show');
          $('#pegawai_id').val(data.id);
          $('#nama').val(data.nama);
          $('#role_id').val(data.role_id);
          $('#nip').val(data.nip);
          if (data.foto) {
            $('#previewFoto').attr('src', SITEURL + 'storage/' + data.foto).removeClass('d-none');
          } else {
            $('#previewFoto').addClass('d-none');
          }
        });
      });

      $('#formPegawai').on('submit', function (e) {
        e.preventDefault();
        var id = $('#pegawai_id').val();
        var formData = new FormData(this);

        if ($('#btn-save').val() === 'edit-pegawai') {
          formData.append('_method', 'PUT');
        }

        var url = $('#btn-save').val() === 'edit-pegawai'
          ? SITEURL + "pegawai/" + id
          : SITEURL + "pegawai";

        $('#btn-save').html('Menyimpan...');

        $.ajax({
          type: 'POST',
          url: url,
          data: formData,
          contentType: false,
          processData: false,
          success: function () {
            $('#formPegawai').trigger("reset");
            $('#modalPegawai').modal('hide');
            $('#btn-save').html('Simpan');
            location.reload();
          },
          error: function (xhr) {
            console.error("Error:", xhr.responseText);
            $('#btn-save').html('Simpan');
          }
        });
      });

      $('body').on('click', '.btn-delete', function () {
        var id = $(this).data('id');
        if (confirm("Yakin ingin menghapus pegawai ini?")) {
          $.ajax({
            type: "DELETE",
            url: SITEURL + "pegawai/" + id,
            success: function () {
              location.reload();
            },
            error: function (xhr) {
              console.error("Error:", xhr.responseText);
            }
          });
        }
      });
    });
  </script>
</body>
</html>
<?php /**PATH C:\laragon\www\absen-fiks\absensi\resources\views/pegawai/index.blade.php ENDPATH**/ ?>