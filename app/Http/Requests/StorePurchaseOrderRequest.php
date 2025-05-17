<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_number' => 'required|string|unique:purchase_orders,order_number',
            'total_quantity' => 'required|integer|min:1',
        ];
    }
}
