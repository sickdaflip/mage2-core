# FlipDev_Core Usage Examples

This document provides practical examples of how to use FlipDev_Core in your custom modules.

## Table of Contents

1. [Module Setup](#module-setup)
2. [Configuration Helper](#configuration-helper)
3. [Data Helper](#data-helper)
4. [Logger Usage](#logger-usage)
5. [Creating Custom Sections](#creating-custom-sections)
6. [Console Commands](#console-commands)
7. [Events and Observers](#events-and-observers)

---

## Module Setup

### Example: Creating a new FlipDev module that depends on Core

**File: `app/code/FlipDev/ProductOptionsMedia/etc/module.xml`**

```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <module name="FlipDev_ProductOptionsMedia" setup_version="1.0.0">
        <sequence>
            <module name="FlipDev_Core"/>
            <module name="Magento_Catalog"/>
        </sequence>
    </module>
</config>
```

**File: `app/code/FlipDev/ProductOptionsMedia/etc/adminhtml/system.xml`**

```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Config:etc/system_file.xsd">
    <system>
        <section id="product_options_media" translate="label" sortOrder="20"
                 showInDefault="1" showInWebsite="1" showInStore="1">
            <label>Product Options Media</label>
            <tab>flipdev</tab> <!-- Uses FlipDev tab from Core -->
            <resource>FlipDev_ProductOptionsMedia::config</resource>
            
            <group id="general" translate="label" sortOrder="10"
                   showInDefault="1" showInWebsite="1" showInStore="1">
                <label>General Settings</label>
                
                <field id="enabled" translate="label" type="select" sortOrder="10"
                       showInDefault="1" showInWebsite="1" showInStore="1">
                    <label>Enable Module</label>
                    <source_model>Magento\Config\Model\Config\Source\Yesno</source_model>
                </field>
            </group>
        </section>
    </system>
</config>
```

---

## Configuration Helper

### Example 1: Basic Configuration Access

```php
<?php
namespace FlipDev\ProductOptionsMedia\Helper;

use FlipDev\Core\Helper\Config as CoreConfig;

class Config
{
    private $coreConfig;
    
    public function __construct(CoreConfig $coreConfig)
    {
        $this->coreConfig = $coreConfig;
    }
    
    /**
     * Check if module is enabled (depends on Core being enabled)
     */
    public function isEnabled($storeId = null): bool
    {
        // First check if FlipDev Core is enabled
        if (!$this->coreConfig->isEnabled($storeId)) {
            return false;
        }
        
        // Then check this module's config
        return $this->coreConfig->isSetFlag(
            'product_options_media/general/enabled',
            $storeId
        );
    }
}
```

### Example 2: Configuration with Debug Mode

```php
<?php
namespace FlipDev\ProductOptionsMedia\Model;

use FlipDev\Core\Helper\Config as CoreConfig;
use FlipDev\Core\Logger\Logger;

class MediaProcessor
{
    private $coreConfig;
    private $logger;
    
    public function __construct(
        CoreConfig $coreConfig,
        Logger $logger
    ) {
        $this->coreConfig = $coreConfig;
        $this->logger = $logger;
    }
    
    public function processMedia($mediaFile)
    {
        // Log only if debug mode is enabled
        if ($this->coreConfig->isDebugMode()) {
            $this->logger->logWithModule(
                'debug',
                'FlipDev_ProductOptionsMedia',
                'Processing media file: ' . $mediaFile
            );
        }
        
        // Process media...
    }
}
```

---

## Data Helper

### Example 1: Using Utility Functions

```php
<?php
namespace FlipDev\ProductOptionsMedia\Controller\Adminhtml\Upload;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use FlipDev\Core\Helper\Data as CoreHelper;

class Save extends Action
{
    private $coreHelper;
    
    public function __construct(
        Context $context,
        CoreHelper $coreHelper
    ) {
        parent::__construct($context);
        $this->coreHelper = $coreHelper;
    }
    
    public function execute()
    {
        $file = $this->getRequest()->getFiles('media_file');
        
        if ($file) {
            // Sanitize filename using Core helper
            $sanitizedName = $this->coreHelper->sanitizeFilename($file['name']);
            
            // Get formatted file size
            $fileSize = $this->coreHelper->formatFileSize($file['size']);
            
            $this->messageManager->addSuccessMessage(
                __('File "%1" uploaded successfully (%2)', $sanitizedName, $fileSize)
            );
        }
        
        return $this->resultRedirectFactory->create()->setPath('*/*/index');
    }
}
```

### Example 2: Module Version Check

```php
<?php
namespace FlipDev\ProductOptionsMedia\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use FlipDev\Core\Helper\Data as CoreHelper;

class ModuleInfo extends Field
{
    private $coreHelper;
    
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        CoreHelper $coreHelper,
        array $data = []
    ) {
        $this->coreHelper = $coreHelper;
        parent::__construct($context, $data);
    }
    
    protected function _renderValue(AbstractElement $element)
    {
        $modules = $this->coreHelper->getFlipDevModules();
        
        $html = '<td class="value">';
        $html .= '<ul>';
        foreach ($modules as $module => $version) {
            $html .= sprintf('<li>%s: v%s</li>', $module, $version);
        }
        $html .= '</ul>';
        $html .= '</td>';
        
        return $html;
    }
}
```

---

## Logger Usage

### Example 1: Basic Logging with Module Context

```php
<?php
namespace FlipDev\ProductOptionsMedia\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use FlipDev\Core\Logger\Logger;

class ProductSaveAfter implements ObserverInterface
{
    private $logger;
    
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
    
    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        
        // Log with module context
        $this->logger->logWithModule(
            'info',
            'FlipDev_ProductOptionsMedia',
            'Product saved',
            [
                'product_id' => $product->getId(),
                'sku' => $product->getSku()
            ]
        );
    }
}
```

### Example 2: Error Logging in Try-Catch

```php
<?php
namespace FlipDev\ProductOptionsMedia\Model;

use FlipDev\Core\Logger\Logger;
use FlipDev\Core\Helper\Config as CoreConfig;

class MediaUploader
{
    private $logger;
    private $coreConfig;
    
    public function __construct(
        Logger $logger,
        CoreConfig $coreConfig
    ) {
        $this->logger = $logger;
        $this->coreConfig = $coreConfig;
    }
    
    public function upload($file)
    {
        try {
            // Upload logic...
            
            if ($this->coreConfig->isDebugMode()) {
                $this->logger->debug('File uploaded successfully', [
                    'file' => $file['name']
                ]);
            }
            
            return true;
            
        } catch (\Exception $e) {
            // Always log errors
            $this->logger->logWithModule(
                'error',
                'FlipDev_ProductOptionsMedia',
                'Upload failed: ' . $e->getMessage(),
                [
                    'file' => $file['name'],
                    'exception' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ]
            );
            
            return false;
        }
    }
}
```

---

## Creating Custom Sections

### Example: Advanced Configuration Section with Dependencies

```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Config:etc/system_file.xsd">
    <system>
        <section id="flipdev_advanced" translate="label" sortOrder="100"
                 showInDefault="1" showInWebsite="1" showInStore="1">
            <label>Advanced Settings</label>
            <tab>flipdev</tab>
            <resource>FlipDev_ProductOptionsMedia::config_advanced</resource>
            
            <!-- Image Processing Group -->
            <group id="image_processing" translate="label" sortOrder="10"
                   showInDefault="1" showInWebsite="1" showInStore="1">
                <label>Image Processing</label>
                
                <field id="enable_processing" translate="label" type="select" sortOrder="10"
                       showInDefault="1" showInWebsite="1" showInStore="1">
                    <label>Enable Image Processing</label>
                    <source_model>Magento\Config\Model\Config\Source\Yesno</source_model>
                </field>
                
                <field id="max_width" translate="label" type="text" sortOrder="20"
                       showInDefault="1" showInWebsite="1" showInStore="1">
                    <label>Maximum Width (px)</label>
                    <validate>validate-number validate-greater-than-zero</validate>
                    <depends>
                        <field id="enable_processing">1</field>
                    </depends>
                </field>
                
                <field id="quality" translate="label" type="text" sortOrder="30"
                       showInDefault="1" showInWebsite="1" showInStore="1">
                    <label>JPEG Quality (1-100)</label>
                    <validate>validate-number validate-number-range number-range-1-100</validate>
                    <depends>
                        <field id="enable_processing">1</field>
                    </depends>
                </field>
            </group>
        </section>
    </system>
</config>
```

---

## Console Commands

### Example: Custom Console Command Using Core Helpers

```php
<?php
namespace FlipDev\ProductOptionsMedia\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use FlipDev\Core\Helper\Data as CoreHelper;
use FlipDev\Core\Logger\Logger;

class CleanupCommand extends Command
{
    private $coreHelper;
    private $logger;
    
    public function __construct(
        CoreHelper $coreHelper,
        Logger $logger
    ) {
        $this->coreHelper = $coreHelper;
        $this->logger = $logger;
        parent::__construct();
    }
    
    protected function configure()
    {
        $this->setName('flipdev:media:cleanup')
            ->setDescription('Cleanup unused media files');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Starting cleanup...</info>');
        
        // Use Core helper for file operations
        $modules = $this->coreHelper->getFlipDevModules();
        $output->writeln(sprintf('Found %d FlipDev modules', count($modules)));
        
        // Log the operation
        $this->logger->logWithModule(
            'info',
            'FlipDev_ProductOptionsMedia',
            'Cleanup command executed'
        );
        
        $output->writeln('<info>Cleanup completed!</info>');
        
        return Command::SUCCESS;
    }
}
```

---

## Events and Observers

### Example: Observer Using Core Functionality

```php
<?php
namespace FlipDev\ProductOptionsMedia\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use FlipDev\Core\Helper\Config as CoreConfig;
use FlipDev\Core\Logger\Logger;

class CheckoutCartProductAddAfter implements ObserverInterface
{
    private $coreConfig;
    private $logger;
    
    public function __construct(
        CoreConfig $coreConfig,
        Logger $logger
    ) {
        $this->coreConfig = $coreConfig;
        $this->logger = $logger;
    }
    
    public function execute(Observer $observer)
    {
        // Only execute if Core module is enabled
        if (!$this->coreConfig->isEnabled()) {
            return;
        }
        
        $product = $observer->getEvent()->getProduct();
        
        // Log if debug mode is on
        if ($this->coreConfig->isDebugMode()) {
            $this->logger->logWithModule(
                'debug',
                'FlipDev_ProductOptionsMedia',
                'Product added to cart',
                [
                    'product_id' => $product->getId(),
                    'product_name' => $product->getName()
                ]
            );
        }
        
        // Your custom logic...
    }
}
```

---

## Best Practices

### 1. Always Check Core Module Status

```php
if (!$this->coreConfig->isEnabled()) {
    return; // or throw exception
}
```

### 2. Use Debug Mode for Development Logging

```php
if ($this->coreConfig->isDebugMode()) {
    $this->logger->debug('Debug information here');
}
```

### 3. Leverage Core Helpers

```php
// Don't reinvent the wheel
$sanitized = $this->coreHelper->sanitizeFilename($filename);
$formatted = $this->coreHelper->formatFileSize($bytes);
$random = $this->coreHelper->generateRandomString(32);
```

### 4. Module Context in Logs

```php
// Always include module name in logs
$this->logger->logWithModule(
    'error',
    'FlipDev_YourModule',
    $message,
    $context
);
```

### 5. Proper Dependency Declaration

```xml
<!-- Always declare FlipDev_Core as dependency -->
<sequence>
    <module name="FlipDev_Core"/>
</sequence>
```

---

## Additional Resources

- [FlipDev_Core README](README.md)
- [Installation Guide](INSTALLATION.md)
- [Changelog](CHANGELOG.md)
- [Magento 2 DevDocs](https://devdocs.magento.com/)

---

**Need more examples?** Contact support@flipdev.com
