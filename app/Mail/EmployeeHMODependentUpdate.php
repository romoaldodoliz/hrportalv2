<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmployeeHMODependentUpdate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->employee_data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('HR Portal - HMO Dependent Updates')
        ->view('email.employee_hmo_dependent')
        ->with([
            'employee_name'  =>  $this->employee_data['employee_name'],
            'position'  =>  $this->employee_data['position'],
            'company'  =>  $this->employee_data['company']
        ]);
    }
}
