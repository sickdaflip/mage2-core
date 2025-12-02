# FlipDev_Core Module Structure

## Complete Directory Tree

```
mage2-core/
│
├── src/
│   ├── Api/
│   │   └── LoggerInterface.php               # Logger API interface
│   │
│   ├── Block/
│   │   └── Adminhtml/
│   │       └── System/
│   │           └── Config/
│   │               ├── Info.php              # Module information display block
│   │               └── Version.php           # Version display block
│   │
│   ├── Console/
│   │   └── Command/
│   │       └── InfoCommand.php               # CLI command: flipdev:info
│   │
│   ├── Helper/
│   │   ├── Config.php                        # Configuration helper
│   │   └── Data.php                          # General data/utility helper
│   │
│   ├── Logger/
│   │   ├── Handler.php                       # Log file handler
│   │   └── Logger.php                        # Custom logger implementation
│   │
│   └── etc/
│       ├── acl.xml                          # Access Control List
│       ├── config.xml                       # Default configuration values
│       ├── di.xml                           # Dependency Injection config
│       ├── module.xml                       # Module declaration
│       │
│       ├── adminhtml/
│       │   └── system.xml                   # Admin configuration structure
│       │
│       └── console/
│           └── di.xml                       # Console commands DI config
│
├── .gitignore                           # Git ignore rules
├── CHANGELOG.md                         # Version history and changes
├── EXAMPLES.md                          # Usage examples
├── INSTALLATION.md                      # Detailed installation guide
├── MODULE_STRUCTURE.md                  # This file
├── README.md                            # Main documentation
├── composer.json                        # Composer package definition
└── registration.php                     # Module registration
```

## File Purpose Overview

### Core Files

| File | Purpose |
|------|---------|
| `registration.php` | Registers the module with Magento |
| `composer.json` | Package metadata and dependencies |
| `etc/module.xml` | Declares module and its dependencies |

### Configuration Files

| File | Purpose |
|------|---------|
| `etc/config.xml` | Default configuration values |
| `etc/di.xml` | Dependency injection configuration |
| `etc/acl.xml` | Admin access control permissions |
| `etc/adminhtml/system.xml` | Admin panel configuration structure |
| `etc/console/di.xml` | Console command registration |

### Helper Classes

| File | Purpose |
|------|---------|
| `Helper/Config.php` | Access system configuration values |
| `Helper/Data.php` | Common utility functions |

### Logging System

| File | Purpose |
|------|---------|
| `Logger/Logger.php` | Custom logger with module context |
| `Logger/Handler.php` | Log file handler (writes to flipdev.log) |
| `Api/LoggerInterface.php` | Logger contract/interface |

### Admin Blocks

| File | Purpose |
|------|---------|
| `Block/Adminhtml/System/Config/Info.php` | Displays module information |
| `Block/Adminhtml/System/Config/Version.php` | Shows module version |

### Console Commands

| File | Purpose |
|------|---------|
| `Console/Command/InfoCommand.php` | CLI command to show module info |

### Documentation

| File | Purpose |
|------|---------|
| `README.md` | Main module documentation |
| `INSTALLATION.md` | Step-by-step installation instructions |
| `EXAMPLES.md` | Usage examples and code samples |
| `CHANGELOG.md` | Version history and updates |

## Configuration Paths

### System Configuration Section
```
Stores → Configuration → FlipDev Extensions → Core Settings
```

### Configuration Path Structure
```
flipdev_core/
├── general/
│   ├── enabled              # Master enable/disable
│   └── license_key          # License key (encrypted)
│
└── developer/
    ├── debug_mode           # Enable debug logging
    └── log_file             # Custom log file name
```

## ACL Resources

```
Magento_Backend::admin
└── FlipDev_Core::flipdev
    ├── FlipDev_Core::config
    └── FlipDev_Core::developer
```

## Dependency Injection

### Virtual Types
- `FlipDevCoreLogger` - Logger instance for Core module

### Preferences
- `FlipDev\Core\Api\LoggerInterface` → `FlipDev\Core\Logger\Logger`

## Console Commands

```bash
# Display FlipDev module information
php bin/magento flipdev:info
```

## Log Files

- **Default:** `var/log/flipdev.log`
- **Custom:** Configurable via admin panel

## Database Tables

*This module does not create any database tables.*

## Events Observed

*This module does not observe any events by default.*

## Cron Jobs

*This module does not define any cron jobs.*

## API Endpoints

*This module does not expose any REST/SOAP APIs.*

## Frontend Components

*This module does not have any frontend components.*

## Key Features by File

### Helper/Config.php
- Check if FlipDev extensions are enabled
- Check debug mode status
- Get log file name
- Get license key
- Generic config value retrieval

### Helper/Data.php
- Get module versions
- List all FlipDev modules
- Serialize/unserialize data
- Format file sizes
- Sanitize filenames
- Validate JSON
- Generate random strings

### Logger/Logger.php
- Standard PSR-3 log levels
- Module context logging
- Formatted log messages

### Console/Command/InfoCommand.php
- Display configuration status
- List installed FlipDev modules
- Show versions

## Integration Points

### For Other FlipDev Modules

1. **Declare Dependency** in `etc/module.xml`:
   ```xml
   <sequence>
       <module name="FlipDev_Core"/>
   </sequence>
   ```

2. **Reference Tab** in `etc/adminhtml/system.xml`:
   ```xml
   <tab>flipdev</tab>
   ```

3. **Use Helpers** in your classes:
   ```php
   use FlipDev\Core\Helper\Config;
   use FlipDev\Core\Helper\Data;
   use FlipDev\Core\Logger\Logger;
   ```

## File Size

- **Total Files:** 20
- **PHP Classes:** 9
- **XML Config:** 6
- **Documentation:** 5

## Lines of Code (Approximate)

- **PHP Code:** ~1,200 lines
- **XML Config:** ~400 lines
- **Documentation:** ~2,000 lines

---

## Quick Reference

### Check if enabled:
```php
$this->configHelper->isEnabled()
```

### Check debug mode:
```php
$this->configHelper->isDebugMode()
```

### Log with module context:
```php
$this->logger->logWithModule('info', 'ModuleName', 'Message', $context)
```

### Get all FlipDev modules:
```php
$this->dataHelper->getFlipDevModules()
```

### Sanitize filename:
```php
$this->dataHelper->sanitizeFilename($filename)
```

---

**Last Updated:** 2024-12-02  
**Module Version:** 1.0.0
