<?php

/*
    This file is part of PUPI - Flexible Notifications System, a
    Question2Answer plugin that allows users to receive notifications in a
    flexible and efficient way.

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

interface PUPI_FNS_IExtension
{
    /**
     * Receive a notification to be processed and identification of the user receiver of the notification.
     *
     * Notification structure to be returned (additional fields can be added): [
     *   'plugin_id': 'pupi_dm',
     *   'event_name': 'mention',
     *   'event_id': 0,
     *   'name': 'Mention',
     *   'created_at': '2023-12-24T18:32:20-03:00',
     *   'url': 'http://site.com/q2a/9997/how-to-clean-a-microwave-oven?show=10040#c10040',
     *   'text': 'You have been mentioned in a post',
     * ]
     *
     * @param array $notification
     * @param mixed $userId
     * @param string $handle
     *
     * @return array Processed notification
     */
    public function processNotification(array $notification, $userId, string $handle): array;
}
