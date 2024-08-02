<?php
namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function getAllLaporan()
    {
        $laporan = Laporan::query()->get();
        return response()->json([
            "laporan" => $laporan,
        ]);
    }


    public function getLaporan(){
        $user = Auth::user();
        $laporan = Laporan::query()->where("user_id", $user->id)->first();

        return response()->json([
            "laporan" => $laporan
        ]);

    }

    public function updateLaporan(Request $request){

        $laporan = Auth::user()->laporan;

        $request->validate([
            "link_laporan" => "required",
        ]);


        $laporan->link_laporan = $request->link_laporan;
        $laporan->save();

        return response()->json([
            "laporan" => $laporan,
            "status" => "Data laporan berhasil diubah"
        ]);

    }

    public function deleteLaporan(){

        $laporan = Auth::user()->laporan;

        $laporan->delete();

        return response()->json([
            "status" => "Data laporan berhasil dihapus"
        ]);

    }

    public function addLaporan(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            "link_laporan" => "required",
        ]);

        $data =  Laporan::query()->create([
            "user_id" => $user->id,
            "link_laporan" => $request->link_laporan,
        ]);

        return response()->json([
            "laporan" => $data,
            "status" => "Data Laporan berhasil ditambah"
        ]);
    }
}
