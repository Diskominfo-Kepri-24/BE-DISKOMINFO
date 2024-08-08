<?php

namespace App\Http\Controllers;

use App\Mail\MagangEmail;
use App\Models\Magang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MagangController extends Controller
{


    public function getAllUserMagang(){

        $users = Magang::query()->join('users', 'users.id', '=', 'magang.id_user')
                                    ->select('magang.id as id_magang', 'users.id as id_user' ,'users.nama as nama', 'email', 'mulai_magang', 'akhir_magang','instansi', 'role', 'status')
                                    ->get();

        return response()->json([
            "users" => $users
        ]);

    }


    public function acceptMagang(Magang $userMagang){

        // $user = $userMagang->user();
        $user = User::query()->where('id', $userMagang->id_user)->firstOrFail();

        $userMagang->status = "diterima";
        $isSuccess = $userMagang->save();

        if($isSuccess){
            Mail::to($user->email)->send(new MagangEmail($user, $userMagang));
        }

        return response()->json([
            "success" => $isSuccess
        ]);

    }

    public function rejectMagang(Magang $userMagang){

        $user = User::query()->where('id', $userMagang->id_user)->firstOrFail();

        $userMagang->status = "ditolak";
        $isSuccess = $userMagang->save();

        if($isSuccess){
            Mail::to($user->email)->send(new MagangEmail($user, $userMagang));
        }

        return response()->json([
            "success" => $isSuccess
        ]);

    }


}
