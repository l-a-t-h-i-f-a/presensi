<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasis = Lokasi::all();
        return view('lokasi.index', compact('lokasis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:1',
        ]);

        Lokasi::create([
            'nama_lokasi' => $request->nama_lokasi,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'radius'       => $request->radius,
            'status'       => 'nonaktif' // default nonaktif
        ]);

        return response()->json(['success' => 'Lokasi berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        return response()->json($lokasi);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:1',
        ]);

        $lokasi = Lokasi::findOrFail($id);
        $lokasi->update([
            'nama_lokasi' => $request->nama_lokasi,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'radius'      => $request->radius,
        ]);

        return response()->json(['success' => 'Lokasi berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->delete();

        return response()->json(['success' => 'Lokasi berhasil dihapus!']);
    }

    public function aktifkan($id)
    {
        $lokasi = Lokasi::find($id);
        if (!$lokasi) {
            return response()->json(['error' => 'Lokasi tidak ditemukan'], 404);
        }

        $lokasi->status = 'aktif';
        $lokasi->save();

        return response()->json(['success' => 'Lokasi berhasil diaktifkan']);
    }

    public function nonaktifkan($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->status = 'nonaktif';
        $lokasi->save();

        return response()->json(['success' => 'Lokasi berhasil dinonaktifkan.']);
    }
}
