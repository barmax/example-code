<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Entity\Device;
use Illuminate\Support\Collection;

interface DeviceRepository
{
    /**
     * Finds all devices by country code.
     *
     * @param string $countryCode
     *
     * @return LazyCollection LazyCollection
     */
    public function findAllActiveByCountryCode(string $countryCode): LazyCollection;

    /**
     * Finds all devices by country code and user ID.
     *
     * @param string $countryCode
     * @param int $userId
     *
     * @return LazyCollection LazyCollection
     */
    public function findAllActiveByCountryCodeAndUserId(string $countryCode, int $userId): LazyCollection;
}