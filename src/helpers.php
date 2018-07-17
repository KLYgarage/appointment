<?php

namespace Appointment;

/**
 * Filter string
 * @param  mixed $str
 * @return string
 * @throws Exception
 */
function filterString($str)
{
    if (is_string($str)) {
        return $str;
    }
    throw new \Exception("Not a string", 1);
}
/**
 * Filter file path
 * @param  string $path
 * @return string
 * @throws \Exception
 */
function filterFilePath($path)
{
    if (!file_get_contents($path)) {
        throw new \Exception("File not found", 1);
    }
    return $path;
}
/**
 * Check whether the choosen slot is available or not before submitting event
 * Based on available_slots defined in config.json and list event on google calendar
 * @param  string  $startTime
 * @param  string  $endTime
 * @param  array $config
 * @param  array $events
 * @return boolean
 */
function getAvailableSlots($startTime, $endTime, $config = array(), $events)
{
    $date = date_create($startTime);
    $day = strtolower((date_format($date, "l")));
    $startHour = getHourFromRFCDate($startTime);
    $endHour = getHourFromRFCDate($endTime);
    $slotsOnConfig = array_column($config, $day);
    $availableSlots = [];

    //temporary hardcoded
    $eventTypeDuration = 60;

    if (!empty($slotsOnConfig)) {
        $startOnConfig = substr($slotsOnConfig[0], 0, 5);
        $endOnConfig = substr($slotsOnConfig[0], 8, 5);

        if ($startHour >= $startOnConfig && $endHour <= $endOnConfig) {
            $bookedSlots = tmpEventsToArray($events);

            $bookedSlotsLength = count($bookedSlots);
            
            if ($bookedSlotsLength == 0) {
                array_push($availableSlots, $slotsOnConfig[0]);
            }

            for ($i = 0; $i < $bookedSlotsLength; $i++) {
                if ($i == 0 && getInterval($startOnConfig, getHourFromRFCDate($bookedSlots[$i]['start'])) >= $eventTypeDuration) {
                    array_push($availableSlots, [
                        'start'=>$startOnConfig,
                        'end'=>$bookedSlots[$i]['start']
                    ]);
                }

                if ($i == ($bookedSlotsLength - 1) && getInterval(getHourFromRFCDate($bookedSlots[$i]['end']), $endOnConfig) >= $eventTypeDuration) {
                    array_push($availableSlots, [
                        'start'=>$bookedSlots[$i]['end'],
                        'end'=>$endOnConfig
                    ]);
                }

                if ($i < ($bookedSlotsLength - 1)) {
                    if (getInterval(getHourFromRFCDate($bookedSlots[$i]['end']), getHourFromRFCDate($bookedSlots[$i + 1]['start'])) >= $eventTypeDuration) {
                        array_push($availableSlots, [
                            'start'=>$bookedSlots[$i]['end'],
                            'end'=>$bookedSlots[$i + 1]['start']
                        ]);
                    }
                }
            }
        }
    }
    return $availableSlots;
}

function getInterval($startTime, $endTime)
{
    $start = strtotime('1/1/1990 ' . $startTime);
    $end = strtotime('1/1/1990 ' . $endTime);

    return ($end - $start) / 60;
}

function getHourFromRFCDate($date)
{
    return substr($date, 11, 5);
}

function tmpEventsToArray($events)
{
    $result = array_map(function ($event) {
        return [
            'start'=>$event->getStart()->dateTime,
            'end'=>$event->getEnd()->dateTime
        ];
    }, $events);

    return $result;
}
