<?php

use App\Model\Entity\Device;

class NotificationManager
{

	public $countryCode;
	public $client;

	public function __construct($client, $countryCode)
	{
		$this->client = $client;
		$this->countryCode = $countryCode;
	}

	public function notifyAllUsers($text, $metadata)
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

	public function notifyUser($userId, $text, $metadata)
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