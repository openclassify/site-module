<?php

namespace Visiosoft\SiteModule\Jobs;

use phpseclib3\Net\SSH2;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteAliasSSH implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $server;
    protected $alias;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($alias)
    {
        $this->alias = $alias;
        $this->server = $alias->site->server;

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
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo unlink /etc/nginx/sites-enabled/' . $this->alias->getDomain() . '.conf');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo unlink /etc/nginx/sites-available/' . $this->alias->getDomain() . '.conf');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo systemctl restart nginx.service');
        $ssh->exec('exit');
    }
}
