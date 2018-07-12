<?php

namespace Appointment\Test;

use Appointment\Maker;

class MakerTest extends \PHPUnit\Framework\TestCase
{
    private $maker;

    public function setUp()
    {
        $this->maker = new Maker();
    }

    public function testInstanceNotNull()
    {
        $this->assertNotNull($this->maker);
    }
}
