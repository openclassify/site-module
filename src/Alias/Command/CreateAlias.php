<?php namespace Visiosoft\SiteModule\Alias\Command;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Visiosoft\SiteModule\Alias\Contract\AliasInterface;
use Visiosoft\SiteModule\Alias\Contract\AliasRepositoryInterface;
use Visiosoft\SiteModule\Helpers\AliasStatus;
use Visiosoft\SiteModule\Helpers\Log;
use Visiosoft\SiteModule\Jobs\NewAliasSSH;
use Visiosoft\SiteModule\Site\Contract\SiteInterface;

//class CreateAlias implements ShouldQueue
class CreateAlias
{
//    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected SiteInterface $site;
    protected string $domain;

    public function __construct(SiteInterface $site, string $domain)
    {
        $this->site = $site;
        $this->domain = $domain;
    }

    public function handle(AliasRepositoryInterface $aliases): AliasInterface
    {
        $alias = $aliases->getModel();
        $alias->setAttribute('alias_id', Str::uuid());
        $alias->setAttribute('site_id', $this->site->getId());
        $alias->setAttribute('domain', $this->domain);
        $alias->save();

        try {
            NewAliasSSH::dispatch($alias)->delay(Carbon::now()->addSeconds(3));
        } catch (\Exception $e) {
            $alias->setAttribute('status', AliasStatus::CREATE_FAIL);
            $alias->save();
            (new Log())->createLog('alias_create', $e);
        }

        return $alias;
    }
}
