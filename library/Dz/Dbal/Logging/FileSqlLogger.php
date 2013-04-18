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
 * A SQL logger that logs to files in the same directory of error_log.
 *
 * @see        Dz_Dbal_Logging_FileSqlLogger::LONG_QUERY_TIME
 * @category   Dz
 * @package    Dz_Dbal
 * @subpackage Logging
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */
class Dz_Dbal_Logging_FileSqlLogger implements SQLLogger
{
    /**
     * If the query takes longer than LONG_QUERY_TIME seconds,
     * logger writes to queries-slow.log. Else, writes to queries-all.log.
     *
     * @var int
     */
    const LONG_QUERY_TIME = 1;

    /**
     * Dump buffer.
     *
     * @var string
     */
    protected $_buffer = '';

    /**
     * Log directory (usually specified in php.ini's error_log property).
     *
     * @var string
     */
    protected $_logDirectory;

    /**
     * Unix timestamp when query starts.
     *
     * @var int
     */
    protected $_startTime = 0;

    /**
     * Just appends $value to the buffer.
     *
     * @param string $value
     * @return Dz_Dbal_Logging_FileSqlLogger Fluent interface.
     */
    protected function _bufferize($value)
    {
        $value .= ' ';

        $this->_buffer .= $value;

        return $this;
    }

    /**
     * Tries setting log directory.
     *
     * @return string
     * @throws Dz_Dbal_Logging_FileSqlLogger_Exception If error_log property
     *                                                 cannot be read.
     */
    protected function _getLogDirectory()
    {
        if ($this->_logDirectory === null) {
            $errorLogFile = ini_get('error_log');

            if ($errorLogFile === false) {
                throw new Dz_Dbal_Logging_FileSqlLogger_Exception(
                    'Cannot determine error log directory.');
            }

            $this->_logDirectory = dirname($errorLogFile)
                                 . DIRECTORY_SEPARATOR;
        }

        return $this->_logDirectory;
    }

    /**
     * Returns the $value representation according its PHP type.
     *
     * @param mixed $value
     * @return string
     */
    protected function _getString($value)
    {
        if (is_scalar($value)) {
            $value = (string) $value;
        } else {
            $value = var_export($value, true);
            $value = preg_replace(
                array('/^\s+/m', '/\r?\n/'), array('', ' '), $value);
        }

        return $value;
    }

    /**
     * Writes buffer in log file.
     *
     * @param string $logFile
     * @param bool $cleanBuffer If true, sets buffer to an empty string.
     * @return Dz_Dbal_Logging_FileSqlLogger Fluent interface.
     */
    protected function _write($logFile, $cleanBuffer = false)
    {
        if (($handle = @fopen($this->_getLogDirectory() . $logFile, 'a')) !== false) {
            fwrite($handle, $this->_buffer . PHP_EOL);
            fclose($handle);
        }

        if ($cleanBuffer === true) {
            $this->_buffer = '';
        }

        return $this;
    }

    /**
     * Bufferizes a SQL statement.
     *
     * @param string $sql The SQL to be executed.
     * @param array $params The SQL parameters.
     * @param array $types The SQL parameter types.
     * @return void
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->_startTime = microtime(true);

        $this->_bufferize(sprintf('[%s]', date('Y-m-d H:i:s')));
        $this->_bufferize($sql);

        if (!empty($params)) {
            $params = array_map(array($this, '_getString'), $params);

            $this->_bufferize(sprintf('(%s)', join(', ', $params)));
        }
    }

    /**
     * Marks the last started query as stopped, times query and writes log.
     *
     * @return void
     */
    public function stopQuery()
    {
        $time = microtime(true) - $this->_startTime;

        $this->_bufferize(sprintf('[%ss]', $time));

        if ($time >= self::LONG_QUERY_TIME) {
            $this->_write('queries-slow.log');
        }

        $this->_write('queries-all.log', true);
    }
}