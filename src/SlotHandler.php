<?php

namespace Appointment;

use Appointment\createDateRFC;
use Appointment\getDuration;

/**
 *
 */
class SlotHandler
{
    /**
     * Get available slots
     * @param  int $duration
     * @param  string $daySlot
     * @param  \Google_Calendar_Service_Event $events
     * @return array
     */
    public function getAvailableSlots($duration, $daySlot, $events)
    {
        //print_r($daySlot);
        $schedules = array();

        $daySlot = explode("-", $daySlot);

        $slots;
        foreach ($daySlot as $slot) {
            $slots[] = trim($slot);
        }

        $startTime = explode(":", $slots[0]);

        $endTime = explode(":", $slots[1]);

        $d1 = createDateRFC($startTime[0], $startTime[1]);

        $d2 = createDateRFC($endTime[0], $endTime[1]);

        $schedules[] = $this->filterDuration(

            getDuration(

                $events[0]->getStart()->getDateTime(),
                $d1
            ),
            $duration
        );

        for ($i = 1; $i < count($events); $i++) {
            $eventBefore = $events[$i - 1];
            $eventAfter  = $events[$i];
            $duration    = $this->filterDuration(
                getDuration(
                    $eventAfter->getStart()->getDateTime(),
                    $eventBefore->getEnd()->getDateTime()
                ),
                $duration
            );

            $schedules[] = $duration;
        }

        $schedules[] = $this->filterDuration(
            getDuration(

                $d2,
                $events[count($events) - 1]->getEnd()->getDateTime()
            ),
            $duration
        );

        return $schedules;
    }
    /**
     * Filter duration
     * @param  int $durationAvailable
     * @param  int $minimum
     * @return int
     */
    private function filterDuration($durationAvailable, $minimum)
    {
        if ($durationAvailable >= $minimum) {
            return $durationAvailable;
        }
        return -1;
    }
}
