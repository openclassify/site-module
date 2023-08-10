<?php namespace Visiosoft\SiteModule;

use Anomaly\Streams\Platform\Addon\Module\Module;

class SiteModule extends Module
{

    /**
     * The navigation display flag.
     *
     * @var bool
     */
    protected $navigation = true;

    /**
     * The addon icon.
     *
     * @var string
     */
    protected $icon = 'fa fa-puzzle-piece';

    /**
     * The module sections.
     *
     * @var array
     */
    protected $sections = [
        'site' => [
            'buttons' => [
                'new_site',
            ],
        ],
        'aliases' => [
            'buttons' => [
                'new_alias',
            ],
        ],
    ];

}
