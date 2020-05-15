<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegistrationFormRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:10'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required!',
            'email.unique'  => 'Email is unique!',
            'name.required' => 'Name is required!',
            'password.required' => 'Password is required!'
        ];
    }

    //驗證不過時，用json格式輸出response
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $errors
        ], 401/*JsonResponse::HTTP_UNPROCESSABLE_ENTITY*/ ));
    }


}
