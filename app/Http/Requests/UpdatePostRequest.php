<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $this->post->id,
            'category_id' => 'required|exists:categories,id',
            'body' => 'required|string',
        ];
    }
}
