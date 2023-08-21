<?php namespace Visiosoft\SiteModule\Site;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Visiosoft\SiteModule\Site\Contract\SiteRepositoryInterface;
use Anomaly\Streams\Platform\Entry\EntryRepository;

class SiteRepository extends EntryRepository implements SiteRepositoryInterface
{

    /**
     * The entry model.
     *
     * @var SiteModel
     */
    protected $model;

    /**
     * Create a new SiteRepository instance.
     *
     * @param SiteModel $model
     */
    public function __construct(SiteModel $model)
    {
        $this->model = $model;
    }


    /**
     * @param $siteID
     * @return Builder|Model|object|null
     */
    public function getSiteBySiteID($siteID)
    {
        return $this->model->newQuery()->where('site_id', $siteID)->first();
    }
}
