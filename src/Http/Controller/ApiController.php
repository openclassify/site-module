<?php

namespace Visiosoft\SiteModule\Http\Controller;

use Anomaly\Streams\Platform\Http\Controller\ResourceController;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Firebase\JWT\JWT;
use Illuminate\Support\Str;
use Visiosoft\ServerModule\Http\Request\CreateSiteRequest;
use Visiosoft\ServerModule\Server\Contract\ServerRepositoryInterface;
use Visiosoft\SiteModule\Helpers\Formatters;
use Visiosoft\SiteModule\Helpers\Log;
use Visiosoft\SiteModule\Jobs\NewSiteSSH;
use Visiosoft\SiteModule\Site\Contract\SiteRepositoryInterface;

class ApiController extends ResourceController
{
    protected $sites;

    protected $servers;

    public function __construct(ServerRepositoryInterface $servers, SiteRepositoryInterface $sites)
    {
        $this->servers = $servers;
        $this->sites = $sites;
        parent::__construct();
    }

    public function create(CreateSiteRequest $request)
    {
        $request->validate();

        if ($request->php) {
            if (!in_array($request->php, config('visiosoft.module.server::pure.phpvers'))) {
                return response()->json([
                    'success' => false,
                    'message' => trans('visiosoft.module.site::message.bad_request'),
                    'errors' => [trans('visiosoft.module.site::message.invalid_php_version')]
                ], 400);
            }
            $php = $request->php;
        } else {
            $php = config('visiosoft.module.server::pure.default_php');
        }

        $server = $this->servers->findBy('server_id', $request->server_id)->where('status', 1)->first();

        if (!$server) {
            return response()->json([
                'success' => false,
                'message' => trans('visiosoft.module.site::message.server_not_found_message'),
                'errors' => [trans('visiosoft.module.site::message.server_not_found')]
            ], 404);
        }

        $pdftoken = JWT::encode(['iat' => time(), 'exp' => time() + 180], config('visiosoft.module.server::pure.jwt_secret') . '-Pdf');

        $site = $this->sites->getModel();
        $site->setAttribute('username', (new Formatters())->cleanUsername($request->get('username')));
        $site->setAttribute('site_id', Str::uuid()); // Auto Generated
        $site->setAttribute('database', Str::random(24)); // Auto Generated
        $site->setAttribute('password', Str::random(24)); // Auto Generated
        $site->setAttribute('server_id', $server->getId());
        $site->setAttribute('php', $php);
        $site->setAttribute('basepath', $request->basepath);
        $site->save();

        try {
            NewSiteSSH::dispatch($site)->delay(Carbon::now()->addSeconds(3));
        } catch (\Exception $e) {
            (new Log())->createLog('api_site_create', $e);
        }

        return response()->json([
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
            'basepath' => $site->getAttribute('basepath'),
            'pdf' => URL::to('/pdf/' . $site->getAttribute('site_id') . '/' . $pdftoken)
        ]);
    }
}
