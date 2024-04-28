# Expedition

## Expedition process

We send fleet with expedition task.
We don't need to select coordinates
Fleet goes to unknown islands.

After fleet arrives to unknown place and fleet should wait for 10 minutes.
After that fleet gets 1 of 4 options and returns back to the original city.

Fleet with that task can repeat its task if we set so while sending that fleet.

## Expedition Result

### Getting gold 75% chance

Fleet gets gold by formula:

$availableCapacity - 25% of available capacity of whole fleet (percent can be different - in the future)

$totalValue - total value of all resource in dictionary

$qtyOfResources - number of resources in dictionary

$capacityForEachResource = $totalValue / $qtyOfResources;

$distributedQty = ($capacityForEachResource / $resource['value']);

If we have move space after distributing we can fill whole capacity by multiplying qty by coefficient

$coefficient = $availableCapacity / $totalQty;

We should multiply Qty of each resources by this coefficient. 

We can't get more than we can carry.

We can find Cards for improving warships. Cards don't have weight.

We can find 1 type of card for 1 warship randomly. Quantity: 0-2, randomly.

### Storm 4% chance

During storm, we loose 20% of each fleet detail in our Fleet.

If we had 20 luggers and 4 frigate - we loose 4 luggers and 1 frigate (we can't lose less than 1 warship).

Formula is:

$fleetDetail->update(['qty' => floor($fleetDetail['qty'] * 0.8)]);

### Nothing 20% chance

Nothing happened during expedition.

### Lost whole fleet 1% chance

We loose whole fleet with all fleet details.

## Future Updates

- we can found something in expedition, like some boosters
    - for attack, health, speed
- different coefficient for capacity (small 25%/medium 50%/large 100%)
