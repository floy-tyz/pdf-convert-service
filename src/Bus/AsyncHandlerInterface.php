<?php

declare(strict_types=1);

namespace App\Bus;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('messenger.message_handler', ['bus' => 'async.bus'])]
interface AsyncHandlerInterface
{
}
