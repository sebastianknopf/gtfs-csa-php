<?php

namespace CSA;

use CSA\Common\TimeFormat;
use CSA\Data\Connection;
use CSA\Data\TimetableConnection;

/**
 * The Scanner class is used to apply the CSA routing and compute all connections based on
 * the input data.
 *
 * @package CSA
 */
class Scanner
{
    private $timetableConnectionsList;
    private $transferConnectionsList;
    private $minInterchangeTime;

	private $earliestArrivals = [];
	private $connectionIndex = [];

    /**
     * Basic Scanner constructor.
     *
     * @param array $timetableConnectionsList The timetable connections list
     * @param array $transferConnectionsList The transfer connections list
     * @param int $minInterchangeTime The minimum interchange time in seconds
     */
	public function __construct(array $timetableConnectionsList, array $transferConnectionsList = [], $minInterchangeTime = 300)
	{
		$this->timetableConnectionsList = $timetableConnectionsList;
		$this->transferConnectionsList = $transferConnectionsList;
		$this->minInterchangeTime = $minInterchangeTime;
	}

    /**
     * Checks whether a connection is reachable in the current state.
     *
     * @param Connection $connection The connection to approve
     * @return bool Whether a connection is reachable
     */
	private function _isReachable(Connection $connection)
	{
		$reachable = array_key_exists($connection->getSourceId(), $this->earliestArrivals) && $this->earliestArrivals[$connection->getSourceId()] <= $connection->getDepartureTime();
		return $reachable;
	}

    /**
     * Checks whether a connection improves another connection in the current state.
     *
     * @param Connection $connection The connection to approve
     * @return bool Whether a connection is better
     */
	private function _isBetter(Connection $connection)
	{
		if ($connection instanceof TimetableConnection) {
		    $better = !array_key_exists($connection->getDestinationId(), $this->earliestArrivals) || $this->earliestArrivals[$connection->getDestinationId()] > $connection->getArrivalTime();
		    return $better;
        }

		return false;
	}

    /**
     * Checks the termination condition: If a desired destination ID is already scanned.
     *
     * @param string $destId The destination ID
     * @return bool Whether the scan has finished
     */
	private function _isFinish(string $destId)
	{
		return array_key_exists($destId, $this->earliestArrivals);
	}

    /**
     * Adds a connection to the current index.
     *
     * @param Connection$connection The connection to add
     * @return bool Whether a new stop is reached
     */
	private function _addConnection(Connection $connection)
	{
		$exists = array_key_exists($connection->getDestinationId(), $this->earliestArrivals);

		$this->earliestArrivals[$connection->getDestinationId()] = $connection->getArrivalTime();
		$this->connectionIndex[$connection->getDestinationId()] = $connection;

		return !$exists;
	}

    /**
     * Computes a connection index for the desired source ID, destination ID by the start time.
     *
     * @param string $sourceId The source ID
     * @param string $destId The destination ID
     * @param string $startTime The start time formatted as HH:mm:ss
     * @return array|null The computed connection index
     * @throws \Exception When the time conversion fails
     */
	public function computeConnections(string $sourceId, string $destId, string $startTime)
	{
		$this->earliestArrivals[$sourceId] = TimeFormat::getTimestamp($startTime);

		gc_disable();

		foreach ($this->timetableConnectionsList as $connection) {
			if ($this->_isReachable($connection) && $this->_isBetter($connection)) {
				$this->_addConnection($connection);
			}

			if ($this->_isFinish($destId)) {
				break;
			}
		}

		$result = $this->_isFinish($destId) ? $this->connectionIndex : null;

		gc_enable();
		unset($this->earliestArrivals);
		unset($this->connectionIndex);

		return $result;
	}
}