<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::all();
        return response()->json($feedbacks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer',
            'feedback' => 'nullable|string',
        ]);

        $feedback = Feedback::create([
            'user_id' => Auth::user()->id,
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return response()->json($feedback, 201);
    }

    public function show($id)
    {
        $feedback = Feedback::where('user_id', Auth::user()->id)->get();
        return response()->json($feedback);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer',
            'feedback' => 'nullable|string',
        ]);

        $feedback = Feedback::findOrFail($id);
        $feedback->update([
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return response()->json($feedback);
    }

    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return response()->json(null, 204);
    }
}
