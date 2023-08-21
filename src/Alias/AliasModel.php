<?php namespace Visiosoft\SiteModule\Alias;

use Anomaly\Streams\Platform\Support\Str;
use Visiosoft\SiteModule\Alias\Contract\AliasInterface;
use Anomaly\Streams\Platform\Model\Site\SiteAliasesEntryModel;
use Visiosoft\SiteModule\Helpers\AliasStatus;

class AliasModel extends SiteAliasesEntryModel implements AliasInterface
{

    public function getDomain()
    {
        return $this->domain;
    }

    public function getAliasID()
    {
        return $this->alias_id;
    }

    public function setAliasID()
    {
        $this->setAttribute('alias_id', Str::uuid());
        $this->save();
    }

    public function setStatus($aliasStatus)
    {
        $this->setAttribute('status', $aliasStatus);
        $this->save();
    }

    public function getStatus(): ?string
    {
        return AliasStatus::getAliasStatus($this->status);
    }
}
