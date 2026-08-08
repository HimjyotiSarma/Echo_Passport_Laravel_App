<?php

namespace App\Http\Requests;

use App\Enums\ConversationType;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreConversationRequest extends FormRequest
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
            'type' => [
                'required',
                Rule::enum(ConversationType::class)
            ],
            'name' => [
                Rule::requiredIf(
                    $this->input('type') === ConversationType::GROUP->value
                ),
                'string',
                'max:255'
            ],
            'description' => [
                'nullable',
                'string',
                Rule::prohibitedUnless(
                    $this->input('type') === ConversationType::GROUP->value
                )
            ],
            'participants' => [
                Rule::requiredIf($this->input('type') === ConversationType::DIRECT->value),
                'array',
                Rule::when($this->input('type') === ConversationType::DIRECT->value,
                ['size:1']
                )

            ],
            'participants.*' => [
                'ulid',
                Rule::exists(User::class, 'id'),
                'distinct'
            ]
        ];
    }
}
