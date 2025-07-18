<?php

namespace App\Exports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PegawaiExport implements FromCollection, WithHeadings
{
    protected $role;

    public function __construct($role = null)
    {
        $this->role = $role;
    }

    public function collection()
    {
        $query = Pegawai::with(['role', 'user']);

        if ($this->role) {
            $query->whereHas('role', function ($q) {
                $q->where('nama', $this->role);
            });
        }

        return $query->get()->map(function ($pegawai) {
            return [
                'Nama' => $pegawai->nama,
                'Email' => $pegawai->user->email ?? '-',
                'Role' => $pegawai->role->nama ?? '-',
                'NIDN' => $pegawai->nidn ?? '-',
            ];
        });
    }


    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Role',
            'NIDN',
        ];
    }
}
