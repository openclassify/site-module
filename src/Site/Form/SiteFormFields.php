<?php namespace Visiosoft\SiteModule\Site\Form;

use Illuminate\Support\Str;

class SiteFormFields
{
    public function handle(SiteFormBuilder $builder)
    {
        $entryId = $builder->getFormEntryId();
        $entry = $builder->getFormEntry();

        $builder->setFields([
            'server',
            'username' => [
                'value' => function () {
                    return config('site::username_prefix') . hash('crc32', (Str::uuid()->toString())) . rand(1, 9);
                }
            ],
            'basepath' => [
                'placeholder' => 'e.g. public'
            ],
            'php' => [
                'value' => function () use ($entryId, $entry) {
                    return $entryId ? $entry->php : config('server::default_php_version');
                }
            ],
        ]);
    }
}
