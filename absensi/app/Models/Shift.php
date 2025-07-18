<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    /**
     * fillable
     *
     * @var array
     */
    use HasFactory;
     protected $table = 'shifts';
    protected $fillable = [
        'nama_shift',
        'jam_masuk',
        'jam_pulang',
    ];

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
