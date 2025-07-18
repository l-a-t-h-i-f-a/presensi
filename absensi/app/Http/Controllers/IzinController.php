<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use App\Models\Pegawai;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Exports\IzinExport;
use Maatwebsite\Excel\Facades\Excel;

class IzinController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->usertype === 'admin') {
            $izins = Izin::with(['pegawai.role', 'role'])->get();
            $roles = Role::all(); // untuk dropdown export
        } else {
            $pegawai = $user->pegawai;
            $izins = Izin::with(['pegawai.role', 'role'])
                        ->where('pegawai_id', $pegawai->id)
                        ->get();
            $roles = null;
        }

        return view('izin.index', compact('izins', 'user', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'nullable|exists:pegawais,id',
            'role_id' => 'nullable|exists:roles,id',
            'jenis_izin' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
            'status' => 'nullable|in:pending,disetujui,ditolak',
        ]);

        $user = auth()->user();
        $pegawai_id = $user->usertype === 'admin' ? $request->pegawai_id : $user->pegawai->id;
        $role_id = $user->usertype === 'admin' ? $request->role_id : $user->pegawai->role_id;

        Izin::create([
            'pegawai_id' => $pegawai_id,
            'role_id' => $role_id,
            'jenis_izin' => $request->jenis_izin,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'status' => $request->status ?? 'pending',
        ]);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $izin = Izin::with(['pegawai.role', 'role'])->findOrFail($id);
        return response()->json($izin);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pegawai_id' => 'nullable|exists:pegawais,id',
            'role_id' => 'nullable|exists:roles,id',
            'jenis_izin' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
            'status' => 'nullable|in:pending,disetujui,ditolak',
        ]);

        $izin = Izin::findOrFail($id);

        $user = auth()->user();
        $pegawai_id = $user->usertype === 'admin' ? $request->pegawai_id : $user->pegawai->id;
        $role_id = $user->usertype === 'admin' ? $request->role_id : $user->pegawai->role_id;

        $izin->update([
            'pegawai_id' => $pegawai_id,
            'role_id' => $role_id,
            'jenis_izin' => $request->jenis_izin,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'status' => $request->status ?? 'pending',
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $izin = Izin::findOrFail($id);
        $izin->delete();

        return response()->json(['success' => true]);
    }

    public function export($role = null)
    {
        if (auth()->user()->usertype !== 'admin') {
            abort(403, '❌ Kamu tidak punya izin akses!');
        }

        return Excel::download(new IzinExport($role), 'izin-' . ($role ?? 'semua') . '.xlsx');
    }
}
