<?php namespace Visiosoft\SiteModule\Http\Controller\Admin;

use Carbon\Carbon;
use Visiosoft\SiteModule\Helpers\Log;
use Visiosoft\SiteModule\Jobs\SiteDbPwdSSH;
use Visiosoft\SiteModule\Jobs\SiteUserPwdSSH;
use Visiosoft\SiteModule\Site\Contract\SiteRepositoryInterface;
use Visiosoft\SiteModule\Site\Form\SiteFormBuilder;
use Visiosoft\SiteModule\Site\Table\SiteTableBuilder;
use Anomaly\Streams\Platform\Http\Controller\AdminController;

class SiteController extends AdminController
{

    /**
     * Display an index of existing entries.
     *
     * @param SiteTableBuilder $table
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(SiteTableBuilder $table)
    {
        return $table->render();
    }

    /**
     * Create a new entry.
     *
     * @param SiteFormBuilder $form
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(SiteFormBuilder $form)
    {
        return $form->render();
    }

    /**
     * Edit an existing entry.
     *
     * @param SiteFormBuilder $form
     * @param        $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(SiteFormBuilder $form, $id)
    {
        return $form->render($id);
    }

    public function info($siteID, SiteRepositoryInterface $siteRepository)
    {

        $site = $siteRepository->getSiteBySiteId($siteID);
        $server = $site->server;
        return $this->view->make('module::info', compact(['site', 'server']));
    }

    public function resetMysqlPassword($siteID, SiteRepositoryInterface $siteRepository)
    {
        $site = $siteRepository->getSiteBySiteId($siteID);
        $oldPassowrd = $site->getDatabasePassword();
        $site->setDatabasePassword();
        $server = $site->server;

        try {
            SiteDbPwdSSH::dispatch($site, $oldPassowrd)->delay(Carbon::now()->addSeconds(3));
        } catch (\Exception $e) {
            (new Log())->createLog('reset_mysql_password', $e);
        }

        return $this->view->make('module::info', compact(['site', 'server']));

    }

    public function resetSshPassword($siteID, SiteRepositoryInterface $siteRepository)
    {
        $site = $siteRepository->getSiteBySiteId($siteID);
        $oldPassowrd = $site->getPassword();
        $site->setPassword();
        $server = $site->server;

        try {
            SiteUserPwdSSH::dispatch($site, $oldPassowrd)->delay(Carbon::now()->addSeconds(3));
        } catch (\Exception $e) {
            (new Log())->createLog('reset_mysql_password', $e);
        }
        return $this->view->make('module::info', compact(['site', 'server']));

    }
}
