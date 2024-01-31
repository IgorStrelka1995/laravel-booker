<?php

namespace Database\Seeders;

use App\Models\Boardroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoardroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Boardroom::factory()->count(3)->sequence(
            ['name' => 'Boardroom 1', 'active' => true],
            ['name' => 'Boardroom 2', 'active' => true],
            ['name' => 'Boardroom 3', 'active' => true],
        )->create();
    }
}
