<?php namespace Visiosoft\SiteModule\Alias\Form;

use Anomaly\Streams\Platform\Message\MessageBag;
use Anomaly\Streams\Platform\Support\Str;
use Carbon\Carbon;
use Visiosoft\SiteModule\Helpers\AliasStatus;
use Visiosoft\SiteModule\Helpers\Log;
use Visiosoft\SiteModule\Helpers\Validation;
use Visiosoft\SiteModule\Jobs\NewAliasSSH;

class AliasFormHandler
{
    public function handle(AliasFormBuilder $builder, MessageBag $bag)
    {
        if (!$builder->canSave()) {
            return;
        }

        if (!(new Validation())->checkAppDomain()) {
            $bag->error(trans('module::message.check_app_domain'));
            return;
        }

        $builder->saveForm();

        $entry = $builder->getFormEntry();
        $entry->setStatus(AliasStatus::CREATED);
        $entry->setAliasID();
        $entry->save();

        try {
            NewAliasSSH::dispatch($entry)->delay(Carbon::now()->addSeconds(3));
        } catch (\Exception $e) {
            $entry->setAttribute('status', AliasStatus::CREATE_FAIL);
            (new Log())->createLog('alias_create', $e);
        }

    }
}
