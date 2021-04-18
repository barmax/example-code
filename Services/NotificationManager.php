<?php

declare(strict_types=1);

namespace App\Services;

use App\Model\Entity\Device;

class NotificationManager
{
    private ClientInterface $client;

	public function __construct($client)
	{
		$this->client = $client;
	}

    /**
     * Sends a message for all users from one country.
     *
     * @param string $countryCode;
     * @param string $text
     * @param array $metadata
     *
     * @return bool
     */
    public function notifyAllUsersByCountry(string $countryCode, string $text, array $metadata): bool
	{
		if (isset($text) && !empty($metadata)) {
			$readyToSend = true;
		} else if(isset($text) && empty($metadata) == true) {
			$metadata = array(
					'icon' => 'default.ico',
			);
			$readyToSend = true;
		} else{
			$readyToSend = false;
		}

		if($readyToSend == false){
			return false;
		}else{
			$messages = array();
			$devices = Device::whereCountryCode($this->countryCode)->get();
			foreach($devices as $device){
				if($device->state != 'ACTIVE'){
					$metadata['is_active'] = false;
				}else{
					$metadata['is_active'] = true;
				}

				$messages[] = array(
					'message' => $text,
					'registration_id' => $device->registration_id,
					'data' => $metadata
				);
			}
			foreach ($messages as $payload){
				$this->client->send($payload);
			}

			if(count($messages) > 0)
				return true;
			else
				return false;
		}
	}

    /**
     * Sends a message to an user.
     *
     * @param string $countryCode;
     * @param int $userId
     * @param string $text
     * @param array $metadata
     *
     * @return bool
     */
    public function notifyUser(string $countryCode, int $userId, string $text, array $metadata): bool
	{
		if (isset($text) && !empty($metadata)) {
			$readyToSend = true;
		} else if(isset($text) && empty($metadata) == true) {
			$metadata = array(
				'icon' => 'default.ico',
			);
			$readyToSend = true;
		} else{
			$readyToSend = false;
		}

		if($readyToSend == false){
			return false;
		}else{
			$messages = array();
			$devices = Device::whereCountryCode($this->countryCode)->whereUserId($userId)->get();
			foreach($devices as $device){
				if($device->state != 'ACTIVE'){
					$metadata['is_active'] = false;
				}else{
					$metadata['is_active'] = true;
				}

				$messages[] = array(
					'message' => $text,
					'registration_id' => $device->registration_id,
					'data' => $metadata
				);
			}
			foreach ($messages as $payload){
				$this->client->send($payload);
			}

			if(count($messages) > 0)
				return true;
			else
				return false;
		}
	}
}