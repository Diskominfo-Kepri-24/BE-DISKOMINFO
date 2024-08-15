<?php

use App\Models\EmailVerificationToken;
use App\Models\Magang;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();


// Schedule::command('deleteemail:cron')->daily();

// cron job (jalankan perintah php artisan schedule:work)
Schedule::call(function () {
    // Ambil semua token yang telah kedaluwarsa
    $expiredTokens = EmailVerificationToken::where('expired_at', '<', Carbon::now())->get();

    if (is_null($expiredTokens)) {
        // $this->info("TIdak ada email yang kadaluarsa");
        info("Tidak ada email yang kadaluarsa");
    } else {
        foreach ($expiredTokens as $token) {
            // Hapus user terkait
            $magang = Magang::where('email', $token->email)->first();

            if ($magang) {
                // Hapus file yang terkait
                Storage::disk('public')->delete(substr($magang->surat_magang, 8));
                $magang->delete();
            }

            // Hapus token verifikasi
            $token->delete();
            info("Email yang kadaluarsa berhasil dihapus");
        }
    }
})->everySecond();
