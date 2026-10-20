<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TableBookingRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'table_number' => 'required|string|max:50',
            'booking_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|date_format:H:i',
            'guest_count' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled',
        ];
    }
}
