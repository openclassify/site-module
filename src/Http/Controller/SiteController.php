<?php

namespace Visiosoft\SiteModule\Http\Controller;

use Anomaly\Streams\Platform\Http\Controller\PublicController;
use Visiosoft\SiteModule\Helpers\Host;
use Visiosoft\SiteModule\Helpers\Log;
use Visiosoft\SiteModule\Site\Contract\SiteRepositoryInterface;

class SiteController extends PublicController
{

    private SiteRepositoryInterface $siteRepository;

    public function __construct()
    {
        $this->siteRepository = app(SiteRepositoryInterface::class);
    }

    public function setup()
    {
        $config = (new Host())->getNewSiteScript();
        return response($config)
            ->withHeaders(['Content-Type' => 'application/x-sh']);
    }

    public function deleteSite()
    {
        $config = (new Host())->getDeleteSiteScript();
        return response($config)
            ->withHeaders(['Content-Type' => 'application/x-sh']);
    }

    /**
     * Site host configuration
     *
     */
    public function getNginxConfig($siteID)
    {
        $site = $this->siteRepository->getSiteBySiteID($siteID);
        if (!$site) {
            (new Log())->createLog('get_nginx_config', "$siteID: site not found");
        }

        $config = (new Host())->getNginxConfig($site->getUsername(), $site->getPhp(), $site->getDomain(), $site->getBasepath());

        return response($config)->withHeaders(['Content-Type' => 'text/plain']);
    }


    /**
     * Site PHP configuration
     *
     */
    public function getPhpConfig($siteID)
    {
        $site = $this->siteRepository->getSiteBySiteID($siteID);
        $config = (new Host())->getPhpConfig($site->getUsername(), $site->getPhp());
        return response($config)->withHeaders(['Content-Type' => 'text/plain']);
    }


    /**
     * Site nginx configuration
     *
     */
    public function getCustomNginxConfig()
    {
        $config = (new Host())->getCustomNginxConfig();
        return response($config)->withHeaders(['Content-Type' => 'text/plain']);
    }


    /**
     * Site supervisor configuration
     *
     */
    public function getSupervisorConfig()
    {
        $config = (new Host())->getSupervisorConfig();
        return response($config)->withHeaders(['Content-Type' => 'text/plain']);
    }
}
