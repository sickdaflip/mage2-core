<?php
/**
 * FlipDev Core Logger Handler
 *
 * @category  FlipDev
 * @package   FlipDev_Core
 * @author    FlipDev <dev@flipdev.com>
 * @copyright Copyright (c) 2024 FlipDev
 */

namespace FlipDev\Core\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger as MonologLogger;

/**
 * Custom Logger Handler
 */
class Handler extends Base
{
    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = MonologLogger::DEBUG;

    /**
     * Log file name
     *
     * @var string
     */
    protected $fileName = '/var/log/flipdev.log';
}
