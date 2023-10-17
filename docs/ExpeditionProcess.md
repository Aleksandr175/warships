# Expedition

### Expedition process

We send fleet with expedition task.
Fleet goes to unknown islands.

After fleet arrives in the city it should wait for 10 minutes. 
After that fleet gets 1 of 4 options and returns back to the original city.

Fleet with that task can repeat its task if we set so while sending that fleet.

### Expedition Result
#### Getting gold 75% chance

Fleet gets gold by formula:

$gold = random_int(1, $availableCapacity);

We can't get more than we can carry.

#### Storm 4% chance

During storm, we loose 20% of each fleet detail in our Fleet.

If we had 20 luggers and 4 frigate - we loose 4 luggers and 1 frigate (we can't loose less than 1 warship).

Formula is:

$fleetDetail->update(['qty' => floor($fleetDetail['qty'] * 0.8)]);

#### Nothing 20% chance

Nothing happened during expedition.

#### Lost whole fleet 1% chance

We loose whole fleet with all fleet details.

### Future Updates

- we can found something in expedition, like some boosters
  - for attack, health, speed
