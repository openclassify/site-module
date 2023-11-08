<?php namespace Visiosoft\SiteModule\Alias\Table;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Visiosoft\SiteModule\Helpers\AliasStatus;

class AliasTableColumns
{
    public function handle(AliasTableBuilder $builder)
    {
        $builder->setColumns([
            'domain',
            'site' => [
                'value' => 'entry.site.username'
            ],
            'ssl_status' => [
                'sortable' => false,
                'wrapper' => function (EntryInterface $entry) {
                    $icon = $entry->getSSLStatus() ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>';
                    $diff = $entry->updated_at->diffForHumans();
                    $class = $entry->updated_at->diff()->days > 60 ? 'text-danger' : '';
                    return '<span>' . $icon . '</span><br><strong class="' . $class . '">' . $diff . '</strong>';

                },
            ],
            'ssl_last_message' => [
                'value' => function (EntryInterface $entry) {
                    return AliasStatus::getAliasStatus($entry->getSSLStatusMessage());
                },
                'sortable' => false,
            ],
        ]);
    }
}
