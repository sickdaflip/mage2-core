# FlipDev_Core Module

**Version:** 1.0.0  
**Compatibility:** Magento 2.4.x

## Overview

FlipDev_Core is the foundational module for all FlipDev extensions. It provides a centralized configuration tab, common utilities, logging functionality, and helper classes that can be utilized across all FlipDev modules.

## Features

✅ **Centralized Configuration Tab** - All FlipDev modules grouped under one admin tab  
✅ **Custom Logger** - Dedicated logging system with debug mode  
✅ **Helper Classes** - Config and Data helpers for common tasks  
✅ **Console Commands** - CLI tools for module management  
✅ **Version Tracking** - Display all installed FlipDev modules  
✅ **ACL Support** - Granular permission control  
✅ **Developer Tools** - Debug mode and custom log files  

## Installation

### Via Composer (Recommended)

```bash
composer config repositories.sickdaflip/mage2-core vcs https://github.com/sickdaflip/mage2-core.git
composer require sickdaflip/mage2-core:dev-main
php bin/magento module:enable FlipDev_Core
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento cache:flush
```

## Configuration

Navigate to: **Stores → Configuration → FlipDev Extensions → Core Settings**

### General Configuration
- **Enable FlipDev Extensions** - Master switch for all modules
- **License Key** - Optional license key (encrypted)

### Developer Settings
- **Debug Mode** - Enable detailed logging
- **Custom Log File** - Specify custom log file name (default: `flipdev.log`)

## Usage

### Using the Config Helper

```php
<?php
use FlipDev\Core\Helper\Config;

class YourClass
{
    protected $configHelper;

    public function __construct(Config $configHelper)
    {
        $this->configHelper = $configHelper;
    }

    public function yourMethod()
    {
        // Check if FlipDev modules are enabled
        if ($this->configHelper->isEnabled()) {
            // Your logic here
        }

        // Check debug mode
        if ($this->configHelper->isDebugMode()) {
            // Debug logic
        }

        // Get custom configuration value
        $value = $this->configHelper->getConfigValue('your/path/here');
    }
}
```

### Using the Data Helper

```php
<?php
use FlipDev\Core\Helper\Data;

class YourClass
{
    protected $dataHelper;

    public function __construct(Data $dataHelper)
    {
        $this->dataHelper = $dataHelper;
    }

    public function yourMethod()
    {
        // Get all FlipDev modules
        $modules = $this->dataHelper->getFlipDevModules();

        // Get specific module version
        $version = $this->dataHelper->getModuleVersion('FlipDev_YourModule');

        // Format file size
        $formatted = $this->dataHelper->formatFileSize(1024000); // Returns "1 MB"

        // Generate random string
        $random = $this->dataHelper->generateRandomString(32);
    }
}
```

### Using the Logger

```php
<?php
use FlipDev\Core\Logger\Logger;

class YourClass
{
    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function yourMethod()
    {
        // Log with module context
        $this->logger->logWithModule(
            'info',
            'FlipDev_YourModule',
            'Your log message',
            ['key' => 'value']
        );

        // Standard logging
        $this->logger->info('Standard log message');
        $this->logger->error('Error message');
    }
}
```

## Console Commands

### Display Module Information

```bash
php bin/magento flipdev:info
```

This command displays:
- Configuration status (Enabled/Disabled)
- Debug mode status
- Log file location
- List of all installed FlipDev modules with versions

## Creating New FlipDev Modules

### 1. Module Structure

```
app/code/FlipDev/YourModule/
├── etc/
│   ├── module.xml
│   └── adminhtml/
│       └── system.xml
├── registration.php
└── composer.json
```

### 2. Define Dependency on Core Module

In `etc/module.xml`:

```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <module name="FlipDev_YourModule" setup_version="1.0.0">
        <sequence>
            <module name="FlipDev_Core"/>
        </sequence>
    </module>
</config>
```

### 3. Reference the FlipDev Tab

In `etc/adminhtml/system.xml`:

```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Config:etc/system_file.xsd">
    <system>
        <section id="your_module_section" translate="label" sortOrder="20">
            <label>Your Module Name</label>
            <tab>flipdev</tab> <!-- Reference to FlipDev tab -->
            <resource>FlipDev_YourModule::config</resource>
            <!-- Your configuration groups here -->
        </section>
    </system>
</config>
```

## File Structure

```
mage2-core/
├── src/
│   ├── Api/
│   │   └── LoggerInterface.php           # Logger API interface
│   ├── Block/
│   │   └── Adminhtml/
│   │       └── System/
│   │           └── Config/
│   │               ├── Info.php          # Module info display
│   │               └── Version.php       # Version display
│   ├── Console/
│   │   └── Command/
│   │       └── InfoCommand.php           # CLI info command
│   ├── Helper/
│   │   ├── Config.php                    # Configuration helper
│   │   └── Data.php                      # General data helper
│   ├── Logger/
│   │   ├── Handler.php                   # Log handler
│   │   └── Logger.php                    # Custom logger
│   └── etc/
│       ├── acl.xml                       # Access control list
│       ├── config.xml                    # Default configuration
│       ├── di.xml                        # Dependency injection
│       ├── module.xml                    # Module declaration
│       ├── adminhtml/
│       │   └── system.xml                # Admin configuration
│       └── console/
│           └── di.xml                    # Console commands DI
├── CHANGELOG.md                          # Version history
├── EXAMPLES.md                           # Usage examples
├── INSTALLATION.md                       # Installation guide
├── MODULE_STRUCTURE.md                   # Detailed structure
├── README.md                             # This file
├── composer.json                         # Composer configuration
└── registration.php                      # Module registration
```

## Log Files

Logs are written to: `var/log/flipdev.log` (or custom location if specified)

Log format includes:
- Timestamp
- Log level
- Module name
- Message
- Context data (if provided)

## ACL Resources

- `FlipDev_Core::flipdev` - Main FlipDev resource
- `FlipDev_Core::config` - Core configuration access
- `FlipDev_Core::developer` - Developer tools access

## Support

- **Email:** support@flipdev.com
- **Documentation:** https://docs.flipdev.com
- **Issues:** Submit via your support portal

## License

Proprietary - Copyright (c) 2024 FlipDev

## Changelog

### Version 1.0.0
- Initial release
- Core tab and configuration
- Helper classes
- Custom logger
- Console commands
- ACL support

---

**Note:** This is a required dependency for all FlipDev modules. Do not disable or remove unless removing all FlipDev extensions.
