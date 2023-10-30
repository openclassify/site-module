<?php

namespace Visiosoft\SiteModule\Jobs;

use phpseclib3\Net\SSH2;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class NewSiteSSH implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $server;
    protected $site;

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
        $serverPassword = $this->server->getPassword();
        $remote = str_replace('https', 'http', config('app.url'));

        $ssh = new SSH2($this->server->getIp(), 22);
        $ssh->login('pure', $serverPassword);
        $ssh->setTimeout(360);
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo unlink newsite');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo wget ' . config('app.url') . '/sh/newsite');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo dos2unix newsite');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo bash newsite -dbr ' . $this->server->getDatabasePassword() . ' -u ' . $this->site->getUsername() . ' -p ' . $this->site->getPassword() . ' -dbp ' . $this->site->getDatabasePassword() . ' -php ' . $this->site->getPhp() . ' -id ' . $this->site->getSiteID() . ' -r ' . $remote . ' -b ' . $this->site->getBasepath());
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo unlink newsite');
        $ssh->exec('exit');
    }
}
