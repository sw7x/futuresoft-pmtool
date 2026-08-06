<?php

namespace Modules\Project\Constants;

class ProjectType
{
    const LOCAL = 'Local';
    const FOREIGN = 'Foreign';

    public static function all(): array
    {
        return [
            self::LOCAL,
            self::FOREIGN,
        ];
    }
}
