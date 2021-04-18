<?php

declare(strict_types=1);

namespace App\Repository;

use App\Repository\DeviceRepository;
use Illuminate\Support\Collection;

class DeviceRepositoryEloquent implements DeviceRepository
{
    /**
     * {@inheritDoc}
     */
    public function findAllByCountryCode(string $countryCode): Collection
    {
        /** @var Collection $collection */
        $collection = Device::whereCountryCode($this->countryCode)->get();

        return $collection;
    }

    /**
     * {@inheritDoc}
     */
    public function findAllByCountryCodeAndUserId(string $countryCode, int $userId): Collection
    {
        /** @var Collection $collection */
        $collection = Device::whereCountryCode($this->countryCode)->whereUserId($userId)->get();

        return $collection;
    }
}