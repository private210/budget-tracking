<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kebutuhan Pokok', 'icon' => '🛒', 'color' => '#EF4444', 'is_default' => true],
            ['name' => 'Transport', 'icon' => '🚗', 'color' => '#F59E0B', 'is_default' => true],
            ['name' => 'Tabungan', 'icon' => '💰', 'color' => '#10B981', 'is_default' => true],
            ['name' => 'Hiburan', 'icon' => '🎮', 'color' => '#8B5CF6', 'is_default' => true],
            ['name' => 'Tagihan & Utilitas', 'icon' => '📄', 'color' => '#3B82F6', 'is_default' => true],
            ['name' => 'Lainnya', 'icon' => '📦', 'color' => '#6B7280', 'is_default' => true],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
