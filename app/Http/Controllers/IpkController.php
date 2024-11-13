<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Krs;
use App\Models\Matakuliah;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class IpkController extends Controller
{

    //UJI POST API
    // public function hitungIpk(Request $request)
    // {
    //     // Ambil NIM dari inputan
    //     $nim = $request->query('nim');
    //     $mahasiswa = Mahasiswa::where('nim', $nim)->first();

    //     if (!$mahasiswa) {
    //         return response()->json(['error' => 'Mahasiswa tidak ditemukan',
    //                                 'nim' => $nim], 404);
    //     }

    //     // Hitung IPS per semester
    //     $ipsPerSemester = $this->hitungIpsPerSemester($mahasiswa->id);

    //     // Hitung IPK berdasarkan IPS per semester
    //     $ipk = $this->hitungIpkIPS($ipsPerSemester);

    //     // Simpan IPK di database
    //     $mahasiswa->ipk()->create(['ipk' => $ipk]);

    //     return response()->json(['nim' => $nim, 'ipk' => $ipk]);
    // }

    //HITUNG IPS
    public function hitungIpsPerSemester($mahasiswaId)
    {
        // Ambil KRS mahasiswa per semester, termasuk matakuliah yang terkait
        $krsPerSemester = DB::table('krs')
                            ->join('matakuliah', 'krs.matakuliah_id', '=', 'matakuliah.id')
                            ->select('krs.semester', 'krs.nilai', 'krs.huruf', 'matakuliah.name as nama_matakuliah', 'matakuliah.sks')
                            ->where('krs.mahasiswa_id', $mahasiswaId)
                            ->orderBy('krs.semester')
                            ->get()
                            ->groupBy('semester');  // Mengelompokkan berdasarkan semester

        $ips = [];
        $daftarMatakuliah = [];
        $bobotNilai = [
            'A' => 4.0,
            'B+' => 3.5,
            'B' => 3.0,
            'C+' => 2.5,
            'C' => 2.0,
            'D' => 1.0,
            'E' => 0.0,
        ];

        // Proses setiap semester
        foreach ($krsPerSemester as $semester => $krsItems) {
            // $nilaiTotal = 0;
            $sksTotal = 0;
            $nilaiIps = 0;
            $matakuliahList = [];

            // Proses setiap KRS pada semester tersebut
            foreach ($krsItems as $item) {
                // Pastikan nilai valid dan matakuliah memiliki SKS
                if (isset($item->nilai) && $item->nilai !== null && $item->sks > 0) {
                    // Ambil bobot nilai berdasarkan huruf
                    $bobot = isset($bobotNilai[$item->huruf]) ? $bobotNilai[$item->huruf] : 0;
                    
                    // Ambil Matakuliah
                    $matakuliahList[] = [
                        'nama_matakuliah' => $item->nama_matakuliah,
                        'sks' => $item->sks,
                        'nilai' => $item->nilai,
                    ];

                    // Total nilai dikalikan SKS
                    // $nilaiTotal += $item->nilai * $item->sks;
                    $nilaiIps += $bobot * $item->sks;
                    $sksTotal += $item->sks;  // Total SKS
                }
            }

            // Hitung IPS per semester, pastikan SKS total tidak 0
            $ips[$semester] = $sksTotal > 0 ? $nilaiIps / $sksTotal : 0;
            $daftarMatakuliah[$semester] = $matakuliahList;
        }

        return [
            'ips' => $ips,
            'matakuiah' => $daftarMatakuliah
        ];
    }

    //HITUNG IPK
    private function hitungIpkIPS($ipsPerSemester)
    {
        // Hitung IPK berdasarkan IPS per semester
        $totalIps = array_sum($ipsPerSemester);
        $jumlahSemester = count($ipsPerSemester);

        return $jumlahSemester > 0 ? $totalIps / $jumlahSemester : 0;
    }

     // Fungsi untuk format waktu (misalnya dalam detik)
     private function getFormattedTime($seconds)
     {
         $minutes = floor($seconds / 60);
         $seconds = round($seconds % 60, 2);
 
         return "{$minutes} menit {$seconds} detik";
     }

    //SIMULASI GET DATA HITUNG IPK
    public function getIpk($nim)
    {

        // Catat waktu mulai
        $startTime = microtime(true);

        // Cari mahasiswa berdasarkan NIM di database
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if ($mahasiswa) {
            $ipsPerSemester = $this->hitungIpsPerSemester($mahasiswa->id);
            $ipk = $this->hitungIpkIPS($ipsPerSemester['ips']);

            // Cek apakah mahasiswa sudah memiliki IPK
            $existingIpk = $mahasiswa->ipk()->latest()->first();

            if ($existingIpk) {
                // Jika IPK yang baru sama dengan yang sudah ada, tidak melakukan update
                if ($existingIpk->ipk == $ipk) {
                    return response()->json([
                        'nim' => $mahasiswa->nim,
                        'nama' => $mahasiswa->name,	
                        'ips' => $ipsPerSemester,
                        'ipk' => $ipk,
                        'waktu respon' => $this->getFormattedTime(microtime(true) - $startTime),
                        'message' => 'IPK sudah diperbarui sebelumnya dan tidak perlu diupdate.',
                    ]);
                }
        
                // Jika IPK berbeda, update nilai IPK yang terakhir
                $existingIpk->update(['ipk' => $ipk]);
        
                return response()->json([
                    'nim' => $mahasiswa->nim,
                    'nama' => $mahasiswa->name,	
                    'ips' => $ipsPerSemester,
                    'ipk' => $ipk,
                    'waktu respon' => $this->getFormattedTime(microtime(true) - $startTime),
                    'message' => 'IPK berhasil diupdate.',
                ]);
            } else {
                // Jika IPK belum ada, simpan IPK baru
                $mahasiswa->ipk()->create(['ipk' => $ipk]);
        
                return response()->json([
                    'nim' => $mahasiswa->nim,
                    'nama' => $mahasiswa->name,	
                    'ips' => $ipsPerSemester,
                    'ipk' => $ipk,
                    'waktu respon' => $this->getFormattedTime(microtime(true) - $startTime),
                    'message' => 'IPK berhasil disimpan.',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'NIM tidak ditemukan'
            ], 404);
        }
    }
}
