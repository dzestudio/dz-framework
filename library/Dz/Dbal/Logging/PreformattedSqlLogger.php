<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Dbal
 * @subpackage Logging
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */

use Doctrine\DBAL\Logging\SQLLogger;

/**
 * @see Doctrine\DBAL\Logging\SQLLogger
 */
require_once 'Doctrine/DBAL/Logging/SQLLogger.php';

/**
 * A SQL logger that logs to the standard output
 * using var_dump inside &lt;pre&gt; tags.
 *
 * @category   Dz
 * @package    Dz_Dbal
 * @subpackage Logging
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */
class Dz_Dbal_Logging_PreformattedSqlLogger implements SQLLogger
{
    /**
     * Dumps a SQL statement.
     *
     * @param string $sql The SQL to be executed.
     * @param array $params The SQL parameters.
     * @param array $types The SQL parameter types.
     * @return void
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        echo '<pre>';

    	var_dump($sql);

        if ($params) {
            var_dump($params);
    	}

        if ($types) {
            var_dump($types);
        }

        echo '</pre>';
    }

    /**
     * Mark the last started query as stopped.
     *
     * @return void
     */
    public function stopQuery()
    {
    }
}