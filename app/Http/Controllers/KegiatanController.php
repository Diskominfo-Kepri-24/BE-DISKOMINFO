<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

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
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'catatan' => 'required|string',
        ]);

        $kegiatan = Kegiatan::create([
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
            'catatan' => $request->catatan,
            'status' => 'Menunggu',
        ]);

        return response()->json($kegiatan, 201);
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return response()->json($kegiatan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
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
