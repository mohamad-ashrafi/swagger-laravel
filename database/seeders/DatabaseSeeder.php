<?php

namespace Database\Seeders;

use App\Models\Post;
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
            'username'=>'mohamad',
            'country_code'=>98,
            'mobile_number'=>9102403100,
            'password'=>123456,
        ]);
        User::factory()->count(30)->create();
        Post::factory()->count(30)->create();
    }
}
