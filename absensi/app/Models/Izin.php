<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Izin extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'role_id',
        'jenis_izin',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'status'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
