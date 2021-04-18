<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Message;
use App\DTO\Metadata;
use App\Enums\DeviceStateEnum;
use App\Repository\DeviceRepository;
use Illuminate\Support\Collection;

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
        if ($this->isReadyToSend($text, $metadata) === false) {
            return false;
        }

        $devices = $this->deviceRepository->findAllByCountryCode($countryCode);
        $messages = $this->createMessages($devices, $text, $metadata);

        foreach ($messages as $payload) {
            $this->client->send($payload);
        }

        if (count($messages) > 0) {
            return true;
        }

        return false;
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
        if ($this->isReadyToSend($text, $metadata) === false) {
            return false;
        }

        $devices = $this->deviceRepository->findAllCountryCodeAndUserId($countryCode, $userId);
        $messages = $this->createMessages($devices, $text, $metadata);

        foreach ($messages as $payload) {
            $this->client->send($payload);
        }

        if (count($messages) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Creates array of message for devices.
     *
     * @param Collection $devices
     * @param string $text
     * @param Metadata $metadata
     *
     * @return Message[]
     */
    private function createMessages(Collection $devices, string $text, Metadata $metadata): array
    {
        $messages = [];

        foreach ($devices as $device) {
            if ($device->state !== DeviceStateEnum::ACTIVE) {
                $metadata->setNotActive();
            }

            $messages[] = new Message($text, $device->registration_id, $metadata);
        }

        return $messages;
    }

    /**
     * Validates that is data ready to send.
     *
     * @todo I think this action should use outside the NotificationManager.
     *
     * @param string $text
     * @param Metadata $metadata
     *
     * @return bool
     */
    private function isReadyToSend(string $text, Metadata $metadata): bool
    {
        $readyToSend = false;

        if (isset($text) && !empty($metadata)) {
            $readyToSend = true;
        } elseif (isset($text) && empty($metadata) == true) {
            $metadata->setDefaultIcon();
            $readyToSend = true;
        }

        return $readyToSend;
    }
}