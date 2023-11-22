<?php namespace Visiosoft\SiteModule\Alias\Command;

use Illuminate\Support\Str;
use Visiosoft\SiteModule\Alias\Contract\AliasInterface;
use Visiosoft\SiteModule\Alias\Contract\AliasRepositoryInterface;
use Visiosoft\SiteModule\Site\Contract\SiteInterface;

class CreateAlias
{
    protected SiteInterface $site;
    protected string $domain;

    public function __construct(SiteInterface $site, string $domain)
    {
        $this->site = $site;
        $this->domain = $domain;
    }

    public function handle(AliasRepositoryInterface $aliases): AliasInterface
    {
        $alias = $aliases->getModel();
        $alias->setAttribute('alias_id', Str::uuid());
        $alias->setAttribute('site_id', $this->site->getId());
        $alias->setAttribute('domain', $this->domain);
        $alias->save();

        return $alias;
    }
}
