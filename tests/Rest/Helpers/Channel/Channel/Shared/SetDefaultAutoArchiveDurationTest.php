<?php

namespace Tests\Exan\Dhp\Rest\Helpers\Channel\Channel\Shared;

use Exan\Dhp\Rest\Helpers\Channel\Channel\Shared\SetDefaultAutoArchiveDuration;
use PHPUnit\Framework\TestCase;

class SetDefaultAutoArchiveDurationTest extends TestCase
{
    public function testSetArchiveDuration()
    {
        $class = new class extends DummyTraitTester {
            use SetDefaultAutoArchiveDuration;
        };

        $class->setDefaultAutoArchiveDuration(10);

        $this->assertEquals(['default_auto_archive_duration' => 10], $class->get());
    }
}
