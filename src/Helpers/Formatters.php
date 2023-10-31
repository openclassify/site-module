<?php

namespace Visiosoft\SiteModule\Helpers;

class Formatters
{

    /**
     * @param string $subject
     * @param array $replaces (['search' => 'replace'], ['black' => 'white'])
     * @param string|null $start (specific format start, ???, ---, <, >)
     * @param string|null $end (specific format end ???, ---, <, >)
     * @return string
     */
    public function strReplace(string $subject, array $replaces = [], string $start = null, string $end = null): string
    {
        foreach ($replaces as $key => $replace) {
            $subject = str_replace($start . $key . $end, $replace, $subject);
        }

        return $subject;
    }

    public function cleanUsername($string)
    {
        return preg_replace('/[^a-zA-Z0-9]+/', '', $string);
    }
}