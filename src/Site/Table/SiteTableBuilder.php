<?php namespace Visiosoft\SiteModule\Site\Table;

use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Illuminate\Database\Eloquent\Builder;

class SiteTableBuilder extends TableBuilder
{

    public function onQuerying(Builder $query)
    {
        $query->where('panel', false);
    }

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
            'handler' => \Visiosoft\SiteModule\Site\Table\Handler\Delete::class,
        ],
    ];

    /**
     * The table options.
     *
     * @var array
     */
    protected $options = [
        'title' => 'visiosoft.module.site::field.basic_information.name',
        'description' => 'visiosoft.module.site::message.site_table_description',
    ];

    /**
     * The table assets.
     *
     * @var array
     */
    protected $assets = [];

}
