<?php namespace Visiosoft\SiteModule\Site\Contract;

use Anomaly\Streams\Platform\Entry\Contract\EntryRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface SiteRepositoryInterface extends EntryRepositoryInterface
{

    /**
     * @param $siteID
     * @return Builder|Model|object|null
     */
    public function getSiteBySiteId($siteID);
}
