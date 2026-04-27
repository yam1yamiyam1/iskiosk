<?php

namespace App\Mail;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function build()
    {
        return $this->subject($this->document->tracking_code . ' - IsKiosk DMS')
                    ->view('emails.document_status_updated');
    }
}