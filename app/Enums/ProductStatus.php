<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductStatus: string implements HasLabel
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => __('status.draft'),
            self::Published => __('status.published'),
            self::Archived => __('status.archived'),
        };
    }
}
