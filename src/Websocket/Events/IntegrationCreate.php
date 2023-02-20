<?php

declare(strict_types=1);

namespace Exan\Fenrir\Websocket\Events;

use Exan\Fenrir\Parts\Integration;
use Exan\Fenrir\Parts\Traits\WithGuildId;

/**
 * @see https://discord.com/developers/docs/topics/gateway-events#integration-create
 */
class IntegrationCreate extends Integration
{
    use WithGuildId;
}
