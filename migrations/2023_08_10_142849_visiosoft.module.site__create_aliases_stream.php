<?php

use Anomaly\Streams\Platform\Database\Migration\Migration;

class VisiosoftModuleSiteCreateAliasesStream extends Migration
{

    /**
     * This migration creates the stream.
     * It should be deleted on rollback.
     *
     * @var bool
     */
    protected $delete = false;

    /**
     * The stream definition.
     *
     * @var array
     */
    protected $stream = [
        'slug' => 'aliases',
        'title_column' => 'domain',
        'translatable' => false,
        'versionable' => true,
        'trashable' => true,
        'searchable' => true,
        'sortable' => false,
    ];

    protected $fields = [
        'alias_id' => 'anomaly.field_type.text',
        'site' => [
            'type' => 'anomaly.field_type.relationship',
            'config' => [
                'related' => \Visiosoft\SiteModule\Site\SiteModel::class,
                'mode' => 'lookup'
            ],
        ],
        'domain' => 'anomaly.field_type.text',
        'ssl' => [
            'type' => 'anomaly.field_type.boolean',
            'config' => [
                'default' => false
            ],
        ],
    ];

    /**
     * The stream assignments.
     *
     * @var array
     */
    protected $assignments = [
        'alias_id' => [
            'unique' => true,
            'required' => true,
        ],
        'site' => [
            'required' => true,
        ],
        'domain' => [
            'required' => true,
        ],
        'ssl',

    ];

}
