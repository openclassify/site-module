<?php

use Anomaly\Streams\Platform\Database\Migration\Migration;

class VisiosoftModuleSiteAddSiteUpdateStatus extends Migration
{
    protected $stream = [
        'slug' => 'site',
    ];

    protected $fields = [
        'update_status' => 'anomaly.field_type.integer',
        'update_status_message' => 'anomaly.field_type.textarea',
    ];

    protected $assignments = [
        'update_status',
        'update_status_message'
    ];
}
