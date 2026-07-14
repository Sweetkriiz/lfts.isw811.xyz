<?php

namespace App\Http\Requests;

use App\IdeaStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IdeaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'nullable',
                Rule::enum(IdeaStatus::class),
            ],

            'links' => [
                'nullable',
                'array',
            ],

            'links.*' => [
                'required',
                'url',
                'max:255',
            ],

            'steps' => [
                'nullable',
                'array',
            ],

            'steps.*.description' => [
                'required',
                'string',
                'max:255',
            ],

            'steps.*.completed' => [
                'nullable',
                'boolean',
            ],

            'image' => [
                'nullable',
                'image',
                'max:5120',
            ],
        ];
    }
}
