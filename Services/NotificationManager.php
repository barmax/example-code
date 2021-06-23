<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Message;
use App\DTO\Metadata;
use App\Enums\DeviceStateEnum;
use App\Repository\DeviceRepository;
use Illuminate\Support\Collection;

/**
 * A manager that notifies users devices separates by country.
 */
class NotificationManager
{
    private ClientInterface $client;

    private DeviceRepository $deviceRepository;

    public function __construct(ClientInterface $client, DeviceRepository $deviceRepository)
    {
        $this->client = $client;
        $this->deviceRepository = $deviceRepository;
    }

    /**
     * Sends a message for all users from one country.
     *
     * @param string $countryCode ;
     * @param string $text
     * @param Metadata $metadata
     *
     * @return bool
     */
    public function notifyAllUsersByCountry(string $countryCode, string $text, Metadata $metadata): bool
    {
        $devices = $this->deviceRepository->findAllByCountryCode($countryCode);

        if ($devices->isEmpty()) {
            return false;
        }

        $messages = $this->createMessages($devices, $text, $metadata);
        $this->sendMessages($messages);

        return true;
    }

    /**
     * Sends a message to an user.
     *
     * @param string $countryCode ;
     * @param int $userId
     * @param string $text
     * @param Metadata $metadata
     *
     * @return bool
     */
    public function notifyUser(string $countryCode, int $userId, string $text, Metadata $metadata): bool
    {
        $devices = $this->deviceRepository->findAllByCountryCodeAndUserId($countryCode, $userId);

        if ($devices->isEmpty()) {
            return false;
        }

        $messages = $this->createMessages($devices, $text, $metadata);
        $this->sendMessages($messages);

        return true;
    }

    /**
     * Creates array of message for devices.
     *
     * @param Iterable $devices
     * @param string $text
     * @param Metadata $metadata
     *
     * @return Message[]
     */
    private function createMessages(Iterable $devices, string $text, Metadata $metadata): array
    {
        $messages = [];

        foreach ($devices as $device) {
            $messages[] = new Message($text, $device->registration_id, $metadata);
        }

        return $messages;
    }

    /**
     * Send messages to users devices.
     *
     * @param iterable $messages
     */
    private function sendMessages(Iterable $messages): void
    {
        foreach ($messages as $payload) {
            $this->client->send($payload);
        }
    }
}