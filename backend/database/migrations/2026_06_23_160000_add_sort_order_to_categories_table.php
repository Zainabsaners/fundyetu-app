<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('description');
        });

        $order = 1;
        foreach (Category::orderBy('name')->get() as $category) {
            if ($category->slug === 'other') {
                $category->update(['sort_order' => 999]);
            } else {
                $category->update(['sort_order' => $order++]);
            }
        }

        Category::create([
            'name' => 'Politics',
            'slug' => 'politics',
            'icon' => 'shield',
            'description' => 'Political campaigns and civic initiatives',
            'sort_order' => $order++,
        ]);
    }

    public function down(): void
    {
        Category::where('slug', 'politics')->delete();
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
