<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'order_type' => 'required|in:dine_in,takeaway,delivery',
            'table_id' => 'nullable|exists:tables,id',
            'total_amount' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'final_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:paid,unpaid',
            'order_status' => 'required|in:pending,preparing,completed,cancelled',
        ];
    }
}
