<?php

/*
    This file is part of PUPI - Flexible Notifications System, a
    Question2Answer plugin that helps manage Q2A's configuration.

    Copyright (C) 2024 Gabriel Zanetti <https://github.com/pupi1985>

    PUPI - Flexible Notifications System is free software: you can redistribute
    it and/or modify it under the terms of the GNU General Public License as
    published by the Free Software Foundation, either version 3 of the License,
    or (at your option) any later version.

    PUPI - Flexible Notifications System is distributed in the hope that it
    will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
    of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General
    Public License for more details.

    You should have received a copy of the GNU General Public License along
    with PUPI - Flexible Notifications System. If not, see
    <http://www.gnu.org/licenses/>.
*/

class PUPI_FNS_NotificationsStatsCache
{
    const CACHE_KEY_PATTERN = 'pupi_fns_stats_%d';
    const STATS_TTL = 1; // Minutes

    public function get($userId): array
    {
        $cacheDriver = Q2A_Storage_CacheFactory::getCacheDriver();
        if (!$cacheDriver->isEnabled()) {
            return [];
        }

        $cacheKey = sprintf(self::CACHE_KEY_PATTERN, $userId);
        $cachedStats = $cacheDriver->get($cacheKey);

        if ($cachedStats === null) {
            return [];
        }

        $values = explode(':', $cachedStats);

        return [
            'total_notifications' => $values[0],
            'unread_notifications' => $values[1],
        ];
    }

    public function save($userId, $stats)
    {
        $cacheDriver = Q2A_Storage_CacheFactory::getCacheDriver();

        if (!$cacheDriver->isEnabled()) {
            return;
        }

        $cacheKey = sprintf(self::CACHE_KEY_PATTERN, $userId);
        $cachedStats = implode(':', [$stats['total_notifications'], $stats['unread_notifications']]);

        $cacheDriver->set($cacheKey, $cachedStats, self::STATS_TTL);
    }

    public function delete($userId)
    {
        $cacheDriver = Q2A_Storage_CacheFactory::getCacheDriver();

        if (!$cacheDriver->isEnabled()) {
            return;
        }

        $cacheDriver->delete(sprintf(self::CACHE_KEY_PATTERN, $userId));
    }
}
