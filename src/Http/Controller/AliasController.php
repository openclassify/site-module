<?php

namespace Visiosoft\SiteModule\Http\Controller;

use Anomaly\Streams\Platform\Http\Controller\PublicController;
use Visiosoft\SiteModule\Alias\Contract\AliasRepositoryInterface;
use Visiosoft\SiteModule\Helpers\Host;

class AliasController extends PublicController
{

    private AliasRepositoryInterface $aliasRepository;

    public function __construct()
    {
        $this->aliasRepository = app(AliasRepositoryInterface::class);
    }

    /**
     * Site alias configuration
     *
     */
    public function getNginxConfig($aliasID)
    {
        $alias = $this->aliasRepository->getAliasByAliasID($aliasID);
        $site = $alias->site;

        $config = (new Host())->getNginxConfig($site->getUsername(), $site->getPhp(), $alias->getDomain(), $site->getBasepath(),);

        return response($config)->withHeaders(['Content-Type' => 'text/plain']);
    }

}
