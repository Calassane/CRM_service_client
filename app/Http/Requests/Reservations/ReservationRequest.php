<?php

namespace App\Http\Requests\Reservations;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

abstract class ReservationRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $reservation = $this->route('reservation');
        $reservationId = $reservation instanceof Reservation ? $reservation->getKey() : null;

        return [
            'client_id' => ['required', 'integer', Rule::exists('clients', 'id')],
            'reference' => ['required', 'string', 'max:30', Rule::unique('reservations', 'reference')->ignore($reservationId)],
            'vehicle' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::enum(ReservationStatus::class)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'reference' => is_string($this->input('reference'))
                ? Str::upper(trim($this->input('reference')))
                : $this->input('reference'),
            'vehicle' => is_string($this->input('vehicle'))
                ? trim($this->input('vehicle'))
                : $this->input('vehicle'),
        ]);
    }
}
