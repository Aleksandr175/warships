<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Message::create([
            'user_id' => config('constants.DEFAULT_USER_ID'),
            'content' => 'Test message',
            'template_id' => 1,
        ]);

        Message::create([
            'user_id' => config('constants.DEFAULT_USER_ID'),
            'content' => 'Pirates attacked your island at archipelago_id: 1, coord_x: 3, coord_y: 3.',
            'event_type' => 'Pirate Attack',
            'archipelago_id' => 1,
            'coord_x' => 3,
            'coord_y' => 3,
        ]);

        Message::create([
            'user_id' => config('constants.DEFAULT_PIRATE_ID'),
            'content' => 'Pirate message',
            'event_type' => 'Pirate Attack',
            'archipelago_id' => 1,
            'coord_x' => 3,
            'coord_y' => 3,
        ]);
    }
}
