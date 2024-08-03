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
    public function sendVerificationLink(object $user): void {

        Notification::send($user, new EmailVerificationNotification($this->generateVerificationLink($user->email)));

    }

    /**
     * Generate verification link
     */
    public function generateVerificationLink(string $email): string
    {
        $checkIfTokenExists = EmailVerificationToken::query()->where("email", $email)->first();

        if($checkIfTokenExists) $checkIfTokenExists->delete();
        $token = Str::uuid();

        $url = config('app.url') . "?token=" . $token . "&email=" . $email;
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
