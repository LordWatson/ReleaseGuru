<?php

namespace App\Enums;

enum ProjectHealthEnum: string
{
    case Running = 'running';
    case Down = 'down';
    case Maintenance = 'maintenance';

    public function labelClass(): string {
        return match($this) {
            ProjectHealthEnum::Running => 'bg-green-100 text-green-800',
            ProjectHealthEnum::Maintenance => 'bg-yellow-100 text-yellow-800',
            ProjectHealthEnum::Down => 'bg-red-100 text-red-800',
        };
    }
}
