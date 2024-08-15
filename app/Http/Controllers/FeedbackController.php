<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        
        $feedback = Feedback::all();
        return response()->json($feedback);
        
    }
    public function getFeedback()
    {
        $user = Auth::user();
        $feedback = Feedback::where("user_id", $user->id)->first();

        if (!$feedback) {
            return response()->json(['message' => 'Feedback tidak ditemukan.'], 404);
        }

        return response()->json($feedback);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role != 'magang') {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk menambahkan feedback.'], 403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string|max:1000',
        ]);

        $feedback = Feedback::create([
            'user_id' => $user->id,
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return response()->json($feedback, 201);
    }

    public function show($id)
    {
        $user = Auth::user();
        $feedback = Feedback::findOrFail($id);

        return response()->json($feedback);
        
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $feedback = Feedback::findOrFail($id);

        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $feedback->update($validatedData);

        return response()->json($feedback);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $feedback = Feedback::findOrFail($id);

        $feedback->delete();

        return response()->json(null, 204);
    }
}
