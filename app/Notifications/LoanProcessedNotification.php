<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoanProcessedNotification extends Notification
{
    use Queueable;

    protected $loan;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/loans/' . $this->loan->id);

        return (new MailMessage)
            ->subject('Congrats! Your loan is processed.')
            ->greeting('Hello!')
            ->line("One of your loan applications is processed! Weekly installment is " . $this->loan->formatted_weekly_installment)
            ->action('View Loan', $url)
            ->line('Thank you for using our service!');
    }
}
