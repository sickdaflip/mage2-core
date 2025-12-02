<?php
/**
 * FlipDev Core Data Helper
 *
 * @category  FlipDev
 * @package   FlipDev_Core
 * @author    FlipDev <dev@flipdev.com>
 * @copyright Copyright (c) 2024 FlipDev
 */

namespace FlipDev\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Data Helper
 * 
 * Provides common utility functions for FlipDev modules
 */
class Data extends AbstractHelper
{
    /**
     * @var ModuleListInterface
     */
    protected $moduleList;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Constructor
     *
     * @param Context $context
     * @param ModuleListInterface $moduleList
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Context $context,
        ModuleListInterface $moduleList,
        SerializerInterface $serializer
    ) {
        parent::__construct($context);
        $this->moduleList = $moduleList;
        $this->serializer = $serializer;
    }

    /**
     * Get module version
     *
     * @param string $moduleName
     * @return string|null
     */
    public function getModuleVersion(string $moduleName): ?string
    {
        $module = $this->moduleList->getOne($moduleName);
        return $module['setup_version'] ?? null;
    }

    /**
     * Get all FlipDev modules
     *
     * @return array
     */
    public function getFlipDevModules(): array
    {
        $modules = [];
        $allModules = $this->moduleList->getNames();

        foreach ($allModules as $moduleName) {
            if (strpos($moduleName, 'FlipDev_') === 0) {
                $modules[$moduleName] = $this->getModuleVersion($moduleName);
            }
        }

        return $modules;
    }

    /**
     * Serialize data
     *
     * @param mixed $data
     * @return string
     */
    public function serialize($data): string
    {
        return $this->serializer->serialize($data);
    }

    /**
     * Unserialize data
     *
     * @param string $data
     * @return mixed
     */
    public function unserialize(string $data)
    {
        try {
            return $this->serializer->unserialize($data);
        } catch (\Exception $e) {
            $this->_logger->error('FlipDev Core: Unserialize error - ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Format file size
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public function formatFileSize(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' Data.php' . $units[$i];
    }

    /**
     * Sanitize string for use in filename
     *
     * @param string $string
     * @return string
     */
    public function sanitizeFilename(string $string): string
    {
        $string = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $string);
        $string = preg_replace('/_+/', '_', $string);
        return trim($string, '_');
    }

    /**
     * Check if string is valid JSON
     *
     * @param string $string
     * @return bool
     */
    public function isJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Generate random string
     *
     * @param int $length
     * @return string
     */
    public function generateRandomString(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }
}
