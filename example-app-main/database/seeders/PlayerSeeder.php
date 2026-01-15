<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Player::firstOrCreate(['name' => 'Cipriano', 'ranking' => 1000, 'retired' => true]);
        Player::firstOrCreate(['name' => 'Novak Djokovic', 'ranking' => 1, 'retired' => false]);
        Player::firstOrCreate(['name' => 'Carlos Alcaraz', 'ranking' => 2, 'retired' => false]);
        Player::firstOrCreate(['name' => 'Jannik Sinner', 'ranking' => 3, 'retired' => false]);
        Player::firstOrCreate(['name' => 'Roger Federer', 'ranking' => 99, 'retired' => true]);
        Player::firstOrCreate(['name' => 'Rafael Nadal', 'ranking' => 98, 'retired' => true]);
    }
}
