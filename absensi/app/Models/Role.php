<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * fillable
     *
     * @var array
     */
    use HasFactory;
    protected $table = 'roles';
    protected $fillable = [
        'nama',
    ];
    
    public function pegawais()
    {
        return $this->hasMany(Pegawai::class);
    }

    public function izins()
    {
        return $this->hasMany(Izin::class);
    }
}
