<?php

namespace Appointment\Test;

use Appointment\Attendee;
use Appointment\AttandeeConfiguration;
use function Appointment\isSlotAvailable;

class FunctionTest extends \PHPUnit\Framework\TestCase
{
    private $startTime;
    private $endTime;
    private $attendeeConfiguration;
    private $attendee;

    public function setUp()
    {
        $this->attendeeConfiguration = new AttandeeConfiguration();
        $this->attendee = new Attendee($this->attendeeConfiguration);
        $this->startTime = '2018-07-12T10:00:00+07:00';
        $this->endTime = '2018-07-12T11:00:00+07:00';
    }

    public function testCheckIfSlotIsAvailable()
    {
        $config = $this->attendeeConfiguration->getDateSlots();
        $startTime = $this->startTime;
        $endTime = $this->endTime;
        $events = $this->attendee->listEvents($startTime, $endTime);
        
        $this->assertTrue(isSlotAvailable($startTime, $endTime, $config, $events));
    }
}
