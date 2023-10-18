<?php
return [
    'DEFAULT_PIRATE_ID' => 1,
    'DEFAULT_PIRATE_CITY_ID'   => 212,

    'DEFAULT_USER_ID' => 5,

    'DEFAULT_USER_CITY_ID'   => 10,
    'DEFAULT_USER_CITY_ID_2' => 11,

    'DEFAULT_USER_ID_2'      => 6,
    'DEFAULT_USER_2_CITY_ID' => 12,

    'CITY_TYPE_ID' => [
        'ISLAND' => 1,
        'PIRATE_BAY' => 2,
        'COLONY' => 3,
    ],

    'BUILDINGS'  => [
        'MAIN'     => 1,
        'MINE'     => 2,
        'HOUSES'   => 3,
        'TAVERN'   => 4,
        'FARM'     => 5,
        'SHIPYARD' => 6,
        'DOCK'     => 7,
        'FORTRESS' => 8,
    ],
    'RESEARCHES' => [
        'SHIP_TECHNOLOGIES' => 1,
        'SHIP_SAILS'        => 2,
        'SHIP_GUNS'         => 3,
        'SHIP_HOLD'         => 4,
    ],
    'WARSHIPS'   => [
        'LUGGER'     => 1,
        'CARAVEL'    => 2,
        'GALERA'     => 3,
        'FRIGATE'    => 4,
        'BATTLESHIP' => 5,
    ],

    'FLEET_TASKS' => [
        'TRADE'      => 1,
        'MOVE'       => 2,
        'ATTACK'     => 3,
        'TRANSPORT'  => 4,
        'EXPEDITION' => 5,
    ],

    'FLEET_STATUSES' => [
        'TRADE_GOING_TO_TARGET' => 1,
        'TRADING'               => 2,
        'TRADE_GOING_BACK'      => 3,

        'MOVING_GOING_TO_TARGET' => 1,
        'MOVING_GOING_BACK'      => 3,

        'TRANSPORT_GOING_TO_TARGET' => 1,
        'TRANSPORT_GOING_BACK'      => 3,

        'ATTACK_GOING_TO_TARGET' => 1,
        'ATTACK_GOING_BACK'      => 3,
        'ATTACK_IN_PROGRESS'     => 4,

        'EXPEDITION_GOING_TO_TARGET' => 40,
        'EXPEDITION_IN_PROGRESS'     => 42,
        'EXPEDITION_GOING_BACK'      => 41,
    ]
];
