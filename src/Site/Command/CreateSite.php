<?php namespace Visiosoft\SiteModule\Site\Command;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Visiosoft\SiteModule\Helpers\Formatters;
use Visiosoft\SiteModule\Helpers\Log;
use Visiosoft\SiteModule\Jobs\NewSiteSSH;
use Visiosoft\SiteModule\Site\Contract\SiteInterface;
use Visiosoft\SiteModule\Site\Contract\SiteRepositoryInterface;

class CreateSite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected string $username;
    protected int $server_id;
    protected $basepath;
    protected $php;

    public function __construct(string $username, int $server_id, $basepath = null, $php = null)
    {
        $this->username = $username;
        $this->server_id = $server_id;
        $this->basepath = $basepath;
        $this->php = $php;
    }

    public function handle(SiteRepositoryInterface $sites): SiteInterface
    {
        if (!$this->php) {
            $this->php = config('visiosoft.module.server::pure.default_php');
        }

        $site = $sites->getModel();
        $site->setAttribute('username', (new Formatters())->cleanUsername($this->username));
        $site->setAttribute('site_id', Str::uuid()); // Auto Generated
        $site->setAttribute('database', Str::random(24)); // Auto Generated
        $site->setAttribute('password', Str::random(24)); // Auto Generated
        $site->setAttribute('server_id', $this->server_id);
        $site->setAttribute('php', $this->php);
        $site->setAttribute('basepath', $this->basepath);
        $site->save();

        /**
         * Create Site in Server
         */
        try {
            NewSiteSSH::dispatch($site)->delay(Carbon::now()->addSeconds(3));
        } catch (\Exception $e) {
            (new Log())->createLog('site_create', $e);
        }

        return $site;
    }
}
