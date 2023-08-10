<?php namespace Visiosoft\SiteModule\Alias;

use Visiosoft\SiteModule\Alias\Contract\AliasInterface;
use Anomaly\Streams\Platform\Model\Site\SiteAliasesEntryModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AliasModel extends SiteAliasesEntryModel implements AliasInterface
{
    use HasFactory;

    /**
     * @return AliasFactory
     */
    protected static function newFactory()
    {
        return AliasFactory::new();
    }
}
