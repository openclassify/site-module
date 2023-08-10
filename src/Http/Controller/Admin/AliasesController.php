<?php namespace Visiosoft\SiteModule\Http\Controller\Admin;

use Visiosoft\SiteModule\Alias\Form\AliasFormBuilder;
use Visiosoft\SiteModule\Alias\Table\AliasTableBuilder;
use Anomaly\Streams\Platform\Http\Controller\AdminController;

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
}
