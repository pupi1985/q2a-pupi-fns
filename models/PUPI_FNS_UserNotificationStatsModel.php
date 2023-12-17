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

class PUPI_FNS_UserNotificationStatsModel
{
    public function getByUserId($userId): ?array
    {
        $sql =
            'SELECT `total_notifications`, `unread_notifications` ' .
            'FROM `^pupi_fns_user_notification_stats` ' .
            'WHERE `user_id` = $';

        $result = qa_db_read_one_assoc(qa_db_query_sub($sql, $userId), true);

        if (is_null($result)) {
            return null;
        }

        $result['total_notifications'] = (int)$result['total_notifications'];
        $result['unread_notifications'] = (int)$result['unread_notifications'];

        return $result;
    }

    public function insert($userId, int $totalNotifications, int $unreadNotifications): void
    {
        $sql =
            'INSERT INTO `^pupi_fns_user_notification_stats` (`user_id`, `total_notifications`, `unread_notifications`) ' .
            'VALUES($, #, #)';

        qa_db_query_sub($sql, $userId, $totalNotifications, $unreadNotifications);
    }

    public function update($userId, array $fields): void
    {
        $params = [];

        $sql =
            'UPDATE `^pupi_fns_user_notification_stats` ' .
            'SET ';

        foreach ($fields as $field => $value) {
            $sql .= sprintf("`%s` = $, ", $field);

            $params[] = $value;
        }

        $sql = substr($sql, 0, -2);

        $sql .= ' WHERE `user_id` = $';

        $params[] = $userId;

        qa_db_query_sub_params($sql, $params);
    }
}
