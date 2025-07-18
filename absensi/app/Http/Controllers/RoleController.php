<?php

namespace App\Http\Controllers;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\PegawaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelType;

class RoleController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = Role::all();
            return response()->json($data);
        }

        $roles = Role::all();
        return view('role.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        Role::create([
            'nama' => $request->nama,
        ]);

        return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    public function edit($id)
    {
        $roles = Role::findOrFail($id);
        return response()->json($roles);
    }

   public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $role = Role::find($id);
        $role->update([
            'nama' => $request->nama,
        ]);

        return response()->json(['success' => 'Data berhasil diupdate.']);
    }

    public function destroy($id)
    {
        $roles = Role::findOrFail($id);
        $roles->delete();

        return response()->json(['success' => 'Data role berhasil dihapus!']);
    }
}
