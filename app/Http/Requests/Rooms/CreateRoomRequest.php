<?php

namespace App\Http\Requests\Rooms;

use App\Services\Rooms\RoomCreationData;
use Illuminate\Foundation\Http\FormRequest;

class CreateRoomRequest extends FormRequest implements RoomCreationData
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'friendly_url' => 'required|string|max:255',
            'max_duration' => 'required|integer|max:4294967295',
        ];
    }

    public function getName(): string
    {
        return $this->get('name');
    }

    public function getFriendlyUrl(): string
    {
        return $this->get('friendly_url');
    }

    public function getMaxDuration(): int
    {
        return $this->get('max_duration');
    }
}
