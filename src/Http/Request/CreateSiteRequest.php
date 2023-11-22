<?php namespace Visiosoft\ServerModule\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreateSiteRequest extends FormRequest
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
            'username' => 'required|string|unique:site_site,username',
            'basepath',
            'serverId' => 'required|integer|exists:recipes_recipe,id',
            'php'
        ];
    }
}