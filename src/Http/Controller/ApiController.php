<?php

namespace Visiosoft\SiteModule\Http\Controller;

use Anomaly\Streams\Platform\Http\Controller\ResourceController;
use Illuminate\Support\Str;
use Visiosoft\ServerModule\Server\Contract\ServerRepositoryInterface;
use Visiosoft\SiteModule\Alias\Command\CreateAlias;
use Visiosoft\SiteModule\Alias\Contract\AliasRepositoryInterface;
use Visiosoft\SiteModule\Http\Request\CreateSiteRequest;
use Visiosoft\SiteModule\Site\Command\CreateSite;
use Visiosoft\SiteModule\Site\Contract\SiteRepositoryInterface;

class ApiController extends ResourceController
{
    /**
     * @var SiteRepositoryInterface
     */
    protected $sites;

    /**
     * @var ServerRepositoryInterface
     */
    protected $servers;

    /**
     * @var AliasRepositoryInterface
     */
    protected $aliases;

    /**
     * @param ServerRepositoryInterface $servers
     * @param AliasRepositoryInterface $aliases
     * @param SiteRepositoryInterface $sites
     */
    public function __construct(
        ServerRepositoryInterface $servers,
        AliasRepositoryInterface  $aliases,
        SiteRepositoryInterface   $sites
    )
    {
        $this->servers = $servers;
        $this->aliases = $aliases;
        $this->sites = $sites;
        parent::__construct();
    }

    /**
     * @param CreateSiteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateSiteRequest $request)
    {
        try {
            $request->validated();
            $username = $request->get('username');

            if (!$request->get('username')) {
                $username = Str::slug($request->get('domain'), '');
            }

            /**
             * Select PHP Version
             */
            if ($request->has('php')) {
                if (!in_array($request->get('php'), config('visiosoft.module.server::pure.phpvers'))) {
                    return response()->json([
                        'success' => false,
                        'message' => trans('visiosoft.module.site::message.bad_request'),
                        'errors' => [trans('visiosoft.module.site::message.invalid_php_version')]
                    ], 400);
                }
                $php = $request->get('php');
            } else {
                $php = config('visiosoft.module.server::pure.default_php');
            }

            /**
             * Select Server
             */
            if ($request->has('serverId')) {
                $server = $this->servers->findBy('server_id', $request->serverId)->where('status', 1)->first();

                if (!$server) {
                    return response()->json([
                        'success' => false,
                        'message' => trans('visiosoft.module.site::message.server_not_found_message'),
                        'errors' => [trans('visiosoft.module.site::message.server_not_found')]
                    ], 404);
                }
            } else {
                $server = $this->servers->getDefaultServer();
            }

            /**
             * Create Site
             */
            $site = dispatch_sync(new CreateSite($username, $server->getId(), $request->get('basepath'), $php));

            dispatch_sync(new CreateAlias($site, $request->get('domain')));

            return response()->json([
                'success' => true,
                'data' => [
                    'site_id' => $site->getAttribute('site_id'),
                    'username' => $site->getAttribute('username'),
                    'password' => $site->getAttribute('password'),
                    'database' => $site->getAttribute('username'),
                    'database_username' => $site->getAttribute('username'),
                    'database_password' => $site->getAttribute('database'),
                    'server_id' => $server->getAttribute('server_id'),
                    'server_name' => $server->getAttribute('name'),
                    'server_ip' => $server->getAttribute('ip'),
                    'php' => $site->getAttribute('php'),
                    'basepath' => $site->getAttribute('basepath')
                ]
            ]);
        } catch (\Exception $exception) {
            dd($exception);
            return $this->response->json([
                'success' => false,
                'message' => trans('streams::error.500.name'),
                'errors' => [trans('streams::error.500.name')]
            ], 500);
        }
    }
}
