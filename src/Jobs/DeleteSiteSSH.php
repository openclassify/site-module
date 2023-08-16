<?php

namespace Visiosoft\SiteModule\Jobs;

use phpseclib3\Net\SSH2;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class DeleteSiteSSH implements ShouldQueue
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
        $serverPassword = $this->server->getPassword();

        $ssh = new SSH2($this->server->ip, 22);
        $ssh->login('pure', $serverPassword);
        $ssh->setTimeout(360);
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo unlink delsite');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo wget ' . config('app.url') . '/sh/delsite');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo dos2unix delsite');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo bash delsite -dbr ' . $this->server->getDatabasePassword() . ' -u ' . $this->site->getUsername() . ' -p ' . $this->site->getPhp());
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo unlink delsite');
        if ($this->site->aliases) {
            foreach ($this->site->aliases as $alias) {
                $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo unlink /etc/nginx/sites-enabled/' . $alias->getDomain() . '.conf');
                $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo unlink /etc/nginx/sites-available/' . $alias->getDomain() . '.conf');
            }
        }
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo systemctl restart nginx.service');
        $ssh->exec('exit');
    }
}
