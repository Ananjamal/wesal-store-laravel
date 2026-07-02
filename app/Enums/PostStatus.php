<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PostStatus: string implements HasLabel
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'مسودّة',
            self::Published => 'منشور',
            self::Archived => 'مؤرشف',
        };
    }
}
