<?php

namespace App\Http\Controllers;

use App\Mail\MagangEmail;
use App\Models\Magang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MagangController extends Controller
{


    public function getAllUserMagang(){

        $users = Magang::query()->get();

        // Iterasi melalui setiap user untuk menambahkan status verifikasi
        foreach ($users as $user) {
            if (is_null($user->email_verified_at)) {
                $user->verifikasi = "Email belum diverifikasi";
            } else {
                $user->verifikasi = "Email sudah diverifikasi";
            }
        }

        return response()->json([
            "users" => $users
        ]);

    }


    public function acceptMagang(Magang $userMagang){

        // $user = $userMagang->user();
        // $user = User::query()->where('id', $userMagang->id_user)->firstOrFail();

        $userMagang->status = "terima";
        $isSuccess = $userMagang->save();

        if($isSuccess){

            $rawPassword = Str::random(12);

            $user = User::create([
                'nama' => $userMagang->nama,
                'password' => Hash::make($rawPassword),
                'email' => $userMagang->email,
                'no_hp' => $userMagang->no_hp,
                'role' => 'magang',
            ]);

            $userMagang->id_user = $user->id;
            $userMagang->save();

            Mail::to($user->email)->send(new MagangEmail($userMagang, $rawPassword));
        }

        return response()->json([
            "success" => $isSuccess
        ]);

    }

    public function rejectMagang(Magang $userMagang){

        $user = User::query()->where('id', $userMagang->id_user)->first();
        if(!is_null($user)){
            $user->delete();
        }

        $userMagang->status = "tolak";
        $isSuccess = $userMagang->save();

        if($isSuccess){
            Mail::to($userMagang->email)->send(new MagangEmail($userMagang, $userMagang->email));
        }

        return response()->json([
            "success" => $isSuccess
        ]);

    }


}
