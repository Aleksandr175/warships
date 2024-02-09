# Battle Process

2 side of battle: attacker (A) & defender (D).

## Shooting logic
If A and D together has warships - we can have new round.

Each round we calculate attack force for each side.
Same for A and D.
Attacker makes first shoot.

Formula: 
Attack force = Sum of (warship qty) * (warship attack value) for all types of warship in fleet

We divide whole attack force to number of warship types.

$attackingDamageToEachType = $attackingForce / count($defendingWarships);

$defendingDamageToEachType = $defendingForce / count($attackingFleetDetails);

If we have A dealt 1000 damage, and D has 5 type of warships -> it means each type of warship on D side will get 200 damage.

### Shot function:
We calculate whole health of warship type

$wholeHealth = warshipsQty * warshipsHealth;

If we did 100 damage, but whole health of warship type was 80 -> it means that 20 damage will be done to next warship type

After getting damage we calculate new warship qty. It could be decimal. But we round it next int value.
If warship qty is 0 -> means we don't have this type of warship anymore.

We log every shoot.

## Calculate result
After shooting we calculate warships in fleet and update this value in database and in the city.
If fleet has no warships -> we remove this fleet.

## Logs
We create log for every shoot and log for result of battle. We need to know who is winner.

## Notifications
- in progress

## Getting resources logic if battle between user and pirates (IN PROCESS)
We calculate full capacity of player.

Formula:

$wholeCapacity = warshipsQty * warshipsCapacity for each warship type in fleet;

1) If user attacks pirates and win -> We get all resources from pirate island in equal proportion.

Player can get maximum 100% of resources in pirate city.

We get all resources in equal proportion.

If sum of all resources is bigger than capacity -> we get as much as we can in equal proportion.

2) If user attacks pirates and lost -> Player doen't get anything. Pirates doesn't get anything too.

3) If pirates attack player and lost -> Player doen't get anything. Pirates doesn't get anything too.

4) If pirates attack player and win -> Pirates get resources the same way as it was for .

## Getting resources logic if battle between users (NOT NECESSARY)
We calculate full capacity of A warships.

Formula:

$wholeCapacity = warshipsQty * warshipsCapacity for each warship type in fleet;

"A" can get maximum 100% of resources in city.

We get all resources in equal proportion.

If sum of all resources is bigger than capacity -> we get as much as we can by equal proportion.

## Getting resources logic if battle between user and adventure island

We calculate full capacity of A warships.

Formula:

$wholeCapacity = warshipsQty * warshipsCapacity for each warship type in fleet;

"A" can get maximum 100% of resources in city.

We get all resources in equal proportion (%).

If sum of all resources is bigger than capacity -> we get as much as we can by equal proportion.

If defender island doesn't have any resources after battle - it will mark as "raided" and can't be attacked again.

### Example:

Capacity: 60

Resources in City: 300, 200, 100

Fleet will get 30, 20, 10

## Future updates

- add skills for warships like ability to make double damage to some type of warships
- set max round of battle (6-7?)
  - if no winner after max round -> it is a tie. Each side returns back without getting resources.
- add fortress, increase damage & defence ability
- add ability to attack alien trade fleet in the city (while it is process of trading)
- add notifications about battle result in real time
- (DONE) when getting resources -> check capacity depends on what fleet had. Cas it could be sent with some resource to attack city.
- (DONE) get new resources in equal proportion after battle
