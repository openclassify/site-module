<?php namespace Visiosoft\SiteModule\Site\Contract;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;

interface SiteInterface extends EntryInterface
{
    public function getUpdateStatus();

    public function getUpdateStatusMessage();

    public function setUpdateStatus($status, $status_message = null);
}
