<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Tag;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo tenants
        $tenant1 = Tenant::create([
            'name' => 'Pizza Palace',
            'slug' => 'pizza-palace',
            'is_active' => true,
        ]);

        $tenant2 = Tenant::create([
            'name' => 'Burger Barn',
            'slug' => 'burger-barn',
            'is_active' => true,
        ]);

        $tenant3 = Tenant::create([
            'name' => 'Greek Taverna',
            'slug' => 'greek-taverna',
            'is_active' => true,
        ]);

        // Create users for each tenant
        $user1 = User::create([
            'name' => 'Pizza Manager',
            'email' => 'pizza@restaurant.com',
            'password' => Hash::make('password'),
            'current_tenant_id' => $tenant1->id,
        ]);

        $user2 = User::create([
            'name' => 'Burger Manager',
            'email' => 'burger@restaurant.com',
            'password' => Hash::make('password'),
            'current_tenant_id' => $tenant2->id,
        ]);

        $user3 = User::create([
            'name' => 'Greek Manager',
            'email' => 'greek@restaurant.com',
            'password' => Hash::make('password'),
            'current_tenant_id' => $tenant3->id,
        ]);

        // Attach users to tenants
        $tenant1->users()->attach($user1->id, ['role' => 'owner']);
        $tenant2->users()->attach($user2->id, ['role' => 'owner']);
        $tenant3->users()->attach($user3->id, ['role' => 'owner']);

        // Create categories and items for Pizza Palace
        $this->createPizzaPalaceData($tenant1);
        
        // Create categories and items for Burger Barn
        $this->createBurgerBarnData($tenant2);
        
        // Create categories and items for Greek Taverna
        $this->createGreekTavernaData($tenant3);
    }

    private function createPizzaPalaceData(Tenant $tenant)
    {
        // Categories
        $pizzas = Category::create([
            'name' => 'Pizzas',
            'description' => 'Fresh handmade pizzas with the finest ingredients',
            'emoji' => '🍕',
            'sort_order' => 1,
            'tenant_id' => $tenant->id,
        ]);

        $drinks = Category::create([
            'name' => 'Beverages',
            'description' => 'Refreshing drinks to complement your meal',
            'emoji' => '🥤',
            'sort_order' => 2,
            'tenant_id' => $tenant->id,
        ]);

        // Tags
        $vegetarian = Tag::create([
            'name' => 'Vegetarian',
            'color' => '#22c55e',
            'tenant_id' => $tenant->id,
        ]);

        $spicy = Tag::create([
            'name' => 'Spicy',
            'color' => '#ef4444',
            'tenant_id' => $tenant->id,
        ]);

        // Items
        Item::create([
            'name' => 'Margherita Pizza',
            'description' => 'Classic pizza with tomato sauce, mozzarella, and fresh basil',
            'price' => 12.50,
            'category_id' => $pizzas->id,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Pepperoni Pizza',
            'description' => 'Delicious pizza topped with pepperoni and mozzarella',
            'price' => 14.50,
            'category_id' => $pizzas->id,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Coca Cola',
            'description' => 'Classic refreshing cola',
            'price' => 2.50,
            'category_id' => $drinks->id,
            'tenant_id' => $tenant->id,
        ]);
    }

    private function createBurgerBarnData(Tenant $tenant)
    {
        // Categories
        $burgers = Category::create([
            'name' => 'Burgers',
            'description' => 'Juicy handcrafted burgers made with premium beef',
            'emoji' => '🍔',
            'sort_order' => 1,
            'tenant_id' => $tenant->id,
        ]);

        $sides = Category::create([
            'name' => 'Sides',
            'description' => 'Perfect sides to complete your meal',
            'emoji' => '🍟',
            'sort_order' => 2,
            'tenant_id' => $tenant->id,
        ]);

        // Items
        Item::create([
            'name' => 'Classic Cheeseburger',
            'description' => 'Beef patty with cheese, lettuce, tomato, and our special sauce',
            'price' => 9.99,
            'category_id' => $burgers->id,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'French Fries',
            'description' => 'Crispy golden fries seasoned to perfection',
            'price' => 4.50,
            'category_id' => $sides->id,
            'tenant_id' => $tenant->id,
        ]);
    }

    private function createGreekTavernaData(Tenant $tenant)
    {
        // Categories
        $mains = Category::create([
            'name' => 'Κυρίως Πιάτα',
            'description' => 'Παραδοσιακά ελληνικά πιάτα με φρέσκα υλικά',
            'emoji' => '🥙',
            'sort_order' => 1,
            'tenant_id' => $tenant->id,
        ]);

        $salads = Category::create([
            'name' => 'Σαλάτες',
            'description' => 'Φρέσκες σαλάτες με λαχανικά από την περιοχή μας',
            'emoji' => '🥗',
            'sort_order' => 2,
            'tenant_id' => $tenant->id,
        ]);

        // Items
        Item::create([
            'name' => 'Μουσακάς',
            'description' => 'Παραδοσιακός μουσακάς με μελιτζάνες και κιμά',
            'price' => 13.50,
            'category_id' => $mains->id,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Χωριάτικη Σαλάτα',
            'description' => 'Ντομάτα, αγγούρι, κρεμμύδι, φέτα και ελιές',
            'price' => 7.50,
            'category_id' => $salads->id,
            'tenant_id' => $tenant->id,
        ]);
    }
}
