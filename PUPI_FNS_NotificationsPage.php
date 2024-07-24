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

class PUPI_FNS_NotificationsPage
{
    private string $directory;

    function load_module($directory, $urltoroot)
    {
        $this->directory = $directory;
    }

    public function match_request($request)
    {
        return qa_is_http_get() && $request === PUPI_FNS_Constants::PAGE_NOTIFICATIONS;
    }

    public function process_request($request)
    {
        $response = [];

        header('Content-type: application/json');

        try {
            $this->checkAuthentication();
            $type = $this->getTypeParam();

            $notificationsService = new PUPI_FNS_NotificationsService();

            $userId = qa_get_logged_in_userid();
            $handle = qa_get_logged_in_handle();

            $response['version'] = (new Q2A_Util_Metadata())->fetchFromAddonPath($this->directory)['version'] ?? '0.0.0';

            $notificationsStats = $notificationsService->getNotificationsStatsForUser($userId);
            $response['notifications_stats'] = $notificationsStats;

            if ($type === PUPI_FNS_Constants::PAGE_NOTIFICATIONS_PARAM_TYPE_VALUE_ALL) {
                $notifications = $notificationsService->getNotificationsForUser($userId, $handle, $notificationsStats['unread_notifications']);
                $response['notifications'] = $notifications;
            }

            $response['lang'] = $this->getLanguageString($notificationsStats['total_notifications'], $notificationsStats['unread_notifications']);

            if ($notificationsStats['unread_notifications'] > 0) {
                $notificationsService->zeroUnread($userId);
            }

        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }

        echo json_encode($response);
    }

    /**
     * @throws Exception
     */
    private function checkAuthentication()
    {
        if (!qa_is_logged_in()) {
            header('HTTP/1.1 401 Unauthorized');

            throw new Exception('Unauthorized');
        }
    }

    private function getLanguageString(int $totalNotifications, int $unreadNotifications): string
    {
        if ($totalNotifications === 0) {
            return pupi_fns()->lang(PUPI_FNS_Constants::LANG_ID_NOTIFICATIONS_LIST_NO_NOTIFICATIONS);
        }

        switch ($unreadNotifications) {
            case 0:
                return pupi_fns()->lang(PUPI_FNS_Constants::LANG_ID_NOTIFICATIONS_LIST_NO_UNREAD_NOTIFICATIONS);
            case 1:
                return pupi_fns()->lang(PUPI_FNS_Constants::LANG_ID_NOTIFICATIONS_LIST_ONE_UNREAD_NOTIFICATION);
            default:
                return pupi_fns()->langSub(PUPI_FNS_Constants::LANG_ID_NOTIFICATIONS_LIST_N_UNREAD_NOTIFICATIONS, $unreadNotifications);
        }
    }

    /**
     * @throws Exception
     */
    private function getTypeParam()
    {
        $type = qa_get(PUPI_FNS_Constants::PAGE_NOTIFICATIONS_PARAM_TYPE);
        if (!in_array($type, [PUPI_FNS_Constants::PAGE_NOTIFICATIONS_PARAM_TYPE_VALUE_ALL, PUPI_FNS_Constants::PAGE_NOTIFICATIONS_PARAM_TYPE_VALUE_STATS_ONLY])) {
            header('HTTP/1.1 400 Bad Request');

            throw new Exception('Param type has an invalid value');
        }

        return $type;
    }
}
