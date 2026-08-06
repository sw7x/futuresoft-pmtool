<?php

namespace Modules\Project\Constants;

class ProjectStatus
{
    const INITIATED = 'Initiated';
    const IN_PROGRESS = 'In Progress';
    const ON_HOLD = 'On Hold';
    const COMPLETED = 'Completed';
    const CANCELLED = 'Cancelled';

    public static function all(): array
    {
        return [
            self::INITIATED,
            self::IN_PROGRESS,
            self::ON_HOLD,
            self::COMPLETED,
            self::CANCELLED,
        ];
    }
}
