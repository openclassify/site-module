<?php

namespace Visiosoft\SiteModule\Http\Controller;

use Anomaly\Streams\Platform\Http\Controller\ResourceController;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Visiosoft\ServerModule\Server\Contract\ServerRepositoryInterface;
use Visiosoft\SiteModule\Alias\Command\CreateAlias;
use Visiosoft\SiteModule\Alias\Contract\AliasRepositoryInterface;
use Visiosoft\SiteModule\Helpers\AliasStatus;
use Visiosoft\SiteModule\Helpers\Log;
use Visiosoft\SiteModule\Helpers\Validation;
use Visiosoft\SiteModule\Http\Request\AddDomainRequest;
use Visiosoft\SiteModule\Http\Request\CreateSiteRequest;
use Visiosoft\SiteModule\Http\Request\SSLRequest;
use Visiosoft\SiteModule\Jobs\DeleteAliasSSH;
use Visiosoft\SiteModule\Jobs\NewAliasSSH;
use Visiosoft\SiteModule\Jobs\SslAliasSSH;
use Visiosoft\SiteModule\Services\CheckSsl;
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

            if (!(new Validation())->checkAppDomain()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('module::message.check_app_domain'),
                    'errors' => [trans('module::message.check_app_domain')]
                ], 400);
            }

            /**
             * Create Site
             */
            $siteCreator = new CreateSite($username, $server->getId(), $request->get('basepath'), $php);
            $site = $siteCreator->handle($this->sites);

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
            return $this->response->json([
                'success' => false,
                'message' => trans('streams::error.500.name'),
                'errors' => [trans('streams::error.500.name')]
            ], 500);
        }
    }

    public function addDomain(AddDomainRequest $request)
    {
        try {
            $request->validated();
            $domain = $request->get('domain');
            $siteId = $request->get('siteId');

            $site = $this->sites->getSiteBySiteID($siteId);

            $alias = $this->aliases->createAlias($site, $domain);

            NewAliasSSH::dispatch($alias)->delay(Carbon::now()->addSeconds(3));

            return response()->json([
                'success' => true,
                'data' => [
                    'alias_id' => $alias->getAttribute('alias_id'),
                    'site_id' => $alias->getSite()->getSiteID(),
                    'domain' => $alias->getDomain(),
                ]
            ]);
        } catch (\Exception $exception) {
            return $this->response->json([
                'success' => false,
                'message' => trans('streams::error.500.name'),
                'errors' => [trans('streams::error.500.name')]
            ], 500);
        }
    }

    public function makeSSL(SSLRequest $request)
    {
        try {
            $request->validated();
            $domain = $request->get('domain');

            $aliases = app(AliasRepositoryInterface::class);
            $alias = $aliases->findByDomain($domain);

            if (!$alias) {
                return response()->json([
                    'success' => false,
                    'message' => trans('visiosoft.module.site::message.domain_not_found'),
                    'errors' => [trans('visiosoft.module.site::message.domain_not_found')]
                ], 404);
            }

            // Create SSL
            SslAliasSSH::dispatch($alias)->delay(Carbon::now()->addSeconds(3));

            return response()->json([
                'success' => true,
                'data' => [
                    'alias_id' => $alias->getAttribute('alias_id'),
                    'site_id' => $alias->getSite()->getSiteID(),
                    'domain' => $alias->getDomain(),
                ]
            ]);
        } catch (\Exception $exception) {
            return $this->response->json([
                'success' => false,
                'message' => trans('streams::error.500.name'),
                'errors' => [trans('streams::error.500.name')]
            ], 500);
        }
    }

    public function verifySSL(SSLRequest $request)
    {
        try {
            $request->validated();
            $domain = $request->get('domain');

            $aliases = app(AliasRepositoryInterface::class);
            $alias = $aliases->findByDomain($domain);

            if (!$alias) {
                return response()->json([
                    'success' => false,
                    'message' => trans('visiosoft.module.site::message.domain_not_found'),
                    'errors' => [trans('visiosoft.module.site::message.domain_not_found')]
                ], 404);
            }

            $verify = (new CheckSsl("https://" . $alias->getDomain()))->handle();
            $alias->setSSLStatus($verify);

            return response()->json([
                'success' => true,
                'data' => [
                    'verify_ssl' => $verify,
                    'alias_id' => $alias->getAttribute('alias_id'),
                    'site_id' => $alias->getSite()->getSiteID(),
                    'domain' => $alias->getDomain(),
                ]
            ]);

        } catch (\Exception $exception) {
            return $this->response->json([
                'success' => false,
                'message' => trans('streams::error.500.name'),
                'errors' => [trans('streams::error.500.name')]
            ], 500);
        }
    }

    public function deleteAliases($site_id, $alises_id)
    {
        $entry = $this->aliases->findAliasBySiteID($alises_id, $site_id);
        if ($entry) {
            try {
                DeleteAliasSSH::dispatch($entry)->delay(Carbon::now()->addSeconds(1));
            } catch (\Exception $e) {
                $entry->setAttribute('status', AliasStatus::DELETE_FAIL);
                $entry->save();
                (new Log())->createLog('alias_delete', $e);
            }
        }
        abort(404);
    }
}
