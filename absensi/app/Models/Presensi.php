<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PresensiStatus;

class Presensi extends Model
{
/**
     * fillable
     *
     * @var array
     */
    use HasFactory;
    protected $table = 'presensis';
    protected $fillable = [
        'pegawai_id',
        'role_id',
        'shift_id',
        'lokasi_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'foto',
        'foto_pulang',
        'status',
    ];

    protected $casts = [
        'status' => \App\Enums\PresensiStatus::class,
    ];
    
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }
}
