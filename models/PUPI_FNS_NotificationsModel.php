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

class PUPI_FNS_NotificationsModel
{
    public function getForUserId($userId, int $maxNotifications): array
    {
        $sql =
            'SELECT `event_id`, `plugin_id`, `params`, `url`, `created_at` ' .
            'FROM `^pupi_fns_notifications` ' .
            'WHERE `user_id` = $ ' .
            'ORDER BY `created_at` DESC ' .
            'LIMIT #';

        $results = qa_db_read_all_assoc(qa_db_query_sub($sql, $userId, $maxNotifications));

        foreach ($results as &$result) {
            $result['event_id'] = (int)$result['event_id'];
            $result['params'] = json_decode($result['params'], true);
            try {
                $result['created_at'] = (new DateTime($result['created_at']))->format('c');
            } catch (Exception $e) {
            }
        }

        return $results;
    }

    public function insert(int $eventId, ?string $pluginId, ?string $params, $userId, ?string $createdAt = null)
    {
        if (is_null($createdAt)) {
            $createdAt = date('Y-m-d H:i:s');
        }

        $sql =
            'INSERT INTO `^pupi_fns_notifications`(`event_id`, `plugin_id`, `params`, `user_id`, `created_at`) ' .
            'VALUES(#, $, $, $, $)';

        qa_db_query_sub($sql, $eventId, $pluginId, $params, $userId, $createdAt);
    }

    public function deleteOldestNotifications($userId, int $notificationsToDelete)
    {
        $sql =
            'DELETE FROM `^pupi_fns_notifications` ' .
            'WHERE `user_id` = $ ' .
            'ORDER BY `created_at` ASC ' .
            'LIMIT #';

        qa_db_query_sub($sql, $userId, $notificationsToDelete);
    }
}
