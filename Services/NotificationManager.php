<?php

declare(strict_types=1);

namespace App\Services;

use App\Model\Entity\Device;
use App\Repository\DeviceRepository;

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
     * @param array $metadata
     *
     * @return bool
     */
    public function notifyAllUsersByCountry(string $countryCode, string $text, array $metadata): bool
    {
        $readyToSend = false;

        if (isset($text) && !empty($metadata)) {
            $readyToSend = true;
        } elseif (isset($text) && empty($metadata) == true) {
            $metadata = ['icon' => 'default.ico'];
            $readyToSend = true;
        }

        if ($readyToSend === false) {
            return false;
        }

        $messages = [];
        $devices = $this->deviceRepository->findAllByCountryCode($countryCode);

        foreach ($devices as $device) {
            if ($device->state != 'ACTIVE') {
                $metadata['is_active'] = false;
            } else {
                $metadata['is_active'] = true;
            }

            $messages[] = [
                'message' => $text,
                'registration_id' => $device->registration_id,
                'data' => $metadata
            ];
        }

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
     * @param array $metadata
     *
     * @return bool
     */
    public function notifyUser(string $countryCode, int $userId, string $text, array $metadata): bool
    {
        $readyToSend = false;

        if (isset($text) && !empty($metadata)) {
            $readyToSend = true;
        } elseif (isset($text) && empty($metadata) == true) {
            $metadata = ['icon' => 'default.ico'];
            $readyToSend = true;
        }

        if ($readyToSend === false) {
            return false;
        }
        $messages = [];
        $devices = $this->deviceRepository->findAllCountryCodeAndUserId($countryCode, $userId);

        foreach ($devices as $device) {
            if ($device->state != 'ACTIVE') {
                $metadata['is_active'] = false;
            } else {
                $metadata['is_active'] = true;
            }

            $messages[] = [
                'message' => $text,
                'registration_id' => $device->registration_id,
                'data' => $metadata
            ];
        }

        foreach ($messages as $payload) {
            $this->client->send($payload);
        }

        if (count($messages) > 0) {
            return true;
        }

        return false;
    }
}
}