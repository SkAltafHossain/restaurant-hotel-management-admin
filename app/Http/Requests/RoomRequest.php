<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'room_number' => 'required|string|max:50|unique:rooms,room_number,' . $this->room,
            'floor' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'max_adults' => 'required|integer|min:1',
            'max_children' => 'required|integer|min:0',
            'status' => 'required|in:available,booked,maintenance',
        ];
    }
}
