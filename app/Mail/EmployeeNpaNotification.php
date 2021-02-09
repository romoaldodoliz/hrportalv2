<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmployeeNpaNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->npa_data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('HR Portal - Notice of Personnel Action')
        ->view('email.employee_npa_notification')
        ->with([
            'reciever_name'  =>  $this->npa_data['reciever_name'],
            'employee_name'  =>  $this->npa_data['employee_name'],
            'company'  =>  $this->npa_data['company'],
            'position'  =>  $this->npa_data['position'],
            'link'  =>  $this->npa_data['link'],
            'npa_title'  =>  $this->npa_data['npa_title']
        ]);
    }
}
