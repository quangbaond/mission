<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
class ConfirmTokenRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => 'required',
            'email' => ['required', 'email', 'max:' . FORM_INPUT_MAX_LENGTH],
            'password' => ['required', 'max:' . FORM_INPUT_MAX_LENGTH]
        ];
    }
}
