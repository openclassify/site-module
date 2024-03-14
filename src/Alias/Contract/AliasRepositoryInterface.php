<?php namespace Visiosoft\SiteModule\Alias\Contract;

use Anomaly\Streams\Platform\Entry\Contract\EntryRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Visiosoft\SiteModule\Site\Contract\SiteInterface;

interface AliasRepositoryInterface extends EntryRepositoryInterface
{
    /**
     * @param $aliasID
     * @return Builder|Model|object|null
     */
    public function getAliasByAliasID($aliasID);

    /**
     * @param $aliasID
     * @param $siteID
     * @return mixed
     */
    public function findAliasBySiteID($aliasID, $siteID);

    /**
     * @param string $domain
     * @return mixed
     */
    public function findByDomain(string $domain);

    /**
     * @param SiteInterface $site
     * @param string $domain
     * @return AliasInterface
     */
    public function createAlias(SiteInterface $site, string $domain): AliasInterface;
}
