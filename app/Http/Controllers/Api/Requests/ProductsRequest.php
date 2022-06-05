<?php

namespace App\Http\Controllers\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductsRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => [
                        'required',
                        'unique:products',
                        'string'
                    ],
                    'description' => [
                        'nullable',
                        'string'
                    ],
                    'image_url' => [
                        'nullable',
                        'url'
                    ],
                    'tags' => [
                        'nullable',
                        'string'
                    ]
                ];
            default: return [];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'name is required',
            'name.unique' => 'name must be unique',
            'image_url.url' => 'Image url must be a valid url'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json(['result' => 0, 'errors' => $validator->errors()], 422));
    }
}
