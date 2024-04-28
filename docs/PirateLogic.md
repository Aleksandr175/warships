# Pirate Logic

## Pirate actions

Each 1 minute pirate island thinks what to do. It can:

- build new warship
- send fleet to attack user

### Building new warship

Only one warship can be building at one time.

Pirate island can't have more than 100 warships in total of any type

Every time pirates check their resources.

If they have more resources that needed for Frigate - they start to choose warship to build.

There is a chances for each type of warship to build:

1 => 50,  // lugger: 50% chance
2 => 25,  // caravel: 25% chance
3 => 20,  // galera: 20% chance
4 => 5,   // frigate: 5% chance
5 => 0    // battleship: 0% chance

Pirates can't build battleships

If pirate has enough resource to build chosen warship - they start to build it.

### Send fleet to attack user

Only one active fleet can be sent from pirate island at one time.

Pirate fleet should content more than 20 warships in total of any type of warships.

Pirate can attack player only in pirate's archipelago.


## Future Updates

- Set dynamic logic for minimal warships for fleet depends on player force
- Send diplomatic request (to stop attacks if user pays resources)
- Set correct production coefficients for pirate islands
  - iron, lumber etc.
