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
     * @return Collection Collection
     */
    public function findAllByCountryCode(string $countryCode): Collection;

    /**
     * Finds all devices by country code and user ID.
     *
     * @param string $countryCode
     * @param int $userId
     *
     * @return Collection Collection
     */
    public function findAllByCountryCodeAndUserId(string $countryCode, int $userId): Collection;
}