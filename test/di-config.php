<?php

use function DI\create;
use Appointment\AttendeeConfiguration;
use Appointment\Attendee;

return [
    Attendee::class=> function () {
        return new Attendee(
            new AttendeeConfiguration()
        );
    }
];
