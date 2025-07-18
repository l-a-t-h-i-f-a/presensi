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
                  <h4 class="mb-4">Data Presensi</h4>

                 <?php if(auth()->user()->usertype === 'admin'): ?>
                    <div class="d-flex gap-2 mb-3">

                      <!-- Tombol Tambah Presensi -->
                      <button class="btn btn-primary" id="createPresensi" data-bs-toggle="modal" data-bs-target="#modalPresensi">
                        Tambah Presensi
                      </button>

                      <!-- Dropdown Export -->
                      <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                          Export Excel
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                          <li>
                            <a class="dropdown-item" href="<?php echo e(route('presensi.export')); ?>">Semua Role</a>
                          </li>
                          <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                              <a class="dropdown-item" href="<?php echo e(route('presensi.export', ['role' => $role->nama])); ?>">
                                Export <?php echo e(ucfirst($role->nama)); ?>

                              </a>
                            </li>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                      </div>
                    </div>
                  <?php else: ?>
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalPresensi">Presensi Sekarang</button>
                  <?php endif; ?>
                  <div class="table-responsive">
                  <table class="table table-bordered" id="tabelPresensi">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Pegawai</th>
                        <th>Tanggal</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Foto Masuk</th>
                        <th>Foto Pulang</th>  
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $__currentLoopData = $presensis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $presensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($presensi->pegawai->nama); ?></td>
                        <td><?php echo e($presensi->tanggal); ?></td>
                        <td><?php echo e($presensi->jam_masuk); ?></td>
                        <td><?php echo e($presensi->jam_pulang ?? '-'); ?></td>
                        <td>
                          <?php if($presensi->foto): ?>
                            <img src="<?php echo e(asset('storage/'.$presensi->foto)); ?>" width="80">
                          <?php else: ?>
                            -
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php if($presensi->foto_pulang): ?>
                            <img src="<?php echo e(asset('storage/'.$presensi->foto_pulang)); ?>" width="80">
                          <?php else: ?>
                            -
                          <?php endif; ?>
                        </td>
                        <td><?php echo e(ucfirst($presensi->status->value)); ?></td>
                        <td>
                          <?php if(auth()->user()->usertype === 'admin'): ?>
                            <button class="btn btn-warning btn-sm btn-edit" data-id="<?php echo e($presensi->id); ?>">Edit</button>
                            <button class="btn btn-danger btn-sm btn-delete" data-id="<?php echo e($presensi->id); ?>">Hapus</button>
                          <?php endif; ?>
                          <?php if(auth()->user()->usertype === 'user' && !$presensi->jam_pulang): ?>
                            <button class="btn btn-info btn-sm btn-pulang" data-id="<?php echo e($presensi->id); ?>">Pulang</button>
                          <?php endif; ?>
                        </td>
                      </tr>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                  </table>

                  <!-- Modal Presensi -->
                  <div class="modal fade" id="modalPresensi" tabindex="-1">
                    <div class="modal-dialog">
                      <form id="formPresensi">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Form Presensi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                            <div class="modal-body">
                              <input type="hidden" name="presensi_id" id="presensi_id">
                              <input type="hidden" name="tanggal" value="<?php echo e(date('Y-m-d')); ?>">
                              <input type="hidden" name="latitude" id="latitude">
                              <input type="hidden" name="longitude" id="longitude">
                              <input type="hidden" name="foto" id="foto">

                              <?php if(auth()->user()->usertype === 'admin'): ?>
                                <div class="mb-3">
                                  <label for="pegawai_id">Pegawai</label>
                                  <select name="pegawai_id" class="form-select" required>
                                    <?php $__currentLoopData = $pegawais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                      <option value="<?php echo e($pegawai->id); ?>"><?php echo e($pegawai->nama); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </select>
                                </div>

                                <div class="mb-3">
                                  <label for="role_id">Role</label>
                                  <select name="role_id" class="form-select" required>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role->id); ?>"><?php echo e(ucfirst($role->nama)); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </select>
                                </div>

                                <div class="mb-3">
                                  <label for="shift_id">Shift (opsional)</label>
                                  <select name="shift_id" class="form-select">
                                    <option value="">Tidak Ada Shift</option>
                                    <?php $__currentLoopData = App\Models\Shift::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                      <option value="<?php echo e($shift->id); ?>"><?php echo e($shift->nama_shift); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </select>
                                </div>

                                <div class="mb-3">
                                  <label for="lokasi_id">Lokasi</label>
                                  <select name="lokasi_id" class="form-select" required>
                                    <?php $__currentLoopData = $lokasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                      <option value="<?php echo e($lokasi->id); ?>"><?php echo e($lokasi->nama_lokasi); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </select>
                                </div>

                                <div class="mb-3">
                                  <label for="jam_masuk">Jam Masuk</label>
                                  <input type="time" name="jam_masuk" class="form-control">
                                </div>

                                <div class="mb-3">
                                  <label for="jam_pulang">Jam Pulang</label>
                                  <input type="time" name="jam_pulang" class="form-control">
                                </div>

                                <div class="mb-3">
                                  <label for="status">Status</label>
                                  <?php
                                    $selectedStatus = old('status', isset($presensi) ? $presensi->status->value : '');
                                  ?>

                                  <select name="status" class="form-select" required>
                                    <option value="hadir" <?php echo e($selectedStatus === 'hadir' ? 'selected' : ''); ?>>Hadir</option>
                                    <option value="izin" <?php echo e($selectedStatus === 'izin' ? 'selected' : ''); ?>>Izin</option>
                                    <option value="sakit" <?php echo e($selectedStatus === 'sakit' ? 'selected' : ''); ?>>Sakit</option>
                                    <option value="terlambat" <?php echo e($selectedStatus === 'terlambat' ? 'selected' : ''); ?>>Terlambat</option>
                                  </select>
                                </div>
                              <?php else: ?>
                                
                                <input type="hidden" name="pegawai_id" value="<?php echo e(auth()->user()->pegawai->id); ?>">
                                <input type="hidden" name="role_id" value="<?php echo e(auth()->user()->pegawai->role_id); ?>">
                                <input type="hidden" name="shift_id" value="<?php echo e(auth()->user()->pegawai->shift_id); ?>">
                                <input type="hidden" name="lokasi_id" value="<?php echo e($lokasis[0]->id ?? ''); ?>">

                                <div class="mb-3">
                                  <label>Lokasi & Radius</label>
                                  <div id="map" style="height: 300px; border-radius: 10px;"></div>
                                </div>

                                <div class="mb-3">
                                  <label>Ambil Foto Wajah</label><br>
                                <video id="webcam" autoplay playsinline style="max-width: 100%; height: auto; border-radius: 10px; aspect-ratio: 4 / 3;"></video>
                                <canvas id="canvas" style="display:none; max-width: 100%; height: auto; border-radius: 10px; aspect-ratio: 4 / 3;"></canvas>
                                  <div class="mt-2">
                                    <button type="button" class="btn btn-secondary" onclick="ambilFoto()">Ambil Foto</button>
                                    <button type="button" class="btn btn-warning" onclick="ulangFoto()">Ulangi Foto</button>
                                  </div>
                                </div>
                              <?php endif; ?>
                            </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
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
      </div>
        <?php echo $__env->make('partial.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      </div>
    </div>
  </div>

  <!-- Leaflet -->

  <script src="<?php echo e(asset('adminpage')); ?>/template/vendors/js/vendor.bundle.base.js"></script><!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/off-canvas.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/hoverable-collapse.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/template.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/settings.js"></script>
  <script src="<?php echo e(asset('adminpage')); ?>/template/js/todolist.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
    }
  });

  const webcam = document.getElementById('webcam');
  const canvas = document.getElementById('canvas');
  const faceInput = document.getElementById('foto');

  <?php if(auth()->user()->usertype === 'user'): ?>
  // ✅ Jalankan webcam
  if (navigator.mediaDevices) {
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => webcam.srcObject = stream)
      .catch(err => alert("❌ Kamera tidak bisa diakses"));
  }

  // ✅ Ambil foto dari webcam
  function ambilFoto() {
    const context = canvas.getContext('2d');

    // Set ukuran canvas sesuai video
    canvas.width = webcam.videoWidth;
    canvas.height = webcam.videoHeight;

    // Gambar dari video ke canvas dengan ukuran eksplisit
    context.drawImage(webcam, 0, 0, canvas.width, canvas.height);

    const dataURL = canvas.toDataURL('image/jpeg');
    faceInput.value = dataURL;

    // Tampilkan hasil foto, sembunyikan video
    webcam.style.display = 'none';
    canvas.style.display = 'block';

    alert('✅ Foto berhasil diambil');
  }

  // 🔁 Ulangi pengambilan foto
  function ulangFoto() {
    webcam.style.display = 'block';
    canvas.style.display = 'none';

    // Kosongkan canvas
    const context = canvas.getContext('2d');
    context.clearRect(0, 0, canvas.width, canvas.height);
  }

  // 📍 Ambil lokasi GPS user
  navigator.geolocation.getCurrentPosition(pos => {
    $('#latitude').val(pos.coords.latitude);
    $('#longitude').val(pos.coords.longitude);
  });
  <?php endif; ?>

  // 📤 Submit form presensi
  $('#formPresensi').on('submit', function (e) {
    e.preventDefault();
    let id = $('#presensi_id').val();
    let method = id ? 'PUT' : 'POST';
    let url = id ? `/presensi/${id}` : `/presensi`;

    $.ajax({
      url: url,
      method: method,
      data: $(this).serialize(),
      success: function (res) {
        alert(res.success);
        $('#modalPresensi').modal('hide');
        location.reload();
      },
     error: function (xhr) {
      let msg = '❌ Terjadi Kesalahan';

      try {
        const res = JSON.parse(xhr.responseText);

        if (
          res.message &&
          res.message.includes('cURL error 7') &&
          res.message.includes('127.0.0.1:5000')
        ) {
          msg = '💤 Flask-nya tidur sayang... nyalain dulu ya~ ☕';
        } else {
          msg = res.error || res.message || msg;
        }
      } catch (e) {
        msg = '🥲 Gagal membaca respon server. Coba lagi nanti yaa~';
      }

      alert(msg);
    }
    });
  });

  // ✏️ Edit presensi (admin)
  $('.btn-edit').on('click', function () {
    let id = $(this).data('id');
    $.get(`/presensi/${id}/edit`, res => {
      $('#presensi_id').val(res.id);
      $('[name=pegawai_id]').val(res.pegawai_id);
      $('[name=role_id]').val(res.role_id);
      $('[name=shift_id]').val(res.shift_id ?? '');
      $('[name=lokasi_id]').val(res.lokasi_id);
      $('[name=status]').val(res.status);
      $('[name=jam_masuk]').val(res.jam_masuk);
      $('[name=jam_pulang]').val(res.jam_pulang);
      $('#modalPresensi').modal('show');
    }).fail(() => {
      alert("❌ Tidak bisa ambil data presensi");
    });
  });

  // 🗑️ Hapus presensi
  $('.btn-delete').on('click', function () {
    if (confirm('Yakin ingin menghapus data ini?')) {
      let id = $(this).data('id');
      $.ajax({
        url: `/presensi/${id}`,
        type: 'DELETE',
        data: { _token: '<?php echo e(csrf_token()); ?>' },
        success: function () { location.reload(); },
        error: function () { alert('❌ Gagal menghapus'); }
      });
    }
  });

  // ⏰ Tombol Pulang
  $('.btn-pulang').on('click', function () {
    let id = $(this).data('id');

    if (!navigator.geolocation) {
      return alert("❌ Lokasi tidak didukung di browser ini");
    }

    navigator.geolocation.getCurrentPosition(pos => {
      const lat = pos.coords.latitude;
      const lon = pos.coords.longitude;

      const context = canvas.getContext('2d');
      canvas.width = webcam.videoWidth;
      canvas.height = webcam.videoHeight;
      context.drawImage(webcam, 0, 0, canvas.width, canvas.height);

      const dataURL = canvas.toDataURL('image/jpeg');

      $.post(`/presensi/${id}/pulang`, {
        _token: '<?php echo e(csrf_token()); ?>',
        latitude: lat,
        longitude: lon,
        foto: dataURL
      }, res => {
        alert(res.success);
        location.reload();
      }).fail(xhr => {
        alert(xhr.responseJSON?.error || '❌ Gagal presensi pulang');
      });
    });
  });

  <?php if(auth()->user()->usertype === 'user'): ?>
  // 🗺️ Leaflet Map & radius lokasi
  const lokasiKantor = <?php echo json_encode($lokasis[0], 15, 512) ?>;
  const map = L.map('map').setView([lokasiKantor.latitude, lokasiKantor.longitude], 17);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  L.marker([lokasiKantor.latitude, lokasiKantor.longitude]).addTo(map).bindPopup("Lokasi Kantor").openPopup();

  L.circle([lokasiKantor.latitude, lokasiKantor.longitude], {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.3,
    radius: lokasiKantor.radius
  }).addTo(map);

  navigator.geolocation.getCurrentPosition(pos => {
    const userLat = pos.coords.latitude;
    const userLng = pos.coords.longitude;

    $('#latitude').val(userLat);
    $('#longitude').val(userLng);

    const jarak = map.distance([lokasiKantor.latitude, lokasiKantor.longitude], [userLat, userLng]);

    L.marker([userLat, userLng]).addTo(map).bindPopup("Lokasi Kamu").openPopup();

    if (jarak > lokasiKantor.radius) {
      alert('❌ Kamu di luar radius lokasi. Presensi tidak diizinkan.');
      $('button[type=submit]').prop('disabled', true);
    } else {
      $('button[type=submit]').prop('disabled', false);
    }
  });
  <?php endif; ?>
</script>
</body>
</html>
<?php /**PATH C:\laragon\www\absen-fiks\absensi\resources\views/presensi/index.blade.php ENDPATH**/ ?>