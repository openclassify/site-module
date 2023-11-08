<?php namespace Visiosoft\SiteModule\Site\Table;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;

class SiteTableColumns
{
    public function handle(SiteTableBuilder $builder)
    {
        $columns = [
            'username',
            'site_id',
            'aliases' => [
                'wrapper' => '{value.aliases}',
                'value' => [
                    'aliases' => function (EntryInterface $entry) {
                        return count($entry->aliases) ? count($entry->aliases) : "0";
                    }
                ]
            ],
            'server' => [
                'wrapper' => '{value.aliases}',
                'value' => [
                    'aliases' => function (EntryInterface $entry) {
                        return count($entry->aliases) ? count($entry->aliases) : "0";
                    }
                ]
            ],
        ];

        $builder->setColumns($columns);
    }
}