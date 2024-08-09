<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::all();
        return response()->json($kegiatan);
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'catatan' => 'required|string',
        ]);

        // kalo kegiatan mau 1 kali aja diisi pake bawah ini buat validasi
        // $cekKegiatan = Kegiatan::query()->where("tanggal", $request->tanggal)->where('user_id', Auth::user()->id)->first();
        // if(!is_null($cekKegiatan)){
        //     return response()->json([
        //         "status" => "Kegiatan hari ini sudah dikirim"
        //     ], 400);
        // }

        $kegiatan = Kegiatan::create([
            // 'user_id' => $request->user_id,
            'user_id' => Auth::user()->id,  // diambil dari id user yang sedang login
            'tanggal' => $request->tanggal,
            'catatan' => $request->catatan,
            'status' => 'Menunggu',
        ]);

        return response()->json($kegiatan, 201);
    }

    public function show($id)
    {
        // $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan = Kegiatan::where('user_id', Auth::user()->id)->get();
        return response()->json($kegiatan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string',
        ]);

        // $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan = Kegiatan::findOrFail(Auth::user()->id);
        $kegiatan->update([
            'catatan' => $request->catatan,
        ]);

        return response()->json($kegiatan);
    }

    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->delete();

        return response()->json(null, 204);
    }

    public function confirm($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update(['status' => 'Dikonfirmasi']);

        return response()->json($kegiatan);
    }

    public function reject($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update(['status' => 'Ditolak']);

        return response()->json($kegiatan);
    }
}
