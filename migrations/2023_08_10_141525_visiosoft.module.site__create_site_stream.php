<?php

use Anomaly\Streams\Platform\Database\Migration\Migration;

class VisiosoftModuleSiteCreateSiteStream extends Migration
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
        'slug' => 'site',
        'title_column' => 'site_id',
        'translatable' => false,
        'versionable' => true,
        'trashable' => true,
        'searchable' => true,
        'sortable' => false,
    ];

    protected $fields = [
        'site_id' => 'anomaly.field_type.text',
        'server' => [
            'type' => 'anomaly.field_type.relationship',
            'config' => [
                'mode' => 'lookup',
                'related' => \Visiosoft\ServerModule\Server\ServerModel::class
            ],
        ],
        'username' => 'anomaly.field_type.text',
        'password' => 'anomaly.field_type.text',
        'database' => 'anomaly.field_type.text',
        'basepath' => 'anomaly.field_type.text',
        'repository' => 'anomaly.field_type.text',
        'branch' => 'anomaly.field_type.text',
        'php' => [
            'type' => 'anomaly.field_type.select',
            'config' => [
                'handler' => \Visiosoft\ServerModule\Handler\PhpVersions::class
            ],
        ],
        'supervisor' => 'anomaly.field_type.textarea',
        'nginx' => 'anomaly.field_type.textarea',
        'deploy' => 'anomaly.field_type.textarea',
        'panel' => [
            'type' => 'anomaly.field_type.boolean',
            'config' => [
                'default_value' => false
            ],
        ],
    ];

    /**
     * The stream assignments.
     *
     * @var array
     */
    protected $assignments = [
        'site_id' => [
            'unique' => true,
            'required' => true,
        ],
        'server_id' => [
            'required' => true,
        ],
        'username' => [
            'required' => true,
        ],
        'password' => [
            'required' => true,
        ],
        'database' => [
            'required' => true,
        ],
        'basepath',
        'repository',
        'branch',
        'php' => [
            'required' => true,
        ],
        'supervisor',
        'nginx',
        'deploy',
        'panel',
    ];

}
