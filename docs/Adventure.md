# Adventure

### Process

Server generates first level adventure for user if user tries to get adventure map.

When map is generated user can see the map with islands.

User needs to raid all islands in the generated archipelago. If user raids all islands he can move to next level adventure.

Each next level of adventure has even more resources and warships.

There is no limit of level of adventure.

If player attacks island and battle is won, and we grabbed all resources - we mark island as Raided. Player can't raid this island again.

If all islands are raided - it means that we can change adventure to next level.

We get resources after battle as it is described in usual battle process.

### Map content

Each adventure map includes several islands:
- 1 empty island with just a few resources
- 1 village with small amount of resources and several warships
- 1 pirate bay with normal amount of resources and normal amount of warships
- 1 rich city with good amount of resources and good amount of warships
- 1 treasure island with a lot of resources and no warships

### Formulas

Warships points are converted to warships while generating island.

#### Empty Island
Has resources by formula:

$gold = $lvl * 100

$population = 0

Has warships: 0

#### Village

Has resources by formula:

$gold = $lvl * 300

$population = $lvl * 50

Has warships points: $lvl * 100

#### Pirate bay

Has resources by formula:

$gold = $lvl * 500

$population = $lvl * 100

Has warships points: $lvl * 300

#### Rich City

Has resources by formula:

$gold = $lvl * 1000

$population = $lvl * 400

Has warships points: $lvl * 700

#### Treasure Island

Has resources by formula:

$gold = $lvl * 800

$population = 0

Has warships points: 0

### Future updates

- generate warships depends on warship points
- add ability to take materials from islands
