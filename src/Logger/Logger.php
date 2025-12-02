<?php
/**
 * FlipDev Core Logger
 *
 * @category  FlipDev
 * @package   FlipDev_Core
 * @author    FlipDev <dev@flipdev.com>
 * @copyright Copyright (c) 2024 FlipDev
 */

namespace FlipDev\Core\Logger;

use Monolog\Logger as MonologLogger;

/**
 * Custom Logger for FlipDev modules
 */
class Logger extends MonologLogger
{
    /**
     * Log levels mapping
     */
    const LOG_LEVELS = [
        'debug' => MonologLogger::DEBUG,
        'info' => MonologLogger::INFO,
        'notice' => MonologLogger::NOTICE,
        'warning' => MonologLogger::WARNING,
        'error' => MonologLogger::ERROR,
        'critical' => MonologLogger::CRITICAL,
        'alert' => MonologLogger::ALERT,
        'emergency' => MonologLogger::EMERGENCY,
    ];

    /**
     * Add module context to log message
     *
     * @param string $moduleName
     * @param string $message
     * @param array $context
     * @return string
     */
    protected function formatMessage(string $moduleName, string $message, array $context = []): string
    {
        $formattedMessage = sprintf('[%s] %s', $moduleName, $message);
        
        if (!empty($context)) {
            $formattedMessage .= ' | Context: ' . json_encode($context);
        }

        return $formattedMessage;
    }

    /**
     * Log with module context
     *
     * @param string $level
     * @param string $moduleName
     * @param string $message
     * @param array $context
     * @return void
     */
    public function logWithModule(
        string $level,
        string $moduleName,
        string $message,
        array $context = []
    ): void {
        $formattedMessage = $this->formatMessage($moduleName, $message, $context);
        $this->log(self::LOG_LEVELS[$level] ?? MonologLogger::INFO, $formattedMessage);
    }
}
