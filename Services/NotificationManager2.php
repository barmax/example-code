<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\DeviceStateEnum;
use App\Model\Entity\Device;

/**
 * A manager that notifies users devices separates by country.
 */
class NotificationManager
{
    public ClientInterface $client;

    public string $countryCode;

    public function __construct(ClientInterface $client, string $countryCode)
    {
        $this->client = $client;
        $this->countryCode = $countryCode;
    }

    /**
     * Validates input data and sends messages to users devices of country.
     *
     * @param $text
     * @param $metadata
     *
     * @return bool
     */
    public function notifyAllUsers(?string $text, array $metadata)
    {
        if ($text === null) {
            return false;
        }

        $devices = Device::whereCountryCode($this->countryCode)->get();

        if ($devices->isEmpty()) {
            return false;
        }

        $messages = $this->createMessages($devices, $text, $metadata);

        foreach ($messages as $payload) {
            $this->client->send($payload);
        }

        return true;
    }

    /**
     * Validates input data and sends messages to devices of a user of country.
     *
     * @param $userId
     * @param $text
     * @param $metadata
     *
     * @return bool
     */
    public function notifyUser($userId, $text, $metadata)
    {
        if ($text === null) {
            return false;
        }

        $devices = Device::whereCountryCode($this->countryCode)->whereUserId($userId)->get();

        if ($devices->isEmpty()) {
            return false;
        }

        $messages = $this->createMessages($devices, $text, $metadata);

        foreach ($messages as $payload) {
            $this->client->send($payload);
        }

        return true;
    }

    /**
     * Creates messages by input data.
     *
     * @param Iterable $devices
     * @param $text
     * @param $metadata
     *
     * @return array
     */
    private function createMessages(Iterable $devices, $text, $metadata): array
    {
        $messages = [];

        if (empty($metadata)) {
            $metadata = ['icon' => 'default.ico'];
        }

        foreach ($devices as $device) {
            $metadata['is_active'] = $device->state !== DeviceStateEnum::ACTIVE ? false : true;

            $messages[] = [
                'message' => $text,
                'registration_id' => $device->registration_id,
                'data' => $metadata,
            ];
        }

        return $messages;
    }
}