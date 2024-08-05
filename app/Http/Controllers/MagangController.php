<?php

namespace App\Http\Controllers;

use App\Models\Magang;
use Illuminate\Http\Request;

class MagangController extends Controller
{


    public function getAllUserMagang(){

        $users = Magang::query()->join('users', 'users.id', '=', 'magang.id_user')
                                    ->select('magang.id as id_magang', 'users.id as id_user' ,'users.nama as nama', 'email', 'mulai_magang', 'akhir_magang', 'role', 'status')
                                    ->get();

        return response()->json([
            "users" => $users
        ]);

    }


    public function acceptMagang(Magang $userMagang){

        $userMagang->status = "diterima";
        $isSuccess = $userMagang->save();

        return response()->json([
            "success" => $isSuccess
        ]);

    }

    public function rejectMagang(Magang $userMagang){

        $userMagang->status = "ditolak";
        $isSuccess = $userMagang->save();

        return response()->json([
            "success" => $isSuccess
        ]);

    }


}
