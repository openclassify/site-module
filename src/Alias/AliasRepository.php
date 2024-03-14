<?php namespace Visiosoft\SiteModule\Alias;

use Anomaly\Streams\Platform\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Visiosoft\SiteModule\Alias\Contract\AliasInterface;
use Visiosoft\SiteModule\Alias\Contract\AliasRepositoryInterface;
use Anomaly\Streams\Platform\Entry\EntryRepository;
use Visiosoft\SiteModule\Helpers\AliasStatus;
use Visiosoft\SiteModule\Site\Contract\SiteInterface;

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

    /**
     * @param SiteInterface $site
     * @param string $domain
     * @return AliasInterface
     */
    public function createAlias(SiteInterface $site, string $domain): AliasInterface
    {
        return $this->model->create([
            'status' => AliasStatus::CREATED,
            'alias_id' => Str::uuid(),
            'domain' => $domain,
            'site_id' => $site->getId()
        ]);
    }
}
