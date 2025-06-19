<?php

namespace App\Jobs;

use App\Mail\ReservationCreateMail;
use App\Models\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReservationCreatedMail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Reservation $reservation)
    {
        //
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->reservation->user->email)
            ->send(new ReservationCreateMail($this->reservation));
    }
}
