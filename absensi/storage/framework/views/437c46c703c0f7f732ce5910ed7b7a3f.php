<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    
    <li class="nav-item">
      <a class="nav-link" href="<?php echo e(auth()->user()->usertype === 'admin' ? route('dashboard-absensi') : route('dashboard')); ?>">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    
    <?php if(auth()->user()->usertype === 'admin'): ?>
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
          <i class="icon-layout menu-icon"></i>
          <span class="menu-title">Pegawai</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="<?php echo e(route('pegawai.index')); ?>">Tabel Pegawai</a></li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
          <i class="icon-columns menu-icon"></i>
          <span class="menu-title">Role</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="form-elements">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('role.index')); ?>">Tabel Role</a></li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
          <i class="icon-contract menu-icon"></i>
          <span class="menu-title">Lokasi</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="icons">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('lokasi.index')); ?>">Tabel Lokasi</a></li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
          <i class="icon-head menu-icon"></i>
          <span class="menu-title">Shift</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="auth">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('shift.index')); ?>">Tabel Shift</a></li>
          </ul>
        </div>
      </li>
    <?php endif; ?>

    
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
        <i class="icon-bar-graph menu-icon"></i>
        <span class="menu-title">Presensi</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="charts">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="<?php echo e(route('presensi.index')); ?>">Tabel Presensi</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
        <i class="icon-grid-2 menu-icon"></i>
        <span class="menu-title">Perizinan</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="tables">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="<?php echo e(route('izin.index')); ?>">Tabel Perizinan</a></li>
        </ul>
      </div>
    </li>

  </ul>
</nav>
<?php /**PATH C:\laragon\www\absen-fiks\absensi\resources\views/partial/sidebar.blade.php ENDPATH**/ ?>