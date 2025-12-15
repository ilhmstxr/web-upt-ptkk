<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneralNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;
    public $data;
    public $viewName;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string|array $content The main content or data for the view
     * @param array $data Additional data to pass to the view
     * @param string|null $viewName Custom view name (optional)
     */
    public function __construct($subject, $content, $data = [], $viewName = null)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->data = $data;
        $this->viewName = $viewName ?? 'template_surat.email_notification';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view($this->viewName)
            ->with(array_merge([
                'content' => $this->content,
                'subject' => $this->subject,
            ], $this->data));
    }
}
