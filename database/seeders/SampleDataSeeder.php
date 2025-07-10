<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Item;
use App\Models\Tag;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Δημιουργία κατηγοριών
        $piataImeras = Category::create([
            'name' => 'Πιάτα Ημέρας',
            'description' => 'Φρεσκοψημένα καθημερινά πιάτα που προετοιμάζονται με εποχιακά υλικά. Μεσογειακές γεύσεις που μαγειρεύονται με αγάπη.',
            'emoji' => '🍽️',
            'sort_order' => 1,
        ]);

        $tisOras = Category::create([
            'name' => 'Της Ώρας',
            'description' => 'Κρέατα και πιάτα που μαγειρεύονται στη στιγμή στη σχάρα. Ζουμερά και τραγανά για τους λάτρεις της σχάρας.',
            'emoji' => '🔥',
            'sort_order' => 2,
        ]);

        $salates = Category::create([
            'name' => 'Σαλάτες',
            'description' => 'Δροσερές σαλάτες με φρέσκα λαχανικά και παραδοσιακές συνταγές. Υγιεινές επιλογές γεμάτες βιταμίνες.',
            'emoji' => '🥗',
            'sort_order' => 3,
        ]);

        $kafes = Category::create([
            'name' => 'Καφέδες',
            'description' => 'Ποιοτικοί καφέδες για κάθε γούστο. Από παραδοσιακό ελληνικό έως μοντέρνα espresso και freddo.',
            'emoji' => '☕',
            'sort_order' => 4,
        ]);

        $anapsiktika = Category::create([
            'name' => 'Αναψυκτικά',
            'description' => 'Δροσιστικά ποτά και αναψυκτικά για τη συνοδεία του φαγητού σας. Κρύα και αναζωογονητικά.',
            'emoji' => '🥤',
            'sort_order' => 5,
        ]);

        // Πιάτα Ημέρας
        Item::create([
            'name' => 'Μοσχαράκι Κοκκινιστό',
            'price' => 10.50,
            'description' => 'Μοσχαράκι μαγειρεμένο σε κόκκινη σάλτσα, σερβίρεται με ρύζι ή πατάτες',
            'category_id' => $piataImeras->id,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Κεμπάπ Γεμιστό',
            'price' => 9.80,
            'description' => 'Χειροποίητο κεμπάπ με γέμιση τυριού, σερβίρεται με ρύζι ή πατάτες',
            'category_id' => $piataImeras->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Κεφτεδάκια',
            'price' => 8.50,
            'description' => 'Τραγανά κεφτεδάκια με μυρωδικά και συνοδευτικό',
            'category_id' => $piataImeras->id,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Μακαρόνια με Κιμά',
            'price' => 9.00,
            'description' => 'Κλασικά μακαρόνια με σπιτικό κιμά και τριμμένο τυρί',
            'category_id' => $piataImeras->id,
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // Της Ώρας
        Item::create([
            'name' => 'Κοτόπουλο Φιλέτο Σχάρας',
            'price' => 9.50,
            'description' => 'Ζουμερό φιλέτο κοτόπουλο στη σχάρα με συνοδευτικό',
            'category_id' => $tisOras->id,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Μπιφτέκι Μοσχαρίσιο Σχάρας',
            'price' => 9.00,
            'description' => 'Χειροποίητο μπιφτέκι μοσχαρίσιο στη σχάρα με πατάτες',
            'category_id' => $tisOras->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Πανσετάκια Χοιρινά',
            'price' => 9.20,
            'description' => 'Τραγανά πανσετάκια χοιρινά στη σχάρα',
            'category_id' => $tisOras->id,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Σαλάτες
        Item::create([
            'name' => 'Χωριάτικη',
            'price' => 6.00,
            'description' => 'Ντομάτα, αγγούρι, φέτα, ελιά, πιπεριά και ρίγανη',
            'category_id' => $salates->id,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Ταμπουλέ',
            'price' => 6.50,
            'description' => 'Δροσιστική σαλάτα με πλιγούρι, ντομάτα, μαϊντανό και λεμόνι',
            'category_id' => $salates->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Καφέδες
        Item::create([
            'name' => 'Freddo Espresso',
            'price' => 2.50,
            'description' => 'Διπλός espresso με πάγο',
            'category_id' => $kafes->id,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Freddo Cappuccino',
            'price' => 2.80,
            'description' => 'Freddo espresso με αφρόγαλα',
            'category_id' => $kafes->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Γαλλικός',
            'price' => 2.00,
            'description' => 'Ζεστός καφές φίλτρου',
            'category_id' => $kafes->id,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Φραπεδάκι',
            'price' => 2.20,
            'description' => 'Κλασικός ελληνικός φραπέ',
            'category_id' => $kafes->id,
            'sort_order' => 4,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Ελληνικός',
            'price' => 1.80,
            'description' => 'Δεν τον λέμε, αλλά τον φτιάχνουμε :P',
            'category_id' => $kafes->id,
            'sort_order' => 5,
            'is_active' => true,
        ]);

        // Αναψυκτικά
        Item::create([
            'name' => 'Coca Cola Zero',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Coca Cola Light',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Coca Cola',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Fanta',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 4,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Sprite',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 5,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Ανθρακούχο Νερό',
            'price' => 1.50,
            'description' => 'Ανθρακούχο φυσικό μεταλλικό νερό',
            'category_id' => $anapsiktika->id,
            'sort_order' => 6,
            'is_active' => true,
        ]);

        Item::create([
            'name' => 'Νερό 1.4L',
            'price' => 1.00,
            'description' => 'Εμφιαλωμένο νερό 1.5 λίτρων',
            'category_id' => $anapsiktika->id,
            'sort_order' => 7,
            'is_active' => true,
        ]);

        // Δημιουργία Tags
        $veganTag = Tag::create([
            'name' => 'Βίγκαν',
            'color' => '#22C55E', // Green
            'sort_order' => 1,
        ]);

        $vegetarianTag = Tag::create([
            'name' => 'Χορτοφαγικό',
            'color' => '#84CC16', // Light green
            'sort_order' => 2,
        ]);

        $glutenFreeTag = Tag::create([
            'name' => 'Χωρίς Γλουτένη',
            'color' => '#F59E0B', // Amber
            'sort_order' => 3,
        ]);

        // Σύνδεση tags με items
        // Σαλάτες - όλες χορτοφαγικές
        $choriatiki = Item::where('name', 'Χωριάτικη')->first();
        $choriatiki->tags()->attach($vegetarianTag->id, ['sort_order' => 1]);
        $choriatiki->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        $taboule = Item::where('name', 'Ταμπουλέ')->first();
        $taboule->tags()->attach($veganTag->id, ['sort_order' => 1]);

        // Καφέδες - όλοι βίγκαν εκτός από freddo cappuccino
        $freddoEspresso = Item::where('name', 'Freddo Espresso')->first();
        $freddoEspresso->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $freddoEspresso->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        $gallikos = Item::where('name', 'Γαλλικός')->first();
        $gallikos->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $gallikos->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        $frape = Item::where('name', 'Φραπεδάκι')->first();
        $frape->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $frape->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        $ellinikos = Item::where('name', 'Ελληνικός')->first();
        $ellinikos->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $ellinikos->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        // Freddo Cappuccino - χορτοφαγικό (όχι βίγκαν λόγω γάλακτος)
        $freddoCappuccino = Item::where('name', 'Freddo Cappuccino')->first();
        $freddoCappuccino->tags()->attach($vegetarianTag->id, ['sort_order' => 1]);
        $freddoCappuccino->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        // Αναψυκτικά - όλα βίγκαν και χωρίς γλουτένη
        $cocaColaZero = Item::where('name', 'Coca Cola Zero')->first();
        $cocaColaZero->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $cocaColaZero->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        $cocaColaLight = Item::where('name', 'Coca Cola Light')->first();
        $cocaColaLight->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $cocaColaLight->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        $cocaCola = Item::where('name', 'Coca Cola')->first();
        $cocaCola->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $cocaCola->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        $fanta = Item::where('name', 'Fanta')->first();
        $fanta->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $fanta->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        $sprite = Item::where('name', 'Sprite')->first();
        $sprite->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $sprite->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        $anthrakoucho = Item::where('name', 'Ανθρακούχο Νερό')->first();
        $anthrakoucho->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $anthrakoucho->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        $nero = Item::where('name', 'Νερό 1.4L')->first();
        $nero->tags()->attach($veganTag->id, ['sort_order' => 1]);
        $nero->tags()->attach($glutenFreeTag->id, ['sort_order' => 2]);

        // Κοτόπουλο - χωρίς γλουτένη
        $kotopouloFileto = Item::where('name', 'Κοτόπουλο Φιλέτο Σχάρας')->first();
        $kotopouloFileto->tags()->attach($glutenFreeTag->id, ['sort_order' => 1]);
    }
}
