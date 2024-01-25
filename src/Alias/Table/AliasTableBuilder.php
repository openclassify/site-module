<?php namespace Visiosoft\SiteModule\Alias\Table;

use Anomaly\Streams\Platform\Ui\Table\TableBuilder;

class AliasTableBuilder extends TableBuilder
{

    /**
     * The table views.
     *
     * @var array|string
     */
    protected $views = [];

    /**
     * The table filters.
     *
     * @var array|string
     */
    protected $filters = [];

    /**
     * The table actions.
     *
     * @var array|string
     */
    protected $actions = [
        'delete' => [
            'handler' => \Visiosoft\SiteModule\Alias\Table\Handler\Delete::class,
        ],
    ];

    /**
     * The table options.
     *
     * @var array
     */
    protected $options = [
        'order_by' => [
            'id' => 'DESC'
        ],
    ];

    /**
     * The table assets.
     *
     * @var array
     */
    protected $assets = [];

}
