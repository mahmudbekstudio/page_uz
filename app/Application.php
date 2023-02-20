<?php

namespace App;

use Gecche\Multidomain\Foundation\Application as MultidomainApplication;
use Illuminate\Foundation\Application as FoundationApplication;

class Application extends MultidomainApplication
{
    /*
     * Returns the exact storage path based on the domain (useful for package commands, could not exists)
     *
     * @return string
     */
    public function exactDomainStoragePath($domain = null): string
    {
        $this->checkDomainDetection();

        if (is_null($domain)) {
            $domain = $this['domain'];
        }

        return rtrim(
            FoundationApplication::storagePath() . DIRECTORY_SEPARATOR . 'domains' . DIRECTORY_SEPARATOR . domain_sanitized($domain),
            DIRECTORY_SEPARATOR
        );
    }

    public function setEnvironmentFile($environmentFile)
    {
        $this->environmentFile = $environmentFile;
    }
}
