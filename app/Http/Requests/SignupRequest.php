<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255|unique:accounts',
            'name' => 'required|string|max:255',
        ];
    }

    public function getEmail(): string
    {
        return $this->get('email');
    }

    public function getName(): string
    {
        return $this->get('name');
    }
}
