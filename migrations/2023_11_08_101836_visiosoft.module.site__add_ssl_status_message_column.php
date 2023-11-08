<?php

use Anomaly\Streams\Platform\Database\Migration\Migration;

class VisiosoftModuleSiteAddSslStatusMessageColumn extends Migration
{

    protected $stream = [
        'slug' => 'aliases',
    ];

    protected $fields = [
        'ssl_status_message' => 'anomaly.field_type.textarea'
    ];

    protected $assignments = [
        'ssl_status_message'
    ];
}
