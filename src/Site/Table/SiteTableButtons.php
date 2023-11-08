<?php namespace Visiosoft\SiteModule\Site\Table;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;

class SiteTableButtons
{
    public function handle(SiteTableBuilder $builder)
    {
        $buttons = [
            'manage' => [
                'icon' => 'cog',
                'type' => 'info',
                'href' => '/admin/site/show/{entry.site_id}'
            ],
            'settings' => [
                'type' => 'default',
                'text' => false,
                'href' => false,
                'dropdown' => [
                    'reset_mysql_password' => [
                        'text' => "Reset Mysql Password",
                        'icon' => "fa fa-lock",
                        'href' => function (EntryInterface $entry) {
                            return "/admin/site/reset_mysql_password/" . $entry->site_id;
                        },
                        'type' => "info",
                    ],
                    'reset_ssh_password' => [
                        'text' => "Reset Ssh Password",
                        'icon' => "fa fa-lock",
                        'href' => function (EntryInterface $entry) {
                            return "/admin/site/reset_ssh_password/" . $entry->site_id;
                        },
                        'type' => "info",
                    ]
                ],
            ],

        ];

        $builder->setButtons($buttons);
    }
}