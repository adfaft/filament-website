<?php

namespace Database\Seeders;

use App\Enums\ActiveStatusEnum;
use App\Models\User;
use App\Rules\PasswordRule;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Validator;

class WebcmsSeeder extends Seeder
{
    public function run(): void
    {
        $validator = Validator::make(\config('webcms.admin'), [
            'name' => 'required',
            'email' => 'required',
            'password' => ['required', \env('ADMIN_PASSWORD_ENFORCE') ? 'string' : PasswordRule::default_rule()],
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
