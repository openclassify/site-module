<?php namespace Visiosoft\SiteModule\Site\Table;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Visiosoft\SiteModule\Helpers\UpdateStatus;

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
            'update' => [
                'wrapper' => function (EntryInterface $entry) {
                    $value = '-';
                    if ($entry->getUpdateStatus()) {
                        $update_timeago = $entry->updated_at->diffForHumans();
                        $update_status = UpdateStatus::getUpdateStatus($entry->getUpdateStatus());
                        $update_status_message = $entry->getUpdateStatusMessage();
                        $value = '<small class="fa fa-hand-pointer-o" data-toggle="tooltip" title="' . $update_status_message . '"> ' . $update_status . '</small><br><small>' . $update_timeago . '</small>';
                    }
                    return $value;
                }
            ],
        ];

        $builder->setColumns($columns);
    }
}