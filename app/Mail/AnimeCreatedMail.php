<?php

namespace App\Mail;

use App\Models\Anime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnimeCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $anime;

    public function __construct(Anime $anime)
    {
        $this->anime = $anime;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Нове аніме додано в каталог!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Вказуємо, який HTML-шаблон використовувати
        return new Content(
            view: 'emails.anime-added',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
