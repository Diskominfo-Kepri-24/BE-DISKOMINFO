<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MagangEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $user;
    private $magang;
    /**
     * Create a new message instance.
     */
    public function __construct($user, $magang)
    {
        //
        $this->user = $user;
        $this->magang = $magang;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengunguman Penerimaan Magang Diskominfo Kepulauan Riau',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if ($this->magang->status == "diterima"){
            return new Content(
                view: 'emails.terimamagang',
                with: [
                    'name' => $this->user->nama,
                    'status' => $this->magang->status,
                    'tanggal_mulai' => $this->magang->mulai_magang,
                    'tanggal_selesai' => $this->magang->akhir_magang
                ]
            );
        }else if($this->magang->status == "ditolak"){
            return new Content(
                view: 'emails.tolakmagang',
                with: [
                    'name' => $this->user->nama
                ]
            );
        }
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
