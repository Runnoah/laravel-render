<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        Player::updateOrCreate(['name' => 'Novak Djokovic', 'ranking' => 1, 'retired' => false]);
        Player::updateOrCreate(['name' => 'Carlos Alcaraz', 'ranking' => 2, 'retired' => false]);
        Player::updateOrCreate(['name' => 'Jannik Sinner', 'ranking' => 3, 'retired' => false]);
        Player::updateOrCreate(['name' => 'Roger Federer', 'ranking' => 99, 'retired' => true]);
        Player::updateOrCreate(['name' => 'Rafael Nadal', 'ranking' => 98, 'retired' => true]);
    }
}