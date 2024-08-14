<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    
    public function getSkillByIdProgram($idProgram){

        $skill = Skill::query()->where('id_program', $idProgram)->get();
        
        return response()->json([
            "skill" => $skill
        ]);
    }

    public function getSkillByIdSkill($idSkill){
        return response()->json([
            "skill" => Skill::query()->where('id', $idSkill)->first(),
        ]);
    }

    public function addSkillByIdProgram(Request $request, $idProgram){

        $request->validate([
            "title" => "required",
            "description" => "required",
        ]);

        // id_program

        $skill = Skill::query()->create([
            "title" => $request->title,
            "description" => $request->description,
            "id_program" => $idProgram
        ]);

        return response()->json([
            "skill" => $skill,
            "status" => "Data skill berhasil ditambahkan"
        ]);
    }


    public function updateSkillByIdSkill(Request $request, $idSkill){

        $skill = Skill::query()->where('id', $idSkill)->firstOrFail();

        $request->validate([
            "title" => "required",
            "description" => "required"
        ]);

        $skill->title = $request->title;
        $skill->description = $request->description;
        $skill->save();

        return response()->json([
            "skill" => $skill,
            "status" => "Data skill berhasil diubah"
        ]);

    }

    public function deleteSkillByIdSkill($idSkill){

        $skill = Skill::query()->where('id', $idSkill)->first();
        $titleSkill = $skill->title;
        $isSuccess = $skill->delete();

        return response()->json([
            "isSuccess" => $isSuccess,
            "status" => "Data " . $titleSkill . " berhasil dihapus"
        ]);

    }

}
