<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Idea;

class StoreIdeaRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => ['required', 'min:10'],
            
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'Come on, dude. You need to write something.'
        ];
    }
}
