<?php

namespace App\Exports;

use App\Models\Presensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PresensiExport implements FromCollection, WithHeadings
{
    protected $role;

    public function __construct($role = null)
    {
        $this->role = $role;
    }

    public function collection()
    {
        $query = Presensi::with('pegawai.role');

        if ($this->role) {
            $query->whereHas('pegawai.role', function ($q) {
                $q->where('nama', $this->role);
            });
        }

        return $query->get()->map(function ($item) {
            return [
                'Nama Pegawai' => $item->pegawai->nama,
                'Role' => $item->pegawai->role->nama ?? '-',
                'Tanggal' => $item->tanggal,
                'Jam Masuk' => $item->jam_masuk,
                'Jam Pulang' => $item->jam_pulang,
                'status' => $item->status->value,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama Pegawai', 'Role', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status'];
    }
}

