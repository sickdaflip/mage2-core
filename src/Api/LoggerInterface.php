<?php
/**
 * FlipDev Core Logger Interface
 *
 * @category  FlipDev
 * @package   FlipDev_Core
 * @author    FlipDev <dev@flipdev.com>
 * @copyright Copyright (c) 2024 FlipDev
 */

namespace FlipDev\Core\Api;

/**
 * Logger Interface for FlipDev modules
 *
 * @api
 */
interface LoggerInterface
{
    /**
     * Log debug message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function debug($message, array $context = []);

    /**
     * Log info message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function info($message, array $context = []);

    /**
     * Log notice message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function notice($message, array $context = []);

    /**
     * Log warning message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function warning($message, array $context = []);

    /**
     * Log error message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function error($message, array $context = []);

    /**
     * Log critical message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function critical($message, array $context = []);

    /**
     * Log alert message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function alert($message, array $context = []);

    /**
     * Log emergency message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function emergency($message, array $context = []);
}
