<?php

namespace Visiosoft\SiteModule\Jobs;

use Carbon\Carbon;
use phpseclib3\Net\SSH2;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NewAliasSSH implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $alias;
    protected $site;
    protected $server;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($alias)
    {
        $this->alias = $alias;
        $this->site = $alias->site;
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
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo wget ' . config('app.url') . '/conf/alias/' . $this->alias->getAliasID() . ' -O /etc/nginx/sites-available/' . $this->alias->getDomain() . '.conf');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo dos2unix /etc/nginx/sites-available/' . $this->alias->getDomain() . '.conf');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo ln -s /etc/nginx/sites-available/' . $this->alias->getDomain() . '.conf /etc/nginx/sites-enabled/' . $this->alias->getDomain() . '.conf');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo service php' . $this->site->getPhp() . '-fpm restart');
        $ssh->exec('echo ' . $serverPassword . ' | sudo -S sudo systemctl reload nginx.service');
        $ssh->exec('exit');

        // Create SSL
        SslAliasSSH::dispatch($this->alias)->delay(Carbon::now()->addSeconds(3));
    }
}
