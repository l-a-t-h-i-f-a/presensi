<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return view('shift.index', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_shift' => 'required|string|max:255',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_masuk',
        ]);

        Shift::create($request->all());

        return response()->json(['success' => 'Shift berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $shift = Shift::findOrFail($id);
        return response()->json($shift);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_shift' => 'required',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_masuk',
        ]);

        $shift = Shift::findOrFail($id);

        $data = [
            'nama_shift' => $request->nama_shift,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
        ];

        $shift->update($data);

        return response()->json(['success' => 'Data shift berhasil diperbarui!', 'shift' => $shift]);
    }

    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();

        return response()->json(['success' => 'Shift berhasil dihapus!']);
    }
}
