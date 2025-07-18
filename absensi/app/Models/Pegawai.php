<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Pegawai extends Model
{
    /**
     * fillable
     *
     * @var array
     */
    use HasFactory;
    protected $table = 'pegawais';
    protected $fillable = [
        'user_id',
        'foto',
        'nama',
        'role_id',
        'nidn',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function izins()
    {
        return $this->hasMany(Izin::class);
    }
}
