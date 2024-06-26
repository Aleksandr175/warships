<?php
return [
    'DEFAULT_PIRATE_ID'      => 1,
    'DEFAULT_PIRATE_CITY_ID' => 212,

    'DEFAULT_USER_ID' => 5,

    'DEFAULT_USER_CITY_ID'   => 10,
    'DEFAULT_USER_CITY_ID_2' => 11,

    'DEFAULT_USER_ID_2'      => 6,
    'DEFAULT_USER_2_CITY_ID' => 12,

    'CITY_TYPE_ID' => [
        'ISLAND'     => 1,
        'PIRATE_BAY' => 2,
        'COLONY'     => 3,

        'ADVENTURE_EMPTY'      => 10,
        'ADVENTURE_VILLAGE'    => 11,
        'ADVENTURE_RICH_CITY'  => 12,
        'ADVENTURE_PIRATE_BAY' => 13,
        'ADVENTURE_TREASURE'   => 14,
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
        'WORKSHOP' => 9,
        //'EMBASSY' => 10,
    ],
    'RESEARCHES' => [
        'SHIP_TECHNOLOGIES' => 1,
        'SHIP_SAILS'        => 2,
        'SHIP_GUNS'         => 3,
        'SHIP_HOLD'         => 4,
        'TRADE_SYSTEM'      => 5,
        'EXPEDITION_SYSTEM' => 6,
    ],
    'WARSHIPS'   => [
        'LUGGER'     => 1,
        'CARAVEL'    => 2,
        'GALERA'     => 3,
        'FRIGATE'    => 4,
        'BATTLESHIP' => 5,
    ],

    'FLEET_TASKS' => [
        'TRADE'      => 1000,
        'MOVE'       => 1001,
        'ATTACK'     => 1002,
        'TRANSPORT'  => 1003,
        'EXPEDITION' => 1004,
    ],

    'FLEET_STATUSES' => [
        'TRADE_GOING_TO_TARGET' => 1,
        'TRADING'               => 2,
        'TRADE_GOING_BACK'      => 3,

        'MOVING_GOING_TO_TARGET' => 10,
        'MOVING_GOING_BACK'      => 11,

        'TRANSPORT_GOING_TO_TARGET' => 21,
        'TRANSPORT_GOING_BACK'      => 22,

        'ATTACK_GOING_TO_TARGET' => 30,
        'ATTACK_GOING_BACK'      => 31,
        'ATTACK_IN_PROGRESS'     => 32,
        'ATTACK_COMPLETED'       => 33,

        'EXPEDITION_GOING_TO_TARGET' => 40,
        'EXPEDITION_IN_PROGRESS'     => 42,
        'EXPEDITION_DONE'            => 43,
        'EXPEDITION_GOING_BACK'      => 41,
    ],

    'MESSAGE_TEMPLATE_IDS' => [
        'FLEET_TRADE_START_TRADING' => 1,
        'FLEET_TRADE_IS_BACK'       => 2,

        'FLEET_MOVE_DONE'      => 3,
        'FLEET_MOVE_CANT'      => 4,
        'FLEET_MOVE_WENT_BACK' => 5,

        'FLEET_EXPEDITION_RESOURCES' => 6,
        'FLEET_EXPEDITION_STORM'     => 7,
        'FLEET_EXPEDITION_LOST'      => 8,
        'FLEET_EXPEDITION_NOTHING'   => 9,
        'FLEET_EXPEDITION_IS_BACK'   => 10,

        'BATTLE_ATTACK_HAPPENED' => 100,
        'BATTLE_DEFEND_HAPPENED' => 101,
    ],

    'ARCHIPELAGO_TYPES' => [
        'USUAL'     => 1,
        'ADVENTURE' => 2,
    ],

    'ADVENTURE_STATUSES' => [
        'NEW'       => 1,
        'COMPLETED' => 2,
    ],

    'RESOURCE_TYPE_IDS' => [
        'COMMON'   => 1,
        'CARD'     => 2,
        'RESEARCH' => 3,
    ],

    'RESOURCE_IDS' => [
        'GOLD'       => 1,
        'POPULATION' => 2,
        'LOG'        => 3,
        'PLANK'      => 4,
        'LUMBER'     => 5,
        'ORE'        => 6,
        'IRON'       => 7,
        'STEEL'      => 8,

        'CARD_LUGGER_ATTACK'       => 9,
        'CARD_LUGGER_CAPACITY'     => 10,
        'CARD_LUGGER_HEALTH'       => 11,
        'CARD_CARAVEL_ATTACK'      => 12,
        'CARD_CARAVEL_CAPACITY'    => 13,
        'CARD_CARAVEL_HEALTH'      => 14,
        'CARD_GALERA_ATTACK'       => 15,
        'CARD_GALERA_CAPACITY'     => 16,
        'CARD_GALERA_HEALTH'       => 17,
        'CARD_FRIGATE_ATTACK'      => 18,
        'CARD_FRIGATE_CAPACITY'    => 19,
        'CARD_FRIGATE_HEALTH'      => 20,
        'CARD_BATTLESHIP_ATTACK'   => 21,
        'CARD_BATTLESHIP_CAPACITY' => 22,
        'CARD_BATTLESHIP_HEALTH'   => 23,

        'KNOWLEDGE' => 100,
    ],

    'DURATION' => [ // sec
        'TRADING_GOING_TO'      => 10,
        'TRADING_GOING_BACK'    => 10,
        'TRADING'               => 20 * 60,
        'ATTACK_GOING_TO'       => 10,
        'ATTACK_GOING_BACK'     => 10,
        'TRANSPORT_GOING_TO'    => 10,
        'TRANSPORT_GOING_BACK'  => 10,
        'MOVE_GOING_TO'         => 10,
        'MOVE_GOING_BACK'       => 10,
        'EXPEDITION_GOING_TO'   => 10,
        'EXPEDITION_GOING_BACK' => 10,
        'EXPEDITION'            => 30 * 60,
    ]
];
