<?php

namespace App\Http\Requests\CustomerCalls;

use App\Enums\CallDirection;
use App\Enums\CallReason;
use App\Enums\CallStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

abstract class CustomerCallRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', Rule::exists('clients', 'id')],
            'reservation_id' => [
                'nullable',
                'integer',
                Rule::exists('reservations', 'id')->where(
                    fn (Builder $query): Builder => $query->where('client_id', $this->integer('client_id')),
                ),
            ],
            'direction' => ['required', Rule::enum(CallDirection::class)],
            'reason' => ['required', Rule::enum(CallReason::class)],
            'started_at' => ['required', 'date', 'before_or_equal:now'],
            'duration_seconds' => ['required', 'integer', 'min:1', 'max:86400'],
            'status' => ['required', Rule::enum(CallStatus::class)],
            'notes' => ['nullable', 'string', 'max:5000'],
            'tag_ids' => ['nullable', 'array', 'max:10'],
            'tag_ids.*' => ['integer', 'distinct', Rule::exists('tags', 'id')],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function callAttributes(): array
    {
        return Arr::except($this->validated(), ['tag_ids']);
    }

    /**
     * @return list<int>
     */
    public function tagIds(): array
    {
        return array_map('intval', $this->validated('tag_ids') ?? []);
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'client_id' => 'client',
            'reservation_id' => 'réservation',
            'direction' => 'sens de l’appel',
            'reason' => 'motif',
            'started_at' => 'date et heure',
            'duration_seconds' => 'durée',
            'status' => 'statut',
            'notes' => 'notes',
            'tag_ids' => 'étiquettes',
            'tag_ids.*' => 'étiquette',
        ];
    }
}
