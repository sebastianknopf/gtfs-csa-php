<?php

namespace CSA;

use CSA\Data\Connection;
use CSA\Data\TimetableLeg;
use CSA\Exception\RoutingException;

/**
 * The Journey class is used to transform the result of the Scanner into an valid and easy to read
 * journey with at least one leg.
 *
 * @package CSA
 */
class Journey
{
    private $connectionIndex = [];

    /**
     * Basic constructor for Journey class.
     *
     * @param $connectionIndex The connections index calculated before
     * @throws RoutingException When the journey construction fails
     */
	public function __construct($connectionIndex)
	{
		if ($connectionIndex == null || count($connectionIndex) == 0) {
		    throw new RoutingException('The scanner result must not be null or an empty string!');
        }

	    $this->connectionIndex = $connectionIndex;
	}

    /**
     * Checks whether an interchange is required.
     *
     * @param Connection $connectionA The first connection
     * @param Connection $connectionB The second connection
     * @return bool Whether an interchange is required
     */
	private function _isInterchangeRequired(Connection $connectionA, Connection $connectionB)
	{
		return $connectionA->getTripId() != $connectionB->getTripId();
	}

    /**
     * Walks through the connections index and finds all required legs for a
     * route to the desired destination ID.
     *
     * @param string $destId The destination ID
     * @return array|null The calculated journey legs
     */
	private function _getLegs(string $destId)
	{
		$legs = [];
		$legConnections = [];
		$lastConnection = null;
		
		gc_disable();
		
		while (array_key_exists($destId, $this->connectionIndex)) {
			$connection = $this->connectionIndex[$destId];
			
			if ($lastConnection != null && $this->_isInterchangeRequired($lastConnection, $connection)) {
				array_push($legs, $this->_flattenConnections(array_reverse($legConnections)));
				$legConnections = [];
			}
			
			array_push($legConnections, $connection);
			$lastConnection = $connection;

			$destId = $connection->getSourceId();
		}
		
		array_push($legs, $this->_flattenConnections(array_reverse($legConnections)));
		
		gc_enable();
		unset($this->connectionIndex);
		unset($legConnections);
		
		return count($legs) == 0 ? null : array_reverse($legs);
	}

    /**
     * Combines multiple connections to one single TimetableLeg.
     *
     * @param array $connections The input connections list
     * @return TimetableLeg The single timetable leg
     */
	private function _flattenConnections(array $connections)
	{
	    $resultLeg = new TimetableLeg();
		$lastIndex = count($connections) - 1;
		
		$resultLeg->setSourceId($connections[0]->getSourceId());
		$resultLeg->setDepartureTime($connections[0]->getDepartureTime());
		$resultLeg->setDestinationId($connections[$lastIndex]->getDestinationId());
		$resultLeg->setArrivalTime($connections[$lastIndex]->getArrivalTime());
		$resultLeg->setTripId($connections[0]->getTripId());
		
		return $resultLeg;
	}

    /**
     * Computes a complete route to the desired destination ID.
     *
     * @param string $destId The destination ID
     * @return array|null The computed route
     */
	public function computeLegs(string $destId)
	{
		return $this->_getLegs($destId);
	}
}