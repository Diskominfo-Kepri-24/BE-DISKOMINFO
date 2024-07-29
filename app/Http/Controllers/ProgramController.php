<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{

    public function getPrograms()
    {

        $program = Program::query()->get();

        return response()->json([
            "program" => $program
        ]);
    }

    public function getProgram(Program $program)
    {

        $program = Program::query()->where('id', $program->id)->firstOrFail();

        return response()->json([
            "program" => $program
        ]);
    }

    public function addProgram(Request $request)
    {

        $request->validate([
            "title" => "required",
            "description" => "required",
            "category" => "required",
            "image" => "required|mimes:png,jpg,jpeg|max:4096"
        ]);

        $gambar = $request->file('image');
        $gambarName = now() . "_" . "program" . "_" . $gambar->hashName();
        $gambar->storeAs("public/program", $gambarName);

        $data = Program::query()->create([
            "title" => $request->title,
            "description" => $request->description,
            "image" => "storage/program/" . $gambarName,
            "category" => $request->category,
        ]);


        return response()->json([
            "data" => $data,
            "status" => "Data berhasil ditambahkan"
        ]);
    }

    public function updateProgram(Request $request, Program $program)
    {

        $rules = [
            "title" => "required",
            "description" => "required",
            "category" => "required"
        ];

        $request->validate($rules);

        if ($request->hasFile("image")) {

            Storage::disk('public')->delete(substr($program->image, 8));

            $request->validate([
                "image" => "mimes:png,jpg,jpeg|max:4096"
            ]);

            $gambar = $request->file('image');
            $gambarName = now() . "_" . "program" . "_" . $gambar->hashName();
            $gambar->storeAs("public/program", $gambarName);
            
            $program->image = "storage/program/" . $gambarName;

        }

        $program->title = $request->title;
        $program->description = $request->description;
        $program->category = $request->category;

        $program->save();

        return response()->json([
            "data" => $program,
            "status" => "Data program berhasil diubah"
        ]);

    }

    public function deleteProgram(Program $program)
    {

        Storage::disk('public')->delete(substr($program->image, 8));

        $program->delete();

        return response()->json([
            "message" => "Data program berhasil dihapus"
        ]);

    }
}
