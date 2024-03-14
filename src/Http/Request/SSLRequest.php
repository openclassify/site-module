<?php namespace Visiosoft\SiteModule\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class SSLRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'domain' => 'required|string',
        ];
    }
}