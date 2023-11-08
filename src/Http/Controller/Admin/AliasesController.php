<?php namespace Visiosoft\SiteModule\Http\Controller\Admin;

use Carbon\Carbon;
use Visiosoft\SiteModule\Alias\Contract\AliasRepositoryInterface;
use Visiosoft\SiteModule\Alias\Form\AliasFormBuilder;
use Visiosoft\SiteModule\Alias\Table\AliasTableBuilder;
use Anomaly\Streams\Platform\Http\Controller\AdminController;
use Visiosoft\SiteModule\Jobs\SslAliasSSH;

class AliasesController extends AdminController
{

    /**
     * Display an index of existing entries.
     *
     * @param AliasTableBuilder $table
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(AliasTableBuilder $table)
    {
        return $table->render();
    }

    /**
     * Create a new entry.
     *
     * @param AliasFormBuilder $form
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(AliasFormBuilder $form)
    {
        return $form->render();
    }

    /**
     * Edit an existing entry.
     *
     * @param AliasFormBuilder $form
     * @param        $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(AliasFormBuilder $form, $id)
    {
        return $form->render($id);
    }

    public function generateSSL(AliasRepositoryInterface $aliasRepository, string $aliasId)
    {
        if (!$alias = $aliasRepository->getAliasByAliasID($aliasId)) {
            abort(404);
        }

        SslAliasSSH::dispatch($alias)->delay(Carbon::now()->addSeconds(3));

        $this->messages->success(trans('module::message.ssl_started'));

        return $this->redirect->to('/admin/site/aliases');
    }
}
