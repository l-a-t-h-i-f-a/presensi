<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    /**
     * fillable
     *
     * @var array
     */
    use HasFactory;
    protected $table = 'lokasis';
    protected $fillable = [
        'nama_lokasi',
        'latitude',
        'longitude',
        'radius',
        'status',
    ];

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }
}
