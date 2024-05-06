# Warships Queue Process

## Requirements

Each warship has specific requirements for construction. 
If the required resources are not available, the warship cannot be built.

## Process

When a warship is ordered for construction, a slot is occupied. Subsequent orders for warship construction will occupy additional slots.

Once the construction (for slot) is complete, the warships are added to the island.

The construction time for a group of warships is determined by the formula: time * quantity of warships.

Example:
Let's say we order 5 warships, with each warship requiring 10 seconds to build. 
In this scenario, our slot has a total construction time of 50 seconds. 
Once this time elapses, all five warships are completed simultaneously, and the slot becomes available for further use.

## Slots

We can build 10 warships maximum with one slot.

This number depends on level of Shipyard Building.

## Future updates

- add dependency of shipyard building (max number for slot, number of available slots)
- idea: change required time for building warship depending on shipyard building lvl (?)
- (DONE) Number of slots depends on shipyard building lvl
