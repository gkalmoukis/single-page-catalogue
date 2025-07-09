# signle-page-catalogue

A Laravel application with Filament admin panel for managing restaurant operations including categories and items.

## Configuration Variables

The following configuration variables can be set in your `.env` file:

| Variable | Description | Default Value | Required |
|----------|-------------|---------------|----------|
| `DEFAULT_ADMIN_PASSWORD` | Default password for the admin user when seeding the database | `password` | No |
| `CATALOGUE_CACHE_KEY` | Cache key for storing restaurant catalogue data (categories and items) | `catalogue` | No |
| `CATALOGUE_CACHE_TTL` | Cache time-to-live in seconds for restaurant catalogue data | `3600` (1 hour) | No |

**Security Note**: Always change the default admin password in production environments by setting `DEFAULT_ADMIN_PASSWORD` in your `.env` file or updating the password through the admin panel.

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite (default) or MySQL/PostgreSQL

## Local Installation

### 1. Clone the Repository


### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

```bash
# Create SQLite database file (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### 5. Build Assets

```bash
# For development
npm run dev

# For production
npm run build
```

### 6. Start Development Server

```bash
# Option 1: Use Laravel's built-in server
php artisan serve

# Option 2: Use the dev command (runs server, queue, logs, and vite)
composer run dev
```

### 7. Access the Application

- **Frontend**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
- **Admin Credentials**: 
  - Email: admin@restaurant.com
  - Password: password

## Laravel Forge Installation

### 1. Server Requirements

Ensure your Forge server meets the requirements:
- PHP 8.2+
- Composer
- Node.js & NPM

### 2. Site Setup

1. Create a new site in Laravel Forge
2. Set the web directory to `/public`
3. Enable "Wildcard Sub-Domains" if needed

### 3. Repository Deployment

1. Connect your Git repository to the site
2. Set up auto-deployment if desired
3. Configure the deployment script:

```bash
cd /home/forge/your-site-name
git pull origin main
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
npm ci
npm run build

# Copy environment file if not exists
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate key if needed
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Database setup
php artisan migrate --force
php artisan db:seed --force

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 4. Environment Configuration

Update your `.env` file on the server:

```env
APP_NAME="OTI THELEIS"
APP_ENV=local
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database (if using MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Or keep SQLite for simplicity
DB_CONNECTION=sqlite
DB_DATABASE=/home/forge/your-site-name/database/database.sqlite

# Mail configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
```

### 5. SSL Certificate

Enable SSL certificate through Laravel Forge for your domain.

### 6. Queue Workers (Optional)

If using queues, set up a daemon in Forge:
- Command: `php artisan queue:work --sleep=3 --tries=3`
- Directory: `/home/forge/your-site-name`

## Usage

### Admin Panel Features

- **Dashboard**: Overview of system statistics
- **Categories**: Manage item categories
- **Items**: Manage restaurant items
- **Users**: User management

### Default Admin Account

After seeding, you can log in with:
- Email: `admin@restaurant.com`
- Password: `password`

**Important**: Change the default password immediately in production!

## Development Commands

```bash
# Run tests
composer test
# or
php artisan test

# Code formatting
./vendor/bin/pint

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run queue worker
php artisan queue:work

# Monitor logs
php artisan pail
```

## File Structure

```
app/
├── Filament/          # Filament admin resources
├── Http/Controllers/  # Web controllers
├── Models/           # Eloquent models
│   ├── Category.php
│   ├── Item.php
│   └── User.php
└── Providers/        # Service providers

database/
├── migrations/       # Database migrations
├── seeders/         # Database seeders
└── database.sqlite  # SQLite database file
```

## Troubleshooting

### Common Issues

1. **Permission Errors**
   ```bash
   sudo chown -R www-data:www-data storage bootstrap/cache
   sudo chmod -R 775 storage bootstrap/cache
   ```

2. **Database Connection Issues**
   - Verify database credentials in `.env`
   - Ensure database file exists (for SQLite)
   - Check database server is running (for MySQL/PostgreSQL)

3. **Asset Issues**
   ```bash
   npm run build
   php artisan cache:clear
   ```

4. **Filament Issues**
   ```bash
   php artisan filament:upgrade
   php artisan view:clear
   ```

## Security

- Change default admin password
- Update `APP_KEY` in production
- Set `APP_DEBUG=false` in production
- Configure proper file permissions
- Enable SSL certificates
- Regular security updates

## Support

For issues and questions, please check the Laravel and Filament documentation:
- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
