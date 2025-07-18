<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Pegawai;
use App\Models\Role;
use App\Models\Shift;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use App\Exports\PresensiExport;
use Maatwebsite\Excel\Facades\Excel;


class PresensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $presensis = $user->usertype === 'admin'
            ? Presensi::with(['pegawai', 'role', 'shift'])->get()
            : Presensi::with(['pegawai', 'role', 'shift'])->where('pegawai_id', $user->pegawai->id)->get();

        $lokasis = Lokasi::where('status', 'aktif')->get();
        $pegawais = Pegawai::with(['role', 'user'])->get();
        $roles = Role::all();
        return view('presensi.index', compact('presensis', 'lokasis', 'pegawais', 'roles'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'pegawai_id' => 'required',
            'role_id' => 'required',
            'shift_id' => 'nullable',
            'lokasi_id' => 'required',
            'tanggal' => 'required|date',
        ];

        if ($user->usertype === 'admin') {
            $rules['jam_masuk'] = 'required';
            $rules['jam_pulang'] = 'nullable';
            $rules['status'] = 'required|in:hadir,terlambat,izin,alpa';
        } else {
            $rules['latitude'] = 'required|numeric';
            $rules['longitude'] = 'required|numeric';
            $rules['foto'] = 'required|string';
        }

        $request->validate($rules);

        $pegawai_id = $request->pegawai_id;
        $tanggal = $request->tanggal;

        if (Presensi::where('pegawai_id', $pegawai_id)->where('tanggal', $tanggal)->exists()) {
            return response()->json(['error' => 'Anda sudah melakukan presensi hari ini.'], 422);
        }

        $lokasi = Lokasi::where('id', $request->lokasi_id)->where('status', 'aktif')->first();
        if (!$lokasi) {
            return response()->json(['error' => 'Lokasi tidak aktif atau tidak ditemukan.'], 422);
        }

        $filename = null;
        $now = Carbon::now();
        $status = 'hadir';

        if ($user->usertype !== 'admin') {
            $jarak = $this->hitungJarak($request->latitude, $request->longitude, $lokasi->latitude, $lokasi->longitude);
            if ($jarak > $lokasi->radius) {
                return response()->json(['error' => 'Anda berada di luar radius lokasi.'], 422);
            }

            $pegawai = Pegawai::find($pegawai_id);
            $response = Http::post('http://172.16.5.117:5000/recognize', [
                'nama_pegawai' => $pegawai->nama,
                'foto' => $request->foto,
            ]);

            $hasil = $response->json();
            if (!$response->successful() || empty($hasil['is_matched']) || $hasil['is_matched'] !== true) {
                return response()->json([
                    'error' => $hasil['message'] ?? 'Wajah tidak cocok, presensi dibatalkan.'
                ], 422);
            }

            $jamMasuk = $this->getJamMasuk($request->role_id, $request->shift_id);
            $buka = $jamMasuk->copy()->subMinutes(30);
            $tutup = $jamMasuk->copy()->addHour(2);

            if ($now->lt($buka)) {
                return response()->json(['error' => 'Presensi belum dibuka.'], 422);
            }

            if ($now->gt($tutup)) {
                return response()->json(['error' => 'Presensi sudah ditutup.'], 422);
            }

            if ($now->gt($jamMasuk)) {
                $status = 'terlambat';
            }

            if (preg_match('/^data:image\/(\w+);base64,/', $request->foto, $type)) {
                $image = substr($request->foto, strpos($request->foto, ',') + 1);
                $image = base64_decode($image);
                $extension = strtolower($type[1]);

                if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                    return response()->json(['error' => 'Format gambar tidak didukung.'], 422);
                }

                $filename = 'foto-presensi/' . uniqid() . '.' . $extension;
                Storage::disk('public')->put($filename, $image);
            } else {
                return response()->json(['error' => 'Format gambar tidak valid.'], 422);
            }
        }

        $data = [
            'pegawai_id' => $pegawai_id,
            'role_id' => $request->role_id,
            'shift_id' => $request->shift_id,
            'lokasi_id' => $request->lokasi_id,
            'tanggal' => $tanggal,
        ];

        if ($user->usertype === 'admin') {
            $data['jam_masuk'] = $request->jam_masuk;
            $data['jam_pulang'] = $request->jam_pulang;
            $data['status'] = $request->status;
        } else {
            $data['jam_masuk'] = $now->toTimeString();
            $data['status'] = $status;
            $data['latitude'] = $request->latitude;
            $data['longitude'] = $request->longitude;
            $data['foto'] = $filename;
        }

        Presensi::create($data);

        return response()->json(['success' => 'Presensi berhasil disimpan.']);
    }
    
    public function edit($id)
    {
        $presensi = Presensi::with(['pegawai', 'role', 'shift', 'lokasi'])->findOrFail($id);

        // Cuma admin yang boleh edit
        if (auth()->user()->usertype !== 'admin') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        return response()->json($presensi);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->usertype !== 'admin') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'role_id' => 'required|exists:roles,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'lokasi_id' => 'required|exists:lokasis,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable',
            'jam_pulang' => 'nullable',
            'status' => 'required|in:hadir,terlambat,izin,alpa',
            'foto' => 'nullable|string',
        ]);

        $presensi = Presensi::findOrFail($id);

        // Simpan ulang foto jika dikirim ulang (optional)
        $filename = $presensi->foto;
        if ($request->filled('foto') && str_starts_with($request->foto, 'data:image/')) {
            if (preg_match('/^data:image\/(\w+);base64,/', $request->foto, $type)) {
                $image = substr($request->foto, strpos($request->foto, ',') + 1);
                $image = base64_decode($image);
                $extension = strtolower($type[1]);

                if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                    return response()->json(['error' => 'Format gambar tidak didukung.'], 422);
                }

                $filename = 'foto-presensi/' . uniqid() . '.' . $extension;
                \Storage::disk('public')->put($filename, $image);
            }
        }

        $presensi->update([
            'pegawai_id' => $request->pegawai_id,
            'role_id' => $request->role_id,
            'shift_id' => $request->shift_id,
            'lokasi_id' => $request->lokasi_id,
            'tanggal' => $request->tanggal,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
            'status' => $request->status,
            'foto' => $filename,
        ]);

        return response()->json(['success' => 'Data presensi berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $presensi = Presensi::findOrFail($id);

        if (auth()->user()->usertype !== 'admin') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $presensi->delete();

        return response()->json(['success' => 'Data presensi berhasil dihapus.']);
    }

    public function pulang(Request $request, $id)
    {
        $presensi = Presensi::findOrFail($id);
        $user = auth()->user();

        if ($user->usertype !== 'admin' && $user->pegawai->id !== $presensi->pegawai_id) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        if ($presensi->jam_pulang !== null) {
            return response()->json(['error' => 'Sudah presensi pulang.'], 422);
        }

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'foto' => 'required|string',
        ]);

        $lokasi = Lokasi::find($presensi->lokasi_id);
        if (!$lokasi || $lokasi->status !== 'aktif') {
            return response()->json(['error' => 'Lokasi tidak aktif.'], 422);
        }

        $jarak = $this->hitungJarak($request->latitude, $request->longitude, $lokasi->latitude, $lokasi->longitude);
        if ($jarak > $lokasi->radius) {
            return response()->json(['error' => 'Anda di luar radius lokasi.'], 422);
        }

        // Verifikasi wajah (Flask)
        $pegawai = $presensi->pegawai; $response = Http::post('http://127.0.0.1:5000/recognize', [
            'nama_pegawai' => $pegawai->nama,
            'foto' => $request->foto,
        ]);
       

        $hasil = $response->json();
        if (!$response->successful() || empty($hasil['is_matched']) || $hasil['is_matched'] !== true) {
            return response()->json([
                'error' => $hasil['message'] ?? 'Wajah tidak cocok. Presensi pulang gagal.'
            ], 422);
        }

        $now = Carbon::now();
        $jamPulang = $this->getJamPulang($presensi->role_id, $presensi->shift_id);

        if ($now->lt($jamPulang)) {
            return response()->json(['error' => 'Belum waktunya pulang.'], 422);
        }

        if ($now->gt($jamPulang->copy()->addHours(2))) {
            return response()->json(['error' => 'Presensi pulang sudah ditutup.'], 422);
        }

        // Simpan foto
        $filename = null;
        if (preg_match('/^data:image\/(\w+);base64,/', $request->foto, $type)) {
            $image = substr($request->foto, strpos($request->foto, ',') + 1);
            $image = base64_decode($image);
            $extension = strtolower($type[1]);

            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                return response()->json(['error' => 'Format gambar tidak didukung.'], 422);
            }

            $filename = 'foto-presensi/' . uniqid() . '.' . $extension;
            Storage::disk('public')->put($filename, $image);
        } else {
            return response()->json(['error' => 'Format gambar tidak valid.'], 422);
        }

        // Update presensi
        $presensi->update([
            'jam_pulang' => $now->toTimeString(),
            'latitude_pulang' => $request->latitude,
            'longitude_pulang' => $request->longitude,
            'foto_pulang' => $filename,
        ]);

        return response()->json(['success' => 'Presensi pulang berhasil.']);
    }


    private function getJamMasuk($roleId, $shiftId = null)
    {
       $role = Role::findOrFail($roleId);

    if (in_array(strtolower($role->nama_role), ['satpam', 'sarpras']) && $shiftId) {
        return Carbon::createFromTimeString(Shift::findOrFail($shiftId)->jam_masuk);
    }

    if ($role->jam_masuk) {
        return Carbon::createFromTimeString($role->jam_masuk);
    }

    // fallback default
    return Carbon::createFromTimeString('07:30:00');
    }

    private function getJamPulang($roleId, $shiftId = null)
    {
        $role = Role::findOrFail($roleId);

        if (in_array(strtolower($role->nama_role), ['satpam', 'sarpras']) && $shiftId) {
            return Carbon::createFromTimeString(Shift::findOrFail($shiftId)->jam_pulang);
        }

        if ($role->jam_pulang) {
            return Carbon::createFromTimeString($role->jam_pulang);
        }

        // fallback default
        return Carbon::createFromTimeString('1:00:00');
    }


    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    public function export($role = null)
    {
        if (auth()->user()->usertype !== 'admin') {
            abort(403, '❌ Kamu tidak punya izin akses!');
        }

        return Excel::download(new PresensiExport($role), 'presensi-' . ($role ?? 'semua') . '.xlsx');
    }

}
