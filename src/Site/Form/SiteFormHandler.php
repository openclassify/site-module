<?php namespace Visiosoft\SiteModule\Site\Form;

use Illuminate\Support\Str;

class SiteFormHandler
{
    public function handle(SiteFormBuilder $builder)
    {
        if (!$builder->canSave()) {
            return;
        }

        $form = $builder->getForm();

        $form->setValue('site_id', Str::uuid()); // Auto Generated
        $form->setValue('database', Str::random(24)); // Auto Generated
        $form->setValue('password', Str::random(24)); // Auto Generated

        $builder->saveForm();
    }
}
