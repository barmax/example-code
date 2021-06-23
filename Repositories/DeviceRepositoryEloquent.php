<?php

declare(strict_types=1);

namespace App\Repository;

use App\Enums\DeviceStateEnum;
use App\Repository\DeviceRepository;
use Illuminate\Support\LazyCollection;

class DeviceRepositoryEloquent implements DeviceRepository
{
    /**
     * {@inheritDoc}
     */
    public function findAllActiveByCountryCode(string $countryCode): LazyCollection
    {
        /** @var LazyCollection $collection */
        $collection = Device::whereCountryCode($countryCode)
            ->where('state', '=', DeviceStateEnum::ACTIVE)
            ->lazy();

        return $collection;
    }

    /**
     * {@inheritDoc}
     */
    public function findAllActiveByCountryCodeAndUserId(string $countryCode, int $userId): LazyCollection
    {
        /** @var LazyCollection $collection */
        $collection = Device::whereCountryCode($countryCode)
            ->whereUserId($userId)
            ->where('state', '=', DeviceStateEnum::ACTIVE)
            ->lazy();

        return $collection;
    }
}