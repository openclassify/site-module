<?php namespace Visiosoft\SiteModule\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class AddDomainRequest extends FormRequest
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
            'siteId' => 'required|string|exists:site_site,site_id',
            'domain' => 'required|string',
        ];
    }
}