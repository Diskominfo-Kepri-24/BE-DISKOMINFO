<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\UserMagang;
use App\Models\UserMagangMahasiswa;
use App\Models\UserMagangSiswa;

class AuthController extends Controller
{
    public function registerMahasiswa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nim' => 'required|string',
            'no_telp' => 'string|min:10',
            'jurusan' => 'string',
            'tahun_angkatan' => 'string',
            'universitas' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'role' => 'mahasiswa',
        ]);

        $userMagang = UserMagang::create([
            'no_telp' => $request->no_telp,
            'mulai_magang' => $request->mulai_magang,
            'akhir_magang' => $request->akhir_magang,
            'id_user' => $user->id,
        ]);

        $userMahasiswa = UserMagangMahasiswa::create([
            'nim' => $request->nim,
            'jurusan' => $request->jurusan,
            'tahun_angkatan' => $request->tahun_angkatan,
            'universitas' => $request->universitas,
            'id_user_magang' => $userMagang->id,
            
        ]);

        $data = $userMahasiswa->toArray();
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['no_telp'] = $userMagang->no_telp;
        $data['role'] = $user->role;
        $data['id'] = $user->id;
        

        return response()->json([
            'data' => $data
        ]);
    }


    public function registerSiswa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nisn' => 'required|string',
            'no_telp' => 'string|min:10',
            'jurusan' => 'string',
            'tahun_angkatan' => 'string',
            'sekolah' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'role' => 'siswa',
        ]);

        $userMagang = UserMagang::create([
            'no_telp' => $request->no_telp,
            'mulai_magang' => $request->mulai_magang,
            'akhir_magang' => $request->akhir_magang,
            'id_user' => $user->id,
        ]);

        $userSiswa = UserMagangSiswa::create([
            'nisn' => $request->nisn,
            'jurusan' => $request->jurusan,
            'tahun_angkatan' => $request->tahun_angkatan,
            'sekolah' => $request->sekolah,
            'id_user_magang' => $userMagang->id,
            
        ]);

        $data = $userSiswa->toArray();
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['no_telp'] = $userMagang->no_telp;
        $data['role'] = $user->role;
        $data['id'] = $user->id;
        

        return response()->json([
            'data' => $data
        ]);
    }


    public function registerPembimbing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            "password" => "required|min:8",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'role' => 'pembimbing',
        ]);
        

        return response()->json([
            'data' => $user
        ]);
    }

    public function registerAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            "password" => "required|min:8",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'role' => 'admin',
        ]);
        

        return response()->json([
            'data' => $user
        ]);
    }



    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // $user = User::where('email', $request->email)->firstOrFail()
        $user = User::query()->where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'logout success'
        ]);
    }
}
