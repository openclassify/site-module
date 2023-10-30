<?php

namespace Visiosoft\SiteModule\Helpers;

use Illuminate\Support\Facades\DB;

class Validation
{

    public function isUsernameUnique($username, $siteId = null)
    {
        $isSiteExist = DB::table('site_site')
            ->where('username', $username);

        if (!is_null($siteId)) {
            $isSiteExist->where('id', '!=', $siteId);
        }


        return $isSiteExist->first();
    }
}