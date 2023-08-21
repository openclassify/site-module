<?php namespace Visiosoft\SiteModule\Alias\Contract;

use Anomaly\Streams\Platform\Entry\Contract\EntryRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface AliasRepositoryInterface extends EntryRepositoryInterface
{
    /**
     * @param $aliasID
     * @return Builder|Model|object|null
     */
    public function getAliasByAliasID($aliasID);
}
