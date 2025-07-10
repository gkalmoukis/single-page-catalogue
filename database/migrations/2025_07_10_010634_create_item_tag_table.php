<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('item_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->integer('sort_order')->default(0); // For ordering tags on items
            $table->timestamps();
            
            $table->unique(['item_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_tag');
    }
};
