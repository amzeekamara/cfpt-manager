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
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        
        // Insert default categories
        DB::table('expense_categories')->insert([
            ['name' => 'Restauration & Hébergement', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transport & Logistique', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Matériel & Équipements', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Communication & Marketing', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Frais Administratifs', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_categories');
    }
};
