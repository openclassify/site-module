<?php namespace Visiosoft\SiteModule\Alias\Table;

class AliasTableButtons
{
    public function handle(AliasTableBuilder $builder)
    {
        $builder->setButtons([
            'generate_ssl' => [
                'type' => 'success',
                'icon' => 'fa fa-link',
                'href' => '/admin/site/aliases/generate-ssl/{entry.alias_id}'
            ]
        ]);
    }
}
