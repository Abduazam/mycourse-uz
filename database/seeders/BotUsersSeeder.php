<?php

namespace Database\Seeders;

use App\Models\BotUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BotUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws \Exception
     */
    public function run(): void
    {
        // Generate 30 rows of data
        for ($i = 0; $i < 30; $i++) {
            BotUser::create([
                'chat_id' => random_int(100000000, 999999999),
                'first_name' => Str::random(8),
                'username' => Str::random(10),
            ]);
        }
    }
}
