<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RobotsSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'crawling_mode' => [
                'required',
                'string',
                Rule::in(['production', 'maintenance', 'development', 'custom']),
            ],
            'allow_frontend' => ['nullable', 'boolean'],
            'block_backend' => ['nullable', 'boolean'],
            'block_api' => ['nullable', 'boolean'],
            'block_login' => ['nullable', 'boolean'],
            'include_sitemap' => ['nullable', 'boolean'],
            'block_images' => ['nullable', 'boolean'],
            'block_css' => ['nullable', 'boolean'],
            'block_js' => ['nullable', 'boolean'],
            'custom_rules' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
