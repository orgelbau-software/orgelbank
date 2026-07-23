<?php

use Psr\Clock\ClockInterface;

final class OrgelbankClock implements ClockInterface {
    public function now(): DateTimeImmutable {
        return new DateTimeImmutable('now');
    }
}