<?php

namespace Mamun\ShopPreOrder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use GuzzleHttp\Client;

class StorePreOrderRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:15',
            'product_id' => 'required|exists:products,id',
            'g-recaptcha-response' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = [
            'errors' => $validator->errors(),
        ];
        throw new \Illuminate\Validation\ValidationException($validator, response()->json($response, 422));
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->verifyRecaptcha()) {
                $validator->errors()->add('g-recaptcha-response', 'reCAPTCHA verification failed.');
            }
        });
    }

    private function verifyRecaptcha()
    {
        $token = $this->input('g-recaptcha-response');
        $secretKey = env('RECAPTCHA_SECRET_KEY');

        $client = new Client();
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret'   => $secretKey,
                'response' => $token,
                'remoteip' => $this->ip(),
            ],
        ]);

        $body = json_decode((string) $response->getBody(), true);
        return isset($body['success']) && $body['success'] === true;
    }
}
