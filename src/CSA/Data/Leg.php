<?php


namespace CSA\Data;

use CSA\Common\TimeFormat;

/**
 * Basic abstract class for a journey leg. A leg describes a set of connections running immediately
 * after each other without the need of an interchange.
 *
 * @package CSA\Data
 */
abstract class Leg
{
    protected $sourceId;
    protected $destinationId;
    protected $departureTime;
    protected $arrivalTime;

    /**
     * Returns the source stop ID.
     *
     * @return String The source stop ID
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * Assigns the source stop ID.
     *
     * @param string $sourceId The source stop ID
     */
    public function setSourceId(string $sourceId)
    {
        $this->sourceId = $sourceId;
    }

    /**
     * Returns the destination stop ID.
     *
     * @return String The destination stop ID
     */
    public function getDestinationId()
    {
        return $this->destinationId;
    }

    /**
     * Assigns the destination stop ID.
     *
     * @param String $destinationId The destination stop ID
     */
    public function setDestinationId(string $destinationId)
    {
        $this->destinationId = $destinationId;
    }

    /**
     * Returns the departure time as human-readable string.
     *
     * @return string The departure time
     */
    public function getDepartureTime()
    {
        return $this->departureTime;
    }

    /**
     * Assigns the departure time as human-readable string.
     *
     * @param mixed $departureTime The departure time
     */
    public function setDepartureTime($departureTime)
    {
        if (is_int($departureTime)) {
            $departureTime = TimeFormat::getString($departureTime);
        }

        $this->departureTime = $departureTime;
    }

    /**
     * Returns the arrival time as human-readable string.
     *
     * @return string The arrival time
     */
    public function getArrivalTime()
    {
        return $this->arrivalTime;
    }

    /**
     * Assigns the arrival time as human-readable string.
     *
     * @param mixed $arrivalTime The arrival time
     */
    public function setArrivalTime($arrivalTime)
    {
        if (is_int($arrivalTime)) {
            $arrivalTime = TimeFormat::getString($arrivalTime);
        }

        $this->arrivalTime = $arrivalTime;
    }
}