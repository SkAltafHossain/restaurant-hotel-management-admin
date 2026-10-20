<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
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
            'item_name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:inventories,sku,' . $this->inventory,
            'current_stock' => 'required|integer|min:0',
            'unit' => 'required|in:kg,pcs,ltr',
            'low_alert_qty' => 'required|integer|min:0',
            'supplier_name' => 'nullable|string|max:255',
        ];
    }
}
