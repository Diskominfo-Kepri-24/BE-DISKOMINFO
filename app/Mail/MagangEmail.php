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
    private $magang;
    private $password;

    /**
     * Create a new message instance.
     */
    public function __construct($magang, $password)
    {
        //
        $this->magang = $magang;
        $this->password = $password;
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
        if ($this->magang->status == "terima"){
            return new Content(
                view: 'emails.terimamagang',
                with: [
                    'name' => $this->magang->nama,
                    'status' => $this->magang->status,
                    'email' => $this->magang->email,
                    'password' => $this->password,
                    'tanggal_mulai' => $this->magang->mulai_magang,
                    'tanggal_selesai' => $this->magang->akhir_magang
                ]
            );
        }else if($this->magang->status == "tolak"){
            return new Content(
                view: 'emails.tolakmagang',
                with: [
                    'name' => $this->magang->nama
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
