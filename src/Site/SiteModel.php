<?php namespace Visiosoft\SiteModule\Site;

use Illuminate\Support\Str;
use Visiosoft\ServerModule\Server\ServerModel;
use Visiosoft\SiteModule\Alias\AliasModel;
use Visiosoft\SiteModule\Site\Contract\SiteInterface;
use Anomaly\Streams\Platform\Model\Site\SiteSiteEntryModel;

class SiteModel extends SiteSiteEntryModel implements SiteInterface
{
    public function server()
    {
        return $this->belongsTo(ServerModel::class);
    }

    public function aliases()
    {
        return $this->hasMany(AliasModel::class, 'site_id', 'id');
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDatabasePassword(): string
    {
        return $this->database;
    }

    public function getPhp(): string
    {
        return $this->php;
    }

    public function getBasepath(): string
    {
        return $this->basepath ?? '';
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getDirectoryPath(): string
    {
        return "/home" . "/" . $this->getUsername() . "/web/";
    }

    public function getSiteID(): string
    {
        return $this->site_id;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setPassword(): void
    {
        $this->setAttribute('password', Str::random(24));
        $this->save();
    }

    public function setDatabasePassword(): void
    {
        $this->setAttribute('database', Str::random(24));
        $this->save();
    }
}
