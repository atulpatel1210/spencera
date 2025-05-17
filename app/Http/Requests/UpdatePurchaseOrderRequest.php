<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'production_quantity' => 'nullable|integer|min:0',
            'completed_quantity' => 'nullable|integer|min:0',
        ];
    }
}
