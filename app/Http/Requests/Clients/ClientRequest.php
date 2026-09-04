<?php

namespace App\Http\Requests\Clients;

use App\Models\Client;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

abstract class ClientRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $client = $this->route('client');
        $clientId = $client instanceof Client ? $client->getKey() : null;

        return [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['nullable', 'email:rfc', 'max:255', Rule::unique('clients', 'email')->ignore($clientId)],
            'phone' => ['required', 'string', 'max:30', Rule::unique('clients', 'phone')->ignore($clientId)],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => $this->trimmed('first_name'),
            'last_name' => $this->trimmed('last_name'),
            'email' => $this->normalizedEmail(),
            'phone' => $this->trimmed('phone'),
            'address' => $this->trimmedOrNull('address'),
            'city' => $this->trimmed('city'),
        ]);
    }

    private function normalizedEmail(): mixed
    {
        $email = $this->trimmedOrNull('email');

        return is_string($email) ? Str::lower($email) : $email;
    }

    private function trimmed(string $key): mixed
    {
        $value = $this->input($key);

        return is_string($value) ? trim($value) : $value;
    }

    private function trimmedOrNull(string $key): mixed
    {
        $value = $this->trimmed($key);

        return $value === '' ? null : $value;
    }
}
