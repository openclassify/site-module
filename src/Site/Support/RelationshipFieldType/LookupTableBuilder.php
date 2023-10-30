<?php

namespace Visiosoft\SiteModule\Site\Support\RelationshipFieldType;

class LookupTableBuilder extends \Anomaly\RelationshipFieldType\Table\LookupTableBuilder
{
    protected $filters = [
        'username','site_id'
    ];
    protected $columns = [
        'username',
        'site_id',
    ];
}