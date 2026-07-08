<?php

namespace App;

enum IdeaStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendig',
            self::IN_PROGRESS => 'In_progress',
            self::COMPLETED => 'Completed',
        };
    }

    Public static function Values()
    {
        return array_map(fn($status) => $status->value, self::cases());
    }
}
