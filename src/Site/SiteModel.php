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

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getDatabasePassword()
    {
        return $this->database;
    }

    public function getPhp()
    {
        return $this->php;
    }

    public function getBasepath()
    {
        return $this->basepath;
    }

    public function getSiteID()
    {
        return $this->site_id;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setPassword()
    {
        $this->setAttribute('password', Str::random(24));
        $this->save();
    }

    public function setDatabasePassword()
    {
        $this->setAttribute('database', Str::random(24));
        $this->save();
    }
}
