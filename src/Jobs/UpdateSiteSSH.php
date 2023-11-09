<?php

namespace Visiosoft\SiteModule\Jobs;

use phpseclib3\Net\SSH2;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Visiosoft\SiteModule\Helpers\UpdateStatus;

class UpdateSiteSSH implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $site;
    protected $server;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($site)
    {
        $this->site = $site;
        $this->server = $site->server;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->site->setUpdateStatus(UpdateStatus::WAITING);
        $serverPassword = $this->server->getPassword();

        $ssh = new SSH2($this->server->ip, 22);
        $ssh->login('pure', $serverPassword);
        $ssh->setTimeout(360);
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo unlink updatesite');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo wget ' . config('app.url') . '/sh/updatesite');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo dos2unix updatesite');
        $response = $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo bash updatesite');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo unlink updatesite');
        $ssh->exec('exit');

        $this->site->setUpdateStatus(UpdateStatus::UPDATED,$response);
    }
}
