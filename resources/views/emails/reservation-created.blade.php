<x-mail::message>
    # Your reservation has been successfully created. Here are the details:

    **Reservation ID:** {{ $reservation->id }}
    **Date:** {{ $reservation->reserved_at->format('Y-m-d H:i') }}
    **Duration:** {{ $reservation->duration }} Hour(s)
    **Number of Guests:** {{ $reservation->number_of_people }}

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
