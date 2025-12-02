<?php
/**
 * FlipDev Core Configuration Helper
 *
 * @category  FlipDev
 * @package   FlipDev_Core
 * @author    FlipDev <dev@flipdev.com>
 * @copyright Copyright (c) 2024 FlipDev
 */

namespace FlipDev\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

/**
 * Configuration Helper
 * 
 * Provides easy access to FlipDev Core configuration values
 */
class Config extends AbstractHelper
{
    /**
     * Configuration paths
     */
    const XML_PATH_ENABLED = 'flipdev_core/general/enabled';
    const XML_PATH_DEBUG_MODE = 'flipdev_core/developer/debug_mode';
    const XML_PATH_LOG_FILE = 'flipdev_core/developer/log_file';
    const XML_PATH_LICENSE_KEY = 'flipdev_core/general/license_key';

    /**
     * Default log file name
     */
    const DEFAULT_LOG_FILE = 'flipdev.log';

    /**
     * Check if FlipDev extensions are enabled
     *
     * @param string|null $scopeCode
     * @return bool
     */
    public function isEnabled($scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    /**
     * Check if debug mode is enabled
     *
     * @return bool
     */
    public function isDebugMode(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_DEBUG_MODE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get custom log file name
     *
     * @return string
     */
    public function getLogFile(): string
    {
        $logFile = $this->scopeConfig->getValue(
            self::XML_PATH_LOG_FILE,
            ScopeInterface::SCOPE_STORE
        );

        return $logFile ?: self::DEFAULT_LOG_FILE;
    }

    /**
     * Get license key (decrypted)
     *
     * @return string|null
     */
    public function getLicenseKey(): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_LICENSE_KEY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get configuration value by path
     *
     * @param string $path
     * @param string|null $scopeCode
     * @return mixed
     */
    public function getConfigValue(string $path, $scopeCode = null)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    /**
     * Check if configuration flag is set
     *
     * @param string $path
     * @param string|null $scopeCode
     * @return bool
     */
    public function isSetFlag(string $path, $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            $path,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }
}
