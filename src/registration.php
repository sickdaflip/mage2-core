<?php
/**
 * FlipDev Core Module Registration
 *
 * @category  FlipDev
 * @package   FlipDev_Core
 * @author    FlipDev <dev@flipdev.com>
 * @copyright Copyright (c) 2024 FlipDev
 */

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'FlipDev_Core',
    __DIR__
);
