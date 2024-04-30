<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\MessageFleetDetail;
use App\Models\MessageFleetResource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            Message::create([
                'user_id'     => config('constants.DEFAULT_USER_ID'),
                'content'     => 'Test message',
                'template_id' => 1,
            ]);
        }

        Message::create([
            'user_id'     => config('constants.DEFAULT_USER_ID'),
            'content'     => 'Test message',
            'template_id' => 1,
        ]);

        $messageId = Message::create([
            'user_id'     => config('constants.DEFAULT_USER_ID'),
            'content'     => 'Test Trade Fleet Return message',
            'template_id' => 1,
        ])->id;

        MessageFleetDetail::create([
            'message_id' => $messageId,
            'fleet_id'   => 1,
            'warship_id' => config('constants.WARSHIPS.LUGGER'),
            'qty'        => 3
        ]);
        MessageFleetDetail::create([
            'message_id' => $messageId,
            'fleet_id'   => 1,
            'warship_id' => config('constants.WARSHIPS.CARAVEL'),
            'qty'        => 1
        ]);

        MessageFleetResource::create([
            'message_id' => $messageId,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'        => 200
        ]);
        MessageFleetResource::create([
            'message_id' => $messageId,
            'resource_id'   => config('constants.RESOURCE_IDS.LOG'),
            'qty'        => 50
        ]);

        Message::create([
            'user_id'        => config('constants.DEFAULT_USER_ID'),
            'content'        => 'Pirates attacked your island.',
            'event_type'     => 'Pirate Attack',
            'archipelago_id' => 1,
            'coord_x'        => 3,
            'coord_y'        => 3,
        ]);

        Message::create([
            'user_id'        => config('constants.DEFAULT_PIRATE_ID'),
            'content'        => 'Pirate message',
            'event_type'     => 'Pirate Attack',
            'archipelago_id' => 1,
            'coord_x'        => 3,
            'coord_y'        => 3,
        ]);
    }
}
