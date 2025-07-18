<!DOCTYPE html>
<html lang="en">
<?php echo $__env->make('partial.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<body>
  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
   <?php echo $__env->make('partial.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_settings-panel.html -->
      
      <!-- partial -->
      <!-- partial:../../partials/_sidebar.html -->
      <?php echo $__env->make('partial.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">          
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <div class="container mt-5">
                      <h4 class="mb-4">Data Role</h4>
                      <button class="btn btn-primary mb-3" id="create-new-role" data-bs-toggle="modal" data-bs-target="#modalRole">Tambah Role</button><br><br>

                      <table class="table table-bordered" id="laravel_12_datatable">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr id="index_<?php echo e($role->id); ?>">
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($role->nama); ?></td>
                            <td>
                              <button class="btn btn-sm btn-warning btn-edit" data-id="<?php echo e($role->id); ?>">Edit</button>
                              <button class="btn btn-sm btn-danger btn-delete" data-id="<?php echo e($role->id); ?>">Hapus</button>
                            </td>
                          </tr>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                      </table>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="modalRole" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <form id="formRole" name="formRole">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="roleCrudModal">Form Role</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                              <input type="hidden" name="role_id" id="role_id">
                              <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="submit" class="btn btn-primary" id="btn-save" value="create-role">Simpan</button>
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
         <?php echo $__env->make('partial.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
   <!-- JQuery HARUS muncul sebelum Bootstrap & DataTables -->

  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="<?php echo e(asset('adminpage')); ?>/template/vendors/js/vendor.bundle.base.js"></script><!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/off-canvas.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/hoverable-collapse.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/template.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/settings.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/todolist.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
  var SITEURL = "<?php echo e(url('/')); ?>/";

  $(document).ready(function () {
    // CSRF setup
    $.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // DataTable init
    $('#laravel_12_datatable').DataTable();

    // Tambah Data
    $('#create-new-role').click(function () {
      $('#btn-save').val("create-role");
      $('#role_id').val('');
      $('#formRole').trigger("reset");
      $('#roleCrudModal').text("Tambah Data Role");
      $('#modalRole').modal('show');
    });

    // Edit Data
    $('body').on('click', '.btn-edit', function () {
      var id = $(this).data('id');
      $.get(SITEURL + "role/" + id + "/edit", function (data) {
        $('#roleCrudModal').text("Edit Data Role");
        $('#btn-save').val("edit-role");
        $('#modalRole').modal('show');
        $('#role_id').val(data.id);
        $('#nama').val(data.nama);
      });
    });

    // Simpan / Update Data
    $('#formRole').on('submit', function (e) {
      e.preventDefault();
      var id = $('#role_id').val();
      var actionType = $('#btn-save').val();
      var url = actionType === "edit-role" 
        ? SITEURL + "role/" + id
        : SITEURL + "role";
      var type = actionType === "edit-role" ? "PUT" : "POST";

      $('#btn-save').html('Menyimpan..');

      $.ajax({
        type: type,
        url: url,
        data: $(this).serialize(),
        success: function () {
          $('#formRole').trigger("reset");
          $('#modalRole').modal('hide');
          $('#btn-save').html('Simpan');
          location.reload();
        },
        error: function (xhr) {
          console.error("Error:", xhr.responseText);
          $('#btn-save').html('Simpan');
        }
      });
    });

    // Hapus Data
    $('body').on('click', '.btn-delete', function () {
      var id = $(this).data("id");
      if (confirm("Yakin ingin menghapus data ini?")) {
        $.ajax({
          type: "DELETE",
          url: SITEURL + "role/" + id,
          success: function () {
            location.reload();
          },
          error: function (xhr) {
            console.error("Error saat menghapus data:", xhr.responseText);
          }
        });
      }
    });
  });
</script>
</body>
</html>
 <?php /**PATH C:\laragon\www\absen-fiks\absensi\resources\views/role/index.blade.php ENDPATH**/ ?>