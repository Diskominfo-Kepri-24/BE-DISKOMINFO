<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function getAllLaporan()
    {
        $laporan = Laporan::query()->get();

        return response()->json([
            "laporan" => $laporan,
        ]);
    }

    public function addLaporan(Request $request)
    {
        $request->validate([
            "user_id" => "required|exists:users,id",
            "link_laporan" => "required",
        ]);

        $data =  Laporan::query()->create([
            "user_id" => $request->user_id,
            "link_laporan" => $request->link_laporan,
        ]);

        return response()->json([
            "laporan" => $data,
            "status" => "Data Laporan berhasil ditambah"
        ]);
    }
}