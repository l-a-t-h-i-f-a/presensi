<?php

namespace App\Http\Controllers;

use App\Models\{Pegawai, Role, User,};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exports\PegawaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelType;
use Yajra\DataTables\Facades\DataTables;

class PegawaiController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->usertype === 'admin') {
            $pegawais = Pegawai::with('user', 'role')->get();
            $roles = Role::all();
           $users = User::all();
            return view('pegawai.index', compact('pegawais', 'roles', 'users'));
        }

        $pegawai = Pegawai::with('user', 'role')->where('user_id', $user->id)->first();
        return view('pegawai.user_view', compact('pegawai'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id|unique:pegawais,user_id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nama' => 'required',
            'role_id' => 'required|exists:roles,id',
            'nidn' => 'required|unique:pegawais,nidn',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-pegawai', 'public');
        }

        $validatedData['foto'] = $fotoPath;
        //$validatedData['user_id'] = Auth::id();

        Pegawai::create($validatedData);

        return redirect()->route('pegawai.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function edit(Pegawai $pegawai)
    {
        return response()->json($pegawai);
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id|unique:pegawais,user_id,' . $pegawai->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nama' => 'required',
            'role_id' => 'required|exists:roles,id',
            'nidn' => 'required|unique:pegawais,nidn,' . $pegawai->id,
        ]);

        $data = [
            'nama' => $request->nama,
            'role_id' => $request->role_id,
            'nidn' => $request->nidn,
        ];

        if ($request->hasFile('foto')) {
            if ($pegawai->foto) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto-pegawai', 'public');
        }

        $pegawai->update($data);

        return response()->json(['success' => 'Data pegawai berhasil diperbarui!', 'pegawai' => $pegawai]);
    }

    public function destroy(Pegawai $pegawai)
    {
        if ($pegawai->foto) {
            Storage::disk('public')->delete($pegawai->foto);
        }
        $pegawai->delete();

        return response()->json(['success' => 'Pegawai berhasil dihapus.']);
    }


    public function export($role = null)
    {
        return Excel::download(new PegawaiExport($role), 'pegawai-' . ($role ?? 'semua') . '.xlsx');
    }

}
