<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReactionRequest extends FormRequest
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
            'emoji_code' => [
                'required',
                'string',
                Rule::in([
                    'U+1F44D', // 👍
                    'U+2764', // ❤️
                    'U+1F602', // 😂
                    'U+1F62E', // 😮
                    'U+1F622', // 😢
                    'U+1F621', // 😡
                ]),
            ]
        ];
    }
}
