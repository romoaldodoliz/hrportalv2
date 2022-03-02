<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmployeeRegularizationThirdMonthNotification extends Mailable
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
        return $this->subject('HR Reminder - 3rd Month Performance Review')
        ->view('email.employee_third_notification_regularization')
        ->with([
            'reciever_name'  =>  $this->employee_data['reciever_name'],
            'employee_name'  =>  $this->employee_data['employee_name'],
            'position'  =>  $this->employee_data['position'],
            'company'  =>  $this->employee_data['company'],
            'department'  =>  $this->employee_data['department'],
            'date_hired'  =>  $this->employee_data['date_hired'],
            'date_of_regularization'  =>  $this->employee_data['date_of_regularization'],
        ]);
    }
}
