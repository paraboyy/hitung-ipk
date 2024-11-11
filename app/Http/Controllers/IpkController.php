<?php

namespace App\Http\Controllers;

use App\Model\Mahasiswa;
use App\Model\Krs;
use App\Model\Matakuliah;

use Illuminate\Http\Request;

class IpkController extends Controller
{
    public function hitungIpk(Request $request)
    {
        // Ambil NIM dari inputan
        $nim = $request->input('nim');
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if (!$mahasiswa) {
            return response()->json(['error' => 'Mahasiswa tidak ditemukan'], 404);
        }

        // Hitung IPS per semester
        $ipsPerSemester = $this->hitungIpsPerSemester($mahasiswa->id);

        // Hitung IPK berdasarkan IPS per semester
        $ipk = $this->hitungIpkIPS($ipsPerSemester);

        // Simpan IPK di database
        $mahasiswa->ipk()->create(['ipk' => $ipk]);

        return response()->json(['nim' => $nim, 'ipk' => $ipk]);
    }

    private function hitungIpsPerSemester($mahasiswaId)
    {
        // Ambil KRS mahasiswa per semester
        $krsPerSemester = Krs::where('mahasiswa_id', $mahasiswaId)
                            ->groupBy('semester')
                            ->get();

        $ips = [];
        
        foreach ($krsPerSemester as $semester) {
            $nilaiTotal = 0;
            $sksTotal = 0;

            // Ambil KRS pada semester tersebut
            $krs = Krs::where('mahasiswa_id', $mahasiswaId)
                        ->where('semester', $semester->semester)
                        ->get();

            foreach ($krs as $item) {
                $matakuliah = Matakuliah::find($item->matakuliah_id);
                $nilaiTotal += $item->nilai * $matakuliah->sks;
                $sksTotal += $matakuliah->sks;
            }

            $ips[$semester->semester] = $sksTotal > 0 ? $nilaiTotal / $sksTotal : 0;
        }

        return $ips;
    }

    private function hitungIpkIPS($ipsPerSemester)
    {
        // Hitung IPK berdasarkan IPS per semester
        $totalIps = array_sum($ipsPerSemester);
        $jumlahSemester = count($ipsPerSemester);

        return $jumlahSemester > 0 ? $totalIps / $jumlahSemester : 0;
    }

    //SIMULASI GET DATA
    public function getIpk($nim){
        // Simulasi data IPK berdasarkan NIM
        $dataIpk = [
            '2105551050' => 3.5,
            '2105551051' => 3.7,
            '2105551052' => 3.9,
        ];

        // Cek apakah NIM ada dalam data
        if (array_key_exists($nim, $dataIpk)) {
            return response()->json([
                'nim' => $nim,
                'ipk' => $dataIpk[$nim]
            ]);
        } else {
            return response()->json([
                'message' => 'NIM tidak ditemukan'
            ], 404);
        }
    } 
}
