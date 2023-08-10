<?php namespace Visiosoft\SiteModule\Alias;

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
}
