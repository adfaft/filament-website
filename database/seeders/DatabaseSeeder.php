<?php

namespace Database\Seeders;

use App\Enums\ActiveStatusEnum;
use App\Models\User;
use App\Rules\PasswordRule;
use Exception;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Validator;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $validator = Validator::make(\config('webcms.admin'), [
            'name' => 'required',
            'email' => 'required',
            'password' => ['required', PasswordRule::default_rule()],
        ]);

        if ($validator->fails()) {
            $error_message = implode("\n\t", $validator->errors()->all());
            throw new Exception("Please provide a valid admin credentials in webcms config or env \n\t{$error_message}");
        }

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
