<?php namespace Visiosoft\SiteModule\Site;

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
        return $this->hasMany(AliasModel::class);
    }
}
