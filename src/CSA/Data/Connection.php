<?php


namespace CSA\Data;

/**
 * Basic abstract class for connections. A connection basically describes timetable information
 * in very low level as set of a departure and a arrival station.
 *
 * @package CSA\Data
 */
abstract class Connection
{
    protected $sourceId;
    protected $destinationId;

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
     * @param String $sourceId The source stop ID
     */
    public function setSourceId($sourceId)
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
    public function setDestinationId($destinationId)
    {
        $this->destinationId = $destinationId;
    }


}