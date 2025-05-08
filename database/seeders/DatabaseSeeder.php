<?php

namespace Database\Seeders;

use App\Enums\ActiveStatusEnum;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => \config('webcms.admin.name'),
            'email' => \config('webcms.admin.email'),
            'password' => \config('webcms.admin.password'),
            'status' => ActiveStatusEnum::ACTIVE,
        ]);

        $this->call([
            ShieldSeeder::class,
        ]);
    }
}
