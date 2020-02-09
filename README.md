# Connection Scan Algorithm in PHP
Simple library to compute public transport routes directly based on timetable data.

## Overview
This library provides a basic implementation of the CSA (Connection Scan Algorithm) introduced 2013. To use this algorithm with data from public transport agencies,
there's no need to build a graph out of them like this would be using the Dijkstra or the A* algorithm.

Sadly there're only a few little projects using this algorithm in practice. The intent to develop this library is not to re-invent the wheel
but learn about the issues when using 'real data' and how to fix them as well as keeping a simple and extendable PHP implementation
of CSA which can be used in other projects.

## Installation
To use this library, add it to your composer dependencies by typing

```
composer require gtfs/csa-routing
```

or when beginning from the scratch, simply clone this repo and type

```
composer install
```

in your command line environment.

## Usage
For usage there're two main classes called `Scanner` (for computing the connections) and `Journey` for back-routing and computing your route.
```php
<?php 

$connectionsList = []; // read the connections from database or file - must not be empty!

try {
    $scanner = new \CSA\Scanner($connectionsList);
    $connectionsIndex = $scanner->computeConnections('8000299', '8002549', '06:15:00');
    
    $journey = new \CSA\Journey($connectionsIndex);
    print_r($journey->computeLegs('8002549'));
} catch (\CSA\Exception\RoutingException $e) {
    print_r($e);
}
```
This snipped for e.g. calculates a route from `Pforzheim Hbf (8000299)` to `Hamburg Hbf (8002549)`. The output is shown in the following section. You
can see there're two legs: The first one from `Pforzheim Hbf (8000299)` to `Karlsruhe Hbf (8000191)` and the second one
from `Karlsruhe Hbf (8000191)` to `Hamburg Hbf (8002549)` with a start time of `06:15:00`.

```
Array
(
    [0] => CSA\Data\TimetableLeg Object
        (
            [tripId:protected] => 7282
            [sourceId:protected] => 8000299
            [destinationId:protected] => 8000191
            [departureTime:protected] => 06:22:00
            [arrivalTime:protected] => 06:45:00
        )

    [1] => CSA\Data\TimetableLeg Object
        (
            [tripId:protected] => 13778
            [sourceId:protected] => 8000191
            [destinationId:protected] => 8002549
            [departureTime:protected] => 06:51:00
            [arrivalTime:protected] => 11:35:00
        )

)
```

### Input Data Structure
The input connections must be an array of type `\CSA\Data\Connection` or a subclass of them. You can
obtain these connections directly from the `stop_times.txt` file in [GTFS](https://www.gtfs.org/). Of course
you can use any other arbitrary timetable data format.

Each connection consists of an `departure stop id` and a matching `departure time` as well as a `arrival stop id` and a matching `arrival time`. Additionally
connections of type `\CSA\Data\TimetableConnection` contain a field named `tripID`, which is used to determine whether an
interchange between two connections is required or not. This is especially important for the back-routing to ensure that a 
consistent trip is returned correctly.

**Important Note:** The connections in the input list *must be* sorted by their arrival time to ensure the algorithm is working properly! To avoid issues with consistent trips,
they also should be sorted by their trip ID afterwards.

## Performance
As we know, PHP is not the first choice when we speak about performance for large upcoming data streams and possible long-running
algorithms. There're also limitations like the memory limit and the max. execution time.

For experimental purposes, we took two different datasets, called dataset A & B. Dataset A consists of *one operational day* of all long-distance trains
in Germany with about ~8,500 connections. Dataset B consists of also *one operational day* of a German local transport authority with about ~180,000 connections.

### Memory
Due to the object-oriented implementation of this library it is very memory-friendly. (See this gist: https://gist.github.com/nikic/5015323)
The maxmimum memory consumption ran about 4 MB by using dataset A, and about 55 MB by using dataset B.

### Runtime
The runtime of this implementation is lower than expected: A average route computation for dataset A took around 60ms in maxiumum, for dataset B
we talk about 200ms in maximum. Compared to the runtime mentioned in the original paper this is very slow, but still enough for a 
practical use in web environments.

### Improvements & Notes
There're some possibility which you can apply to improve the performance a little bit. 

* process only connections which are running on your desired date
* prune connections arriving before your start time: those can never be reached

## Contributing
Feel free to contribute this project! Please format your code similar to the code written by the origin author and
document what you're doing in. This is not only an improvement for you, but for all other contributors, too :-)

If you found any issue or want to inform about some enhancements, please create an issue in this repository. You'll also 
find known issues and planned extensions there. If you fixed a bug or created a new feature, feel free to create a pull request.

## License
This project is licensed under the MIT License. See [LICENSE](LICENSE.md) for more information.