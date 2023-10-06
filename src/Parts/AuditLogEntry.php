<?php

declare(strict_types=1);

namespace Ragnarok\Fenrir\Parts;

use Ragnarok\Fenrir\Enums\AuditLogEvents;

class AuditLogEntry
{
    public ?string $target_id;
    /**
     * @var \Ragnarok\Fenrir\Parts\AuditLogChange[]
     */
    public ?array $changes;
    public ?string $user_id;
    public string $id;
    public AuditLogEvents $action_type;
    public ?OptionalAuditEntryInfo $options;
    public ?string $reason;

    public function setActionType(int $value): void
    {
        $this->action_type = AuditLogEvents::tryFrom($value);
    }
}
