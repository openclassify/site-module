<?php namespace Visiosoft\SiteModule\Site;

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
}
