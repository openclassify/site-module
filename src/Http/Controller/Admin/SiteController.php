<?php namespace Visiosoft\SiteModule\Http\Controller\Admin;

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
}
