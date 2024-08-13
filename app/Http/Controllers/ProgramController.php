<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{

    public function getPrograms(Request $request)
    {

        $page = $request->input('page', 1);
        $size = $request->input('size', 8);

        // $program = Program::query()->get();
        $program = new Program();

        $program = $program->where(function (Builder $builder) use ($request) {
            $title = $request->input("title");
            if ($title) {
                $builder->where("title", 'like', "%" . $title . "%");
            }

            $category = $request->input("category");
            if ($category) {
                $builder->where("category", "like", "%" . $category . "%");
            }
        });

        $program = $program->paginate(perPage: $size, page: $page);

        return response()->json([
            "program" => $program
        ]);
    }

    public function getProgram($slug)
    {

        $program = Program::query()->where('slug', $slug)->firstOrFail();
        $program->load('mentors');
        $program->load('skills');

        return response()->json([
            "program" => $program
        ]);
    }

    public function addProgram(Request $request)
    {

        $request->validate([
            "title" => "required",
            "slug" => "required|unique:programs,slug",
            "jadwal" => "required",
            "jam_program_dimulai" => "required",
            "tipe_modul" => "required",
            "tipe_mentoring" => "required",
            "tipe_pembelajaran" => "required",
            "deskripsi_sertifikat" => "required",
            "tipe_program" => "required",
            "link_pendaftaran" => "required",
            "description" => "required",
            // "category" => "required",
            "image" => "required|mimes:png,jpg,jpeg|max:4096"
        ]);

        $gambar = $request->file('image');
        $gambarName = time() . "_" . "program" . "_" . $gambar->hashName();
        $gambar->storeAs("public/program", $gambarName);

        $data = Program::query()->create([
            "title" => $request->title,
            "slug" => $request->slug,
            "jadwal" => $request->jadwal,
            "jam_program_dimulai" => $request->jam_program_dimulai,
            "tipe_modul" => $request->tipe_modul,
            "tipe_mentoring" => $request->tipe_mentoring,
            "tipe_pembelajaran" => $request->tipe_pembelajaran,
            "deskripsi_sertifikat" => $request->deskripsi_sertifikat,
            "tipe_program" => $request->tipe_program,
            "link_pendaftaran" => $request->link_pendaftaran,
            "description" => $request->description,
            "image" => "storage/program/" . $gambarName,
            "category" => $request->category,
        ]);


        return response()->json([
            "data" => $data,
            "status" => "Data berhasil ditambahkan"
        ]);
    }

    public function updateProgram(Request $request, $slug)
    {

        $program = Program::query()->where('slug', $slug)->firstOrFail();

        $rules = [
            "title" => "required",
            "jadwal" => "required",
            "jam_program_dimulai" => "required",
            "tipe_modul" => "required",
            "tipe_mentoring" => "required",
            "tipe_pembelajaran" => "required",
            "deskripsi_sertifikat" => "required",
            "tipe_program" => "required",
            "link_pendaftaran" => "required",
            "description" => "required",
            "category" => "required"
        ];

        if ($request->slug != $program->slug) {
            $rules['slug'] = "required|unique:programs,slug";
            $program->slug = $request->slug;
        }

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
        $program->jadwal = $request->jadwal;
        $program->jam_program_dimulai = $request->jam_program_dimulai;
        $program->tipe_modul = $request->tipe_modul;
        $program->tipe_mentoring = $request->tipe_mentoring;
        $program->tipe_pembelajaran = $request->tipe_pembelajaran;
        $program->deskripsi_sertifikat = $request->deskripsi_sertifikat;
        $program->tipe_program = $request->tipe_program;
        $program->category = $request->category;

        $program->save();

        return response()->json([
            "data" => $program,
            "status" => "Data program berhasil diubah"
        ]);
    }

    public function deleteProgram($slug)
    {

        $program = Program::query()->where('slug', $slug)->firstOrFail();

        Storage::disk('public')->delete(substr($program->image, 8));

        $program->delete();

        return response()->json([
            "message" => "Data program berhasil dihapus"
        ]);
    }
}
