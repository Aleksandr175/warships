<?php

namespace Database\Seeders;

use App\Models\BuildingDictionary;
use Illuminate\Database\Seeder;

class BuildingDictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BuildingDictionary::create(['title' => 'Главное управление', 'description' => 'Главное здание на острове']);
        BuildingDictionary::create(['title' => 'Шахта', 'description' => 'Здесь добывается золото']);
        BuildingDictionary::create(['title' => 'Дом', 'description' => 'Чем больше домов, тем больше рабочих рук!']);
        BuildingDictionary::create(['title' => 'Таверна', 'description' => 'Повышает престиж острова и увеличивает приток населения']);
        BuildingDictionary::create(['title' => 'Ферма', 'description' => 'Здесь добывается еда!']);
        BuildingDictionary::create(['title' => 'Верфь', 'description' => 'Здесь производятся военные корабли']);
        BuildingDictionary::create(['title' => 'Пристань', 'description' => 'Позволяет рыбакам ловить рыбу, а торговцам проводить свои операции']);
        BuildingDictionary::create(['title' => 'Форт', 'description' => 'Основное защитное сооружение на острове']);
    }
}
