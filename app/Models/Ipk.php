<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ipk extends Model
{
    protected $fillable = ['mahasiswa_id', 'ipk'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
