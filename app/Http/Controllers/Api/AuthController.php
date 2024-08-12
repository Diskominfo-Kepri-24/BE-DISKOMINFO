<?php

namespace App\Http\Controllers\Api;

use App\Custom\Services\EmailVerificationService;
use App\Http\Controllers\Controller;
use App\Models\Magang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct(private EmailVerificationService $service)
    {
        
    }

    public function registerMagang(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'no_induk' => 'required|string',
            'jurusan' => 'required',
            'jenjang' => 'required',
            'instansi' => 'required',
            'surat_magang' => 'required|mimes:pdf',
            'mulai_magang' => 'required',
            'akhir_magang' => 'required',
            'alasan_magang' => 'required',
        ]);

        $surat_magang = $request->file('surat_magang');
        $surat_magangName = time() . "_" . "surat_magang" . "_" . $surat_magang->hashName();
        $surat_magang->storeAs("public/surat_magang", $surat_magangName);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // $user = User::create([
        //     'nama' => $request->nama,
        //     'password' => Hash::make($request->password),
        //     'email' => $request->email,
        //     'no_hp' => $request->no_hp,
        //     'role' => 'magang',
        // ]);
        $magang = Magang::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_induk' => $request->no_induk,
            'no_hp' => $request->no_hp,
            'jurusan' => $request->jurusan,
            'jenjang' => $request->jenjang,
            'instansi' => $request->instansi,
            'surat_magang' => 'storage/surat_magang/' . $surat_magangName,
            'mulai_magang' => $request->mulai_magang,
            'akhir_magang' => $request->akhir_magang,
            'alasan_magang' => $request->alasan_magang,
            'status' => "pending",
            // 'id_user' => $user->id,
        ]);

        if($magang){
            $this->service->sendVerificationLink($magang);
        }


        // $magang['nama'] = $magang->nama;
        // $magang['email'] = $user->email;
        // $magang['no_hp'] =  $user->no_hp;
        // $magang['role'] = $user->role;
        // $magang['id_user'] = $user->id;

        return response()->json([
            'data' => $magang
        ]);
    }


    public function registerPembimbing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            "password" => "required|min:8",
            'no_hp' => 'required|numeric',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
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
            'nama' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            "password" => "required|min:8",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
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
            'token_type' => 'Bearer',
            "role" => $user->role,
            "nama" => $user->nama,
            "email" => $user->email,
            "user_id" => $user->id,
        ]);
    }

    public function loginDiff(Request $request)
    {
        // Verifikasi kredensial pengguna
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => "Unauthorized"
            ], 401);
        }
    
        $user = User::query()->where('email', $request->email)->firstOrFail();
    
        // Jika peran pengguna adalah mahasiswa atau siswa
        if ($user->role == "magang") {

            $magang = Magang::query()->where('id_user', $user->id)->firstOrFail();

            if(is_null($magang->email_verified_at)){
                return response()->json([
                    "message" => "Email belum diverifikasi, silakan verifikasi terlebih dahulu email anda",
                    "name" => $user->nama,
                    "email" => $user->email
                ], 403);
            }
    
            // Buat token otentikasi
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                "message" => "Login Success",
                "access_token" => $token,
                "status" => $magang->status,
                "token_type" => "Bearer",
                "role" => $user->role,
                "name" => $user->nama,
                "email" => $user->email,
                "user_id" => $user->id, // Tambahkan ID pengguna ke respons
                "no_hp" => $user->no_hp,
                "jurusan" => $magang->jurusan,
                "no_induk" => $magang->no_induk,
                "jenjang" => $magang->jenjang,
                "instansi" => $magang->instansi,
                "surat_magang" => $magang->surat_magang,
                "mulai_magang" => $magang->mulai_magang,
                "akhir_magang" => $magang->akhir_magang,
                "motivasi_magang" => $magang->motivasi_magang,
            ]);
        }
    
        // Buat token otentikasi untuk pengguna dengan peran lain
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            "message" => "Login Success",
            "name" => $user->nama,
            "email" => $user->email,
            "access_token" => $token,
            "token_type" => 'Bearer',
            "role" => $user->role,
            "user_id" => $user->id, // Tambahkan ID pengguna ke respons
        ]);
    }
    

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'logout success'
        ]);
    }

    // ubah password
    public function changePassword(Request $request){

        $request->validate([
            "password" => "required|min:8"
        ]);

        $user = User::query()->where('id', Auth::user()->id)->first();
        $user->password = Hash::make($request->password);
        $isSave = $user->save();
        
        return response()->json([
            "message" => $isSave
        ]);

    }

    public function getAllPembimbing()
{
    // Mengambil semua pembimbing dengan role 'pembimbing'
    $pembimbing = User::where('role', 'pembimbing')->get();

    return response()->json([
        'data' => $pembimbing
    ]);
}

public function deletePembimbingById($id)
{
    // Mencari pembimbing berdasarkan ID
    $pembimbing = User::where('role', 'pembimbing')->find($id);

    if (!$pembimbing) {
        return response()->json([
            'message' => 'Pembimbing tidak ditemukan'
        ], 404);
    }

    // Menghapus pembimbing
    $pembimbing->delete();

    return response()->json([
        'message' => 'Pembimbing berhasil dihapus'
    ]);
}
}
