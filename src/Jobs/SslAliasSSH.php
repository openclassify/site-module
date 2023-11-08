<?php

namespace Visiosoft\SiteModule\Jobs;

use phpseclib3\Net\SSH2;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Visiosoft\SiteModule\Helpers\AliasStatus;

class SslAliasSSH implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $alias;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $site = $this->alias->site;

        $ssh = new SSH2($site->server->ip, 22);
        $ssh->login('pure', $site->server->password);
        $ssh->setTimeout(360);

        $this->alias->setSSLStatusMessage(AliasStatus::SSL_STARTED);

        try {
            $ssh->exec('echo ' . $site->server->password . ' | sudo -S sudo fuser -k 80/tcp');
            $ssh->exec('echo ' . $site->server->password . ' | sudo -S sudo fuser -k 443/tcp');
            $ssh->exec('echo ' . $site->server->password . ' | sudo -S sudo systemctl restart nginx.service');
            $ssh->exec('echo ' . $site->server->password . ' | sudo -S sudo ufw disable');
            $ssh->exec('echo ' . $site->server->password . ' | sudo -S sudo certbot --nginx -d ' . $this->alias->domain . ' --non-interactive --agree-tos --register-unsafely-without-email');
            $ssh->exec("echo " . $site->server->password . " | sudo -S sudo sed -i 's/443 ssl/443 ssl http2/g' /etc/nginx/sites-enabled/" . $this->alias->domain . ".conf");
            $ssh->exec('echo ' . $site->server->password . ' | sudo -S sudo ufw --force enable');
            $ssh->exec('echo ' . $site->server->password . ' | sudo -S sudo systemctl restart nginx.service');

            $this->alias->setSSLStatus(true);
        } catch (\Exception $exception) {
            $this->alias->setSSLStatusMessage(AliasStatus::SSL_FAIL);
        }
        $ssh->exec('exit');
    }
}