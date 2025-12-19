<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء / تحديث يوزر الأدمن الرئيسي
        User::updateOrCreate(
            ['email' => 'admin@thiqah-itech.com'],
            [
                'name'     => 'System Administrator',
                'password' => Hash::make('12345678'),
            ]
        );
    }
}
