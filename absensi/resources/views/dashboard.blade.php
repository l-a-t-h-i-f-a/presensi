{{--<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}

<!DOCTYPE html>
<html lang="en">
@include('partial.headuser')

<body>
  <div class="container-scroller">
    
    {{-- Navbar --}}
    @include('partial.navbar')
    
    <div class="container-fluid page-body-wrapper">
      
      {{-- Sidebar --}}
      @include('partial.sidebar')

      {{-- Main Panel --}}
      <div class="main-panel d-flex flex-column">
        
        {{-- Content --}}
        <div class="content-wrapper flex-grow-1">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Welcome {{ Auth::user()->name }}!</h3>
                </div>
              </div>
            </div>
          </div>

          {{-- Konten lainnya --}}
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card tale-bg">
                
                  <img src="{{asset('adminpage')}}/template/images/absen1.jpg" alt="people">
                
              </div>
            </div>

            @php
              use Carbon\Carbon;
              $user = auth()->user();
              $pegawai = $user->pegawai;
              $totalPresensi = $pegawai->presensi->count();
              $totalIzin = $pegawai->izins->count();
              $tanggal = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');
            @endphp

            <div class="col-md-6 grid-margin transparent">
              <div class="row">
                <!-- Tanggal dan Hari -->
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                      <p class="mb-4">Hari & Tanggal</p>
                      <p class="fs-30 mb-2">{{ $tanggal }}</p>
                      <p>Selamat datang, {{ $pegawai->nama }}</p>
                    </div>
                  </div>
                </div>

                <!-- Jam Sekarang -->
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Jam Sekarang</p>
                      <p class="fs-30 mb-2" id="clock">00:00:00</p>
                      <p>Jam akan berjalan otomatis</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <!-- Presensi Milik Sendiri -->
                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                  <div class="card card-light-blue">
                    <div class="card-body">
                      <p class="mb-4">Total Presensi Kamu</p>
                      <p class="fs-30 mb-2">{{ $totalPresensi }}</p>
                      <p>Presensi yang sudah tercatat</p>
                    </div>
                  </div>
                </div>

                <!-- Izin Milik Sendiri -->
                <div class="col-md-6 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="mb-4">Total Izin Kamu</p>
                      <p class="fs-30 mb-2">{{ $totalIzin }}</p>
                      <p>Izin yang sudah kamu ajukan</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>          
        </div> <!-- end content-wrapper -->

        {{-- Footer --}}
        @include('partial.footer')
      </div> <!-- end main-panel -->
    </div> <!-- end page-body-wrapper -->
  </div> <!-- end container-scroller -->

  {{-- Scripts --}}
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      function updateClock() {
        const now = new Date();
        const jam = String(now.getHours()).padStart(2, '0');
        const menit = String(now.getMinutes()).padStart(2, '0');
        const detik = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('clock').textContent = `${jam}:${menit}:${detik}`;
      }

      setInterval(updateClock, 1000);
      updateClock(); // panggil sekali saat load awal
    });
  </script>

  <script src="{{asset('adminpage')}}/template/vendors/js/vendor.bundle.base.js"></script>
  <script src="{{asset('adminpage')}}/template/vendors/chart.js/Chart.min.js"></script>
  <script src="{{asset('adminpage')}}/template/vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="{{asset('adminpage')}}/template/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <script src="{{asset('adminpage')}}/template/js/dataTables.select.min.js"></script>
  <script src="{{asset('adminpage')}}/template/js/off-canvas.js"></script>
  <script src="{{asset('adminpage')}}/template/js/hoverable-collapse.js"></script>
  <script src="{{asset('adminpage')}}/template/js/template.js"></script>
  <script src="{{asset('adminpage')}}/template/js/settings.js"></script>
  <script src="{{asset('adminpage')}}/template/js/todolist.js"></script>
  <script src="{{asset('adminpage')}}/template/js/dashboard.js"></script>
  <script src="{{asset('adminpage')}}/template/js/Chart.roundedBarCharts.js"></script>

</body>
</html>
