<?php

namespace Peyas\PreOrderForm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Peyas\PreOrderForm\Rules\PhoneNumber;



class PreOrderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email',
            'product_id' => 'required|exists:products,id',
            'recaptcha' => 'required',
        ];

        // Add a conditional rule for phone if email ends with "@xyz.com"
        if (str_ends_with($this->email, '@xyz.com')) {

            $rules['phone'] = ['required', new PhoneNumber];
        }
        return $rules;
    }
}
