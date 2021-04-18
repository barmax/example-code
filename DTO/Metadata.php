<?php

declare(strict_types=1);

namespace App\DTO;

class Metadata
{
    private string $type;

    private string $icon;

    private bool $isActive;

    public function __construct(string $type, string $icon, bool $isActive)
    {
        $this->type = $type;
        $this->icon = $icon;
        $this->isActive = $isActive;
    }

    public function setDefaultIcon(): void
    {
        $this->icon = 'default.ico';
    }

    public function setNotActive(): void
    {
        $this->isActive = false;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}