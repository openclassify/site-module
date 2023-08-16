<?php

namespace Visiosoft\SiteModule\Jobs;

use phpseclib3\Net\SSH2;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SiteUserPwdSSH implements ShouldQueue
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
        $serverPassword = $this->server->password;

        $ssh = new SSH2($this->server->getIp(), 22);
        $ssh->login('pure', $serverPassword);
        $ssh->setTimeout(360);
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo unlink sitepass');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo wget ' . config('app.url') . '/sh/sitepass');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo dos2unix sitepass');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo bash sitepass  -u ' . $this->site->getUsername() . ' -p ' . $this->site->getPassword());
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo unlink sitepass');
        $ssh->exec('exit');
    }
}
