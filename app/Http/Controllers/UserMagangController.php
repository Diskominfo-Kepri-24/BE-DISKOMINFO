<?php

namespace App\Http\Controllers;

use App\Models\UserMagang;
use Illuminate\Http\Request;

class UserMagangController extends Controller
{


    public function getAllUserMagang(){

        $users = UserMagang::query()->join('users', 'users.id', '=', 'user_magang.id_user')
                                    ->select('user_magang.id as id_user_magang', 'users.id as id_user' ,'users.name as name', 'email', 'mulai_magang', 'akhir_magang', 'role', 'status')
                                    ->get();

        return response()->json([
            "users" => $users
        ]);

    }


    public function acceptMagang(UserMagang $userMagang){

        $userMagang->status = "diterima";
        $isSuccess = $userMagang->save();

        return response()->json([
            "success" => $isSuccess
        ]);

    }

    public function rejectMagang(UserMagang $userMagang){

        $userMagang->status = "ditolak";
        $isSuccess = $userMagang->save();

        return response()->json([
            "success" => $isSuccess
        ]);

    }


}
