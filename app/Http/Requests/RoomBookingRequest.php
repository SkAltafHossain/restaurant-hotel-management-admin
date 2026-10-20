<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RoomBookingRequest extends FormRequest
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
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'total_guests' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:paid,unpaid',
            'booking_status' => 'required|in:confirmed,checked_in,checked_out,cancelled',
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:20',
            'guest_id_proof_type' => 'nullable|string|max:50',
            'guest_id_proof_number' => 'nullable|string|max:100',
            'guest_id_proof_image' => 'nullable|string|max:500',
        ];
    }
}
