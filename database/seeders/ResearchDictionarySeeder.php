<?php

namespace Database\Seeders;

use App\Models\ResearchDictionary;
use Illuminate\Database\Seeder;

class ResearchDictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResearchDictionary::create(['title' => 'Улучшенные паруса', 'description' => '+10% скорости кораблей']);
        ResearchDictionary::create(['title' => 'Корабельные технологии', 'description' => 'Позволяет строить верфь']);
        ResearchDictionary::create(['title' => 'Улучшенные пушки', 'description' => '+10% к атаке кораблей, позволяет строить военные корабли']);
        ResearchDictionary::create(['title' => 'Трюм', 'description' => 'Увеличивает вместимость торговых кораблей на 10%']);
    }
}
