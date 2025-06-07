<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{

    
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $this->user()->id,
            'email' => 'required|email|max:255|unique:users,email,' . $this->user()->id,
        ];
    }

    public function persist()
    {
        $user = $this->user();
        $user->update($this->validated());
    }
}
