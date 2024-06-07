<?php

namespace App\Bus;

interface EventBusInterface
{
    public function publish(EventInterface $event): mixed;
}
