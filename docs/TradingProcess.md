# Trading

## Trade process

We send fleet with trade task. We can trade with any city, even with pirates.

After fleet arrives in the city it should wait for 10 minutes. After that fleet gets gold and returns back to the original city.

Fleet with that task can repeat its task if we set so while sending that fleet.

## Getting gold

Fleet gets gold by formula:

$gold = floor($availableCapacity * 0.1).

Island gets gold too by formula:

$gold = $availableCapacity * 0.05.


## Future Updates

- trade fleet can be attacked in city where it is trading at that moment
- trading fleet can be attacked on their way to some city by pirates or player
- fix getting gold
- add tests for trading
- you can't trade with your own islands
- we can trade only with other players
- trading is a long process (1 hour?)
- you can't send more than 2 trade fleets (technology for trading)
- you can hold other trading fleets in your city. You can see it in different zone in the right panel
- if you trade with weaker player - you get more money for trading
