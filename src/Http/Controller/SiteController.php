<?php

namespace Visiosoft\SiteModule\Http\Controller;

use Anomaly\Streams\Platform\Http\Controller\PublicController;
use App\Jobs\EditSitePhpSSH;
use Carbon\Carbon;
use Visiosoft\SiteModule\Alias\AliasModel;
use Visiosoft\SiteModule\Alias\Contract\AliasRepositoryInterface;
use Visiosoft\SiteModule\Helpers\Host;
use Visiosoft\SiteModule\Helpers\Log;
use Visiosoft\SiteModule\Jobs\DeleteAliasSSH;
use Visiosoft\SiteModule\Site\Contract\SiteRepositoryInterface;

class SiteController extends PublicController
{

    private SiteRepositoryInterface $siteRepository;
    private AliasRepositoryInterface $aliasRepository;

    public function __construct()
    {
        $this->siteRepository = app(SiteRepositoryInterface::class);
        $this->aliasRepository = app(AliasRepositoryInterface::class);
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

    /**
     * @param string $site_id
     * @param string $alias_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyalias(string $site_id, string $alias_id)
    {
        $site = $this->siteRepository->getSiteBySiteID($site_id);

        if (!$site) {
            return response()->json([
                'message' => trans('visiosoft.module.site::message.not_found_message_default', ['name' => 'Site']),
                'errors' => trans('visiosoft.module.site::message.not_found', ['name' => 'Site'])
            ], 404);
        }

        $alias = $this->aliasRepository->findAliasBySiteID($alias_id, $site->getId());

        if (!$alias) {
            return response()->json([
                'message' => trans('visiosoft.module.site::message.not_found_message_default', ['name' => 'Alias']),
                'errors' => trans('visiosoft.module.site::message.not_found', ['name' => 'Alias'])
            ], 404);
        }

        DeleteAliasSSH::dispatch($alias)->delay(Carbon::now()->addSeconds(1));

        $alias->delete();

        return response()->json([]);
    }

    /**
     * @param Request $request
     * @param string $siteId
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(string $siteId)
    {
        $site = $this->siteRepository->getSiteBySiteID($siteId);

        if (!$site) {
            return response()->json([
                'message' => trans('visiosoft.module.site::message.not_found_message_default', ['name' => 'Site']),
                'errors' => trans('visiosoft.module.site::message.not_found', ['name' => 'Site'])
            ], 404);
        }

        if (request()->get('php')) {
            if ($site->php != request()->get('php')) {
                $lastPhp = $site->php;
                $site->php = request()->get('php');
                $site->save();
                EditSitePhpSSH::dispatch($site, $lastPhp)->delay(Carbon::now()->addSeconds(10));
            }
        }

        $site->save();

        return response()->json([]);
    }
}
