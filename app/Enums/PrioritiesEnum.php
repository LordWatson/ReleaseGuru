<?php

namespace App\Enums;

enum PrioritiesEnum: string
{
    case High = 'high';
    case Medium = 'medium';
    case Low = 'low';

    public function labelClass(): string {
        return match($this) {
            PrioritiesEnum::High => 'bg-red-100 text-red-800',
            PrioritiesEnum::Medium => 'bg-yellow-100 text-yellow-800',
            PrioritiesEnum::Low => 'bg-green-100 text-green-800',
        };
    }
}
