# Process of production resources on island

## Production Logic

Each island produces several resources, but with different coefficient of productivity of resource.

On main island we produce gold and population with coefficient 1, logs and ore with coefficient 0.1.

Final quantity of resources we get with formula:

$qty = $productionResourcePerHour * $coefficient;

## Resources calculation

We calculate produced resources on island each one minute. We calculate gap we get for 1 minute and add it to island.

We calculate produced resources for all resources for what we have buildings on island.

## Future Updates

- add busters for production resources
