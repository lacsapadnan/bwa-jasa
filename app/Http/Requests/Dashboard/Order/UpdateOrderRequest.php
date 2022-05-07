<?php

namespace App\Http\Requests\Dashboard\Order;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'buyer_id' => [
                'nullable', 'int',
            ],
            'freelancer_id' => [
                'nullable', 'int',
            ],
            'service_id' => [
                'nullable', 'int',
            ],
            'file' => [
                'required', 'mimes:zip','max:1024',
            ],
            'note' => [
                'required', 'string','max:10000',
            ],
            'expired' => [
                'nullable', 'date',
            ],
            'order_status_id' => [
                'nullable', 'int',
            ],
        ];
    }
}
