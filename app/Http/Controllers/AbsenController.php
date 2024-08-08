<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Magang;
use App\Models\User;
use App\Models\UserMagang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenController extends Controller
{

    // dipakai di role siswa/mahasiswa buat dapatin data absen merekanya, berdasarkan siapa yang login dari session
    public function getAbsen()
    {

        $userMagang = Auth::user()->magang;

        $absen = Absen::query()->where("id_user_magang", $userMagang->id)
                                ->orderBy('tanggal', 'desc')
                                ->get();

        return response()->json([
            "user" => $absen
        ]);
    }

    // dipakai di role pembimbing buat dapatin semua data magang
    public function getAllAbsenMagang(){

        $userMagang = Magang::query()->join('users', 'users.id', '=', 'magang.id_user')
                                            ->join('absens', 'absens.id_user_magang', '=', 'magang.id')
                                            ->select("users.id as id_user", "absens.id as id_absen", "users.nama as name", "absens.tanggal as tanggal", "absens.hari as hari", "absens.jam_masuk as jam_masuk", "absens.jam_pulang as jam_pulang", "absens.status as status")
                                            ->get();

        return response()->json([
            "user" => $userMagang
        ]);

    }

    // dipakai di role pembimbing buat ke spesifik data usernya berdasarkan id_user
    public function getAbsenMagang($id_user){


        $absen = Absen::query()->join("magang", "magang.id", "=", "absens.id_user_magang")
                                ->join("users", "users.id", "=", "magang.id_user")
                                ->select("absens.id as id_absen", "users.id as id_user", "users.nama as name", "absens.tanggal as tanggal", "absens.hari as hari", "absens.jam_masuk as jam_masuk", "absens.jam_pulang as jam_pulang", "absens.status as status")
                                ->where('users.id', $id_user)
                                ->get();

        return response()->json([
            "absen" => $absen,
        ]);

    }

    // dipakai role mahasiswa/siswa buat nambah jam masuk
    public function tambahJamMasuk(Request $request)
    {

        $userMagang = Auth::user()->magang;

        $absen = Absen::query()->where("tanggal", substr(now("Asia/Jakarta"), 0, 10))->where("id_user_magang", $userMagang->id)->first();
        

        if (!is_null($absen)) {
            return response()->json([
                "message" => "Anda sudah melakukan absen hari ini"
            ], 403);
        }

        $request->validate([
            "tanggal" => "required",
            "jam_masuk" => "required"
        ]);

        Carbon::setLocale("id");
        $hari = Carbon::createFromFormat('Y-m-d', $request->input("tanggal"))->isoFormat("dddd");
        // $tanggal = Carbon::createFromFormat('d-m-Y', $request->input("tanggal"))->format("Y-m-d");

        $jamMasuk = null;
        $jamPulang = null;

        if (!is_null($request->jam_masuk)) {

            $jamMasukArray = array_map('intval', explode(":", $request->jam_masuk));
            if ($jamMasukArray[0] >= 7 && $jamMasukArray[0] <= 8) {
                $jamMasuk = $request->jam_masuk;
            } else {
                return response()->json([
                    "message" => "Absensi hanya dapat dilakukan pada jam yang ditentukan"
                ], 403);
            }
        }


        $absen = Absen::query()->create([
            "id_user_magang" => $userMagang->id,
            "tanggal" => $request->tanggal,
            "hari" => $hari,
            "jam_masuk" => $jamMasuk,
            "jam_pulang" => $jamPulang
        ]);

        return response()->json([
            "absen" => $absen
        ]);
    }

    // dipakai di role mahasiswa/siswa buat nambah jam pulang
    public function tambahJamPulang(Request $request)
    {

        $userMagang = Auth::user()->magang;

        $request->validate([
            "tanggal" => "required",
            "jam_pulang" => "required"
        ]);

        $hari = Carbon::createFromFormat('Y-m-d', $request->input("tanggal"))->isoFormat("dddd");
        // $tanggal = Carbon::createFromFormat('d-m-Y', $request->input("tanggal"))->format("Y-m-d");

        $absen = Absen::query()->where("tanggal", substr(now("Asia/Jakarta"), 0, 10))
                                ->Where("id_user_magang", $userMagang->id)->first();

        if (is_null($absen)) {

            // jika data belum ada alias belum absen jam masuk
            $jamMasuk = null;

            $jamPulangArray = array_map('intval', explode(":", $request->jam_pulang));
            if ($jamPulangArray[0] >= 15 && $jamPulangArray[0] < 16) {

                $absen = Absen::query()->create([
                    "id_user_magang" => $userMagang->id,
                    "tanggal" => $request->tanggal,
                    "hari" => $hari,
                    "jam_masuk" => $jamMasuk,
                    "jam_pulang" => $request->jam_pulang
                ]);

                return response()->json([
                    "absen" => $absen
                ]);
            } else {
                return response()->json([
                    "message" => "Absensi hanya dapat dilakukan pada jam yang ditentukan"
                ], 403);
            }
        } else {

            // jika data sudah ada alias sudah absen jam masuk
            $jamPulangArray = array_map('intval', explode(":", $request->jam_pulang));
            if ($jamPulangArray[0] >= 15 && $jamPulangArray[0] <= 16) {
                $absen->jam_pulang = $request->jam_pulang;
                $absen->save();

                return response()->json([
                    "absen" => $absen
                ]);
            } else {
                return response()->json([
                    "message" => "Absensi hanya dapat dilakukan pada jam yang ditentukan"
                ], 403);
            }
        }
    }

    // dipakai di role pembimbing buat terima absen
    public function terimaAbsen(Absen $absen){

        $absen->status = "dikonfirmasi";
        $absen->save();

        return response()->json([
            "absen" => $absen,
            "status" => "Absen berhasil disetujui" 
        ]);
    }

    // dipakai di role pembimbing buat tolak absen
    public function tolakAbsen(Absen $absen){

        $absen->status = "ditolak";
        $absen->save();

        return response()->json([
            "absen" => $absen,
            "status" => "Absen berhasil ditolak"
        ]);
    }

}
