<?php

namespace App\Exports;

use App\Models\Izin;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IzinExport implements FromCollection, WithHeadings, WithMapping
{
    protected $role;

    public function __construct($role = null)
    {
        $this->role = $role;
    }

    public function collection()
    {
        $query = Izin::with(['pegawai.role']);

        if ($this->role) {
            $query->whereHas('pegawai.role', function ($q) {
                $q->where('nama', $this->role);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pegawai',
            'Role',
            'Jenis Izin',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Keterangan',
            'Status',
            'Dibuat Pada',
        ];
    }

    public function map($izin): array
    {
        static $number = 1;

        return [
            $number++,
            $izin->pegawai->nama ?? '-',
            $izin->pegawai->role->nama ?? '-',
            $izin->jenis_izin,
            $izin->tanggal_mulai,
            $izin->tanggal_selesai,
            $izin->keterangan ?? '-',
            ucfirst($izin->status),
            $izin->created_at ? $izin->created_at->format('Y-m-d H:i') : '-',
        ];
    }
}
