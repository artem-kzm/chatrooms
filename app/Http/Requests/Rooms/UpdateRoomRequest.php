<?php

namespace App\Http\Requests\Rooms;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'friendly_url' => 'string|max:255',
            'max_duration' => 'integer|max:4294967295',
        ];
    }

    public function getName(): ?string
    {
        return $this->get('name');
    }

    public function getFriendlyUrl(): ?string
    {
        return $this->get('friendly_url');
    }

    public function getMaxDuration(): ?int
    {
        return $this->get('max_duration');
    }
}
