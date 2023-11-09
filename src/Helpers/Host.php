<?php

namespace Visiosoft\SiteModule\Helpers;


use Visiosoft\SiteModule\SiteModuleInterface;

class Host
{
    private string $resourcesPath;

    public function __construct()
    {
        $this->resourcesPath = __DIR__ . '/../../resources/';
    }

    /**
     * @return string
     */
    public function getNewSiteScript(): string
    {
        return file_get_contents($this->resourcesPath . 'scripts/newsite.sh');
    }

    /**
     * @return string
     */
    public function getDeleteSiteScript(): string
    {
        return file_get_contents($this->resourcesPath . 'scripts/delsite.sh');
    }

    /**
     * @return string
     */
    public function getUpdateSiteScript(): string
    {
        return file_get_contents($this->resourcesPath . 'scripts/updatesite.sh');
    }

    /**
     * @param string $username
     * @param string $php
     * @param string|null $domain
     * @param null $basepath
     * @return string
     */
    public function getNginxConfig(string $username, string $php, string $domain = null, $basepath = null): string
    {
        $path = '/home/' . $username . '/web';
        if ($basepath) {
            $path .= "/" . $basepath;
        }

        $config = file_get_contents($this->resourcesPath . 'config_templates/host.conf');
        $replaces = [
            'USER' => $username,
            'BASE' => $path,
            'PHP' => $php,
            'DOMAIN' => $domain
        ];

        return (new Formatters)->strReplace($config, $replaces, '???', '???');
    }


    /**
     * @param string $username
     * @param string $php
     * @return string
     */
    public function getPhpConfig(string $username, string $php): string
    {
        $config = file_get_contents($this->resourcesPath . 'config_templates/php.conf');
        $replaces = [
            'USER' => $username,
            'PHP' => $php,
        ];

        return (new Formatters)->strReplace($config, $replaces, '???', '???');
    }

    /**
     * @return string
     */
    public function getCustomNginxConfig(): string
    {
        return file_get_contents($this->resourcesPath . 'config_templates/nginx.conf');
    }

    /**
     * @return string
     */
    public function getSupervisorConfig(): string
    {
        return file_get_contents($this->resourcesPath . 'config_templates/supervisor.conf');
    }

}