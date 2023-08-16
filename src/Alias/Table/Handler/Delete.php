<?php namespace Visiosoft\SiteModule\Alias\Table\Handler;

use Anomaly\Streams\Platform\Model\EloquentModel;
use Anomaly\Streams\Platform\Ui\Table\Component\Action\ActionHandler;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Carbon\Carbon;
use Visiosoft\SiteModule\Helpers\AliasStatus;
use Visiosoft\SiteModule\Helpers\Log;
use Visiosoft\SiteModule\Jobs\DeleteAliasSSH;

class Delete extends ActionHandler
{
    public function handle(TableBuilder $builder, array $selected)
    {
        $count = 0;

        $model = $builder->getTableModel();

        /* @var EloquentModel $entry */
        foreach ($selected as $id) {

            $entry = $model->find($id);
            $deletable = true;

            if ($entry instanceof EloquentModel) {
                $deletable = $entry->isDeletable();
            }

            if ($entry && $deletable) {
                try {
                    DeleteAliasSSH::dispatch($entry)->delay(Carbon::now()->addSeconds(1));
                } catch (\Exception $e) {
                    $entry->setAttribute('status', AliasStatus::DELETE_FAIL);
                    $entry->save();
                    (new Log())->createLog('alias_delete', $e);
                }

                $entry->delete();

                $count++;
            }
        }

        if ($count) {
            $builder->fire('rows_deleted', compact('count', 'builder', 'model'));
        }

        if ($selected && $count > 0) {
            $this->messages->success(trans('streams::message.delete_success', compact('count')));
        }

        if ($selected && $count === 0) {
            $this->messages->warning(trans('streams::message.delete_success', compact('count')));
        }
    }
}
