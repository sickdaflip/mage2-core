# FlipDev_Core Installation Guide

## Prerequisites

- Magento 2.4.x or higher
- PHP 8.1, 8.2, or 8.3
- Composer (recommended)
- SSH/CLI access to server

## Installation Methods

### Method 1: Composer Installation (Recommended)

#### Step 1: Add the package to your Magento installation

```bash
composer require flipdev/module-core
```

#### Step 2: Enable the module

```bash
php bin/magento module:enable FlipDev_Core
```

#### Step 3: Run setup upgrade

```bash
php bin/magento setup:upgrade
```

#### Step 4: Compile dependency injection

```bash
php bin/magento setup:di:compile
```

#### Step 5: Deploy static content (production mode)

```bash
php bin/magento setup:static-content:deploy -f
```

#### Step 6: Flush cache

```bash
php bin/magento cache:flush
```

---

### Method 2: Manual Installation

#### Step 1: Create module directory

```bash
mkdir -p app/code/FlipDev/Core
```

#### Step 2: Upload files

Upload all module files to `app/code/FlipDev/Core/`

Ensure the following structure:
```
app/code/FlipDev/Core/
├── Api/
├── Block/
├── Console/
├── Helper/
├── Logger/
├── etc/
├── composer.json
├── registration.php
└── README.md
```

#### Step 3: Set correct file permissions

```bash
cd /path/to/magento
find app/code/FlipDev/Core -type f -exec chmod 644 {} \;
find app/code/FlipDev/Core -type d -exec chmod 755 {} \;
```

#### Step 4: Enable the module

```bash
php bin/magento module:enable FlipDev_Core
```

#### Step 5: Run setup upgrade

```bash
php bin/magento setup:upgrade
```

#### Step 6: Compile dependency injection

```bash
php bin/magento setup:di:compile
```

#### Step 7: Deploy static content (if in production mode)

```bash
php bin/magento setup:static-content:deploy -f
```

#### Step 8: Flush cache

```bash
php bin/magento cache:flush
```

---

## Verification

### 1. Check module status

```bash
php bin/magento module:status FlipDev_Core
```

Expected output: Module should be listed under "List of enabled modules"

### 2. Run the info command

```bash
php bin/magento flipdev:info
```

Expected output: Should display FlipDev module information

### 3. Check admin panel

1. Log in to Magento Admin
2. Navigate to **Stores → Configuration**
3. Look for **FlipDev Extensions** tab
4. Click on **Core Settings**
5. You should see the configuration options

---

## Post-Installation Configuration

### 1. Enable FlipDev Extensions

Navigate to: **Stores → Configuration → FlipDev Extensions → Core Settings**

- Set **Enable FlipDev Extensions** to **Yes**
- Click **Save Config**

### 2. Configure Developer Settings (Optional)

If you need debug logging:

- Navigate to **Developer Settings** group
- Set **Debug Mode** to **Yes**
- Optionally set a **Custom Log File** name
- Click **Save Config**

### 3. Flush Cache

```bash
php bin/magento cache:flush
```

---

## Troubleshooting

### Issue: Module not showing in admin

**Solution:**
```bash
php bin/magento setup:upgrade
php bin/magento cache:flush
```

### Issue: Permission denied errors

**Solution:**
```bash
cd /path/to/magento
chmod -R 755 app/code/FlipDev/Core
chown -R www-data:www-data app/code/FlipDev/Core  # Adjust user/group as needed
```

### Issue: Static content deployment fails

**Solution:**
```bash
rm -rf pub/static/*
rm -rf var/view_preprocessed/*
php bin/magento setup:static-content:deploy -f
```

### Issue: DI compilation errors

**Solution:**
```bash
rm -rf generated/*
php bin/magento setup:di:compile
```

### Issue: Log file not being created

**Solution:**
```bash
# Check directory permissions
ls -la var/log/

# Create log file manually
touch var/log/flipdev.log
chmod 666 var/log/flipdev.log

# Set proper ownership
chown www-data:www-data var/log/flipdev.log
```

---

## Uninstallation

### Remove via Composer

```bash
composer remove flipdev/module-core
php bin/magento setup:upgrade
php bin/magento cache:flush
```

### Manual Removal

```bash
# Disable module
php bin/magento module:disable FlipDev_Core

# Remove files
rm -rf app/code/FlipDev/Core

# Run setup upgrade
php bin/magento setup:upgrade

# Clear generated files
rm -rf generated/*
rm -rf var/cache/*
rm -rf var/page_cache/*

# Flush cache
php bin/magento cache:flush
```

---

## Multi-Store Setup

FlipDev_Core supports multi-store configurations. After installation:

1. Go to **Stores → Configuration**
2. Select your store view from **Store View** dropdown
3. Navigate to **FlipDev Extensions → Core Settings**
4. Uncheck **Use Website** or **Use Default** for settings you want to customize
5. Configure settings per store view
6. Save configuration

---

## Development Mode vs Production Mode

### Development Mode
```bash
php bin/magento deploy:mode:set developer
```
- No need for static content deployment after changes
- Errors shown in browser
- Better for debugging

### Production Mode
```bash
php bin/magento deploy:mode:set production
```
- Requires static content deployment
- Better performance
- Use for live stores

---

## File Permissions (Production Server)

```bash
cd /path/to/magento

# Set directory permissions
find . -type d -exec chmod 755 {} \;

# Set file permissions
find . -type f -exec chmod 644 {} \;

# Make bin/magento executable
chmod +x bin/magento

# Set ownership
chown -R www-data:www-data .  # Adjust user/group as needed

# Special permissions for var and generated
chmod -R 777 var/ generated/ pub/static/
```

---

## Need Help?

- **Email:** support@flipdev.com
- **Documentation:** https://docs.flipdev.com
- **Community:** https://community.flipdev.com

---

**Important Notes:**

1. Always backup your database and files before installation
2. Test on staging environment first
3. Run in maintenance mode for production installations
4. Check compatibility with your Magento version
5. Ensure all prerequisites are met
