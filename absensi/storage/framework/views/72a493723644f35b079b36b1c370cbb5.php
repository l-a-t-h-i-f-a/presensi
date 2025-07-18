<!DOCTYPE html>
<html lang="en">
<?php echo $__env->make('partial.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<body>
  <div class="container-scroller">
    
    
    <?php echo $__env->make('partial.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <div class="container-fluid page-body-wrapper">
      
      
      <?php echo $__env->make('partial.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

      
      <div class="main-panel d-flex flex-column">
        
        
        <div class="content-wrapper flex-grow-1">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Welcome <?php echo e(Auth::user()->name); ?>!</h3>
                </div>
              </div>
            </div>
          </div>

          
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card tale-bg">
               
                  <img src="<?php echo e(asset('adminpage')); ?>/template/images/absen3.jpg" alt="people">
                
              </div>
            </div>

            <div class="col-md-6 grid-margin transparent">
              <div class="row">
                <!-- Total Pegawai -->
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                      <p class="mb-4">Total Pegawai</p>
                      <p class="fs-30 mb-2"><?php echo e(\App\Models\Pegawai::count()); ?></p>
                      <p>Jumlah pegawai yang terdaftar</p>
                    </div>
                  </div>
                </div>

                <!-- Total Presensi -->
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Total Presensi</p>
                      <p class="fs-30 mb-2"><?php echo e(\App\Models\Presensi::count()); ?></p>
                      <p>Seluruh data presensi</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <!-- Total Izin -->
                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                  <div class="card card-light-blue">
                    <div class="card-body">
                      <p class="mb-4">Total Izin</p>
                      <p class="fs-30 mb-2"><?php echo e(\App\Models\Izin::count()); ?></p>
                      <p>Pengajuan izin yang tercatat</p>
                    </div>
                  </div>
                </div>

                <!-- Total Role -->
                <div class="col-md-6 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="mb-4">Total Role</p>
                      <p class="fs-30 mb-2"><?php echo e(\App\Models\Role::count()); ?></p>
                      <p>Jenis role pengguna</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>         
        </div> <!-- end content-wrapper -->

        
        <?php echo $__env->make('partial.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      </div> <!-- end main-panel -->
    </div> <!-- end page-body-wrapper -->
  </div> <!-- end container-scroller -->

  
  
  <script src="<?php echo e(asset('adminpage')); ?>/template/vendors/js/vendor.bundle.base.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/vendors/chart.js/Chart.min.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/dataTables.select.min.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/off-canvas.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/hoverable-collapse.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/template.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/settings.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/todolist.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/dashboard.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/Chart.roundedBarCharts.js"></script>

</body>
</html>
<?php /**PATH C:\laragon\www\absen-fiks\absensi\resources\views/dashboard-absensi.blade.php ENDPATH**/ ?>