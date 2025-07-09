<?php

namespace Database\Seeders;

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
        // Δημιουργία κατηγοριών
        $piataImeras = Category::create([
            'name' => 'Πιάτα Ημέρας',
            'emoji' => '🍽️',
            'sort_order' => 1,
        ]);

        $tisOras = Category::create([
            'name' => 'Της Ώρας',
            'emoji' => '🔥',
            'sort_order' => 2,
        ]);

        $salates = Category::create([
            'name' => 'Σαλάτες',
            'emoji' => '🥗',
            'sort_order' => 3,
        ]);

        $kafes = Category::create([
            'name' => 'Καφέδες',
            'emoji' => '☕',
            'sort_order' => 4,
        ]);

        $anapsiktika = Category::create([
            'name' => 'Αναψυκτικά',
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
        ]);

        Item::create([
            'name' => 'Κεμπάπ Γεμιστό',
            'price' => 9.80,
            'description' => 'Χειροποίητο κεμπάπ με γέμιση τυριού, σερβίρεται με ρύζι ή πατάτες',
            'category_id' => $piataImeras->id,
            'sort_order' => 2,
        ]);

        Item::create([
            'name' => 'Κεφτεδάκια',
            'price' => 8.50,
            'description' => 'Τραγανά κεφτεδάκια με μυρωδικά και συνοδευτικό',
            'category_id' => $piataImeras->id,
            'sort_order' => 3,
        ]);

        Item::create([
            'name' => 'Μακαρόνια με Κιμά',
            'price' => 9.00,
            'description' => 'Κλασικά μακαρόνια με σπιτικό κιμά και τριμμένο τυρί',
            'category_id' => $piataImeras->id,
            'sort_order' => 4,
        ]);

        // Της Ώρας
        Item::create([
            'name' => 'Κοτόπουλο Φιλέτο Σχάρας',
            'price' => 9.50,
            'description' => 'Ζουμερό φιλέτο κοτόπουλο στη σχάρα με συνοδευτικό',
            'category_id' => $tisOras->id,
            'sort_order' => 1,
        ]);

        Item::create([
            'name' => 'Μπιφτέκι Μοσχαρίσιο Σχάρας',
            'price' => 9.00,
            'description' => 'Χειροποίητο μπιφτέκι μοσχαρίσιο στη σχάρα με πατάτες',
            'category_id' => $tisOras->id,
            'sort_order' => 2,
        ]);

        Item::create([
            'name' => 'Πανσετάκια Χοιρινά',
            'price' => 9.20,
            'description' => 'Τραγανά πανσετάκια χοιρινά στη σχάρα',
            'category_id' => $tisOras->id,
            'sort_order' => 3,
        ]);

        // Σαλάτες
        Item::create([
            'name' => 'Χωριάτικη',
            'price' => 6.00,
            'description' => 'Ντομάτα, αγγούρι, φέτα, ελιά, πιπεριά και ρίγανη',
            'category_id' => $salates->id,
            'sort_order' => 1,
        ]);

        Item::create([
            'name' => 'Ταμπουλέ',
            'price' => 6.50,
            'description' => 'Δροσιστική σαλάτα με πλιγούρι, ντομάτα, μαϊντανό και λεμόνι',
            'category_id' => $salates->id,
            'sort_order' => 2,
        ]);

        // Καφέδες
        Item::create([
            'name' => 'Freddo Espresso',
            'price' => 2.50,
            'description' => 'Διπλός espresso με πάγο',
            'category_id' => $kafes->id,
            'sort_order' => 1,
        ]);

        Item::create([
            'name' => 'Freddo Cappuccino',
            'price' => 2.80,
            'description' => 'Freddo espresso με αφρόγαλα',
            'category_id' => $kafes->id,
            'sort_order' => 2,
        ]);

        Item::create([
            'name' => 'Γαλλικός',
            'price' => 2.00,
            'description' => 'Ζεστός καφές φίλτρου',
            'category_id' => $kafes->id,
            'sort_order' => 3,
        ]);

        Item::create([
            'name' => 'Φραπεδάκι',
            'price' => 2.20,
            'description' => 'Κλασικός ελληνικός φραπέ',
            'category_id' => $kafes->id,
            'sort_order' => 4,
        ]);

        Item::create([
            'name' => 'Ελληνικός',
            'price' => 1.80,
            'description' => 'Δεν τον λέμε, αλλά τον φτιάχνουμε :P',
            'category_id' => $kafes->id,
            'sort_order' => 5,
        ]);

        // Αναψυκτικά
        Item::create([
            'name' => 'Coca Cola Zero',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 1,
        ]);

        Item::create([
            'name' => 'Coca Cola Light',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 2,
        ]);

        Item::create([
            'name' => 'Coca Cola',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 3,
        ]);

        Item::create([
            'name' => 'Fanta',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 4,
        ]);

        Item::create([
            'name' => 'Sprite',
            'price' => 1.80,
            'description' => '330ml κουτάκι',
            'category_id' => $anapsiktika->id,
            'sort_order' => 5,
        ]);

        Item::create([
            'name' => 'Ανθρακούχο Νερό',
            'price' => 1.50,
            'description' => 'Ανθρακούχο φυσικό μεταλλικό νερό',
            'category_id' => $anapsiktika->id,
            'sort_order' => 6,
        ]);

        Item::create([
            'name' => 'Νερό 1.4L',
            'price' => 1.00,
            'description' => 'Εμφιαλωμένο νερό 1.5 λίτρων',
            'category_id' => $anapsiktika->id,
            'sort_order' => 7,
        ]);
    }
}
