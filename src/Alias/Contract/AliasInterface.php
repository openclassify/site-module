<?php namespace Visiosoft\SiteModule\Alias\Contract;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;

interface AliasInterface extends EntryInterface
{
    public function getSSLStatus();

    public function setSSLStatus($status);

    public function setSSLStatusMessage(string $message);

    public function getSSLStatusMessage();

    public function getSite();
}
