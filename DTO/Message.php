<?php

declare(strict_types=1);

namespace App\DTO;``

class Message
{
    private string $text;

    private int $deviceRegistrationId;

    private array $metadata;

    public function __construct(string $text, int $deviceRegistrationId, array $metadata) {
        $this->text = $text;
        $this->deviceRegistrationId = $deviceRegistrationId;
        $this->metadata = $metadata;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getDeviceRegistrationId(): int
    {
        return $this->deviceRegistrationId;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }
}