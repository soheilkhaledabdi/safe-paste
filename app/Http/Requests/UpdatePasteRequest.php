<?php

namespace App\Http\Requests;

use App\Models\Paste;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePasteRequest extends FormRequest
{
    public function authorize(): bool
    {
        $paste = $this->route('paste');

        return $paste && $this->user()?->is($paste->user);
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'language' => ['nullable', 'string', 'max:50', Rule::in(Paste::LANGUAGES)],
            'password' => ['nullable', 'string', 'min:4', 'max:255'],
            'remove_password' => ['nullable', 'boolean'],
            'expires_in' => ['nullable', Rule::in(['never', '10_minutes', '1_hour', '1_day', '7_days', '30_days'])],
            'burn_after_reading' => ['nullable', 'boolean'],
            'max_views' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'visibility' => ['nullable', Rule::in(Paste::VISIBILITIES)],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                if (strlen((string) $this->input('content')) > 1024 * 1024) {
                    $validator->errors()->add('content', 'Content may not be greater than 1MB.');
                }
            },
        ];
    }
}
