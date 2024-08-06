<?php

namespace App\Http\Controllers;

use App\Models\EmailVerificationToken;
use App\Models\Magang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmailVerificationController extends Controller
{

    public function index(Request $request)
    {
        $token = EmailVerificationToken::query()->where('token', $request->input('token'))->first();

        if (is_null($token)) {
            abort(404);
        }

        // Cek apakah token telah kedaluwarsa
        if (Carbon::now()->greaterThan($token->expired_at)) {
            // Hapus user terkait
            $user = User::query()->where('email', $token->email)->first();
            $magang = Magang::query()->where('id_user', $user->id)->first();

            if ($user) {
                Storage::disk('public')->delete(substr($magang->surat_magang, 8));
                $user->delete();
            }

            // Hapus token verifikasi
            $token->delete();

            // Kembali dengan pesan error atau view yang sesuai
            return response()->view('verifyemails.gagal', [], 410);
        }

        // Jika token masih valid, lanjutkan verifikasi email
        $user = User::query()->where('email', $token->email)->first();
        $user->email_verified_at = now();
        $user->save();
        $token->delete();

        return view('verifyemails.berhasil');
    }
}
