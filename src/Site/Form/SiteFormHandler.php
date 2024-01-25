<?php namespace Visiosoft\SiteModule\Site\Form;

use Anomaly\Streams\Platform\Message\MessageBag;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Visiosoft\SiteModule\Helpers\Formatters;
use Visiosoft\SiteModule\Helpers\Log;
use Visiosoft\SiteModule\Helpers\Validation;
use Visiosoft\SiteModule\Jobs\NewSiteSSH;

class SiteFormHandler
{
    public function handle(SiteFormBuilder $builder, MessageBag $bag)
    {
        if (!$builder->canSave()) {
            return;
        }

        $builder->saveForm();
        $entry = $builder->getFormEntry();
        $entry->setAttribute('username', (new Formatters())->cleanUsername($entry->username));
        $entry->setAttribute('site_id', Str::uuid()); // Auto Generated
        $entry->setAttribute('database', Str::random(24)); // Auto Generated
        $entry->setAttribute('password', Str::random(24)); // Auto Generated
        $entry->save();

        if (!(new Validation())->checkAppDomain()) {
            $bag->error(trans('module::message.check_app_domain'));
            return;
        }

        try {
            NewSiteSSH::dispatch($entry)->delay(Carbon::now()->addSeconds(3));
        } catch (\Exception $e) {
            (new Log())->createLog('site_create', $e);
        }

    }
}
