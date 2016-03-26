<?php

namespace EricMakesStuff\ServerMonitor\Helpers;

use Carbon\Carbon;

class Format
{
    /**
     * @param int $sizeInBytes
     *
     * @return string
     */
    public static function getHumanReadableSize($sizeInBytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($sizeInBytes === 0) {
            return '0 '.$units[1];
        }
        for ($i = 0; $sizeInBytes > 1024; ++$i) {
            $sizeInBytes /= 1024;
        }

        return round($sizeInBytes, 2).' '.$units[$i];
    }
}
