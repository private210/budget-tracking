<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public static function defaults(): array
    {
        return [
            ['name' => 'Kebutuhan Pokok', 'icon' => '🛒', 'color' => '#EF4444', 'is_default' => true],
            ['name' => 'Transport', 'icon' => '🚗', 'color' => '#F59E0B', 'is_default' => true],
            ['name' => 'Tabungan', 'icon' => '💰', 'color' => '#10B981', 'is_default' => true],
            ['name' => 'Hiburan', 'icon' => '🎮', 'color' => '#8B5CF6', 'is_default' => true],
            ['name' => 'Tagihan & Utilitas', 'icon' => '📄', 'color' => '#3B82F6', 'is_default' => true],
            ['name' => 'Lainnya', 'icon' => '📦', 'color' => '#6B7280', 'is_default' => true],
        ];
    }

    public static function seedFor(User $user): void
    {
        foreach (self::defaults() as $category) {
            Category::firstOrCreate(
                ['name' => $category['name'], 'user_id' => $user->id],
                $category,
            );
        }
    }

    public function run(): void
    {
        $user = User::query()->orderBy('id')->first();

        if ($user) {
            self::seedFor($user);

            return;
        }

        foreach (self::defaults() as $category) {
            Category::create($category);
        }
    }
}
