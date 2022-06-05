<?php

namespace App\Http\Controllers\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
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
            case 'GET':
                return [
                    'id' => [
                        'required',
                        'int',
                        Rule::exists('products')
                    ]
                ];
            case 'DELETE':
                return [
                    'id' => [
                        'required',
                        'int',
                        Rule::exists('products')
                    ]
                ];
            case 'PATCH':
                return [
                    'id' => [
                        'required',
                        'int',
                        Rule::exists('products')
                    ],
                    'name' => [
                        'required',
                        'unique:products,name,' . $this->id,
                        'string'
                    ],
                    'description' => [
                        'nullable',
                        'string'
                    ],
                    'image_url' => [
                        'nullable',
                        'url'
                    ]
                ];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            'id.exists' => 'Cannot find product',
            'name.required' => 'name is required',
            'name.unique' => 'name must be unique',
            'image_url.url' => 'Image url must be a valid url'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['result' => 0, 'errors' => $validator->errors()], 422));
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['id'] = $this->route('id');
        return $data;
    }
}
