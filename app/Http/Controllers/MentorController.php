<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MentorController extends Controller
{

    public function getMentors()
    {

        $mentor = Mentor::query()->get();

        $mentor->load('programs');

        return response()->json([
            "mentor" => $mentor,
        ]);
    }


    public function getMentorById($idMentor)
    {

        $mentor = Mentor::query()->where('id', $idMentor)->firstOrFail();

        $mentor->load('programs');

        return response()->json([
            "mentor" => $mentor,
        ]);
    }

    public function addMentor(Request $request)
    {


        $programIdsArray = explode(',', $request->program_ids);
        
        $request->validate([
            "nama_mentor" => "required",
            "deskripsi_mentor" => "required",
            "foto_mentor" => "required|mimes:png,jpg,jpeg|max:4096",
            "link_linkedin" => "nullable|url",
            "program_ids" => [
                'required',
                function($attribute, $value, $fail) use ($programIdsArray){
                    foreach ($programIdsArray as $id) {
                        if (!Program::find($id)) {
                            $fail("The program with ID $id does not exist.");
                        }
                    }
                }
            ]
        ]);

        $foto_mentor = $request->file('foto_mentor');
        $foto_mentorName = time() . "_" . "mentor" . "_" . $foto_mentor->hashName();
        $foto_mentor->storeAs("public/mentor", $foto_mentorName);


        $mentor = Mentor::query()->create([
            "nama_mentor" => $request->nama_mentor,
            "deskripsi_mentor" => $request->deskripsi_mentor,
            "foto_mentor" => "storage/mentor/" . $foto_mentorName,
            "link_linkedin" => $request->link_linkedin
        ]);

        $mentor->programs()->sync($programIdsArray);

        $mentor->load('programs');

        return response()->json([
            "mentor" => $mentor,
            "status" => "Berhasil ditambahkan",
        ]);
    }

    public function updateMentor(Request $request, $idMentor)
    {

        $mentor = Mentor::query()->where('id', $idMentor)->firstOrFail();

        $request->validate([
            "nama_mentor" => "required",
            "deskripsi_mentor" => "required",
            "link_linkedin" => "nullable|url"
        ]);

        if ($request->hasFile("foto_mentor")) {

            $request->validate([
                "foto_mentor" => "mimes:png,jpg,jpeg|max:4096",
            ]);

            Storage::disk('public')->delete(substr($mentor->foto_mentor, 8));

            $foto_mentor = $request->file('foto_mentor');
            $foto_mentorName = time() . "_" . "mentor" . "_" . $foto_mentor->hashName();
            $foto_mentor->storeAs("public/mentor", $foto_mentorName);

            $mentor->foto_mentor = "storage/mentor/" . $foto_mentorName;
        }

        $mentor->nama_mentor = $request->nama_mentor;
        $mentor->deskripsi_mentor = $request->deskripsi_mentor;
        $mentor->link_linkedin = $request->link_linkedin;
        $mentor->save();

        return response()->json([
            "mentor" => $mentor,
            "status" => "Data mentor " . $mentor->nama_mentor . " Berhasil diubah"
        ]);
    }

    public function deleteMentor($idMentor)
    {
        $mentor = Mentor::query()->where('id', $idMentor)->firstOrFail();
        $namaMentor = $mentor->nama_mentor;

        Storage::disk('public')->delete(substr($mentor->foto_mentor, 8));

        $isSuccess = $mentor->delete();

        return response()->json([
            "success" => $isSuccess,
            "status" => "Data mentor " . $namaMentor . " Berhasil dihapus",
        ]);
    }
}
