<?php namespace Visiosoft\SiteModule\Site\Form;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Visiosoft\SiteModule\Helpers\Formatters;
use Visiosoft\SiteModule\Helpers\Log;
use Visiosoft\SiteModule\Jobs\NewAliasSSH;
use Visiosoft\SiteModule\Jobs\NewSiteSSH;

class SiteFormHandler
{
    public function handle(SiteFormBuilder $builder)
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

        try {
            NewSiteSSH::dispatch($entry)->delay(Carbon::now()->addSeconds(3));
        } catch (\Exception $e) {
            (new Log())->createLog('site_create', $e);
        }

    }
}
