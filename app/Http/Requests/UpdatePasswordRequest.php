<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'password' => 'required|string|confirmed|min:8',
        ];
    }

    public function persist()
    {
    $user = $this->user();
    if (!$this->current_password || !\Hash::check($this->current_password, $user->password)) {
        // Akan otomatis tampil di error bag di Blade
        throw ValidationException::withMessages([
            'current_password' => 'Password lama salah'
        ]);
    }
    $user->update(['password' => bcrypt($this->password)]);
    }
}
