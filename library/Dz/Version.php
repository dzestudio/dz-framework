<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Version
 * @copyright  Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @version    $Id: Version.php 15 2013-04-04 19:29:48Z lf $
 */

/**
 * Class to store and retrieve the version of DZ Framework.
 *
 * @category   Dz
 * @package    Dz_Version
 * @copyright  Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 */
final class Dz_Version
{
    /**
     * DZ Framework version identification - see compareVersion()
     */
    const VERSION = '0.1';

    /**
     * Compare the specified DZ Framework version string $version
     * with the current Dz_Version::VERSION of DZ Framework.
     *
     * @param  string  $version  A version string (e.g. "0.7.1").
     * @return int           -1 if the $version is older,
     *                        0 if they are the same and
     *                       +1 if $version is newer.
     */
    public static function compareVersion($version)
    {
        return version_compare($version, self::VERSION);
    }
}