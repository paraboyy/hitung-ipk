<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $fillable = ['name', 'nim'];

    public function krs()
    {
        return $this->hasMany(Krs::class);
    }

    // Relasi dengan IPK
    public function ipk()
    {
        return $this->hasOne(Ipk::class);
    }
}
