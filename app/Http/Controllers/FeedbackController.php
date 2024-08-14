<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    // Mengambil semua feedback berdasarkan izin pengguna
    public function index()
{
    $user = Auth::user();

    // Cek apakah user adalah peserta magang, pembimbing, atau admin
    if ($user->role === 'magang') {
        $feedbacks = Feedback::where('user_id', $user->id)->get();
    } elseif ($user->role === 'pembimbing' || $user->role === 'admin') {
        $feedbacks = Feedback::all();
    } else {
        return response()->json(['message' => 'unauthorized'], 403);
    }

    return response()->json($feedbacks);
}


    // Menyimpan feedback baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5', // Batasan min dan max untuk rating
            'feedback' => 'nullable|string|max:1000', // Batasan panjang feedback
        ]);

        $feedback = Feedback::create([
            'user_id' => Auth::id(),
            'rating' => $validatedData['rating'],
            'feedback' => $validatedData['feedback'],
        ]);

        return response()->json($feedback, 201);
    }

    // Menampilkan feedback berdasarkan ID, dengan pengecekan izin akses
    public function show($id)
    {
        $feedback = Feedback::find($id);
        $user = Auth::user();

        // Pastikan pengguna hanya bisa melihat feedback mereka sendiri, kecuali jika mereka adalah pembimbing atau admin
        if (!$feedback || ($feedback->user_id != $user->id && $user->role !== 'pembimbing' && $user->role !== 'admin')) {
            return response()->json(['message' => 'Data feedback tidak ditemukan atau Anda tidak memiliki izin untuk mengaksesnya.'], 404);
        }

        return response()->json($feedback);
    }

    // Memperbarui feedback yang ada
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5', // Batasan min dan max untuk rating
            'feedback' => 'nullable|string|max:1000', // Batasan panjang feedback
        ]);

        // Mendapatkan feedback milik pengguna yang sedang login
        $feedback = Feedback::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $feedback->update([
            'rating' => $validatedData['rating'],
            'feedback' => $validatedData['feedback'],
        ]);

        return response()->json($feedback);
    }

    // Menghapus feedback milik pengguna yang sedang login
    public function destroy($id)
    {
        // Mendapatkan feedback milik pengguna yang sedang login
        $feedback = Feedback::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $feedback->delete();

        return response()->json(null, 204);
    }
}
