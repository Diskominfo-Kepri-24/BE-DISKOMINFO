<?php

namespace App\Custom\Services;

use App\Models\EmailVerificationToken;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class EmailVerificationService
{

    /**
     * Send verification link to a user
     */
    public function sendVerificationLink(object $magang){

        Notification::send($magang, new EmailVerificationNotification($this->generateVerificationLink($magang->email), $magang));

    }

    /**
     * Generate verification link
     */
    public function generateVerificationLink(string $email): string
    {
        $checkIfTokenExists = EmailVerificationToken::query()->where("email", $email)->first();

        if($checkIfTokenExists) $checkIfTokenExists->delete();
        $token = Str::uuid();

        $url = config('app.url') . "/verify?token=" . $token . "&email=" . $email;
        $saveToken = EmailVerificationToken::query()->create([
            "email" => $email,
            "token" => $token,
            "expired_at" => now()->addMinutes(60),
        ]);

        if($saveToken){
            return $url;
        }

    }
}
