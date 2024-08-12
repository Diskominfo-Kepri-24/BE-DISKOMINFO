<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function index()
    {
        $penilaians = Penilaian::all();
        return response()->json($penilaians);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nilai' => 'required|integer',
        ]);
    
        $penilaian = Penilaian::create([
            'user_id' => $request->user_id,  // Menggunakan id user dari inputan
            'nilai' => $request->nilai,
        ]);
    
        return response()->json($penilaian, 201);
    }

    public function show($id)
    {
        $penilaian = Penilaian::where('user_id', Auth::user()->id)->get();
        return response()->json($penilaian);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'required|integer',
        ]);

        $penilaian = Penilaian::findOrFail($id);
        $penilaian->update([
            'nilai' => $request->nilai,
        ]);

        return response()->json($penilaian);
    }

    public function destroy($id)
    {
        $penilaian = Penilaian::findOrFail($id);
        $penilaian->delete();

        return response()->json(null, 204);
    }
}