<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    protected $fillable = ['mahasiswa_id', 'mata_kuliah_id', 'nilai', 'semester', 'huruf'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }
}
