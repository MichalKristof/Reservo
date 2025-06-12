<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AvailableReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reserved_at' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['nullable', 'date_format:H:i'],
            'duration' => ['nullable', 'integer'],
        ];
    }

    public function getDateTime(): ?\DateTimeImmutable
    {
        if (!$this->filled(['reserved_at', 'time'])) {
            return null;
        }

        return Carbon::createFromFormat(
            'Y-m-d H:i',
            $this->input('reserved_at') . ' ' . $this->input('time'),
            config('app.timezone')
        )->toDateTimeImmutable();
    }

    public function getReservedAt(): \DateTimeImmutable
    {
        return Carbon::parse(
            $this->input('reserved_at'),
            config('app.timezone')
        )->startOfDay()->toDateTimeImmutable();
    }

    public function getTime(): ?string
    {
        return $this->input('time');
    }

    public function getDuration(): ?int
    {
        return $this->has('duration') ? (int)$this->input('duration') : null;
    }
}
