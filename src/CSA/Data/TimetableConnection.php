<?php

namespace CSA\Data;

use CSA\Common\TimeFormat;

/**
 * A timetable connection describes a connection travelled by a certain trip referenced by the
 * unique trip ID. It also consists of a defined departure time and according to this a
 * defined arrival time.
 *
 * @package CSA\Data
 */
class TimetableConnection extends Connection
{
    protected $departureTime;
    protected $arrivalTime;
    protected $tripId;

    /**
     * Returns the departure time in seconds from today 00:00:00.
     *
     * @return Int The departure time in seconds from today 00:00:00
     */
    public function getDepartureTime()
    {
        return $this->departureTime;
    }

    /**
     * Assigns the departure time.
     *
     * @param Int|String $departureTime The departure time in format HH:mm:ss
     * @throws \Exception When supplied time is invalid
     */
    public function setDepartureTime($departureTime)
    {
        if (is_string($departureTime)) {
            $departureTime = TimeFormat::getTimestamp($departureTime);
        }

        $this->departureTime = $departureTime;
    }

    /**
     * Returns the arrival time.
     *
     * @return String The arrival time in format HH:mm:ss
     */
    public function getArrivalTime()
    {
        return $this->arrivalTime;
    }

    /**
     * Assigns the arrival time.
     *
     * @param Int|String $arrivalTime The arrival time in format HH:mm:ss
     * @throws \Exception When supplied time is invalid
     */
    public function setArrivalTime($arrivalTime)
    {
        if (is_string($arrivalTime)) {
            $arrivalTime = TimeFormat::getTimestamp($arrivalTime);
        }

        $this->arrivalTime = $arrivalTime;
    }

    /**
     * Returns the trip ID.
     *
     * @return String The trip ID.
     */
    public function getTripId()
    {
        return $this->tripId;
    }

    /**
     * Assigns the trip ID.
     *
     * @param String $tripId The trip ID
     */
    public function setTripId($tripId)
    {
        $this->tripId = $tripId;
    }


}