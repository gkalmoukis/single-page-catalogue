# Multi-Tenant Restaurant Catalog SaaS

A Laravel application with Filament admin panel transformed into a multi-tenant SaaS for managing restaurant operations. Each tenant gets their own subdomain and isolated admin panel.

[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F240a3d35-6015-44bd-92e1-bf652345a42f%3Fdate%3D1%26commit%3D1&style=flat)](https://forge.laravel.com/servers/779123/sites/2777838)

## 🚀 Multi-Tenancy Features

- **Subdomain-based Tenancy**: Each restaurant gets `restaurant-name.single-page-catalogue.theloom.gr`
- **Custom Domain Support**: Restaurants can use their own domains
- **Isolated Data**: Complete data separation between tenants
- **Tenant-specific Admin Panels**: Each tenant has their own Filament admin interface
- **SaaS Landing Page**: Main site showcases available restaurants and allows new registrations
- **User Management**: Users can be associated with multiple tenants with different roles

## 🏗️ Architecture

### Database Schema
- **Tenants Table**: Stores tenant information (name, slug, domain, settings)
- **Tenant-User Pivot**: Many-to-many relationship with roles
- **Scoped Models**: All restaurant data (categories, items, tags) are scoped to tenants

### URL Structure
- **Main Site**: `https://single-page-catalogue.theloom.gr` - SaaS landing page
- **Tenant Sites**: `https://pizza-palace.single-page-catalogue.theloom.gr` - Restaurant catalog
- **Tenant Admin**: `https://pizza-palace.single-page-catalogue.theloom.gr/admin` - Admin panel
- **Custom Domains**: `https://restaurant.com` - Custom domain support

## 📋 Requirements

- PHP 8.2+
- Composer
- Laravel 11
- SQLite/MySQL/PostgreSQL
- Node.js & npm

## 🔧 Installation

### 1. Clone and Setup
```bash
git clone <repository-url>
cd otitheleis
composer install
npm install && npm run build
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup
```bash
# For SQLite (default)
touch database/database.sqlite

# Run migrations and seeders
php artisan migrate:fresh --seed
```

### 4. Configuration Variables

Update your `.env` file:

```env
APP_NAME="Restaurant Catalog SaaS"
APP_URL=https://single-page-catalogue.theloom.gr

# Database
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

# Admin Configuration
ADMIN_PASSWORD=your-secure-password

# Cache Configuration
CATALOGUE_CACHE_KEY=restaurant_catalogue
CATALOGUE_CACHE_TTL=3600
```

## 🎯 Demo Data

The application comes with pre-seeded demo tenants:

1. **Pizza Palace** (`pizza-palace.single-page-catalogue.theloom.gr`)
   - Login: `pizza@restaurant.com` / `password`
   - Italian restaurant with pizzas and beverages

2. **Burger Barn** (`burger-barn.single-page-catalogue.theloom.gr`)
   - Login: `burger@restaurant.com` / `password`
   - American restaurant with burgers and sides

3. **Greek Taverna** (`greek-taverna.single-page-catalogue.theloom.gr`)
   - Login: `greek@restaurant.com` / `password`
   - Greek restaurant with traditional dishes

4. **System Admin** (Main site admin only)
   - Login: `giorgoskalmoukis@theloom.gr` / `your-admin-password`

## 🚀 Usage

### For Restaurant Owners

1. **Create Restaurant**: Visit main site and click "Create Your Restaurant Catalog"
2. **Setup Menu**: Log into your admin panel and add categories, items, and tags
3. **Share URL**: Share your unique subdomain with customers
4. **Custom Domain**: Configure custom domain in tenant settings (optional)

### For Customers

1. **Browse Restaurants**: Visit main site to see all available restaurants
2. **View Menu**: Click on any restaurant to view their catalog
3. **Mobile Friendly**: All catalogs are responsive and mobile-optimized

### For System Administrators

1. **Tenant Management**: Access admin panel to manage all tenants
2. **User Management**: Add/remove users from tenants with different roles
3. **System Monitoring**: Monitor tenant activity and resource usage

## 🔒 Multi-Tenancy Implementation

### Tenant Resolution
The `ResolveTenant` middleware automatically detects the current tenant based on:
- Subdomain pattern: `{slug}.single-page-catalogue.theloom.gr`
- Custom domains stored in tenant settings

### Data Scoping
All Eloquent models automatically scope data to the current tenant:
```php
// Automatically scoped to current tenant
$categories = Category::all();
$items = Item::where('is_active', true)->get();
```

### Filament Resources
All Filament resources are automatically scoped using the `getEloquentQuery()` method:
```php
public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();
    $tenantService = app(TenantService::class);
    return $tenantService->scopeToCurrentTenant($query);
}
```

## 🎨 Customization

### Adding New Tenant Features
1. Add fields to tenant migration
2. Update Tenant model fillable array
3. Add form fields to TenantResource
4. Update tenant seeder if needed

### Custom Domains
To add custom domain support:
1. Update DNS settings to point to your server
2. Configure web server (Nginx/Apache) with wildcard SSL
3. Set domain in tenant settings through admin panel

### Styling
Each tenant can have custom styling by:
1. Adding custom CSS fields to tenant data
2. Modifying restaurant view template
3. Implementing theme selection in admin panel

## 🐛 Troubleshooting

### Common Issues

1. **Tenant Not Found**
   ```bash
   # Check tenant exists and is active
   php artisan tinker
   >>> App\Models\Tenant::where('slug', 'your-slug')->first()
   ```

2. **Data Not Scoped**
   ```bash
   # Clear cache and check middleware
   php artisan cache:clear
   php artisan config:clear
   ```

3. **Admin Panel Access**
   ```bash
   # Ensure user is attached to tenant
   php artisan tinker
   >>> $tenant = App\Models\Tenant::find(1)
   >>> $user = App\Models\User::find(1)
   >>> $tenant->users()->attach($user->id, ['role' => 'admin'])
   ```

## 🚀 Deployment

### Laravel Forge
1. Create server and site for main domain
2. Configure wildcard SSL certificate
3. Set up environment variables
4. Deploy with automatic deployment script

### Manual Deployment
1. Configure web server with wildcard domain support
2. Set up SSL certificates (Let's Encrypt wildcard)
3. Configure environment for production
4. Run deployment commands:
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## 📈 Scaling

### Database Scaling
- Consider separate databases per tenant for larger deployments
- Implement database connection switching in TenantService
- Use read replicas for better performance

### Caching
- Tenant-specific cache keys implemented
- Redis recommended for production
- Consider cache tags for better invalidation

### CDN Integration
- Static assets can be served via CDN
- Tenant-specific asset versioning
- Image optimization for menu photos

## 🔐 Security

- Tenant data isolation enforced at application level
- User permissions managed through tenant-user relationships
- SQL injection protection through Eloquent ORM
- CSRF protection enabled
- XSS protection through Blade templating

## 📚 File Structure

```
app/
├── Filament/Admin/Resources/    # Filament resources with tenant scoping
├── Http/Controllers/            # Web controllers including TenantController
├── Http/Middleware/            # ResolveTenant middleware
├── Models/                     # Eloquent models with tenant relationships
├── Services/                   # TenantService for tenant operations
└── Providers/                  # Service providers

database/
├── migrations/                 # Multi-tenancy migrations
└── seeders/                   # Demo tenant and data seeders

resources/views/
├── tenants/                   # Tenant management views
└── restaurant/                # Restaurant catalog views
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch
3. Add tests for new functionality
4. Ensure tenant isolation is maintained
5. Submit pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🆘 Support

For issues and questions:
- Check the troubleshooting section
- Review Laravel and Filament documentation
- Contact: [theloom.gr](https://theloom.gr)
