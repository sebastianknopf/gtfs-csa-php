<?php


namespace CSA\Data;

/**
 * A timetable leg describes a certain type of leg used only by trips. It contains
 * also a trip ID to identify the unique trip which is travelling on the leg.
 *
 * @package CSA\Data
 */
class TimetableLeg extends Leg
{
    protected $tripId;

    /**
     * Returns the trip ID.
     *
     * @return string The trip ID
     */
    public function getTripId()
    {
        return $this->tripId;
    }

    /**
     * Assigns the trip ID.
     *
     * @param string $tripId The trip ID
     */
    public function setTripId(string $tripId)
    {
        $this->tripId = $tripId;
    }
}