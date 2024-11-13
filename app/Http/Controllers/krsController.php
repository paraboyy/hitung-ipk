<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Krs;
use Illuminate\Support\Facades\Validator;

class krsController extends Controller
{
    public function store(){
        // Validasi input
        $validator = Validator::make($request->all(), [
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'semester' => 'required|integer|min:1',
            'nilai' => 'required|numeric|min:0|max:4',
            'huruf' => 'required|in:A,B+,B,C+,C,D,E',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Data tidak valid',
                'message' => $validator->errors(),
            ], 400);
        }

        // Simpan data KRS
        $krs = Krs::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'matakuliah_id' => $request->matakuliah_id,
            'semester' => $request->semester,
            'nilai' => $request->nilai,
            'huruf' => $request->huruf,
        ]);

        return response()->json([
            'message' => 'Data KRS berhasil disimpan',
            'data' => $krs,
        ], 201);
    }
}
