<?php

namespace App\Bus;

interface AsyncBusInterface
{
    public function dispatch(AsyncInterface $command): mixed;
}
