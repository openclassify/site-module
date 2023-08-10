<?php namespace Visiosoft\SiteModule\Site;

use Visiosoft\SiteModule\Site\Contract\SiteInterface;
use Anomaly\Streams\Platform\Model\Site\SiteSiteEntryModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteModel extends SiteSiteEntryModel implements SiteInterface
{
    use HasFactory;

    /**
     * @return SiteFactory
     */
    protected static function newFactory()
    {
        return SiteFactory::new();
    }
}
