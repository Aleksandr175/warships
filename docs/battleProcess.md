# Battle Process

2 side of battle: attacker (A) & defender (D).

### Shooting logic
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

#### Shot function:
We calculate whole health of warship type

$wholeHealth = warshipsQty * warshipsHealth;

If we did 100 damage, but whole health of warship type was 80 -> it means that 20 damage will be done to next warship type

After getting damage we calculate new warship qty. It could be decimal. But we round it next int value.
If warship qty is 0 -> means we don't have this type of warship anymore.

We log every shoot.

### Calculate result
After shooting we calculate warships in fleet and update this value in database and in the city.
If fleet has no warships -> we remove this fleet.

### Logs
We create log for every shoot and log for result of battle. We need to know who is winner.

### Notifications
- in progress

### Getting resources logic
We calculate full capacity of A warships.

Formula:

$wholeCapacity = warshipsQty * warshipsCapacity for each warship type in fleet;

"A" can get maximum 50% of resources in city.

We get 50% of gold and 50% of population from city.

If sum of all resources is bigger than capacity -> first we get all gold and if we have space we get the rest of the "population".

## Future updates

- set max round of battle (6-7?)
  - if no winner after max round -> it is a tie. Each side returns back without getting resources.
- add fortress, increase damage & defence ability
- add ability to attack alien trade fleet in the city (while it is process of trading)
- add notifications about battle result in real time
- when getting resources -> check capacity depends on what fleet had. Cas it could be sent with some resource to attack city.
