<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // TODO: Validate the config file.
        User::factory()->create([
            'name' => config('app.admin.name'),
            'email' => config('app.admin.email'),
            'password' => bcrypt(config('app.admin.password')),
        ]);
    }
}
