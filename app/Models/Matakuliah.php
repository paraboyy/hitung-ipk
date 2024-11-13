<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    protected $table = 'matakuliah';
    protected $fillable = ['code', 'name', 'sks'];

    public function krs()
    {
        return $this->hasMany(Krs::class);
    }
}
