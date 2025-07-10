<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Category;
use App\Models\Item;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Giorgos Kalmoukis',
            'email' => 'giorgoskalmoukis@theloom.gr',
            'password' => Hash::make(config('default.admin_password')),
            'is_admin' => true,
        ]);

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
            'name' => 'Taverna Οτιθέλεις',
            'slug' => 'otitheleis',
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

        // Create data for Pizza Palace
        $this->createPizzaPalaceData($tenant1);
        
        // Create data for Burger Barn
        $this->createBurgerBarnData($tenant2);
        
        // Create main Greek restaurant data (Taverna Οτιθέλεις)
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
        // Create Categories
        $piataImeras = Category::create([
            'name' => 'Πιάτα Ημέρας',
            'description' => 'Φρεσκοψημένα καθημερινά πιάτα που προετοιμάζονται με εποχιακά υλικά. Μεσογειακές γεύσεις που μαγειρεύονται με αγάπη.',
            'emoji' => '🍽️',
            'sort_order' => 1,
            'tenant_id' => $tenant->id,
        ]);

        $tisOras = Category::create([
            'name' => 'Της Ώρας',
            'description' => 'Κρέατα και πιάτα που μαγειρεύονται στη στιγμή στη σχάρα. Ζουμερά και τραγανά για τους λάτρεις της σχάρας.',
            'emoji' => '🔥',
            'sort_order' => 2,
            'tenant_id' => $tenant->id,
        ]);

        $salates = Category::create([
            'name' => 'Σαλάτες',
            'description' => 'Δροσερές σαλάτες με φρέσκα λαχανικά και παραδοσιακές συνταγές. Υγιεινές επιλογές γεμάτες βιταμίνες.',
            'emoji' => '🥗',
            'sort_order' => 3,
            'tenant_id' => $tenant->id,
        ]);

        $kafes = Category::create([
            'name' => 'Καφέδες',
            'description' => 'Ποιοτικοί καφέδες για κάθε γούστο. Από παραδοσιακό ελληνικό έως μοντέρνα espresso και freddo.',
            'emoji' => '☕',
            'sort_order' => 4,
            'tenant_id' => $tenant->id,
        ]);

        $anapsiktika = Category::create([
            'name' => 'Αναψυκτικά',
            'description' => 'Δροσιστικά ποτά και αναψυκτικά για τη συνοδεία του φαγητού σας. Κρύα και αναζωογονητικά.',
            'emoji' => '🥤',
            'sort_order' => 5,
            'tenant_id' => $tenant->id,
        ]);

        // Create Items for each category
        
        // Πιάτα Ημέρας
        Item::create([
            'name' => 'Μοσχαράκι Κοκκινιστό',
            'price' => 10.50,
            'description' => 'Μοσχαράκι μαγειρεμένο σε κόκκινη σάλτσα, σερβίρεται με ρύζι ή πατάτες',
            'category_id' => $piataImeras->id,
            'sort_order' => 1,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Κεμπάπ Γεμιστό',
            'price' => 9.80,
            'description' => 'Χειροποίητο κεμπάπ με γέμιση τυριού, σερβίρεται με ρύζι ή πατάτες',
            'category_id' => $piataImeras->id,
            'sort_order' => 2,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Κεφτεδάκια',
            'price' => 8.50,
            'description' => 'Τραγανά κεφτεδάκια με μυρωδικά και συνοδευτικό',
            'category_id' => $piataImeras->id,
            'sort_order' => 3,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Μακαρόνια με Κιμά',
            'price' => 9.00,
            'description' => 'Κλασικά μακαρόνια με σπιτικό κιμά και τριμμένο τυρί',
            'category_id' => $piataImeras->id,
            'sort_order' => 4,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        // Της Ώρας
        Item::create([
            'name' => 'Κοτόπουλο Φιλέτο Σχάρας',
            'price' => 9.50,
            'description' => 'Ζουμερό φιλέτο κοτόπουλο στη σχάρα με συνοδευτικό',
            'category_id' => $tisOras->id,
            'sort_order' => 1,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Μπιφτέκι Μοσχαρίσιο Σχάρας',
            'price' => 9.00,
            'description' => 'Χειροποίητο μπιφτέκι μοσχαρίσιο στη σχάρα με πατάτες',
            'category_id' => $tisOras->id,
            'sort_order' => 2,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Πανσετάκια Χοιρινά',
            'price' => 9.20,
            'description' => 'Τραγανά πανσετάκια χοιρινά στη σχάρα',
            'category_id' => $tisOras->id,
            'sort_order' => 3,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        // Σαλάτες
        Item::create([
            'name' => 'Χωριάτικη',
            'price' => 6.00,
            'description' => 'Ντομάτα, αγγούρι, φέτα, ελιά, πιπεριά και ρίγανη',
            'category_id' => $salates->id,
            'sort_order' => 1,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Ταμπουλέ',
            'price' => 6.50,
            'description' => 'Δροσιστική σαλάτα με πλιγούρι, ντομάτα, μαϊντανό και λεμόνι',
            'category_id' => $salates->id,
            'sort_order' => 2,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        // Καφέδες
        Item::create([
            'name' => 'Freddo Espresso',
            'price' => 2.50,
            'description' => 'Διπλός espresso με πάγο',
            'category_id' => $kafes->id,
            'sort_order' => 1,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Freddo Cappuccino',
            'price' => 2.80,
            'description' => 'Freddo espresso με αφρόγαλα',
            'category_id' => $kafes->id,
            'sort_order' => 2,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Γαλλικός',
            'price' => 2.00,
            'description' => 'Ζεστός καφές φίλτρου',
            'category_id' => $kafes->id,
            'sort_order' => 3,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Φραπεδάκι',
            'price' => 2.20,
            'description' => 'Κλασικός ελληνικός φραπέ',
            'category_id' => $kafes->id,
            'sort_order' => 4,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Ελληνικός',
            'price' => 1.80,
            'description' => 'Δεν τον λέμε, αλλά τον φτιάχνουμε :P',
            'category_id' => $kafes->id,
            'sort_order' => 5,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        // Αναψυκτικά
        Item::create([
            'name' => 'Coca Cola Zero',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 1,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Coca Cola Light',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 2,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Coca Cola',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 3,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Fanta',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 4,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Sprite',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 5,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Ανθρακούχο Νερό',
            'price' => 1.50,
            'description' => 'Ανθρακούχο φυσικό μεταλλικό νερό',
            'category_id' => $anapsiktika->id,
            'sort_order' => 6,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        Item::create([
            'name' => 'Νερό 1.4L',
            'price' => 1.00,
            'description' => 'Εμφιαλωμένο νερό 1.5 λίτρων',
            'category_id' => $anapsiktika->id,
            'sort_order' => 7,
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        // Create Tags
        $veganTag = Tag::create([
            'name' => 'Βίγκαν',
            'color' => '#22C55E',
            'sort_order' => 1,
            'tenant_id' => $tenant->id,
        ]);

        $vegetarianTag = Tag::create([
            'name' => 'Χορτοφαγικό',
            'color' => '#84CC16',
            'sort_order' => 2,
            'tenant_id' => $tenant->id,
        ]);

        $glutenFreeTag = Tag::create([
            'name' => 'Χωρίς Γλουτένη',
            'color' => '#F59E0B',
            'sort_order' => 3,
            'tenant_id' => $tenant->id,
        ]);

        // Attach tags to items
        $this->attachTagsToItems($tenant, $veganTag, $vegetarianTag, $glutenFreeTag);
    }

    private function attachTagsToItems(Tenant $tenant, Tag $veganTag, Tag $vegetarianTag, Tag $glutenFreeTag)
    {
        // Σαλάτες - όλες χορτοφαγικές
        $choriatiki = Item::where('name', 'Χωριάτικη')->where('tenant_id', $tenant->id)->first();
        $choriatiki->tags()->attach($vegetarianTag->id, ['sort_order' => 1]);
        $choriatiki->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        $taboule = Item::where('name', 'Ταμπουλέ')->where('tenant_id', $tenant->id)->first();
        $taboule->tags()->attach($veganTag->id, ['sort_order' => 1]);

        // Καφέδες - όλοι βίγκαν εκτός από freddo cappuccino
        $freddoEspresso = Item::where('name', 'Freddo Espresso')->where('tenant_id', $tenant->id)->first();
        $freddoEspresso->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $freddoEspresso->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        $gallikos = Item::where('name', 'Γαλλικός')->where('tenant_id', $tenant->id)->first();
        $gallikos->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $gallikos->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        $frape = Item::where('name', 'Φραπεδάκι')->where('tenant_id', $tenant->id)->first();
        $frape->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $frape->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        $ellinikos = Item::where('name', 'Ελληνικός')->where('tenant_id', $tenant->id)->first();
        $ellinikos->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $ellinikos->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        // Freddo Cappuccino - χορτοφαγικό (όχι βίγκαν λόγω γάλακτος)
        $freddoCappuccino = Item::where('name', 'Freddo Cappuccino')->where('tenant_id', $tenant->id)->first();
        $freddoCappuccino->tags()->attach($vegetarianTag->id, ['sort_order' => 1]);
        $freddoCappuccino->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        // Αναψυκτικά - όλα βίγκαν και χωρίς γλουτένη
        $drinks = [
            'Coca Cola Zero', 'Coca Cola Light', 'Coca Cola', 'Fanta', 'Sprite', 
            'Ανθρακούχο Νερό', 'Νερό 1.4L'
        ];
        
        foreach ($drinks as $drinkName) {
            $drink = Item::where('name', $drinkName)->where('tenant_id', $tenant->id)->first();
            if ($drink) {
                $drink->tags()->attach($veganTag->id, ['sort_order' => 1]);
                $drink->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);
            }
        }

        // Κοτόπουλο - χωρίς γλουτένη
        $kotopouloFileto = Item::where('name', 'Κοτόπουλο Φιλέτο Σχάρας')->where('tenant_id', $tenant->id)->first();
        $kotopouloFileto->tags()->attach($glutenFreeTag->id, ['sort_order' => 1]);
    }
}
