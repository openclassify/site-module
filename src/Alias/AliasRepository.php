<?php namespace Visiosoft\SiteModule\Alias;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Visiosoft\SiteModule\Alias\Contract\AliasRepositoryInterface;
use Anomaly\Streams\Platform\Entry\EntryRepository;

class AliasRepository extends EntryRepository implements AliasRepositoryInterface
{

    /**
     * The entry model.
     *
     * @var AliasModel
     */
    protected $model;

    /**
     * Create a new AliasRepository instance.
     *
     * @param AliasModel $model
     */
    public function __construct(AliasModel $model)
    {
        $this->model = $model;
    }

    /**
     * @param $aliasID
     * @return Builder|Model|object|null
     */
    public function getAliasByAliasID($aliasID)
    {
        return $this->model->newQuery()->where('alias_id', $aliasID)->first();
    }

    /**
     * @param $aliasID
     * @param $siteID
     * @return Builder|Model|object|null
     */
    public function findAliasBySiteID($aliasID, $siteID)
    {
        return $this->newQuery()
            ->where('site_id', $siteID)
            ->where('alias_id', $aliasID)
            ->first();
    }

    /**
     * @param string $domain
     * @return Builder|Model|mixed|object|null
     */
    public function findByDomain(string $domain)
    {
        return $this->newQuery()
            ->where('domain', $domain)
            ->first();
    }
}
