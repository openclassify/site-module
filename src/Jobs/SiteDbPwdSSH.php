<?php

namespace Visiosoft\SiteModule\Jobs;

use phpseclib3\Net\SSH2;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SiteDbPwdSSH implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $site;
    protected $server;
    protected $oldpassword;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($site, $oldpassword)
    {
        $this->site = $site;
        $this->server = $site->server;
        $this->oldpassword = $oldpassword;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ssh = new SSH2($this->server->getIp(), 22);
        $ssh->login('pure', $this->server->getPassword());
        $ssh->setTimeout(360);
        $ssh->exec('echo ' . $this->server->getPassword() . ' | sudo -S sudo mysqladmin -u ' . $this->site->getUsername() . ' -p' . $this->oldpassword . ' password ' . $this->site->getDatabasePassword() . '');
        $ssh->exec('exit');
    }
}
