<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Item;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample categories
        $appetizers = Category::create([
            'name' => 'Appetizers',
            'emoji' => '🥗',
            'sort_order' => 1,
        ]);

        $mains = Category::create([
            'name' => 'Main Courses',
            'emoji' => '🍽️',
            'sort_order' => 2,
        ]);

        $desserts = Category::create([
            'name' => 'Desserts',
            'emoji' => '🍰',
            'sort_order' => 3,
        ]);

        $beverages = Category::create([
            'name' => 'Beverages',
            'emoji' => '🥤',
            'sort_order' => 4,
        ]);

        // Create sample items for Appetizers
        Item::create([
            'name' => 'Caesar Salad',
            'price' => 12.50,
            'description' => 'Fresh romaine lettuce, parmesan cheese, croutons, and our signature Caesar dressing',
            'category_id' => $appetizers->id,
            'sort_order' => 1,
        ]);

        Item::create([
            'name' => 'Bruschetta Trio',
            'price' => 9.90,
            'description' => 'Three pieces of toasted bread topped with tomatoes, basil, and mozzarella',
            'category_id' => $appetizers->id,
            'sort_order' => 2,
        ]);

        // Create sample items for Main Courses
        Item::create([
            'name' => 'Grilled Salmon',
            'price' => 24.90,
            'description' => 'Fresh Atlantic salmon grilled to perfection, served with seasonal vegetables and lemon butter sauce',
            'category_id' => $mains->id,
            'sort_order' => 1,
        ]);

        Item::create([
            'name' => 'Ribeye Steak',
            'price' => 32.00,
            'description' => 'Premium ribeye steak cooked to your preference, served with roasted potatoes and garlic butter',
            'category_id' => $mains->id,
            'sort_order' => 2,
        ]);

        Item::create([
            'name' => 'Mushroom Risotto',
            'price' => 18.50,
            'description' => 'Creamy arborio rice with wild mushrooms, parmesan cheese, and fresh herbs',
            'category_id' => $mains->id,
            'sort_order' => 3,
        ]);

        // Create sample items for Desserts
        Item::create([
            'name' => 'Chocolate Lava Cake',
            'price' => 8.50,
            'description' => 'Warm chocolate cake with a molten center, served with vanilla ice cream',
            'category_id' => $desserts->id,
            'sort_order' => 1,
        ]);

        Item::create([
            'name' => 'Tiramisu',
            'price' => 7.90,
            'description' => 'Classic Italian dessert with coffee-soaked ladyfingers and mascarpone cream',
            'category_id' => $desserts->id,
            'sort_order' => 2,
        ]);

        // Create sample items for Beverages
        Item::create([
            'name' => 'House Wine (Red/White)',
            'price' => 6.50,
            'description' => 'Glass of our carefully selected house wine',
            'category_id' => $beverages->id,
            'sort_order' => 1,
        ]);

        Item::create([
            'name' => 'Fresh Lemonade',
            'price' => 4.50,
            'description' => 'Freshly squeezed lemonade with mint',
            'category_id' => $beverages->id,
            'sort_order' => 2,
        ]);

        Item::create([
            'name' => 'Espresso',
            'price' => 3.20,
            'description' => 'Premium Italian espresso',
            'category_id' => $beverages->id,
            'sort_order' => 3,
        ]);
    }
}
